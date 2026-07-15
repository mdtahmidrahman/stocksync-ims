<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Warehouse;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function index()
    {
        // For multi-tenant, we should scope by company_id, but assuming single tenant for now or using first company
        $company_id = \App\Models\Company::first()->id ?? 1;

        $orders = Order::with(['customer', 'items.product', 'payments'])
            ->where('company_id', $company_id)
            ->latest()
            ->paginate(10);
            
        return Inertia::render('Orders', [
            'orders' => $orders,
            'customers' => Customer::where('company_id', $company_id)->get(),
            'products' => Product::where('company_id', $company_id)->get(),
            'warehouses' => Warehouse::where('company_id', $company_id)->get(),
        ]);
    }

    public function store(StoreOrderRequest $request)
    {
        $company_id = \App\Models\Company::first()->id ?? 1;

        DB::beginTransaction();

        try {
            $total_amount = 0;
            foreach ($request->items as $item) {
                $total_amount += $item['quantity'] * $item['unit_price'];
            }

            $order = Order::create([
                'company_id' => $company_id,
                'customer_id' => $request->customer_id,
                'warehouse_id' => $request->warehouse_id,
                'order_number' => 'ORD-' . strtoupper(Str::random(8)),
                'total_amount' => $total_amount,
                'status' => 'pending',
                'payment_status' => 'unpaid',
            ]);

            foreach ($request->items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'subtotal' => $item['quantity'] * $item['unit_price'],
                ]);
            }

            DB::commit();
            return redirect()->back()->with('success', 'Order created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error creating order: ' . $e->getMessage());
        }
    }

    public function update(UpdateOrderRequest $request, Order $order)
    {
        $oldStatus = $order->status;
        $newStatus = $request->status;

        DB::beginTransaction();
        try {
            $order->update(['status' => $newStatus]);

            // If transitioning to shipped/delivered from a pre-shipment state, deduct stock
            if (in_array($newStatus, ['shipped', 'delivered']) && !in_array($oldStatus, ['shipped', 'delivered'])) {
                foreach ($order->items as $item) {
                    $product = $item->product;
                    $product->stock_quantity -= $item->quantity;
                    $product->save();

                    StockMovement::create([
                        'company_id' => $order->company_id,
                        'product_id' => $item->product_id,
                        'warehouse_id' => $order->warehouse_id,
                        'quantity' => -$item->quantity, // negative for deduction
                        'type' => 'sale', // or order fulfillment
                        'reference_id' => $order->id,
                        'remarks' => "Order #{$order->order_number} fulfilled",
                    ]);
                }
            } 
            // If transitioning BACK to pending/cancelled from a shipped state, restore stock
            elseif (in_array($newStatus, ['pending', 'processing', 'cancelled']) && in_array($oldStatus, ['shipped', 'delivered'])) {
                 foreach ($order->items as $item) {
                    $product = $item->product;
                    $product->stock_quantity += $item->quantity;
                    $product->save();

                    StockMovement::create([
                        'company_id' => $order->company_id,
                        'product_id' => $item->product_id,
                        'warehouse_id' => $order->warehouse_id,
                        'quantity' => $item->quantity, // positive for addition
                        'type' => 'return',
                        'reference_id' => $order->id,
                        'remarks' => "Order #{$order->order_number} reversed/cancelled",
                    ]);
                }
            }

            DB::commit();
            return redirect()->back()->with('success', 'Order status updated.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error updating order: ' . $e->getMessage());
        }
    }

    public function destroy(Order $order)
    {
        DB::beginTransaction();
        try {
            if (in_array($order->status, ['shipped', 'delivered'])) {
                // Restore stock before deleting
                foreach ($order->items as $item) {
                    $product = $item->product;
                    $product->stock_quantity += $item->quantity;
                    $product->save();
                    
                    StockMovement::create([
                        'company_id' => $order->company_id,
                        'product_id' => $item->product_id,
                        'warehouse_id' => $order->warehouse_id,
                        'quantity' => $item->quantity,
                        'type' => 'adjustment',
                        'reference_id' => $order->id,
                        'remarks' => "Order #{$order->order_number} deleted",
                    ]);
                }
            }
            
            $order->delete();
            DB::commit();
            return redirect()->back()->with('success', 'Order deleted.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error deleting order: ' . $e->getMessage());
        }
    }
}
