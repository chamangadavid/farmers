<!DOCTYPE html>
<html>

<head>

    <meta charset="UTF-8">

    <title>General Expenses Report</title>

    <style>

        body {

            font-family: DejaVu Sans, sans-serif;

            font-size: 11px;

            color: #333;

        }

        .header {

            text-align: center;

            margin-bottom: 20px;

        }

        .header h1 {

            margin: 0;

            font-size: 22px;

        }

        .header p {

            margin: 5px 0;

            color: #666;

        }

        .summary {

            width: 100%;

            margin-bottom: 20px;

        }

        .summary td {

            width: 33.33%;

            padding: 10px;

            border: 1px solid #ddd;

            text-align: center;

        }

        .summary-title {

            font-size: 10px;

            color: #666;

        }

        .summary-value {

            font-size: 16px;

            font-weight: bold;

            margin-top: 5px;

        }

        table {

            width: 100%;

            border-collapse: collapse;

        }

        th {

            background: #f2f2f2;

            font-weight: bold;

        }

        th,

        td {

            border: 1px solid #ccc;

            padding: 7px;

            text-align: left;

        }

        .amount {

            text-align: right;

        }

        .footer {

            margin-top: 20px;

            text-align: center;

            font-size: 9px;

            color: #777;

        }

    </style>

</head>

<body>


    <div class="header">

        <h1>GENERAL EXPENSES REPORT</h1>

        <p>

            Generated:

            {{ now()->format('d M Y H:i') }}

        </p>

    </div>


    <table class="summary">

        <tr>

            <td>

                <div class="summary-title">

                    TOTAL EXPENSES

                </div>

                <div class="summary-value">

                    {{ $expenses->count() }}

                </div>

            </td>


            <td>

                <div class="summary-title">

                    TOTAL AMOUNT

                </div>

                <div class="summary-value">

                    K

                    {{ number_format($totalAmount, 2) }}

                </div>

            </td>


            <td>

                <div class="summary-title">

                    PAID AMOUNT

                </div>

                <div class="summary-value">

                    K

                    {{ number_format($paidAmount, 2) }}

                </div>

            </td>

        </tr>

    </table>


    <table>

        <thead>

            <tr>

                <th>#</th>

                <th>Date</th>

                <th>Category</th>

                <th>Expense Name</th>

                <th>Vendor / Payee</th>

                <th>Payment Method</th>

                <th>Status</th>

                <th class="amount">

                    Amount

                </th>

            </tr>

        </thead>


        <tbody>

            @forelse($expenses as $index => $expense)

                <tr>

                    <td>

                        {{ $index + 1 }}

                    </td>


                    <td>

                        {{ \Carbon\Carbon::parse(

                            $expense->expense_date

                        )->format('d M Y') }}

                    </td>


                    <td>

                        {{ $expense->expense_category }}

                    </td>


                    <td>

                        {{ $expense->expense_name }}

                    </td>


                    <td>

                        {{ $expense->vendor ?? '-' }}

                    </td>


                    <td>

                        {{ $expense->payment_method ?? '-' }}

                    </td>


                    <td>

                        {{ $expense->payment_status }}

                    </td>


                    <td class="amount">

                        K

                        {{ number_format(

                            $expense->amount,

                            2

                        ) }}

                    </td>

                </tr>

            @empty

                <tr>

                    <td

                        colspan="8"

                        style="text-align: center"

                    >

                        No general expenses found.

                    </td>

                </tr>

            @endforelse

        </tbody>


        <tfoot>

            <tr>

                <th colspan="7">

                    TOTAL

                </th>

                <th class="amount">

                    K

                    {{ number_format(

                        $totalAmount,

                        2

                    ) }}

                </th>

            </tr>

        </tfoot>

    </table>


    <div class="footer">

        General Expenses Report

    </div>


</body>

</html>