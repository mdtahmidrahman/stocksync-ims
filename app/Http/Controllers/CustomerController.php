<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use Rap2hpoutre\FastExcel\FastExcel;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = Customer::query();

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhere('phone', 'like', '%' . $request->search . '%');
            });
        }

        $customers = $query->latest()->paginate(50)->withQueryString();

        return Inertia::render('Customers', [
            'customers' => $customers,
            'filters' => $request->only(['search'])
        ]);
    }

    public function store(StoreCustomerRequest $request)
    {
        $data = $request->validated();
        $data['company_id'] = auth()->user()->company_id;

        Customer::create($data);

        return redirect()->back()->with('success', 'Customer created successfully.');
    }

    public function update(UpdateCustomerRequest $request, Customer $customer)
    {
        $customer->update($request->validated());

        return redirect()->back()->with('success', 'Customer updated successfully.');
    }

    public function destroy(Request $request, Customer $customer)
    {
        $customer->delete();

        return redirect()->back()->with('success', 'Customer deleted successfully.');
    }

    public function export(Request $request)
    {
        $query = Customer::query();

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhere('phone', 'like', '%' . $request->search . '%');
            });
        }

        $customers = $query->latest()->get();
        $format = $request->query('format', 'xlsx');
        $filename = 'customers.' . ($format === 'csv' ? 'csv' : 'xlsx');

        return (new FastExcel($customers))->download($filename, function ($customer) {
            return [
                'ID' => $customer->id,
                'Name' => $customer->name,
                'Email' => $customer->email,
                'Phone' => $customer->phone,
                'Address' => $customer->address,
                'Loyalty Points' => $customer->loyalty_points,
                'Created At' => $customer->created_at->format('Y-m-d H:i:s'),
            ];
        });
    }
}
