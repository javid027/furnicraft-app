<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice #{{ $order->id }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 40px;
            background-color: #f4f6f9;
            color: #333;
        }

        .invoice-container {
            max-width: 800px;
            margin: auto;
            background-color: #fff;
            padding: 40px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
            border-radius: 8px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 2px solid #007bff;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }

        .company h2 {
            margin: 0;
            color: #007bff;
        }

        .invoice-title {
            text-align: right;
        }

        .invoice-title h1 {
            margin: 0;
            font-size: 24px;
        }

        h3 {
            margin-top: 30px;
            font-size: 18px;
            color: #007bff;
        }

        p {
            line-height: 1.6;
            font-size: 14px;
            margin: 4px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            font-size: 14px;
        }

        th, td {
            padding: 12px;
            border: 1px solid #e0e0e0;
            text-align: left;
        }

        th {
            background-color: #f8f9fa;
            font-weight: 500;
        }

        .text-right {
            text-align: right;
        }

        .totals {
            margin-top: 25px;
        }

        .totals td {
            border: none;
            padding: 6px 0;
        }

        .totals td:last-child {
            width: 180px;
            text-align: right;
        }

        .totals .grand-total {
            font-size: 16px;
            font-weight: bold;
            border-top: 2px solid #007bff;
            padding-top: 10px;
        }

        .badge {
            display: inline-block;
            padding: 4px 10px;
            background-color: #e9ecef;
            color: #333;
            border-radius: 4px;
            font-size: 12px;
        }

        .print-button {
            margin-top: 40px;
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
        }

        .print-button button:hover {
            background-color: #0056b3;
        }

        @media print {
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
        <p><strong>Status:</strong> <span class="badge">{{ ucfirst($order->status) }}</span></p>

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

        <!-- {{-- Print Button --}}
        <div class="print-button">
            <button onclick="window.print()">🖨️ Print Invoice</button>
        </div> -->
    </div>
</body>
</html>
