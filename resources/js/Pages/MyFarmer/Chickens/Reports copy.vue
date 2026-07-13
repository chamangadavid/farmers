<!-- resources\js\Pages\MyFarmer\Chickens\Reports.vue -->
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import axios from 'axios'
import { message } from 'ant-design-vue'
import { ref, onMounted, computed } from 'vue'
import dayjs from 'dayjs'


const loading = ref(false)

const filter = ref('month')

const customDates = ref([])

const summary = ref({

    total_birds:0,

    active_batches:0,

    sales_today:0,

    monthly_revenue:0,

    monthly_profit:0

})

const charts = ref({
    revenue: [],
    expenses: [],
    mortality: [],
    salesByBatch: []
})


const batchPerformance = ref([])

const sales = ref([])

const expenses = ref([])


const profitLoss = ref({

    revenue:0,

    expenses:0,

    profit:0

})



const money=(value)=>{

    return Number(value ?? 0)
        .toLocaleString(undefined,{
            minimumFractionDigits:2
        })

}



const fetchReports = async()=>{


loading.value=true


try{



const { data } = await axios.get('/chicken-reports/dashboard',{

    params:{

        filter:filter.value,

        from:customDates.value[0]
            ? dayjs(customDates.value[0]).format('YYYY-MM-DD')
            :null,

        to:customDates.value[1]
            ? dayjs(customDates.value[1]).format('YYYY-MM-DD')
            :null
    }

})

summary.value=data.summary


batchPerformance.value=data.batches


sales.value=data.sales


expenses.value=data.expenses


profitLoss.value=data.profitLoss

charts.value=data.charts



}
catch(error){

console.log(error)

message.error(
    'Unable to load reports'
)

}
finally{

loading.value=false

}


}



onMounted(()=>{

    fetchReports()

})


const revenueSeries = computed(() => [

{

name:'Revenue',

data:charts.value.revenue.map(x=>x.revenue)

}

])

const revenueOptions = computed(()=>({

chart:{
toolbar:{show:false}
},

stroke:{
curve:'smooth'
},

xaxis:{
categories:charts.value.revenue.map(x=>x.month)
}

}))

const expenseSeries = computed(()=>

charts.value.expenses.map(x=>x.total)

)

const expenseOptions = computed(()=>({

labels:charts.value.expenses.map(x=>x.item)

}))

const mortalitySeries = computed(() => [

{

name:'Mortality',

data:charts.value.mortality.map(x=>x.mortality)

}

])

const mortalityOptions = computed(()=>({

xaxis:{

categories:charts.value.mortality.map(x=>x.batch)

}

}))

const downloadPdf=()=>{

window.open(

`/chicken-reports/pdf?filter=${filter.value}`

)

}

const downloadExcel=()=>{

window.open(

`/chicken-reports/excel?filter=${filter.value}`

)

}

</script>
<template>
  <Head title="Chickens Report" />

 <AuthenticatedLayout>

<div class="p-6">


<h1 class="text-3xl font-bold mb-8">

Chicken Farm Reports Dashboard

</h1>

<a-card class="mb-6">

<div class="flex flex-wrap justify-between items-center gap-4">

<div class="flex gap-3">

<a-select
v-model:value="filter"
style="width:180px"
@change="fetchReports">

<a-select-option value="today">
Today
</a-select-option>

<a-select-option value="week">
This Week
</a-select-option>

<a-select-option value="month">
This Month
</a-select-option>

<a-select-option value="custom">
Custom Range
</a-select-option>

</a-select>

<a-range-picker

v-if="filter=='custom'"

v-model:value="customDates"

@change="fetchReports"

/>

</div>

<div class="flex gap-2">

<a-button
type="primary"
@click="downloadPdf">

Download PDF

</a-button>

<a-button
type="primary"
ghost
@click="downloadExcel">

Download Excel

</a-button>

</div>

</div>

</a-card>



<!-- ========================= -->
<!-- SUMMARY CARDS -->
<!-- ========================= -->


<div class="grid grid-cols-1 md:grid-cols-5 gap-5 mb-8">



<a-card>


<div class="text-center">

<p>Total Birds</p>

<h2 class="text-3xl font-bold text-green-600">

{{summary.total_birds}}

