<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Order;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Company;

class InvoiceController extends Controller
{
    public function saleInvoice(Sale $sale)
    {
        $sale->load(['customer', 'items.product']);
        $company = Company::first();
        $currencyRaw = $company ? $company->currency : '$';
        $currency = preg_match('/\((.*?)\)/', $currencyRaw, $m) ? $m[1] : $currencyRaw;
        $currency = str_replace('৳', 'Tk.', $currency);

        $pdf = Pdf::loadView('invoices.pos', [
            'type' => 'Sale',
            'record' => $sale,
            'company' => $company,
            'currency' => $currency,
            'cashier' => auth()->user() ? auth()->user()->name : 'Staff',
            'number' => $sale->invoice_number ?? $sale->reference_number,
            'date' => $sale->created_at->format('M d, Y'),
        ])->setPaper([0, 0, 226.77, 800], 'portrait');

        return $pdf->download('Receipt_' . ($sale->invoice_number ?? $sale->reference_number) . '.pdf');
    }

    public function orderInvoice(Order $order)
    {
        $order->load(['customer', 'items.product']);
        $company = Company::first();
        $currencyRaw = $company ? $company->currency : '$';
        $currency = preg_match('/\((.*?)\)/', $currencyRaw, $m) ? $m[1] : $currencyRaw;
        $currency = str_replace('৳', 'Tk.', $currency);

        $pdf = Pdf::loadView('invoices.template', [
            'type' => 'Order',
            'record' => $order,
            'company' => $company,
            'currency' => $currency,
            'cashier' => auth()->user() ? auth()->user()->name : 'Staff',
            'number' => $order->order_number,
            'date' => $order->created_at->format('M d, Y'),
        ]);

        return $pdf->download('Invoice_' . $order->order_number . '.pdf');
    }
}
