<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Purchase;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Carbon\Carbon;

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

        $totalPurchases = (float) Purchase::where('created_at', '>=', $startDate)->sum('total_amount');
        $totalSales = (float) Sale::where('created_at', '>=', $startDate)->sum('total_amount');
        
        $purchases = Purchase::with('payments')->where('created_at', '>=', $startDate)->get();
        $purchaseDue = (float) $purchases->sum(function ($p) {
            return max(0, $p->total_amount - $p->payments->sum('amount'));
        });

        $sales = Sale::with('payments')->where('created_at', '>=', $startDate)->get();
        $salesDue = (float) $sales->sum(function ($s) {
            return max(0, $s->total_amount - $s->payments->sum('amount'));
        });

        $lowStockProducts = Product::with('category')
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

            $mSales = (float) Sale::whereBetween('created_at', [$monthStart, $monthEnd])->sum('total_amount');
            $mPurchases = (float) Purchase::whereBetween('created_at', [$monthStart, $monthEnd])->sum('total_amount');

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
}
