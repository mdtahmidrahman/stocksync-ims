<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Financial Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 13px;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #3b82f6;
            padding-bottom: 10px;
            margin-bottom: 30px;
        }
        .header h1 {
            margin: 0 0 5px 0;
            color: #1e3a8a;
        }
        .header p {
            margin: 0;
            color: #6b7280;
        }
        .metrics-container {
            width: 100%;
            margin-bottom: 30px;
        }
        .metric-box {
            width: 23%;
            display: inline-block;
            background-color: #f9fafb;
            border: 1px solid #e5e7eb;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
        }
        .metric-box.last {
            margin-right: 0;
        }
        .metric-title {
            font-size: 11px;
            color: #6b7280;
            text-transform: uppercase;
            margin-bottom: 5px;
        }
        .metric-value {
            font-size: 16px;
            font-weight: bold;
            color: #111827;
        }
        .section-title {
            font-size: 16px;
            color: #1f2937;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 5px;
            margin-bottom: 10px;
            margin-top: 30px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #e5e7eb;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f3f4f6;
            color: #374151;
            font-size: 12px;
        }
        td {
            font-size: 12px;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .footer {
            text-align: center;
            font-size: 11px;
            color: #9ca3af;
            margin-top: 50px;
            border-top: 1px solid #e5e7eb;
            padding-top: 10px;
        }
        .status-paid {
            color: #059669;
            font-weight: bold;
        }
        .status-due {
            color: #ef4444;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>{{ $company->name ?? 'StockSync' }}</h1>
        <p>Detailed Financial & Inventory Report ({{ $range }})</p>
        <p style="font-size: 11px; margin-top: 5px;">Generated on: {{ $generatedAt }}</p>
    </div>

    <!-- Summary Metrics -->
    <div class="metrics-container">
        <div class="metric-box">
            <div class="metric-title">Total Purchases</div>
            <div class="metric-value">${{ number_format($totalPurchases, 2) }}</div>
        </div>
        <div class="metric-box">
            <div class="metric-title">Total Sales</div>
            <div class="metric-value">${{ number_format($totalSales, 2) }}</div>
        </div>
        <div class="metric-box">
            <div class="metric-title">Purchase Dues</div>
            <div class="metric-value" style="color: #ef4444;">${{ number_format($purchaseDue, 2) }}</div>
        </div>
        <div class="metric-box last">
            <div class="metric-title">Sales Dues</div>
            <div class="metric-value" style="color: #ef4444;">${{ number_format($salesDue, 2) }}</div>
        </div>
    </div>

    <!-- Recent Sales Activity -->
    <div class="section-title">Sales Activity Breakdown</div>
    @if($sales->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Invoice / Ref</th>
                    <th>Customer</th>
                    <th class="text-right">Total</th>
                    <th class="text-right">Paid</th>
                    <th class="text-right">Due</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sales as $sale)
                @php
                    $paid = $sale->payments->sum('amount');
                    $due = max(0, $sale->total_amount - $paid);
                @endphp
                <tr>
                    <td>{{ \Carbon\Carbon::parse($sale->created_at)->format('d/m/Y') }}</td>
                    <td>{{ $sale->reference_number ?? ('SALE-' . $sale->id) }}</td>
                    <td>{{ $sale->customer->name ?? 'Walk-in Customer' }}</td>
                    <td class="text-right">${{ number_format($sale->total_amount, 2) }}</td>
                    <td class="text-right">${{ number_format($paid, 2) }}</td>
                    <td class="text-right">
                        @if($due > 0)
                            <span class="status-due">${{ number_format($due, 2) }}</span>
                        @else
                            <span class="status-paid">Paid</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p style="color: #6b7280; font-style: italic;">No sales recorded during this period.</p>
    @endif

    <!-- Recent Purchase Activity -->
    <div class="section-title">Purchase Activity Breakdown</div>
    @if($purchases->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Reference</th>
                    <th>Supplier</th>
                    <th class="text-right">Total</th>
                    <th class="text-right">Paid</th>
                    <th class="text-right">Due</th>
                </tr>
            </thead>
            <tbody>
                @foreach($purchases as $purchase)
                @php
                    $paid = $purchase->payments->sum('amount');
                    $due = max(0, $purchase->total_amount - $paid);
                @endphp
                <tr>
                    <td>{{ \Carbon\Carbon::parse($purchase->created_at)->format('d/m/Y') }}</td>
                    <td>{{ $purchase->reference_number ?? ('PUR-' . $purchase->id) }}</td>
                    <td>{{ $purchase->supplier->name ?? 'Unknown Supplier' }}</td>
                    <td class="text-right">${{ number_format($purchase->total_amount, 2) }}</td>
                    <td class="text-right">${{ number_format($paid, 2) }}</td>
                    <td class="text-right">
                        @if($due > 0)
                            <span class="status-due">${{ number_format($due, 2) }}</span>
                        @else
                            <span class="status-paid">Paid</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p style="color: #6b7280; font-style: italic;">No purchases recorded during this period.</p>
    @endif

    <!-- Low Stock Alerts -->
    <div class="section-title">Critical Low Stock Alerts</div>
    @if($lowStockProducts->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>SKU</th>
                    <th>Product Name</th>
                    <th>Category</th>
                    <th class="text-right">Stock Remaining</th>
                </tr>
            </thead>
            <tbody>
                @foreach($lowStockProducts as $product)
                <tr>
                    <td>{{ $product->sku }}</td>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->category->name ?? 'Uncategorized' }}</td>
                    <td class="text-right status-due">
                        {{ $product->stock_quantity }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p style="color: #059669;">No low stock items! All products are adequately stocked.</p>
    @endif

    <div class="footer">
        Powered by StockSync &copy; {{ date('Y') }}
    </div>

</body>
</html>
