<!DOCTYPE html>
<html>
<head>
    <title>Quotation {{ $quotation->quotation_number }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 20px;
        }
        .company-info {
            margin-top: -120px;
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
        .document-info {
            flex: 1;
            text-align: right;
        }

          .document-info p {
            font-size: 11px; /* Adjust the value as needed */
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 6px;
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
            margin-top: 30px;
            font-size: 12px;
        }

         .bank-details p {
            font-size: 11px;
        }

        .footer {
            margin-top: 30px;
            font-size: 12px;
            text-align: center;
        }
        .customer-info {
            margin-bottom: 15px;
        }
        .terms-conditions, .notes {
            margin-top: 20px;
            font-size: 11px;
        }

        .terms-conditions p {
            font-size: 11px;
        }
    </style>
</head>
<body>

    <div class="header">
    <!-- Right side: QUOTATION info (move it first so it's visually at top-right) -->
    <div class="document-info">
        <h4 style="margin-bottom: 5px;">QUOTATION</h4>
        <p><strong>Quotation #:</strong> {{ $quotation->quotation_number }}</p>
        <p><strong>Date:</strong> {{ $quotation->date->format('d/m/Y') }}</p>
        <p><strong>Validity Date:</strong> {{ $quotation->expiry_date->format('d/m/Y') }}</p>
    </div>

    <!-- Left side: Company info with logo -->
    <div class="company-info">
        <img src="{{ public_path('assets/marz-logo.png') }}" alt="Company Logo">
        <h2 style="margin-bottom: 5px;">{{ config('app.name') }}</h2>
        <p>Permanent House, Cairo Road Second Floor 253A, Lusaka, Zambia</p>
        <p>Phone: +260 966 390 807 | 0976 212 184 </p>
        <p>Email: info@marzinnovationsltd.com</p>
        <p>Tpin #: 2003431233</p>
    </div>
</div>

<div class="customer-info">
    <h3 style="margin-bottom: 5px;">To:</h3>
    @if($quotation->customer->name)
        <p><strong>{{ $quotation->customer->name }}</strong></p>
    @endif
    @if($quotation->customer->address)
        <p>{{ $quotation->customer->address }}</p>
    @endif

    <div class="contact-info" style="display: flex; gap: 15px; margin-bottom: 5px;">
        @if($quotation->customer->phone)
            <span>Phone: {{ $quotation->customer->phone }}</span>
        @endif
        @if($quotation->customer->email)
            <span>Email: {{ $quotation->customer->email }}</span>
        @endif
    </div>

    @if($quotation->customer->tax_id)
        <p>Tax ID: {{ $quotation->customer->tax_id }}</p>
    @endif
</div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Description</th>
                <th>Qty</th>
                <th>Unit Price</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($quotation->items as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->description }}</td>
                <td>{{ $item->quantity }}</td>
                <td>{{ number_format($item->unit_price, 2) }}</td>
                <td>{{ number_format($item->total, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="totals">
        <p><strong>Sub Total:</strong> {{ number_format($quotation->subtotal, 2) }}</p>
        <p><strong>Tax:</strong> {{ number_format($quotation->tax, 2) }}</p>
        <p style="font-size: 12px; font-weight: bold;">
            <strong>Total:</strong> {{ number_format($quotation->total, 2) }}
        </p>
    </div>

    <!-- Bank Details (always shown) -->
    <div class="bank-details">
        <h4>Bank Payment Details</h4>
        <p><strong>Bank Name:</strong> INDO ZAMBIA BANK</p>
        <p><strong>Account Name:</strong> MARZ INNOVATIONS LIMITED</p>
        <p><strong>Account Number:</strong> 0142030001151</p>
        <p><strong>Sort Code:</strong> 090014</p>
    </div>

    @if($quotation->terms)
    <div class="terms-conditions">
        <h4>Terms & Conditions</h4>
        <p style="white-space: pre-line;">{{ $quotation->terms }}</p>
    </div>
    @endif

    {{-- @if($quotation->notes)
    <div class="notes">
        <h3>Notes</h3>
        <p style="white-space: pre-line;">{{ $quotation->notes }}</p>
    </div>
    @endif --}}

    <div class="footer">
        <p>Creating value together!</p>
        <p>{{ config('app.name') }} | www.marzinnovationsltd.com</p>
    </div>

</body>
</html>
