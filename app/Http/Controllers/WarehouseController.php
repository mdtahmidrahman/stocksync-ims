<?php

namespace App\Http\Controllers;

use App\Models\Warehouse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Requests\StoreWarehouseRequest;
use App\Http\Requests\UpdateWarehouseRequest;
use App\Http\Requests\BulkImportRequest;

class WarehouseController extends Controller
{
    public function index(Request $request)
    {
        $query = Warehouse::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('location', 'like', '%' . $request->search . '%');
        }

        $warehouses = $query->latest()->paginate(10)->withQueryString();

        return Inertia::render('Warehouses', [
            'warehouses' => $warehouses,
            'filters' => $request->only(['search'])
        ]);
    }

    public function store(StoreWarehouseRequest $request)
    {
        Warehouse::create($request->validated());

        return redirect()->back()->with('success', 'Warehouse created successfully.');
    }

    public function show(Warehouse $warehouse)
    {
        return response()->json($warehouse);
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
}
