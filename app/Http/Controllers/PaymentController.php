<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePaymentRequest;
use App\Models\Payment;
use App\Models\Order;
use App\Models\Sale;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Inertia\Inertia;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = Payment::with('payable');

        if ($request->filled('type') && $request->type !== 'All Types') {
            if ($request->type === 'Incoming (Sales)') {
                $query->whereIn('payable_type', [Sale::class, Order::class]);
            } elseif ($request->type === 'Outgoing (Purchases)') {
                $query->where('payable_type', Purchase::class);
            }
        }

        $payments = $query->latest()->paginate(50)->withQueryString();

        $totalIncoming = (float) Payment::whereIn('payable_type', [Sale::class, Order::class])->sum('amount');
        $totalOutgoing = (float) Payment::where('payable_type', Purchase::class)->sum('amount');

        return Inertia::render('Payments', [
            'payments' => $payments,
            'metrics' => [
                'total_incoming' => $totalIncoming,
                'total_outgoing' => $totalOutgoing,
                'net_cashflow' => $totalIncoming - $totalOutgoing,
            ],
            'filters' => $request->only(['type']),
        ]);
    }

    public function store(StorePaymentRequest $request)
    {
        DB::beginTransaction();

        try {
            // Find the payable entity
            $payableClass = $request->payable_type; // e.g., App\Models\Order
            $payable = $payableClass::findOrFail($request->payable_id);

            // Create the payment
            $payment = Payment::create([
                'payable_id' => $payable->id,
                'payable_type' => $payableClass,
                'amount' => $request->amount,
                'payment_method' => $request->payment_method,
                'payment_date' => $request->payment_date,
                'reference_number' => $request->reference_number ?? 'PAY-' . strtoupper(Str::random(6)),
            ]);

            // Update the payable's payment status if it's an Order
            if ($payableClass === Order::class) {
                $totalPaid = $payable->payments()->sum('amount');
                $totalDue = $payable->total_amount;

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
