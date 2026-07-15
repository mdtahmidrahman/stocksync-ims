<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Receipt {{ $number }}</title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 11px;
            color: #000;
            margin: 0;
            padding: 0;
            text-align: left;
            width: 100%;
        }
        
        .receipt-container {
            width: 100%;
            margin: 0 auto;
            padding: 5px;
        }

        .center {
            text-align: center;
        }

        .bold {
            font-weight: bold;
        }

        .logo {
            max-width: 60px;
            max-height: 40px;
            margin-bottom: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
            margin-bottom: 5px;
        }

        th, td {
            padding: 3px 0;
            vertical-align: top;
        }

        th {
            border-top: 1px dashed #000;
            border-bottom: 1px dashed #000;
            text-align: left;
        }

        .text-right {
            text-align: right;
        }
        
        .text-center {
            text-align: center;
        }

        .item-name {
            display: block;
            margin-bottom: 2px;
        }

        .totals-table {
            border-top: 1px dashed #000;
            margin-top: 5px;
        }

        .totals-table td {
            padding: 2px 0;
        }
        
        .grand-total {
            font-size: 13px;
            font-weight: bold;
            border-top: 1px dashed #000;
            border-bottom: 1px dashed #000;
            padding: 5px 0 !important;
        }

        .footer {
            text-align: center;
            margin-top: 15px;
            font-size: 10px;
        }
        
        .divider {
            border-top: 1px dashed #000;
            margin: 5px 0;
        }
    </style>
</head>
<body>
    <div class="receipt-container">
        <div class="center">
            @if(file_exists(public_path('images/logo-bg-transparent.png')))
                <img src="{{ public_path('images/logo-bg-transparent.png') }}" class="logo" alt="Logo"><br>
            @endif
            <strong style="font-size: 14px;">{{ $company ? $company->name : 'StockSync IMS' }}</strong><br>
            {{ $company && $company->address ? $company->address : '123 Business Rd.' }}<br>
            {{ $company && $company->phone ? $company->phone : '+1 234 567 8900' }}<br>
        </div>
        
        <div class="divider"></div>
        
        <div>
            Receipt #: {{ $number }}<br>
            Date: {{ $date }}<br>
            Cashier: {{ $cashier }}<br>
            Customer: {{ $record->customer ? $record->customer->name : 'Walk-in' }}
        </div>
        
        <table>
            <thead>
                <tr>
                    <th style="width: 50%;">Item</th>
                    <th style="width: 15%;" class="text-center">Qty</th>
                    <th style="width: 35%;" class="text-right">Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach($record->items as $item)
                <tr>
                    <td>
                        <span class="item-name">{{ $item->product ? $item->product->name : 'Unknown' }}</span>
                        <span style="font-size: 9px;">{{ $currency }} {{ number_format($item->unit_price, 2) }}</span>
                    </td>
                    <td class="text-center">{{ $item->quantity }}</td>
                    <td class="text-right">{{ $currency }} {{ number_format($item->quantity * $item->unit_price, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        <table class="totals-table">
            <tr>
                <td>Subtotal</td>
                <td class="text-right">{{ $currency }} {{ number_format($record->total_amount, 2) }}</td>
            </tr>
            @if(isset($record->tax_amount) && $record->tax_amount > 0)
            <tr>
                <td>Tax</td>
                <td class="text-right">{{ $currency }} {{ number_format($record->tax_amount, 2) }}</td>
            </tr>
            @endif
            @if(isset($record->discount_amount) && $record->discount_amount > 0)
            <tr>
                <td>Discount</td>
                <td class="text-right">-{{ $currency }} {{ number_format($record->discount_amount, 2) }}</td>
            </tr>
            @endif
            <tr>
                <td class="grand-total">Total</td>
                <td class="text-right grand-total">{{ $currency }} {{ number_format($record->total_amount + ($record->tax_amount ?? 0) - ($record->discount_amount ?? 0), 2) }}</td>
            </tr>
            <tr>
                <td style="padding-top: 5px;">Payment Mode</td>
                <td class="text-right" style="padding-top: 5px; text-transform: uppercase;">{{ $record->payment_method }}</td>
            </tr>
        </table>
        
        <div class="footer">
            Thank you for shopping with us!<br>
            Please visit again.
            <div style="margin-top: 10px;">
                *** CUSTOMER COPY ***
            </div>
        </div>
    </div>
</body>
</html>
