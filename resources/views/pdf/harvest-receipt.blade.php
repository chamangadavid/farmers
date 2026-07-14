<!DOCTYPE html>
<html>

<head>

    <title>
        Vegetable Harvest Receipt
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


        .logo {

            font-size: 24px;

            font-weight: bold;

            color: #228B22;

        }


        table {

            width: 100%;

            border-collapse: collapse;

            margin-top: 20px;

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


        .total {

            font-size: 18px;

            font-weight: bold;

        }


        .footer {

            margin-top: 40px;

            text-align: center;

            font-size: 12px;

            color: #777;

        }
    </style>


</head>


<body>


    <div class="header">


        <div class="logo">

            Alinaswe Farm

        </div>


        <h3>

            Vegetable Harvest Receipt

        </h3>


        <p>

            Receipt No:
            #{{ $harvest->id }}

        </p>


    </div>




    <table>


        <tr>

            <th>
                Crop
            </th>

            <td>

                {{ 
$harvest->production?->vegetableType?->name
    ?? 'N/A'
}}

            </td>

        </tr>



        <tr>

            <th>
                Variety
            </th>

            <td>

                {{ 
$harvest->production?->vegetableType?->variety
    ?? 'N/A'
}}

            </td>

        </tr>




        <tr>

            <th>
                Production Batch
            </th>

            <td>

                {{ 
$harvest->production?->batch_number
    ?? 'N/A'
}}

            </td>

        </tr>




        <tr>

            <th>
                Harvest Date
            </th>

            <td>

                {{ 
\Carbon\Carbon::parse(
        $harvest->harvest_date
    )->format('d M Y')
}}

            </td>

        </tr>




        <tr>

            <th>
                Grade
            </th>

            <td>

                {{ $harvest->grade }}

            </td>

        </tr>




        <tr>

            <th>
                Quantity
            </th>

            <td>

                {{ number_format($harvest->quantity, 2) }} Kg

            </td>

        </tr>




        <tr>

            <th>
                Waste
            </th>

            <td>

                {{ number_format($harvest->waste, 2) }} Kg

            </td>

        </tr>




        <tr>

            <th>
                Unit Price
            </th>

            <td>

                K {{ number_format($harvest->unit_price, 2) }}

            </td>

        </tr>




        <tr>

            <th>
                Estimated Value
            </th>

            <td class="total">

                K {{ number_format($harvest->estimated_value, 2) }}

            </td>

        </tr>




        <tr>

            <th>
                Harvested By
            </th>

            <td>

                {{ $harvest->harvested_by }}

            </td>

        </tr>



        <tr>

            <th>
                Notes
            </th>

            <td>

                {{ $harvest->notes ?? 'No notes' }}

            </td>

        </tr>



    </table>



    <div class="footer">

        Thank you for supporting Alinaswe Farm.

    </div>


</body>

</html>