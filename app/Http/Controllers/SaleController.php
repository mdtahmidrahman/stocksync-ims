<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Requests\StoreSaleRequest;
use Illuminate\Support\Facades\DB;
use App\Models\Company;

class SaleController extends Controller
{
    public function index(Request $request)
    {
        $query = Sale::with(['customer', 'user', 'items.product'])->latest();
        
        $sales = $query->paginate(20);
        $customers = Customer::all();
        $products = Product::all();

        return Inertia::render('Sales', [
            'sales' => $sales,
            'customers' => $customers,
            'products' => $products
        ]);
    }

    public function store(StoreSaleRequest $request)
    {
        try {
            DB::beginTransaction();

            $data = $request->validated();
            
            $totalAmount = 0;
            foreach ($data['items'] as $item) {
                $totalAmount += ($item['quantity'] * $item['unit_price']);
            }

            $sale = Sale::create([
                'company_id' => Company::first()->id ?? 1,
                'customer_id' => $data['customer_id'] ?? null,
                'user_id' => auth()->id() ?? 1,
                'warehouse_id' => Warehouse::first()->id ?? 1,
                'invoice_number' => $data['reference_number'] ?? 'INV-' . time(),
                'status' => 'completed',
                'total_amount' => $totalAmount,
                'tax_amount' => 0,
                'discount_amount' => 0,
                'payment_method' => $data['payment_method'],
            ]);

            foreach ($data['items'] as $item) {
                $subtotal = $item['quantity'] * $item['unit_price'];
                
                $sale->items()->create([
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'subtotal' => $subtotal
                ]);

                // Adjust stock (Sale subtracts from stock)
                $product = Product::find($item['product_id']);
                if ($product) {
                    $product->stock_quantity -= $item['quantity'];
                    $product->save();
                }
            }
            
            // Add Loyalty Points (e.g. 1 point for every $10 spent)
            if (!empty($data['customer_id'])) {
                $customer = Customer::find($data['customer_id']);
                if ($customer) {
                    $pointsEarned = floor($totalAmount / 10);
                    $customer->loyalty_points += $pointsEarned;
                    $customer->save();
                }
            }

            DB::commit();

            return redirect()->back()->with('success', 'Sale recorded and stock updated.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Failed to record sale. ' . $e->getMessage()]);
        }
    }

    public function destroy(Sale $sale)
    {
        try {
            DB::beginTransaction();

            // Reverse stock
            foreach ($sale->items as $item) {
                $product = Product::find($item->product_id);
                if ($product) {
                    $product->stock_quantity += $item->quantity;
                    $product->save();
                }
            }

            $sale->items()->delete();
            $sale->delete();

            DB::commit();
            return redirect()->back()->with('success', 'Sale deleted and stock reverted.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Failed to delete sale. ' . $e->getMessage()]);
        }
    }
}
