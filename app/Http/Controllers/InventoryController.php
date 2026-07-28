<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Warehouse;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('sku', 'like', '%' . $request->search . '%');
            });
        }

        $products = $query->latest()->paginate(50)->withQueryString();
        $warehouses = Warehouse::all();
        $lowStockCount = Product::where('stock_quantity', '<=', 5)->count();

        return Inertia::render('Inventory', [
            'products' => $products,
            'warehouses' => $warehouses,
            'lowStockCount' => $lowStockCount,
            'filters' => $request->only(['search']),
        ]);
    }

    public function adjust(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'type' => 'required|in:add,remove,transfer',
            'quantity' => 'required|integer|min:1',
            'warehouse_id' => 'nullable|exists:warehouses,id',
            'remarks' => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            $product = Product::findOrFail($validated['product_id']);
            $changeQty = (int) $validated['quantity'];

            if ($validated['type'] === 'remove') {
                $product->stock_quantity = max(0, $product->stock_quantity - $changeQty);
                $signedQty = -$changeQty;
            } else {
                $product->stock_quantity += $changeQty;
                $signedQty = $changeQty;
            }

            $product->save();

            StockMovement::create([
                'product_id' => $product->id,
                'warehouse_id' => $validated['warehouse_id'] ?? Warehouse::first()->id ?? null,
                'quantity' => $signedQty,
                'type' => $validated['type'] === 'add' ? 'adjustment' : ($validated['type'] === 'remove' ? 'adjustment' : 'transfer'),
                'remarks' => $validated['remarks'] ?? 'Manual Stock Adjustment',
            ]);

            \App\Models\AuditLog::record(
                'Stock Adjustment',
                "Adjusted stock for product '{$product->name}' (SKU: {$product->sku}, Change: {$signedQty})"
            );

            DB::commit();
            return redirect()->back()->with('success', 'Stock updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to update stock: ' . $e->getMessage());
        }
    }

    public function history(Product $product)
    {
        $movements = StockMovement::with('warehouse')
            ->where('product_id', $product->id)
            ->latest()
            ->take(20)
            ->get();

        return response()->json([
            'product' => $product,
            'movements' => $movements,
        ]);
    }
}
