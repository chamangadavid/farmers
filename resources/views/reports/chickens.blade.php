<!DOCTYPE html>
<html>

<head>

<meta charset="utf-8">

<title>Chicken Farm Report</title>

<style>

body{
    font-family: DejaVu Sans;
    font-size:12px;
    color:#333;
    margin:20px;
}

h1,h2,h3,h4{
    margin:0;
}

.header{
    text-align:center;
    margin-bottom:20px;
}

.header img{
    width:70px;
    margin-bottom:10px;
}

.report-date{
    text-align:right;
    margin-bottom:15px;
    font-size:11px;
}

.summary-table,
.table{

    width:100%;
    border-collapse:collapse;
    margin-bottom:20px;

}

.summary-table td{

    border:1px solid #ddd;
    padding:8px;

}

.table th{

    background:#0b7a3d;
    color:white;
    border:1px solid #ddd;
    padding:8px;

}

.table td{

    border:1px solid #ddd;
    padding:7px;

}

.section-title{

    background:#f2f2f2;
    padding:8px;
    margin-top:20px;
    margin-bottom:10px;
    font-size:15px;
    font-weight:bold;

}

.text-right{

    text-align:right;

}

.text-center{

    text-align:center;

}

.footer{

    position:fixed;
    bottom:0;
    left:0;
    right:0;
    text-align:center;
    font-size:11px;
    color:#777;

}

.page-break{

    page-break-after:always;

}

</style>

</head>

<body>

{{-- ============================= --}}
{{-- HEADER --}}
{{-- ============================= --}}

<div class="header">

    {{-- Uncomment when logo exists --}}
    {{-- <img src="{{ public_path('images/logo.png') }}"> --}}

    <h1>ALINASWE FARM</h1>

    <h3>Commercial Poultry Management System</h3>

    <h2>Chicken Farm Report</h2>

</div>

<div class="report-date">

    Generated :
    {{ now()->format('d M Y H:i') }}

</div>

{{-- ============================= --}}
{{-- SUMMARY --}}
{{-- ============================= --}}

<div class="section-title">

Executive Summary

</div>

<table class="summary-table">

<tr>

<td><strong>Total Birds</strong></td>

<td>{{ $summary['total_birds'] }}</td>

<td><strong>Active Batches</strong></td>

<td>{{ $summary['active_batches'] }}</td>

</tr>

<tr>

<td><strong>Revenue</strong></td>

<td>K {{ number_format($profitLoss['revenue'],2) }}</td>

<td><strong>Expenses</strong></td>

<td>K {{ number_format($profitLoss['expenses'],2) }}</td>

</tr>

<tr>

<td><strong>Net Profit</strong></td>

<td>K {{ number_format($profitLoss['profit'],2) }}</td>

<td><strong>Sales Today</strong></td>

<td>K {{ number_format($summary['sales_today'],2) }}</td>

</tr>

<tr>

<td><strong>Monthly Revenue</strong></td>

<td>K {{ number_format($summary['monthly_revenue'],2) }}</td>

<td><strong>Monthly Profit</strong></td>

<td>K {{ number_format($summary['monthly_profit'],2) }}</td>

</tr>

</table>

{{-- ============================= --}}
{{-- BATCH PERFORMANCE --}}
{{-- ============================= --}}

<div class="section-title">

Batch Performance

</div>

<table class="table">

<thead>

<tr>

<th>Batch</th>

<th>Birds</th>

<th>Mortality %</th>

<th>Feed Cost</th>

<th>Sales</th>

<th>Expenses</th>

<th>Profit</th>

</tr>

</thead>

<tbody>

@foreach($batchPerformance as $batch)

<tr>

<td>{{ $batch['batch_number'] }}</td>

<td class="text-center">{{ $batch['batch_size'] }}</td>

<td class="text-center">{{ number_format($batch['mortality_percentage'],2) }}%</td>

<td class="text-right">
K {{ number_format($batch['feed_cost'],2) }}
</td>

<td class="text-right">
K {{ number_format($batch['sales'],2) }}
</td>

<td class="text-right">
K {{ number_format($batch['expenses'],2) }}
</td>

<td class="text-right">
K {{ number_format($batch['profit'],2) }}
</td>

</tr>

@endforeach

</tbody>

</table>

<div class="page-break"></div>

{{-- ============================= --}}
{{-- SALES REPORT --}}
{{-- ============================= --}}

<div class="section-title">

Sales Report

</div>

<table class="table">

<thead>

<tr>

