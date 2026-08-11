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
        $user = auth()->user();
        $companyId = $user ? $user->company_id : null;

        // Base Scoped Queries
        $productQuery = Product::query();
        $saleQuery = Sale::query();
        $purchaseQuery = Purchase::query();
        $saleItemQuery = SaleItem::query();
        $purchaseItemQuery = PurchaseItem::query();

        if ($companyId) {
            $productQuery->where('company_id', $companyId);
            $saleQuery->where('company_id', $companyId);
            $purchaseQuery->where('company_id', $companyId);
            $saleItemQuery->whereHas('sale', fn($q) => $q->where('company_id', $companyId));
            $purchaseItemQuery->whereHas('purchase', fn($q) => $q->where('company_id', $companyId));
        }

        // KPIs
        $totalInventoryValue = (clone $productQuery)->selectRaw('SUM(cost * stock_quantity) as total_value')->value('total_value') ?? 0;
        
        $outOfStockCount = (clone $productQuery)->where('stock_quantity', '<=', 0)->count();
        $lowStockCount = (clone $productQuery)->where('stock_quantity', '>', 0)->where('stock_quantity', '<', 10)->count();
        $healthyStockCount = (clone $productQuery)->where('stock_quantity', '>=', 10)->count();
        $totalProducts = $outOfStockCount + $lowStockCount + $healthyStockCount;
        $healthyPercentage = $totalProducts > 0 ? round(($healthyStockCount / $totalProducts) * 100) : 0;
        
        $todaySales = (clone $saleQuery)->whereDate('created_at', Carbon::today())->sum('total_amount');
        $yesterdaySales = (clone $saleQuery)->whereDate('created_at', Carbon::yesterday())->sum('total_amount');
        
        $salesGrowth = 0;
        if ($yesterdaySales > 0) {
            $salesGrowth = round((($todaySales - $yesterdaySales) / $yesterdaySales) * 100, 1);
        }
        
        $pendingDeliveries = (clone $purchaseQuery)->whereIn('status', ['pending', 'draft'])->count();

        // Weekly Sales vs Restock Analytics (Last 7 days)
        $weeklySalesRestock = [];
        $maxChartValue = 1; // Default to avoid division by zero
        
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            
            $itemsSold = (clone $saleItemQuery)->whereDate('created_at', $date)->sum('quantity') ?? 0;
            $itemsRestocked = (clone $purchaseItemQuery)->whereDate('created_at', $date)->sum('quantity') ?? 0;
            
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
        $totalSalesRev = (clone $saleItemQuery)->sum('subtotal') ?: 1;

        $topProducts = (clone $saleItemQuery)
            ->select('product_id', DB::raw('SUM(quantity) as total_sold'), DB::raw('SUM(subtotal) as total_revenue'))
            ->with(['product.category'])
            ->groupBy('product_id')
            ->orderByDesc('total_revenue')
            ->limit(5)
            ->get()
            ->map(function ($item) use ($totalSalesRev) {
                $product = $item->product;
                return [
                    'product_id' => $item->product_id,
                    'name' => $product ? $product->name : 'Unknown Product',
                    'sku' => $product ? $product->sku : 'N/A',
                    'category' => ($product && $product->category) ? $product->category->name : 'General',
                    'stock_quantity' => $product ? $product->stock_quantity : 0,
                    'price' => $product ? (float) $product->price : 0,
                    'total_sold' => (int) $item->total_sold,
                    'total_revenue' => (float) $item->total_revenue,
                    'share_percentage' => min(100, round(($item->total_revenue / $totalSalesRev) * 100, 1)),
                ];
            });

        // Attention Required Feed
        $attentionFeed = (clone $productQuery)->where('stock_quantity', '<', 10)->limit(4)->get()->map(function ($product) {
            return [
                'id' => $product->id,
                'message' => "{$product->name} (SKU: {$product->sku}) is running low on stock ({$product->stock_quantity} remaining).",
                'type' => 'low_stock',
            ];
        });

        // Recent Activity Timeline
        $recentSales = (clone $saleQuery)->with('user')->latest()->limit(5)->get()->map(function ($sale) {
            return [
                'type' => 'sale',
                'title' => 'New POS Sale Completed',
                'description' => "Invoice {$sale->invoice_number} for $" . number_format($sale->total_amount, 2),
                'time' => $sale->created_at->diffForHumans(),
                'created_at' => $sale->created_at
            ];
        });

        $recentPurchases = (clone $purchaseQuery)->with('supplier')->latest()->limit(5)->get()->map(function ($purchase) {
            return [
                'type' => 'purchase',
                'title' => 'Purchase Order Created',
                'description' => "PO {$purchase->purchase_number} created for " . ($purchase->supplier->name ?? 'Vendor'),
                'time' => $purchase->created_at->diffForHumans(),
                'created_at' => $purchase->created_at
            ];
        });

        $recentActivity = collect($recentSales)->merge($recentPurchases)->sortByDesc('created_at')->take(5)->values();

        // Monthly Financials & Profit
        $startOfMonth = Carbon::now()->startOfMonth();
        $monthlyPosSales = (clone $saleQuery)->where('created_at', '>=', $startOfMonth)->sum('total_amount');
        $monthlyOrders = Order::query()->when($companyId, fn($q) => $q->where('company_id', $companyId))->where('created_at', '>=', $startOfMonth)->sum('total_amount');
        $monthlyRevenue = $monthlyPosSales + $monthlyOrders;

        // Cost of Goods Sold (COGS) for current month
        $cogsSales = (clone $saleItemQuery)->where('sale_items.created_at', '>=', $startOfMonth)->join('products', 'sale_items.product_id', '=', 'products.id')->sum(DB::raw('sale_items.quantity * products.cost'));
        $cogsOrders = \App\Models\OrderItem::query()->whereHas('order', function($q) use ($companyId, $startOfMonth) {
            if ($companyId) $q->where('company_id', $companyId);
            $q->where('created_at', '>=', $startOfMonth);
        })->join('products', 'order_items.product_id', '=', 'products.id')->sum(DB::raw('order_items.quantity * products.cost'));
        
        $totalCogs = $cogsSales + $cogsOrders;
        $monthlyGrossProfit = max(0, $monthlyRevenue - $totalCogs);

        // Uncollected Customer Receivables (Dues)
        $uncollectedOrders = Order::query()->when($companyId, fn($q) => $q->where('company_id', $companyId))->with('payments')->get();
        $totalReceivables = (float) $uncollectedOrders->sum(fn($o) => max(0, $o->total_amount - $o->payments->sum('amount')));

        // Order Pipeline Status Counts
        $orderQuery = Order::query()->when($companyId, fn($q) => $q->where('company_id', $companyId));
        $orderPipeline = [
            'pending' => (clone $orderQuery)->where('status', 'pending')->count(),
            'processing' => (clone $orderQuery)->where('status', 'processing')->count(),
            'shipped' => (clone $orderQuery)->where('status', 'shipped')->count(),
            'delivered' => (clone $orderQuery)->where('status', 'delivered')->count(),
        ];

        // Top Selling Categories Breakdown
        $categorySales = DB::table('sale_items')
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->join('products', 'sale_items.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->when($companyId, fn($q) => $q->where('sales.company_id', $companyId))
            ->select('categories.name', DB::raw('SUM(sale_items.subtotal) as total_revenue'), DB::raw('SUM(sale_items.quantity) as total_sold'))
            ->groupBy('categories.id', 'categories.name')
            ->orderByDesc('total_revenue')
            ->limit(5)
            ->get();

        $totalCategoryRev = $categorySales->sum('total_revenue') ?: 1;
        $topCategories = $categorySales->map(function($cat) use ($totalCategoryRev) {
            return [
                'name' => $cat->name,
                'revenue' => (float) $cat->total_revenue,
                'sold' => (int) $cat->total_sold,
                'percentage' => min(100, round(($cat->total_revenue / $totalCategoryRev) * 100)),
            ];
        });

        return Inertia::render('Dashboard', [
            'kpis' => [
                'totalInventoryValue' => $totalInventoryValue,
                'inventoryBadge' => "{$healthyPercentage}% Health Rate",
                'lowStockCount' => $lowStockCount + $outOfStockCount,
                'lowStockBadge' => ($lowStockCount + $outOfStockCount > 0) ? ($outOfStockCount > 0 ? "{$outOfStockCount} Out of Stock" : "{$lowStockCount} Low") : "Optimal",
                'isLowStockWarning' => ($lowStockCount + $outOfStockCount) > 0,
                'todaySales' => $todaySales,
                'todaySalesBadge' => $yesterdaySales > 0 ? ($salesGrowth >= 0 ? "+{$salesGrowth}% vs yest." : "{$salesGrowth}% vs yest.") : 'Live Today',
                'todaySalesPositive' => $salesGrowth >= 0,
                'pendingDeliveries' => $pendingDeliveries,
                'pendingDeliveriesBadge' => $pendingDeliveries > 0 ? "{$pendingDeliveries} Open POs" : "Up to Date",
                'monthlyRevenue' => $monthlyRevenue,
                'monthlyGrossProfit' => $monthlyGrossProfit,
                'totalReceivables' => $totalReceivables,
            ],
            'stockStatus' => [
                'healthy' => $healthyStockCount,
                'low' => $lowStockCount,
                'out' => $outOfStockCount,
                'percentage' => $healthyPercentage
            ],
            'weeklySalesRestock' => $weeklySalesRestock,
            'topProducts' => $topProducts,
            'topCategories' => $topCategories,
            'orderPipeline' => $orderPipeline,
            'attentionFeed' => $attentionFeed,
            'recentActivity' => $recentActivity,
        ]);
    }
}
