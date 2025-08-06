<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice {{ $invoice->invoice_number }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
            color: #333;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 20px;
        }


         .company-info {
            /* margin-top: -60px; */
            width: 60%;
        }

        .company-info p {
            font-size: 11px; /* Adjust the value as needed */
        }

         .company-info img {
            max-width: 100px; /* Reduced from 150px */
            /* height: auto; */
            margin-bottom: 2px;
        }

         .document-info p {
            font-size: 11px; /* Adjust the value as needed */
        }


        .document-info {
            margin-top: -400px;
            flex: 1;
            text-align: right;
        }

        .status-badge {
            padding: 4px 10px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
            margin-left: 10px;
        }

        .paid {
            background-color: #2ecc71;
            color: white;
        }

        .unpaid {
            background-color: #e74c3c;
            color: white;
        }

        .due-date {
            color: #e74c3c;
            font-weight: bold;
        }

        .
        .bill-to {
            margin-top: 110px;
        }

        .bill-to p{
            font-size: 12px;
        }


        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th, table td {
            border: 1px solid #ccc;
            padding: 12px;
            text-align: left;
        }

        table th {
            background-color: #f2f2f2;
            font-size: 12px;
        }

        table td {
            font-size: 11px;
        }

        .totals {
            margin-top: 20px;
            width: 100%;
            text-align: right;
        }

        .totals p {
            font-size: 12px;
            margin: 4px 0;
        }


        .bank-details {
            margin-top: -15px;
            font-size: 11px;
        }
        .bank-details p {
            font-size: 11px;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 12px;
            color: #888;
        }
    </style>
</head>
<body>

    <div class="header">
        <div class="company-info">
            <img src="{{ public_path('assets/marz-logo.png') }}" alt="Company Logo">
            <h4>{{ config('app.name') }}</h4>
            <p>Permanent House, Cairo Road 2nd Floor 253A, Lusaka, Zambia</p>
            <p>Phone: +260 966 390 807 | +260 976 212 184 Email: info@marzinnovationsltd.com</p>
            <p>Tpin #: 2003431233</p>
        </div>

        <div class="document-info">
            <h4>INVOICE</h4>
            <p><strong>Invoice #:</strong> {{ $invoice->invoice_number }}</p>
            <p><strong>Date:</strong> {{ $invoice->date->format('d/m/Y') }}</p>
            <p>
                <strong>Validity:</strong>
                <span class="{{ \Carbon\Carbon::now()->gt($invoice->due_date) ? 'due-date' : '' }}">
                    {{ $invoice->due_date->format('d/m/Y') }}
                </span>
                <span class="status-badge {{ $invoice->paid_at ? 'paid' : 'unpaid' }}">
                    {{ $invoice->paid_at ? 'PAID' : 'UNPAID' }}
                </span>
            </p>
            @if($invoice->paid_at)
                <p><strong>Paid On:</strong> {{ $invoice->paid_at->format('d/m/Y') }}</p>
            @endif
        </div>
    </div>

    <div class="bill-to">
        <h4>Bill To:</h4>
        <p>
            {{ $invoice->customer->name ?? 'N/A' }}<br>
            {{ $invoice->customer->address ?? 'N/A' }}<br>
            {{ $invoice->customer->email ?? 'N/A' }}
        </p>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Description</th>
                <th>Qty</th>
                <th>Unit Price</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($invoice->items as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->description }}</td>
                    <td>{{ $item->quantity }}</td>
                     <td>{{ number_format($item->unit_price, 2) }}</td>
                    <td>{{ number_format($item->quantity * $item->unit_price, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="totals">
        <p><strong>Subtotal:</strong> {{ number_format($invoice->subtotal, 2) }}</p>
        <p><strong>Tax ({{ $invoice->tax_rate }}%):</strong> {{ number_format($invoice->tax_amount, 2) }}</p>
        <p><strong>Total:</strong> {{ number_format($invoice->total, 2) }}</p>
    </div>

       <!-- Bank Details (always shown) -->
    <div class="bank-details">
        <h4>Bank Payment Details</h4>
        <p><strong>Bank Name:</strong> INDO ZAMBIA BANK</p>
        <p><strong>Account Name:</strong> MARZ INNOVATIONS LIMITED</p>
        <p><strong>Account Number:</strong> 0142030001151</p>
        <p><strong>Sort Code:</strong> 090014</p>
    </div>
    {{-- <div class="footer">
        Creating Values Together!
    </div> --}}
     <div class="footer">
        <p>Creating value together!</p>
        <p>{{ config('app.name') }} | www.marzinnovationsltd.com</p>
    </div>

</body>
</html>
