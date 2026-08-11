<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Order;
use App\Models\Purchase;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $range = $request->query('range', 'Last 30 Days');
        
        $startDate = match ($range) {
            'This Month' => Carbon::now()->startOfMonth(),
            'This Year' => Carbon::now()->startOfYear(),
            default => Carbon::now()->subDays(30),
        };

        $companyId = auth()->user() ? auth()->user()->company_id : 1;

        $totalPurchases = (float) Purchase::where('company_id', $companyId)->where('created_at', '>=', $startDate)->sum('total_amount');
        
        $posSalesSum = (float) Sale::where('company_id', $companyId)->where('created_at', '>=', $startDate)->sum('total_amount');
        $orderSalesSum = (float) Order::where('company_id', $companyId)->where('created_at', '>=', $startDate)->sum('total_amount');
        $totalSales = $posSalesSum + $orderSalesSum;

        $purchases = Purchase::with('payments')->where('company_id', $companyId)->where('created_at', '>=', $startDate)->get();
        $purchaseDue = (float) $purchases->sum(function ($p) {
            return max(0, $p->total_amount - $p->payments->sum('amount'));
        });

        $sales = Sale::with('payments')->where('company_id', $companyId)->where('created_at', '>=', $startDate)->get();
        $posSalesDue = (float) $sales->sum(function ($s) {
            return max(0, $s->total_amount - $s->payments->sum('amount'));
        });

        $orders = Order::with('payments')->where('company_id', $companyId)->where('created_at', '>=', $startDate)->get();
        $orderSalesDue = (float) $orders->sum(function ($o) {
            return max(0, $o->total_amount - $o->payments->sum('amount'));
        });

        $salesDue = $posSalesDue + $orderSalesDue;

        $lowStockProducts = Product::where('company_id', $companyId)
            ->with('category')
            ->where('stock_quantity', '<=', 5)
            ->orderBy('stock_quantity', 'asc')
            ->take(10)
            ->get();

        // Calculate Monthly Sales vs Purchases for Chart (Last 6 Months)
        $chartMonths = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $monthStart = $month->copy()->startOfMonth();
            $monthEnd = $month->copy()->endOfMonth();

            $mPosSales = (float) Sale::where('company_id', $companyId)->whereBetween('created_at', [$monthStart, $monthEnd])->sum('total_amount');
            $mOrders = (float) Order::where('company_id', $companyId)->whereBetween('created_at', [$monthStart, $monthEnd])->sum('total_amount');
            $mSales = $mPosSales + $mOrders;

            $mPurchases = (float) Purchase::where('company_id', $companyId)->whereBetween('created_at', [$monthStart, $monthEnd])->sum('total_amount');

            $chartMonths[] = [
                'label' => $month->format('M'),
                'sales' => $mSales,
                'purchases' => $mPurchases,
            ];
        }

        return Inertia::render('Reports', [
            'metrics' => [
                'totalPurchases' => $totalPurchases,
                'totalSales' => $totalSales,
                'purchaseDue' => $purchaseDue,
                'salesDue' => $salesDue,
            ],
            'chartMonths' => $chartMonths,
            'lowStockProducts' => $lowStockProducts,
            'dateRange' => $range,
        ]);
    }

    public function export(Request $request)
    {
        $range = $request->query('range', 'Last 30 Days');
        
        $startDate = match ($range) {
            'This Month' => Carbon::now()->startOfMonth(),
            'This Year' => Carbon::now()->startOfYear(),
            default => Carbon::now()->subDays(30),
        };

        $companyId = auth()->user() ? auth()->user()->company_id : 1;
        $company = auth()->user() ? auth()->user()->company : \App\Models\Company::find(1);

        $currencyRaw = $company ? $company->currency : '$';
        $currency = preg_match('/\((.*?)\)/', $currencyRaw, $m) ? $m[1] : $currencyRaw;
        $currency = str_replace('৳', 'Tk.', $currency);

        $totalPurchases = (float) Purchase::where('company_id', $companyId)->where('created_at', '>=', $startDate)->sum('total_amount');
        
        $posSalesSum = (float) Sale::where('company_id', $companyId)->where('created_at', '>=', $startDate)->sum('total_amount');
        $orderSalesSum = (float) Order::where('company_id', $companyId)->where('created_at', '>=', $startDate)->sum('total_amount');
        $totalSales = $posSalesSum + $orderSalesSum;

        $purchases = Purchase::with(['payments', 'supplier'])->where('company_id', $companyId)->where('created_at', '>=', $startDate)->orderBy('created_at', 'desc')->get();
        $purchaseDue = (float) $purchases->sum(function ($p) {
            return max(0, $p->total_amount - $p->payments->sum('amount'));
        });

        $sales = Sale::with(['payments', 'customer'])->where('company_id', $companyId)->where('created_at', '>=', $startDate)->orderBy('created_at', 'desc')->get();
        $posSalesDue = (float) $sales->sum(function ($s) {
            return max(0, $s->total_amount - $s->payments->sum('amount'));
        });

        $orders = Order::with(['payments', 'customer'])->where('company_id', $companyId)->where('created_at', '>=', $startDate)->orderBy('created_at', 'desc')->get();
        $orderSalesDue = (float) $orders->sum(function ($o) {
            return max(0, $o->total_amount - $o->payments->sum('amount'));
        });

        $salesDue = $posSalesDue + $orderSalesDue;

        $lowStockProducts = Product::where('company_id', $companyId)
            ->with('category')
            ->where('stock_quantity', '<=', 5)
            ->orderBy('stock_quantity', 'asc')
            ->take(20)
            ->get();

        $pdf = Pdf::loadView('reports.pdf', [
            'company' => $company,
            'currency' => $currency,
            'range' => $range,
            'totalPurchases' => $totalPurchases,
            'totalSales' => $totalSales,
            'purchaseDue' => $purchaseDue,
            'salesDue' => $salesDue,
            'sales' => $sales,
            'orders' => $orders,
            'purchases' => $purchases,
            'lowStockProducts' => $lowStockProducts,
            'generatedAt' => Carbon::now()->format('d/m/Y, h:i A')
        ]);

        return $pdf->download("Financial_Report_" . str_replace(' ', '_', $range) . ".pdf");
    }
}
