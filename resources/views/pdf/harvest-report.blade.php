<!DOCTYPE html>
<html>

<head>

<title>
Vegetable Harvest Report
</title>


<style>

body{

font-family:Arial;

font-size:12px;

}


h2{

text-align:center;

color:#228B22;

}


table{

width:100%;

border-collapse:collapse;

margin-top:20px;

}


table,th,td{

border:1px solid #ccc;

}


th,td{

padding:8px;

}


.footer{

margin-top:30px;

text-align:center;

font-size:11px;

}


</style>


</head>


<body>


<h2>
Alinaswe Farm
</h2>


<h3 style="text-align:center">

Vegetable Harvest Report

</h3>



<table>


<thead>

<tr>

<th>#</th>

<th>Crop</th>

<th>Date</th>

<th>Quantity</th>

<th>Grade</th>

<th>Unit Price</th>

<th>Value</th>

</tr>

</thead>



<tbody>


@foreach($harvests as $index=>$harvest)


<tr>


<td>

{{ $index+1 }}

</td>



<td>

{{ 
$harvest->production?->vegetableType?->name
?? 'N/A'
}}

</td>



<td>

{{ 
\Carbon\Carbon::parse(
$harvest->harvest_date
)->format('d M Y')
}}

</td>



<td>

{{ number_format($harvest->quantity,2) }}

Kg

</td>



<td>

{{ $harvest->grade }}

</td>



<td>

K {{ number_format($harvest->unit_price,2) }}

</td>



<td>

K {{ number_format($harvest->estimated_value,2) }}

</td>


</tr>


@endforeach


</tbody>


</table>



<br>


<h3>

Summary

</h3>


<p>

Total Harvest Records:
{{ $harvests->count() }}

</p>


<p>

Total Quantity:
{{ number_format($harvests->sum('quantity'),2) }}
Kg

</p>


<p>

Total Revenue:
K {{ number_format($harvests->sum('estimated_value'),2) }}

</p>



<div class="footer">

Generated from Alinaswe Farm Harvest Management System

</div>



</body>

</html>