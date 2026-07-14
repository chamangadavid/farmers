<!DOCTYPE html>

<html>

<head>

<title>
Vegetable Sales Report
</title>


<style>

body{

font-family: Arial;

font-size:12px;

}


h2{

text-align:center;

color:#2f855a;

}


table{

width:100%;

border-collapse:collapse;

}


table, th, td{

border:1px solid #ccc;

}


th{

background:#f1f5f9;

}


th,td{

padding:8px;

}



.total{

font-weight:bold;

}


.summary{

margin-bottom:20px;

}


</style>


</head>


<body>



<h2>
Alinaswe Farm
</h2>


<h3 style="text-align:center">

Vegetable Sales Report

</h3>



<div class="summary">


<strong>
Generated:
</strong>

{{ date('d M Y') }}


<br>


<strong>
Total Records:
</strong>

{{ $sales->count() }}



<br>


<strong>
Total Revenue:
</strong>


K {{ number_format(
$sales->sum('total_amount'),
2
) }}


</div>




<table>


<thead>


<tr>

<th>
#
</th>


<th>
Invoice
</th>


<th>
Date
</th>


<th>
Crop
</th>


<th>
Batch
</th>


<th>
Customer
</th>


<th>
Qty Kg
</th>


<th>
Unit Price
</th>


<th>
Total
</th>


<th>
Payment
</th>


</tr>


</thead>


<tbody>


@foreach($sales as $index=>$sale)


<tr>


<td>

{{ $index+1 }}

</td>



<td>

{{ $sale->invoice_number }}

</td>



<td>

{{ $sale->sale_date }}

</td>



<td>

{{ 
$sale->harvest
->production
->vegetableType
->name
?? '-'
}}

</td>



<td>

{{

$sale->harvest
->production
->batch_number

?? '-'

}}

</td>



<td>

{{ $sale->customer_name }}

</td>



<td>

{{ $sale->quantity }}

</td>



<td>

K {{ number_format($sale->unit_price,2) }}

</td>



<td class="total">

K {{ number_format($sale->total_amount,2) }}

</td>



<td>

{{ $sale->payment_method }}

</td>



</tr>


@endforeach



</tbody>



</table>




<br><br>


 <div class="footer">

    Generated from Alinaswe Farm Sales Management System

    </div>



</body>

</html>