<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Requests\StorePurchaseRequest;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    public function index(Request $request)
    {
        $query = Purchase::with(['supplier', 'user', 'items.product'])->latest();
        
        $purchases = $query->paginate(20);
        $suppliers = Supplier::all();
        $products = Product::all();

        return Inertia::render('Purchases', [
            'purchases' => $purchases,
            'suppliers' => $suppliers,
            'products' => $products
        ]);
    }

    public function store(StorePurchaseRequest $request)
    {
        try {
            DB::beginTransaction();

            $data = $request->validated();
            
            $totalAmount = 0;
            foreach ($data['items'] as $item) {
                $totalAmount += ($item['quantity'] * $item['unit_price']);
            }

            $purchase = Purchase::create([
                'company_id' => auth()->user()->company_id,
                'supplier_id' => $data['supplier_id'],
                'user_id' => auth()->id(),
                'reference_number' => $data['reference_number'] ?? 'PUR-' . time(),
                'status' => 'received',
                'total_amount' => $totalAmount,
                'notes' => $data['notes'] ?? null,
            ]);

            foreach ($data['items'] as $item) {
                $subtotal = $item['quantity'] * $item['unit_price'];
                
                $purchase->items()->create([
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'subtotal' => $subtotal
                ]);

                // Adjust stock (Purchase adds to stock)
                $product = Product::find($item['product_id']);
                if ($product) {
                    $product->stock_quantity += $item['quantity'];
                    $product->save();
                }
            }

            DB::commit();

            return redirect()->back()->with('success', 'Purchase recorded and stock updated.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Failed to record purchase. ' . $e->getMessage()]);
        }
    }

    public function destroy(Purchase $purchase)
    {
        try {
            DB::beginTransaction();

            // Reverse stock
            foreach ($purchase->items as $item) {
                $product = Product::find($item->product_id);
                if ($product) {
                    $product->stock_quantity -= $item->quantity;
                    $product->save();
                }
            }

            $purchase->items()->delete();
            $purchase->delete();

            DB::commit();
            return redirect()->back()->with('success', 'Purchase deleted and stock reverted.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Failed to delete purchase. ' . $e->getMessage()]);
        }
    }
}
