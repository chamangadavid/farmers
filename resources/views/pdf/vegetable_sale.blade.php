<!DOCTYPE html>

<html>

<head>

    <title>
        Vegetable Sale Receipt
    </title>


    <style>
        body {

            font-family: Arial, sans-serif;

            font-size: 14px;

        }


        .header {

            text-align: center;

            margin-bottom: 30px;

        }


        h1 {

            color: #2f855a;

        }


        table {

            width: 100%;

            border-collapse: collapse;

            margin-bottom: 20px;

        }


        table,
        th,
        td {

            border: 1px solid #ddd;

        }


        th,
        td {

            padding: 10px;

            text-align: left;

        }


        .section-title {

            background: #f3f4f6;

            font-weight: bold;

            padding: 8px;

            margin-top: 15px;

        }


        .total {

            font-size: 18px;

            font-weight: bold;

        }


        .footer {

            margin-top: 40px;

            text-align: center;

            font-size: 12px;

        }
    </style>


</head>


<body>


    <div class="header">


        <h1>
            Vegetable Sale Receipt
        </h1>


        <p>
            Invoice:
            {{ $sale->invoice_number }}
        </p>


    </div>




    <div class="section-title">

        Sale Information

    </div>


    <table>


        <tr>

            <th>
                Invoice Number
            </th>

            <td>
                {{ $sale->invoice_number }}
            </td>

        </tr>


        <tr>

            <th>
                Sale Date
            </th>

            <td>
                {{ $sale->sale_date }}
            </td>

        </tr>


        <tr>

            <th>
                Payment Method
            </th>

            <td>
                {{ $sale->payment_method }}
            </td>

        </tr>


        <tr>

            <th>
                Status
            </th>

            <td>
                {{ $sale->status }}
            </td>

        </tr>


    </table>






    <div class="section-title">

        Customer Details

    </div>


    <table>


        <tr>

            <th>
                Customer Name
            </th>


            <td>
                {{ $sale->customer_name }}
            </td>


        </tr>


        <tr>

            <th>
                Phone
            </th>


            <td>
                {{ $sale->customer_phone }}
            </td>


        </tr>


    </table>






    <div class="section-title">

        Vegetable Details

    </div>


    <table>


        <tr>

            <th>
                Crop
            </th>


            <td>

                {{

    $sale->harvest
        ->production
        ->vegetableType
        ->name

}}

            </td>


        </tr>



        <tr>

            <th>
                Variety
            </th>


            <td>

                {{

    $sale->harvest
        ->production
        ->vegetableType
        ->variety

}}

            </td>


        </tr>



        <tr>

            <th>
                Batch Number
            </th>


            <td>

                {{

    $sale->harvest
        ->production
        ->batch_number

}}

            </td>


        </tr>


    </table>






    <div class="section-title">

        Payment Summary

    </div>



    <table>


        <tr>

            <th>
                Quantity
            </th>


            <td>
                {{ $sale->quantity }} Kg
            </td>


        </tr>



        <tr>

            <th>
                Unit Price
            </th>


            <td>

                K {{ number_format($sale->unit_price, 2) }}

            </td>


        </tr>



        <tr>

            <th>
                Total Amount
            </th>


            <td class="total">

                K {{ number_format($sale->total_amount, 2) }}

            </td>


        </tr>



    </table>






    <div class="section-title">

        Notes

    </div>


    <p>

        {{ $sale->notes ?? 'No notes available' }}

    </p>

    <div class="footer">

    Generated from Alinaswe Farm Sales Management System

    </div>



</body>


</html>