</h2>

</div>


</a-card>



<a-card>


<div class="text-center">

<p>Active Batches</p>

<h2 class="text-3xl font-bold text-blue-600">

{{summary.active_batches}}

</h2>

</div>


</a-card>




<a-card>


<div class="text-center">

<p>Sales Today</p>

<h2 class="text-3xl font-bold text-purple-600">

K {{money(summary.sales_today)}}

</h2>

</div>


</a-card>





<a-card>


<div class="text-center">

<p>Monthly Revenue</p>

<h2 class="text-3xl font-bold text-indigo-600">

K {{money(summary.monthly_revenue)}}

</h2>

</div>


</a-card>





<a-card>


<div class="text-center">

<p>Monthly Profit</p>

<h2 class="text-3xl font-bold text-green-600">

K {{money(summary.monthly_profit)}}

</h2>

</div>


</a-card>



</div>


<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">

<a-card title="Revenue Trend">

<apexchart

height="350"

type="line"

:options="revenueOptions"

:series="revenueSeries"

/>

</a-card>

<a-card title="Expenses by Category">

<apexchart

height="350"

type="pie"

:options="expenseOptions"

:series="expenseSeries"

/>

</a-card>

</div>



<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">

<a-card title="Mortality by Batch">

<apexchart

height="350"

type="bar"

:options="mortalityOptions"

:series="mortalitySeries"

/>

</a-card>

<a-card title="Sales by Batch">

<apexchart

height="350"

type="bar"

:options="{

xaxis:{
categories:charts.salesByBatch.map(x=>x.batch)
}

}"

:series="[{

name:'Sales',

data:charts.salesByBatch.map(x=>x.sales)

}]"

/>

</a-card>

</div>



<!-- ========================= -->
<!-- BATCH PERFORMANCE -->
<!-- ========================= -->


<a-card title="Batch Performance Report"
class="mb-8">


<a-table

:loading="loading"

:data-source="batchPerformance"

:pagination="false"


:columns="[

{
title:'Batch',
dataIndex:'batch_number'
},

{
title:'Starting Birds',
dataIndex:'batch_size'
},

{
title:'Mortality %',
dataIndex:'mortality_percentage'
},

{
title:'Feed Cost',
dataIndex:'feed_cost'
},

{
title:'Sales',
dataIndex:'sales'
},

{
title:'Profit',
dataIndex:'profit'
}


]"



>


</a-table>


</a-card>





<!-- ========================= -->
<!-- SALES REPORT -->
<!-- ========================= -->


<a-card title="Sales Report"
class="mb-8">


<a-table

:data-source="sales"

:pagination="{
pageSize:10
}"

:columns="[


{
title:'Customer',
dataIndex:'customer_name'
},

{
title:'Quantity',
dataIndex:'quantity'
},

{
title:'Price',
dataIndex:'unit_price'
},

{
title:'Payment',
dataIndex:'payment_method'
},

{
title:'Revenue',
dataIndex:'total_amount'
}


]"


>


</a-table>


</a-card>





<!-- ========================= -->
<!-- EXPENSE REPORT -->
<!-- ========================= -->


<a-card title="Expense Report"
class="mb-8">


<a-table

:data-source="expenses"

:columns="[


{
title:'Category',
dataIndex:'item'
},


{
title:'Quantity',
dataIndex:'quantity'
},


{
title:'Amount',
dataIndex:'amount'
}


]"



>


</a-table>


</a-card>






<!-- ========================= -->
<!-- PROFIT LOSS -->
<!-- ========================= -->


<a-card title="Profit / Loss Report">


<div class="grid md:grid-cols-3 gap-5 text-center">



<div>

<p>Revenue</p>

<h2 class="text-3xl font-bold text-blue-600">

K {{money(profitLoss.revenue)}}

</h2>

</div>




<div>

<p>Expenses</p>

<h2 class="text-3xl font-bold text-red-600">

K {{money(profitLoss.expenses)}}

</h2>

</div>




<div>

<p>Net Profit</p>

<h2 class="text-3xl font-bold text-green-600">

K {{money(profitLoss.profit)}}

</h2>

</div>



</div>


</a-card>



</div>

 </AuthenticatedLayout>


</template>