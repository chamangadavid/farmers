<!DOCTYPE html>
<html>

<head>
    <title>Delivery Note #{{ $deliveryNote->delivery_note_number }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 20px;
        }

        .company-info {
            flex: 1;
        }

        .company-info img {
            height: 60px;
            margin-bottom: 10px;
        }

        .company-info h4 {
            margin: 5px 0;
            font-size: 16px;
        }

        .company-info p {
            font-size: 10px;
            margin: 3px 0;
            line-height: 1.3;
        }

        .delivery-note-header {
            text-align: right;
        }

        .delivery-note-header h3 {
            margin: 0;
            font-size: 20px;
        }

        .delivery-note-header p {
            font-size: 12px;
            margin: 5px 0;
        }

        .info-table {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
        }

        .info-table td {
            padding: 5px;
            vertical-align: top;
            font-size: 12px;
        }

        /* Align second column (right side) text to the right */
        .info-table td:last-child {
            text-align: right;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .items-table th,
        .items-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        .items-table th {
            background-color: #f2f2f2;
            font-size: 12px;
        }

          .items-table td {
            font-size: 11px;
        }

        .signature {
            margin-top: 50px;
        }

        .signature-table {
            width: 100%;
        }

        .signature-table td {
            width: 50%;
            padding: 10px;
            font-size: 12px;
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
        {{-- <div class="delivery-note-header">
            <h3>DELIVERY NOTE</h3>
            <p>No: {{ $deliveryNote->delivery_note_number }}</p>
            <p>Date: {{ $deliveryNote->date->format('d/m/Y') }}</p>
        </div> --}}
    </div>

    <table class="info-table">
        <tr>
            <td width="50%">
                <strong>Deliver To:</strong><br>
                {{ $deliveryNote->customer->name }}<br>
                {{ $deliveryNote->delivery_address }}<br>
                @if($deliveryNote->customer->phone)
                    Phone: {{ $deliveryNote->customer->phone }}<br>
                @endif
                @if($deliveryNote->customer->tax_id)
                    Tax ID: {{ $deliveryNote->customer->tax_id }}
                @endif
            </td>
            <td width="50%">
                {{-- <strong>Details:</strong><br> --}}
                <h4>DELIVERY NOTE</h4>
                <p>No: {{ $deliveryNote->delivery_note_number }}</p>
                <p>Date: {{ $deliveryNote->date->format('d/m/Y') }}</p>

                @if($deliveryNote->reference_number)
                    Reference: {{ $deliveryNote->reference_number }}<br>
                @endif
                @if($deliveryNote->vehicle_number)
                    Vehicle: {{ $deliveryNote->vehicle_number }}<br>
                @endif
            </td>
        </tr>
    </table>

    <table class="items-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Description</th>
                <th>Quantity</th>
                <th>Unit</th>
                <th>Unit Price</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($deliveryNote->items as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->description }}</td>
                    <td>{{ number_format($item->quantity, 2) }}</td>
                    <td>{{ $item->unit ?? '-' }}</td>
                    <td>{{ number_format($item->unit_price, 2) }}</td>
                    <td>{{ number_format($item->total, 2) }}</td>
                </tr>
            @endforeach
            <tr>
                <td colspan="5" style="text-align: right;"><strong>Total:</strong></td>
                <td><strong>{{ number_format($deliveryNote->total, 2) }}</strong></td>
            </tr>
        </tbody>
    </table>

    @if($deliveryNote->notes)
        <div style="margin-bottom: 20px; font-size: 12px;">
            <strong>Notes:</strong><br>
            {{ $deliveryNote->notes }}
        </div>
    @endif

    <div class="signature">
        <table class="signature-table">
            <tr>
                <td>
                    <strong>Prepared By:</strong><br><br>
                    _______________________<br>
                    Name/Signature
                </td>
                <td>
                    <strong>Received By:</strong><br><br>
                    ________________________<br>
                    @if($deliveryNote->received_by)
                        {{ $deliveryNote->received_by }}<br>
                    @endif
                    Name/Signature
                </td>
            </tr>
        </table>
    </div>

    <div class="footer">
        <p>Creating value together!</p>
        <p>{{ config('app.name') }} | www.marzinnovationsltd.com</p>
    </div>

</body>

</html>