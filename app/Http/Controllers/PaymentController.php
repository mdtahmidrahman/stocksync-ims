<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePaymentRequest;
use App\Models\Payment;
use App\Models\Order;
use App\Models\Sale;
use App\Models\Purchase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Company;

class PaymentController extends Controller
{
    public function index()
    {
        return inertia('Payments');
    }

    public function store(StorePaymentRequest $request)
    {
        $company_id = Company::first()->id ?? 1;

        DB::beginTransaction();

        try {
            // Find the payable entity
            $payableClass = $request->payable_type; // e.g., App\Models\Order
            $payable = $payableClass::findOrFail($request->payable_id);

            // Create the payment
            $payment = Payment::create([
                'company_id' => $company_id,
                'payable_id' => $payable->id,
                'payable_type' => $payableClass,
                'amount' => $request->amount,
                'payment_method' => $request->payment_method,
                'payment_date' => $request->payment_date,
                'reference_number' => $request->reference_number ?? 'PAY-' . strtoupper(Str::random(6)),
            ]);

            // Update the payable's payment status if applicable
            if (in_array($payableClass, [Order::class, Sale::class, Purchase::class])) {
                $totalPaid = $payable->payments()->sum('amount');
                $totalDue = $payable->total_amount; // Assuming all three have total_amount

                if ($totalPaid >= $totalDue) {
                    $payable->update(['payment_status' => 'paid']);
                } elseif ($totalPaid > 0) {
                    $payable->update(['payment_status' => 'partial']);
                }
            }

            DB::commit();
            return redirect()->back()->with('success', 'Payment recorded successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error recording payment: ' . $e->getMessage());
        }
    }
}