<th>Date</th>

<th>Batch</th>

<th>Customer</th>

<th>Quantity</th>

<th>Unit Price</th>

<th>Total</th>

</tr>

</thead>

<tbody>

@foreach($sales as $sale)

<tr>

<td>{{ $sale->sale_date }}</td>

<td>{{ optional($sale->batch)->batch_number }}</td>

<td>{{ $sale->customer_name }}</td>

<td class="text-center">{{ $sale->quantity }}</td>

<td class="text-right">
K {{ number_format($sale->unit_price,2) }}
</td>

<td class="text-right">
K {{ number_format($sale->total_amount,2) }}
</td>

</tr>

@endforeach

</tbody>

</table>

{{-- ============================= --}}
{{-- EXPENSE REPORT --}}
{{-- ============================= --}}

<div class="section-title">

Expense Report

</div>

<table class="table">

<thead>

<tr>

<th>Date</th>

<th>Batch</th>

<th>Category</th>

<th>Quantity</th>

<th>Amount</th>

</tr>

</thead>

<tbody>

@foreach($expenses as $expense)

<tr>

<td>{{ $expense->expense_date }}</td>

<td>{{ optional($expense->batch)->batch_number }}</td>

<td>{{ $expense->item }}</td>

<td>{{ $expense->quantity }}</td>

<td class="text-right">
K {{ number_format($expense->amount,2) }}
</td>

</tr>

@endforeach

</tbody>

</table>

{{-- ============================= --}}
{{-- PROFIT & LOSS --}}
{{-- ============================= --}}

<div class="section-title">

Profit & Loss Summary

</div>

<table class="summary-table">

<tr>

<td><strong>Total Revenue</strong></td>

<td>K {{ number_format($profitLoss['revenue'],2) }}</td>

</tr>

<tr>

<td><strong>Total Expenses</strong></td>

<td>K {{ number_format($profitLoss['expenses'],2) }}</td>

</tr>

<tr>

<td><strong>Net Profit</strong></td>

<td>K {{ number_format($profitLoss['profit'],2) }}</td>

</tr>

</table>

{{-- ============================= --}}
{{-- MONTHLY SUMMARY --}}
{{-- ============================= --}}

@if(isset($monthlySummary) && count($monthlySummary))

<div class="section-title">

Monthly Summary

</div>

<table class="table">

<thead>

<tr>

<th>Month</th>

<th>Revenue</th>

<th>Expenses</th>

<th>Profit</th>

<th>Birds Sold</th>

<th>Mortality</th>

</tr>

</thead>

<tbody>

@foreach($monthlySummary as $month)

<tr>

<td>{{ DateTime::createFromFormat('!m',$month->month)->format('F') }} {{ $month->year }}</td>

<td class="text-right">
K {{ number_format($month->revenue,2) }}
</td>

<td class="text-right">
K {{ number_format($month->expenses,2) }}
</td>

<td class="text-right">
K {{ number_format($month->profit,2) }}
</td>

<td class="text-center">
{{ $month->birds_sold }}
</td>

<td class="text-center">
{{ $month->mortality }}
</td>

</tr>

@endforeach

</tbody>

</table>

@endif

{{-- ============================= --}}
{{-- FOOTER --}}
{{-- ============================= --}}

<div class="footer">

Generated by MyFarmer Poultry Management System

<br>

© {{ date('Y') }} Alinaswe Farm

</div>

</body>

</html>

{{-- <!DOCTYPE html>
<html>

<head>

<style>

body{

font-family:DejaVu Sans;

font-size:12px;

}

table{

width:100%;

border-collapse:collapse;

}

th,td{

border:1px solid #ddd;

padding:6px;

}

</style>

</head>

<body>

<h2>Chicken Farm Report</h2>

<hr>

<p>

Revenue :

{{ number_format($summary['revenue'],2) }}

</p>

<p>

Expenses :

{{ number_format($summary['expenses'],2) }}

</p>

<p>

Profit :

{{ number_format($summary['profit'],2) }}

</p>

<h3>Sales</h3>

<table>

<thead>

<tr>

<th>Customer</th>

<th>Quantity</th>

<th>Total</th>

</tr>

</thead>

<tbody>

@foreach($sales as $sale)

<tr>

<td>{{ $sale->customer_name }}</td>

<td>{{ $sale->quantity }}</td>

<td>{{ number_format($sale->total_amount,2) }}</td>

</tr>

@endforeach

</tbody>

</table>

</body>

</html> --}}