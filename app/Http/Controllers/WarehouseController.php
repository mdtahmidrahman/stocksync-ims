<?php

namespace App\Http\Controllers;

use App\Models\Warehouse;
use Illuminate\Http\Request;
use Inertia\Inertia;

class WarehouseController extends Controller
{
    public function index(Request $request)
    {
        $warehouses = Warehouse::all();

        if ($request->wantsJson()) {
            return response()->json($warehouses);
        }

        return Inertia::render('Warehouses', [
            'warehouses' => $warehouses
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'is_active' => 'boolean',
        ]);

        $warehouse = Warehouse::create($request->all());

        if ($request->wantsJson()) {
            return response()->json($warehouse, 201);
        }

        return redirect()->back()->with('success', 'Warehouse created successfully.');
    }

    public function show(Warehouse $warehouse)
    {
        return response()->json($warehouse);
    }

    public function update(Request $request, Warehouse $warehouse)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'is_active' => 'boolean',
        ]);

        $warehouse->update($request->all());

        if ($request->wantsJson()) {
            return response()->json($warehouse);
        }

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
}
