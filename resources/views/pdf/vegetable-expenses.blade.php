<!DOCTYPE html>
<html>

<head>

    <title>
        Vegetable Expenses Report
    </title>


    <style>
        body {

            font-family: Arial, sans-serif;

            font-size: 12px;

        }


        h2 {

            text-align: center;

        }


        table {

            width: 100%;

            border-collapse: collapse;

        }


        table,
        th,
        td {

            border: 1px solid #333;

        }


        th {

            background: #eee;

        }


        th,
        td {

            padding: 8px;

        }


        .total {

            text-align: right;

            font-weight: bold;

        }
    </style>


</head>


<body>


    <h2>
        Alinaswe Farm
    </h2>


    <h3>
        Vegetable Expense Report
    </h3>


    <p>
        Generated:
        {{ now()->format('d M Y') }}
    </p>



    <table>


        <thead>

            <tr>

                <th>#</th>

                <th>Date</th>

                <th>Batch</th>

                <th>Vegetable</th>

                <th>Category</th>

                <th>Supplier</th>

                <th>Quantity</th>

                <th>Unit Cost</th>

                <th>Total</th>


            </tr>

        </thead>



        <tbody>


            @foreach($expenses as $index => $expense)


                <tr>


                    <td>
                        {{ $index + 1 }}
                    </td>


                    <td>
                        {{ \Carbon\Carbon::parse($expense->expense_date)->format('d M Y') }}
                    </td>


                    <td>
                        {{ $expense->production->batch_number ?? '' }}
                    </td>


                    <td>
                        {{ $expense->production->vegetableType->name ?? '' }}
                    </td>


                    <td>
                        {{ $expense->expense_category }}
                    </td>


                    <td>
                        {{ $expense->supplier }}
                    </td>


                    <td>
                        {{ $expense->quantity }}
                        {{ $expense->unit }}
                    </td>


                    <td>
                        K {{ number_format($expense->unit_cost, 2) }}
                    </td>


                    <td>
                        K {{ number_format($expense->amount, 2) }}
                    </td>


                </tr>


            @endforeach


        </tbody>


    </table>



    <br>


    <h3 class="total">

        Total Expenses:

        K {{ number_format($expenses->sum('amount'), 2) }}

    </h3>


    
    <div class="footer">

        Generated from Alinaswe Farm Sales Management System

    </div>
    
</body>

</html>