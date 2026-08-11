<?php

namespace App\Http\Controllers;

use App\Models\Warehouse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Requests\StoreWarehouseRequest;
use App\Http\Requests\UpdateWarehouseRequest;
use App\Http\Requests\BulkImportRequest;
use App\Models\User;
use App\Models\Product;
use App\Models\WarehouseTransfer;

class WarehouseController extends Controller
{
    public function index(Request $request)
    {
        $query = Warehouse::with(['manager', 'products.category']);

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('location', 'like', '%' . $request->search . '%');
        }

        // Get all warehouses (no pagination to support scrolling)
        $warehouses = $query->latest()->get()->map(function ($warehouse) {
            $totalItems = $warehouse->products->sum('pivot.quantity');
            $capacityUsed = $warehouse->capacity > 0 ? round(($totalItems / $warehouse->capacity) * 100) : 0;
            return array_merge($warehouse->toArray(), [
                'total_items' => $totalItems,
                'capacity_used' => min($capacityUsed, 100)
            ]);
        });
        
        $users = User::all();
        $products = Product::all();
        $transfers = WarehouseTransfer::with(['sourceWarehouse', 'destinationWarehouse', 'product', 'user'])->latest()->get();

        return Inertia::render('Warehouses', [
            'warehouses' => $warehouses,
            'users' => $users,
            'products' => $products,
            'transfers' => $transfers,
            'filters' => $request->only(['search'])
        ]);
    }

    public function store(StoreWarehouseRequest $request)
    {
        Warehouse::create($request->validated());

        return redirect()->back()->with('success', 'Warehouse created successfully.');
    }

    public function update(UpdateWarehouseRequest $request, Warehouse $warehouse)
    {
        $warehouse->update($request->validated());

        return redirect()->back()->with('success', 'Warehouse updated successfully.');
    }

    public function destroy(Request $request, Warehouse $warehouse)
    {
        $warehouse->delete();

        if ($request->wantsJson()) {
            return response()->noContent();
        }

        return redirect()->back()->with('success', 'Warehouse deleted successfully.');
    }

    public function export(Request $request)
    {
        $query = Warehouse::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('location', 'like', '%' . $request->search . '%');
        }

        $warehouses = $query->latest()->get();

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=warehouses.csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $columns = ['ID', 'Name', 'Location', 'Is Active', 'Created At'];

        $callback = function() use($warehouses, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($warehouses as $warehouse) {
                $row['ID'] = $warehouse->id;
                $row['Name'] = $warehouse->name;
                $row['Location'] = $warehouse->location;
                $row['Is Active'] = $warehouse->is_active ? 'Yes' : 'No';
                $row['Created At'] = $warehouse->created_at;

                fputcsv($file, array($row['ID'], $row['Name'], $row['Location'], $row['Is Active'], $row['Created At']));
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function import(BulkImportRequest $request)
    {
        $file = $request->file('file');
        $fileHandle = fopen($file->getPathname(), 'r');
        $header = fgetcsv($fileHandle);

        while (($row = fgetcsv($fileHandle)) !== false) {
            // Assume CSV format: Name, Location, Is Active (1/0)
            if (count($row) >= 2) {
                Warehouse::updateOrCreate(
                    ['name' => $row[0]], // Update by Name
                    [
                        'location' => $row[1],
                        'is_active' => isset($row[2]) ? (bool)$row[2] : true,
                    ]
                );
            }
        }

        fclose($fileHandle);

        return redirect()->back()->with('success', 'Warehouses imported successfully.');
    }

    public function storeTransfer(Request $request)
    {
        $data = $request->validate([
            'source_warehouse_id' => 'required|exists:warehouses,id',
            'destination_warehouse_id' => 'required|exists:warehouses,id|different:source_warehouse_id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string'
        ]);

        $data['user_id'] = auth()->id() ?? 1;
        $data['status'] = 'requested';

        WarehouseTransfer::create($data);

        return redirect()->back()->with('success', 'Transfer requested successfully.');
    }

    public function updateTransferStatus(Request $request, WarehouseTransfer $transfer)
    {
        $data = $request->validate([
            'status' => 'required|in:requested,in_transit,received'
        ]);

        if ($data['status'] === 'received' && $transfer->status !== 'received') {
            // Deduct from source
            $sourceProduct = $transfer->sourceWarehouse->products()->where('product_id', $transfer->product_id)->first();
            if ($sourceProduct) {
                $transfer->sourceWarehouse->products()->updateExistingPivot($transfer->product_id, [
                    'quantity' => max(0, $sourceProduct->pivot->quantity - $transfer->quantity)
                ]);
            } else {
                // If it doesn't exist, we just let it be or create it with 0
            }

            // Add to destination
            $destProduct = $transfer->destinationWarehouse->products()->where('product_id', $transfer->product_id)->first();
            if ($destProduct) {
                $transfer->destinationWarehouse->products()->updateExistingPivot($transfer->product_id, [
                    'quantity' => $destProduct->pivot->quantity + $transfer->quantity
                ]);
            } else {
                $transfer->destinationWarehouse->products()->attach($transfer->product_id, ['quantity' => $transfer->quantity]);
            }
        }

        $transfer->update(['status' => $data['status']]);

        return redirect()->back()->with('success', 'Transfer status updated.');
    }
}
