<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>Invoice #{{ $order->id }}</title>
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet" />
<style>
    /* A4 page size */
    @page {
        size: A4;
        margin: 20mm 15mm 20mm 15mm;
    }
    html, body {
        margin: 0; padding: 0; height: 100%;
        font-family: 'Roboto', sans-serif;
        background-color: #f4f6f9;
        color: #333;
        font-size: 14px;
        -webkit-print-color-adjust: exact;
        -moz-print-color-adjust: exact;
        print-color-adjust: exact;
    }
    .invoice-container {
        max-width: 180mm;
        margin: auto;
        background: #fff;
        padding: 20px 25px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        border-radius: 6px;
        box-sizing: border-box;
        page-break-inside: avoid;
    }
    .header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        border-bottom: 2px solid #007bff;
        padding-bottom: 15px;
        margin-bottom: 25px;
    }
    .company h2 {
        margin: 0;
        color: #007bff;
        font-weight: 700;
    }
    .company p {
        margin: 6px 0 0 0;
        font-size: 13px;
        color: #555;
        line-height: 1.4;
    }
    .invoice-title {
        text-align: right;
    }
    .invoice-title h1 {
        margin: 0;
        font-size: 22px;
        font-weight: 700;
        letter-spacing: 1.1px;
    }
    .invoice-title p {
        margin: 6px 0 0 0;
        font-size: 13px;
    }
    h3 {
        margin-top: 30px;
        font-size: 17px;
        color: #007bff;
        font-weight: 600;
        border-bottom: 1.5px solid #e0e0e0;
        padding-bottom: 6px;
    }
    p {
        line-height: 1.5;
        font-size: 13px;
        margin: 3px 0;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 12px;
        font-size: 13px;
    }
    th, td {
        padding: 10px 8px;
        border: 1px solid #ddd;
        text-align: left;
        vertical-align: middle;
    }
    th {
        background-color: #f8f9fa;
        font-weight: 600;
    }
    .text-right {
        text-align: right;
        white-space: nowrap;
    }
    .totals {
        margin-top: 20px;
        width: 100%;
        border-collapse: separate;
        border-spacing: 0 5px;
    }
    .totals td {
        border: none;
        padding: 6px 0;
        font-size: 14px;
    }
    .totals td:last-child {
        width: 160px;
        text-align: right;
        font-weight: 600;
    }
    .totals .grand-total {
        font-size: 16px;
        font-weight: 700;
        border-top: 2px solid #007bff;
        padding-top: 10px;
        color: #007bff;
    }
    .badge {
        display: inline-block;
        padding: 5px 12px;
        background-color: #e9ecef;
        color: #333;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 600;
        line-height: 1;
        vertical-align: middle;
    }
    /* Flex container for payment status for vertical alignment */
    .payment-status {
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .print-button {
        margin-top: 30px;
        text-align: center;
    }
    .print-button button {
        padding: 10px 20px;
        font-size: 15px;
        background-color: #007bff;
        color: #fff;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }
    .print-button button:hover {
        background-color: #0056b3;
    }
    @media print {
        body {
            background-color: white;
            padding: 0; margin: 0;
        }
        .invoice-container {
            box-shadow: none;
            padding: 0;
            margin: 0;
            border-radius: 0;
            max-width: 100%;
        }
        .print-button {
            display: none;
        }
    }
</style>
</head>
<body>
    <div class="invoice-container">

        {{-- Header --}}
        <div class="header">
            <div class="company">
                <h2>Your Company</h2>
                <p>123 Business Street<br>City, State, ZIP<br>contact@yourcompany.com</p>
            </div>
            <div class="invoice-title">
                <h1>INVOICE</h1>
                <p>Invoice #: <strong>#{{ $order->id }}</strong></p>
                <p>Date: <strong>{{ $order->created_at->format('d M Y, H:i') }}</strong></p>
            </div>
        </div>

        {{-- Customer Info --}}
        <h3>Customer Details</h3>
        <p><strong>Name:</strong> {{ $order->customer->name }}</p>
        <p><strong>Email:</strong> {{ $order->customer->email }}</p>
        <p><strong>Mobile:</strong> {{ $order->customer->mobile }}</p>
        <p><strong>Location:</strong> {{ $order->customer->location }}</p>

        {{-- Payment Info --}}
        <h3>Payment Info</h3>
        <p><strong>Method:</strong> {{ ucfirst($order->payment_method ?? 'N/A') }}</p>
        <p><strong>Status:</strong> 
            <span class="payment-status">
                <span class="badge">{{ ucfirst($order->status) }}</span>
            </span>
        </p>

        {{-- Items Table --}}
        <h3>Order Summary</h3>
        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Color</th>
                    <th>Qty</th>
                    <th class="text-right">Unit Price</th>
                    <th class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $subtotal = 0;
                    $shipping = 50;
                    $taxRate = 0.18;
                @endphp
                @foreach($order->items as $item)
                    @php
                        $lineTotal = $item->price * $item->quantity;
                        $subtotal += $lineTotal;
                    @endphp
                    <tr>
                        <td>{{ $item->product->name }}</td>
                        <td>{{ $item->product->color_name ?? '-' }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td class="text-right">₹{{ number_format($item->price, 2) }}</td>
                        <td class="text-right">₹{{ number_format($lineTotal, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Totals --}}
        @php
            $taxAmount = $subtotal * $taxRate;
            $grandTotal = $subtotal + $taxAmount + $shipping;
        @endphp
        <table class="totals">
            <tr>
                <td class="text-right">Subtotal:</td>
                <td>₹{{ number_format($subtotal, 2) }}</td>
            </tr>
            <tr>
                <td class="text-right">Tax (18% GST):</td>
                <td>₹{{ number_format($taxAmount, 2) }}</td>
            </tr>
            <tr>
                <td class="text-right">Shipping:</td>
                <td>₹{{ number_format($shipping, 2) }}</td>
            </tr>
            <tr>
                <td class="text-right grand-total">Grand Total:</td>
                <td class="grand-total">₹{{ number_format($grandTotal, 2) }}</td>
            </tr>
        </table>

        {{-- Uncomment to enable print button --}}
        <!-- 
        <div class="print-button">
            <button onclick="window.print()">🖨️ Print Invoice</button>
        </div>
        -->
    </div>
</body>
</html>
