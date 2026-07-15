<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Requests\StoreSupplierRequest;
use App\Http\Requests\UpdateSupplierRequest;
use Rap2hpoutre\FastExcel\FastExcel;

class SupplierController extends Controller
{
    public function index(Request $request)
    {
        $query = Supplier::query();

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhere('phone', 'like', '%' . $request->search . '%');
            });
        }

        $suppliers = $query->latest()->paginate(50)->withQueryString();

        return Inertia::render('Suppliers', [
            'suppliers' => $suppliers,
            'filters' => $request->only(['search'])
        ]);
    }

    public function store(StoreSupplierRequest $request)
    {
        $data = $request->validated();
        $data['company_id'] = auth()->user()->company_id;

        Supplier::create($data);

        return redirect()->back()->with('success', 'Supplier created successfully.');
    }

    public function update(UpdateSupplierRequest $request, Supplier $supplier)
    {
        $supplier->update($request->validated());

        return redirect()->back()->with('success', 'Supplier updated successfully.');
    }

    public function destroy(Request $request, Supplier $supplier)
    {
        $supplier->delete();

        return redirect()->back()->with('success', 'Supplier deleted successfully.');
    }

    public function export(Request $request)
    {
        $query = Supplier::query();

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhere('phone', 'like', '%' . $request->search . '%');
            });
        }

        $suppliers = $query->latest()->get();
        $format = $request->query('format', 'xlsx');
        $filename = 'suppliers.' . ($format === 'csv' ? 'csv' : 'xlsx');

        return (new FastExcel($suppliers))->download($filename, function ($supplier) {
            return [
                'ID' => $supplier->id,
                'Name' => $supplier->name,
                'Email' => $supplier->email,
                'Phone' => $supplier->phone,
                'Address' => $supplier->address,
                'Created At' => $supplier->created_at->format('Y-m-d H:i:s'),
            ];
        });
    }
}
