<!DOCTYPE html>
<html>

<head>

    <title>
        Chicken Sale Receipt
    </title>


    <style>

        body {

            font-family: Arial, sans-serif;

            background: #f5f5f5;

            padding: 30px;

        }


        .receipt {

            width: 380px;

            margin:auto;

            background:white;

            padding:25px;

            border-radius:10px;

            box-shadow:0 0 10px #ccc;

        }


        .header {

            text-align:center;

            border-bottom:1px dashed #999;

            padding-bottom:15px;

            margin-bottom:20px;

        }


        .header h2 {

            margin:0;

            color:#166534;

        }


        .row {

            display:flex;

            justify-content:space-between;

            margin-bottom:10px;

        }


        .total {

            border-top:1px dashed #999;

            margin-top:15px;

            padding-top:15px;

            font-size:20px;

            font-weight:bold;

        }


        .footer {

            text-align:center;

            margin-top:25px;

            font-size:13px;

            color:#666;

        }


        @media print {

            body {

                background:white;

            }


            .receipt {

                box-shadow:none;

            }


        }


    </style>


</head>


<body>


<div class="receipt">


    <div class="header">

        <h2>
            Chicken Sale Receipt
        </h2>


        <p>

            {{ config('app.name') }}

        </p>


        <small>

            Receipt No:
            CS-{{ $sale->id }}

        </small>


    </div>



    <div class="row">

        <span>
            Date
        </span>

        <strong>
            {{ \Carbon\Carbon::parse($sale->sale_date)->format('d M Y') }}
        </strong>

    </div>



    <div class="row">

        <span>
            Customer
        </span>

        <strong>
            {{ $sale->customer_name ?? '-' }}
        </strong>

    </div>



    <div class="row">

        <span>
            Phone
        </span>

        <strong>
            {{ $sale->customer_phone ?? '-' }}
        </strong>

    </div>



    <hr>



    <div class="row">

        <span>
            Batch
        </span>

        <strong>
            {{ $sale->batch->batch_number ?? '-' }}
        </strong>

    </div>



    <div class="row">

        <span>
            Quantity
        </span>

        <strong>
            {{ $sale->quantity }} Birds
        </strong>

    </div>



    <div class="row">

        <span>
            Price/Bird
        </span>

        <strong>
            K {{ number_format($sale->unit_price,2) }}
        </strong>

    </div>



    <div class="row total">

        <span>
            Total
        </span>

        <span>

            K {{ number_format($sale->total_amount,2) }}

        </span>

    </div>



    <div class="row">

        <span>
            Payment
        </span>

        <strong>

            {{ $sale->payment_method ?? 'Cash' }}

        </strong>

    </div>



    @if($sale->notes)

    <hr>

    <strong>
        Notes
    </strong>

    <p>

        {{ $sale->notes }}

    </p>

    @endif



    <div class="footer">


        Thank you for your purchase.


        <br>


        Powered by {{ config('app.name') }}


    </div>


</div>



<script>

    window.onload = function(){

        window.print();

    }

</script>


</body>


</html>