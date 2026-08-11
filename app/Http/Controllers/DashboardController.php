<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Purchase;
use App\Models\Order;
use App\Models\Product;
use App\Models\SaleItem;
use App\Models\PurchaseItem;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // KPIs
        $totalInventoryValue = Product::selectRaw('SUM(cost * stock_quantity) as total_value')->value('total_value') ?? 0;
        
        $outOfStockCount = Product::where('stock_quantity', '<=', 0)->count();
        $lowStockCount = Product::where('stock_quantity', '>', 0)->where('stock_quantity', '<', 10)->count();
        $healthyStockCount = Product::where('stock_quantity', '>=', 10)->count();
        $totalProducts = $outOfStockCount + $lowStockCount + $healthyStockCount;
        $healthyPercentage = $totalProducts > 0 ? round(($healthyStockCount / $totalProducts) * 100) : 0;
        
        $todaySales = Sale::whereDate('created_at', Carbon::today())->sum('total_amount');
        
        $pendingDeliveries = Purchase::whereIn('status', ['pending', 'draft'])->count();

        // Weekly Sales vs Restock Analytics (Last 7 days)
        $weeklySalesRestock = [];
        $maxChartValue = 1; // Default to avoid division by zero
        
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            
            $itemsSold = SaleItem::whereDate('created_at', $date)->sum('quantity') ?? 0;
            $itemsRestocked = PurchaseItem::whereDate('created_at', $date)->sum('quantity') ?? 0;
            
            $weeklySalesRestock[] = [
                'label' => $date->format('D'),
                'itemsSold' => (int) $itemsSold,
                'itemsRestocked' => (int) $itemsRestocked,
            ];

            if ($itemsSold > $maxChartValue) $maxChartValue = $itemsSold;
            if ($itemsRestocked > $maxChartValue) $maxChartValue = $itemsRestocked;
        }

        // Calculate heights for the frontend
        $maxBarHeight = 208;
        foreach ($weeklySalesRestock as &$day) {
            $day['soldHeight'] = round(($day['itemsSold'] / $maxChartValue) * $maxBarHeight);
            $day['restockedHeight'] = round(($day['itemsRestocked'] / $maxChartValue) * $maxBarHeight);
        }

        // Top Performing Products (by Revenue)
        $topProducts = SaleItem::select('product_id', DB::raw('SUM(quantity) as total_sold'), DB::raw('SUM(subtotal) as total_revenue'))
            ->with('product')
            ->groupBy('product_id')
            ->orderByDesc('total_revenue')
            ->limit(4)
            ->get();

        // Attention Required Feed
        $attentionFeed = Product::where('stock_quantity', '<', 10)->limit(4)->get()->map(function ($product) {
            return [
                'id' => $product->id,
                'message' => "{$product->name} (SKU: {$product->sku}) is running low on stock ({$product->stock_quantity} remaining).",
                'type' => 'low_stock',
            ];
        });

        // Recent Activity Timeline
        $recentSales = Sale::with('user')->latest()->limit(5)->get()->map(function ($sale) {
            return [
                'type' => 'sale',
                'title' => 'New POS Sale Completed',
                'description' => "Invoice {$sale->invoice_number} for $" . number_format($sale->total_amount, 2),
                'time' => $sale->created_at->diffForHumans(),
                'created_at' => $sale->created_at
            ];
        });

        $recentPurchases = Purchase::with('supplier')->latest()->limit(5)->get()->map(function ($purchase) {
            return [
                'type' => 'purchase',
                'title' => 'Purchase Order Created',
                'description' => "PO {$purchase->reference_number} created for " . ($purchase->supplier->name ?? 'Vendor'),
                'time' => $purchase->created_at->diffForHumans(),
                'created_at' => $purchase->created_at
            ];
        });

        $recentActivity = collect($recentSales)->merge($recentPurchases)->sortByDesc('created_at')->take(5)->values();

        return Inertia::render('Dashboard', [
            'kpis' => [
                'totalInventoryValue' => $totalInventoryValue,
                'lowStockCount' => $lowStockCount + $outOfStockCount,
                'todaySales' => $todaySales,
                'pendingDeliveries' => $pendingDeliveries,
            ],
            'stockStatus' => [
                'healthy' => $healthyStockCount,
                'low' => $lowStockCount,
                'out' => $outOfStockCount,
                'percentage' => $healthyPercentage
            ],
            'weeklySalesRestock' => $weeklySalesRestock,
            'topProducts' => $topProducts,
            'attentionFeed' => $attentionFeed,
            'recentActivity' => $recentActivity,
        ]);
    }
}
