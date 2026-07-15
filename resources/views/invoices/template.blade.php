<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice {{ $number }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
            text-align: center;
            color: #333;
        }
        
        .invoice-box {
            max-width: 800px;
            margin: auto;
            padding: 30px;
            font-size: 14px;
            line-height: 24px;
            text-align: left;
        }
        
        .invoice-box table {
            width: 100%;
            line-height: inherit;
            text-align: left;
            border-collapse: collapse;
        }
        
        .invoice-box table td {
            padding: 5px;
            vertical-align: top;
        }
        
        .invoice-box table tr td:nth-child(n+2) {
            text-align: right;
        }
        
        .invoice-box table tr.top table td {
            padding-bottom: 20px;
        }
        
        .invoice-box table tr.top table td.title {
            color: #333;
        }
        
        .invoice-box table tr.information table td {
            padding-bottom: 40px;
        }
        
        .invoice-box table tr.heading td {
            background: #eee;
            border-bottom: 1px solid #ddd;
            font-weight: bold;
        }
        
        .invoice-box table tr.details td {
            padding-bottom: 20px;
        }
        
        .invoice-box table tr.item td {
            border-bottom: 1px solid #eee;
        }
        
        .invoice-box table tr.item.last td {
            border-bottom: none;
        }
        
        .invoice-box table tr.total td:nth-child(n+2) {
            border-top: 2px solid #eee;
            font-weight: bold;
        }
        
        .text-right {
            text-align: right;
        }
        
        .text-center {
            text-align: center;
        }
        
        .mb-20 {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="invoice-box">
        <table cellpadding="0" cellspacing="0">
            <tr class="top">
                <td colspan="4">
                    <table>
                        <tr>
                            <td class="title">
                                <div style="display: inline-block; vertical-align: middle;">
                                    <img src="{{ public_path('images/logo-bg-transparent.png') }}" style="max-height: 40px; display: inline-block; vertical-align: middle; margin-right: 10px;" alt="StockSync Logo">
                                    <h2 style="margin: 0; display: inline-block; color: #111827; font-size: 24px; vertical-align: middle;">{{ $company ? $company->name : 'StockSync IMS' }}</h2>
                                </div>
                            </td>
                            
                            <td style="text-align: right;">
                                <strong style="font-size: 20px; color: #4F46E5;">PURCHASE ORDER INVOICE</strong><br>
                                Invoice #: {{ $number }}<br>
                                Date: {{ $date }}<br>
                                Issued By: {{ $cashier }}<br>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            
            <tr class="information">
                <td colspan="4">
                    <table>
                        <tr>
                            <td>
                                <strong>From:</strong><br>
                                {{ $company ? $company->name : 'StockSync IMS' }}<br>
                                {{ $company && $company->address ? $company->address : '123 Business Rd.' }}<br>
                                {{ $company && $company->email ? $company->email : 'billing@stocksync.local' }}
                            </td>
                            
                            <td style="text-align: right;">
                                <strong>Billed To:</strong><br>
                                @if($record->customer)
                                    {{ $record->customer->name }}<br>
                                    {{ $record->customer->address ?? 'N/A' }}<br>
                                    {{ $record->customer->email ?? $record->customer->phone ?? 'N/A' }}
                                @else
                                    Walk-in Customer
                                @endif
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            
            <tr class="heading">
                <td>Item</td>
                <td style="text-align: center;">Qty</td>
                <td>Price</td>
                <td>Total</td>
            </tr>
            
            @foreach($record->items as $item)
            <tr class="item {{ $loop->last ? 'last' : '' }}">
                <td>{{ $item->product ? $item->product->name : 'Unknown Item' }}</td>
                <td style="text-align: center;">{{ $item->quantity }}</td>
                <td>{{ $currency }} {{ number_format($item->unit_price, 2) }}</td>
                <td>{{ $currency }} {{ number_format($item->quantity * $item->unit_price, 2) }}</td>
            </tr>
            @endforeach
            
            <tr class="total">
                <td colspan="2"></td>
                <td>Subtotal:</td>
                <td>{{ $currency }} {{ number_format($record->total_amount, 2) }}</td>
            </tr>
            
            @if(isset($record->tax_amount) && $record->tax_amount > 0)
            <tr class="total">
                <td colspan="2"></td>
                <td>Tax:</td>
                <td>{{ $currency }} {{ number_format($record->tax_amount, 2) }}</td>
            </tr>
            @endif
            
            @if(isset($record->discount_amount) && $record->discount_amount > 0)
            <tr class="total">
                <td colspan="2"></td>
                <td>Discount:</td>
                <td>-{{ $currency }} {{ number_format($record->discount_amount, 2) }}</td>
            </tr>
            @endif
            
            <tr class="total">
                <td colspan="2"></td>
                <td style="border-top: 2px solid #333; padding-top: 10px;">Grand Total:</td>
                <td style="border-top: 2px solid #333; padding-top: 10px;">{{ $currency }} {{ number_format($record->total_amount + ($record->tax_amount ?? 0) - ($record->discount_amount ?? 0), 2) }}</td>
            </tr>
        </table>
        
        <div style="margin-top: 50px; text-align: center; color: #777; font-size: 12px;">
            Thank you for your business!<br>
            If you have any questions concerning this invoice, use the contact information above.
        </div>
    </div>
</body>
</html>
