<!-- resources\js\Pages\MyFarmer\Vegetables\Expenses\Index.vue -->
<script setup>
import { Head } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import VueApexCharts from 'vue3-apexcharts'

import { ref, computed, watch, onMounted } from 'vue'
import axios from 'axios'
import dayjs from 'dayjs'

import {
    SearchOutlined,
    ReloadOutlined,
    PlusOutlined,
    EyeOutlined,
    EditOutlined,
    DeleteOutlined,
    FilePdfOutlined,
    FileExcelOutlined
} from '@ant-design/icons-vue'

import {
    message,
    Modal
} from 'ant-design-vue'

import CreateExpense from './CreateExpense.vue'
import EditExpense from './EditExpense.vue'
import ViewExpense from './ViewExpense.vue'

/*
|--------------------------------------------------------------------------
| Reactive Variables
|--------------------------------------------------------------------------
*/

const loading = ref(false)

const expenses = ref([])

const selectedExpense = ref(null)

/*
|--------------------------------------------------------------------------
| Modal State
|--------------------------------------------------------------------------
*/

const showCreate = ref(false)

const showEdit = ref(false)

const showView = ref(false)

/*
|--------------------------------------------------------------------------
| Dashboard Statistics
|--------------------------------------------------------------------------
*/

const statistics = ref({

    totalExpenses: 0,

    totalCost: 0,

    totalBatches: 0,

    averageExpense: 0

})

/*
|--------------------------------------------------------------------------
| Search & Filters
|--------------------------------------------------------------------------
*/

const search = ref('')

const categoryFilter = ref('All')

const paymentFilter = ref('All')

const dateFilter = ref('All')
//const dateFilter = ref('Month')

const customDates = ref([])

/*
|--------------------------------------------------------------------------
| Table Columns
|--------------------------------------------------------------------------
*/

const columns = [

    {
        title: '#',
        key: 'index',
        width: 70
    },

    {
        title: 'Date',
        dataIndex: 'expense_date',
        key: 'expense_date',
        width: 140
    },

    {
        title: 'Batch',
        key: 'batch',
        width: 150
    },

    {
        title: 'Vegetable',
        key: 'vegetable',
        width: 180
    },

    {
        title: 'Category',
        dataIndex: 'expense_category',
        key: 'expense_category',
        width: 170
    },


    {
        title: 'Supplier',
        dataIndex: 'supplier',
        key: 'supplier',
        width: 180
    },

    {
        title: 'Quantity',
        dataIndex: 'quantity',
        key: 'quantity',
        width: 120
    },

    {
        title: 'Unit Cost',
        key: 'unit_cost',
        width: 140
    },

    {
        title: 'Total Cost',
        key: 'amount',
        width: 160
    },

    {
        title: 'Payment',
        dataIndex: 'payment_method',
        key: 'payment_method',
        width: 150
    },

    {
        title: 'Status',
        key: 'status',
        width: 160
    },

    {
        title: 'Actions',
        key: 'actions',
        fixed: 'right',
        width: 170
    }

]

/*
|--------------------------------------------------------------------------
| Dashboard Charts
|--------------------------------------------------------------------------
*/

const expenseTrendSeries = ref([])

const expenseTrendOptions = ref({})

const categorySeries = ref([])

const categoryOptions = ref({})

const supplierSeries = ref([])

const supplierOptions = ref({})

const batchSeries = ref([])

const batchOptions = ref({})

/*
|--------------------------------------------------------------------------
| CRUD Functions
|--------------------------------------------------------------------------
*/

const fetchExpenses = async () => {

    loading.value = true

    try {

        const { data } = await axios.get('/vegetable-expenses')


        //expenses.value = data.data ?? data
        expenses.value = Array.isArray(data) ? data : data.data

        calculateStatistics()

        buildCharts()

    }
    catch (error) {

        console.error(error)

        message.error('Unable to load expenses.')

    }
    finally {

        loading.value = false

    }

}

const calculateStatistics = () => {


    statistics.value.totalExpenses =
        expenses.value.length


    statistics.value.totalCost =
        expenses.value.reduce(
            (sum,item)=>sum + Number(item.amount),
            0
        )


    statistics.value.totalBatches =
        new Set(
            expenses.value.map(
                e=>e.vegetable_production_id
            )
        ).size



    statistics.value.averageExpense =
        statistics.value.totalExpenses
        ?
        statistics.value.totalCost /
        statistics.value.totalExpenses
        :
        0


}


const buildCharts = () => {

    if (!expenses.value.length) {
        return
    }


    /*
    |--------------------------------------------------------------------------
    | Expense Trend Line Chart (Monthly)
    |--------------------------------------------------------------------------
    */

    const monthlyExpenses = {}


    expenses.value.forEach(expense => {

        const month = dayjs(expense.expense_date)
            .format('MMM YYYY')


        if (!monthlyExpenses[month]) {

            monthlyExpenses[month] = 0

        }


        monthlyExpenses[month] += Number(expense.amount)

    })


    expenseTrendSeries.value = [
        {
            name: 'Expenses',
            data: Object.values(monthlyExpenses)
        }
    ]


    expenseTrendOptions.value = {

        chart: {
            type: 'line',
            toolbar:{
                show:true
            }
        },

        xaxis:{
            categories:Object.keys(monthlyExpenses)
        },

        stroke:{
            curve:'smooth'
        },

        dataLabels:{
            enabled:false
        }

    }



    /*
    |--------------------------------------------------------------------------
    | Expense Category Pie Chart
    |--------------------------------------------------------------------------
    */


    const categories = {}


    // expenses.value.forEach(expense=>{


    //     const category = expense.expense_category


    //     if(!categories[category]){

    //         categories[category]=0

    //     }


    //     categories[category]+=Number(expense.amount)


    // })

      filteredExpenses.value.forEach(expense=>{


        const category = expense.expense_category


        if(!categories[category]){

            categories[category]=0

        }


        categories[category]+=Number(expense.amount)


    })


    categorySeries.value = Object.values(categories)


    categoryOptions.value = {

        labels:Object.keys(categories),

        legend:{
            position:'bottom'
        },


        chart:{
            type:'pie'
        }

    }



    /*
    |--------------------------------------------------------------------------
    | Expense Category Bar Chart
    |--------------------------------------------------------------------------
    */


    supplierSeries.value = [
        {
            name:'Expenses',
            data:Object.values(categories)
        }
    ]


    supplierOptions.value = {

        chart:{
            type:'bar'
        },


        xaxis:{
            categories:Object.keys(categories)
        }


    }



    /*
    |--------------------------------------------------------------------------
    | Batch Expense Bar Chart
    |--------------------------------------------------------------------------
    */


    const batches={}


    expenses.value.forEach(expense=>{


        const batch =
            expense.production?.batch_number ?? 'Unknown'


        if(!batches[batch]){

            batches[batch]=0

        }


        batches[batch]+=Number(expense.amount)


    })



    batchSeries.value=[

        {
            name:'Total Expense',
            data:Object.values(batches)
        }

    ]


    batchOptions.value={


        chart:{
            type:'bar'
        },


        xaxis:{
            categories:Object.keys(batches)
        }

    }


}

const refresh = () => {

}

/*
|--------------------------------------------------------------------------
| Search & Filters
|--------------------------------------------------------------------------
*/

const filteredExpenses = computed(() => {


    let data = [...expenses.value]


    /*
    |--------------------------------------------------------------------------
    | Search Filter
    |--------------------------------------------------------------------------
    */

    if(search.value){


        const keyword = search.value.toLowerCase()


        data = data.filter(expense => {


            return (

                expense.expense_category
                    ?.toLowerCase()
                    .includes(keyword)


                ||

                expense.supplier
                    ?.toLowerCase()
                    .includes(keyword)


                ||

                expense.description
                    ?.toLowerCase()
                    .includes(keyword)


                ||

                expense.production?.batch_number
                    ?.toLowerCase()
                    .includes(keyword)


            )


        })


    }



    /*
    |--------------------------------------------------------------------------
    | Category Filter
    |--------------------------------------------------------------------------
    */


    if(categoryFilter.value !== 'All'){


        data = data.filter(expense =>

            expense.expense_category === categoryFilter.value

        )


    }




    /*
    |--------------------------------------------------------------------------
    | Payment Filter
    |--------------------------------------------------------------------------
    */


    if(paymentFilter.value !== 'All'){


        data = data.filter(expense =>

            expense.payment_method === paymentFilter.value

        )


    }




    /*
    |--------------------------------------------------------------------------
    | Date Filter
    |--------------------------------------------------------------------------
    */


    const today = dayjs()


    if(dateFilter.value === 'All'){

        return data

    }

    if(dateFilter.value === 'Today'){


        data = data.filter(expense =>

            dayjs(expense.expense_date)
            .isSame(today,'day')

        )


    }



    if(dateFilter.value === 'Week'){


        data = data.filter(expense =>

            dayjs(expense.expense_date)
            .isAfter(
                today.subtract(7,'day')
            )

        )


    }



    if(dateFilter.value === 'Month'){


        data = data.filter(expense =>

            dayjs(expense.expense_date)
            .isSame(today,'month')

        )


    }



    if(dateFilter.value === 'Year'){


        data = data.filter(expense =>

            dayjs(expense.expense_date)
            .isSame(today,'year')

        )


    }



    return data


})


/*
|-------------------------------------------------------------------------- 
| CRUD Actions
|--------------------------------------------------------------------------
*/

const openView = (record) => {

    selectedExpense.value = record

    showView.value = true

}


const closeView = () => {

    selectedExpense.value = null

    showView.value = false

}


const openEdit = (record) => {

    selectedExpense.value = record

    showEdit.value = true

}


const closeEdit = () => {

    selectedExpense.value = null

    showEdit.value = false

}


const deleteExpense = async (record) => {

    try {

        await axios.delete(`/vegetable-expenses/${record.id}`)

        message.success('Expense deleted successfully')

        fetchExpenses()

    } catch(error){

        console.error(error)

        message.error('Unable to delete expense')

    }

}


/*
|--------------------------------------------------------------------------
| Export Functions
|--------------------------------------------------------------------------
*/

const exportPdf = async()=>{


    try{


        const response = await axios.get(
            '/vegetable-expenses/pdf',
            {
                responseType:'blob'
            }
        )


        const url =
            window.URL.createObjectURL(
                new Blob([response.data])
            )


        const link =
            document.createElement('a')


        link.href=url


        link.setAttribute(
            'download',
            'Vegetable_Expenses_Report.pdf'
        )


        document.body.appendChild(link)


        link.click()


        link.remove()


    }
    catch(error){

        console.error(error)

        message.error(
            'PDF export failed'
        )

    }


}

// const exportPdf = () => {


//     window.open(
//         '/vegetable-expenses/pdf',
//         '_blank'
//     )


// }


/*
|--------------------------------------------------------------------------
| Lifecycle
|--------------------------------------------------------------------------
*/

watch(

    filteredExpenses,

    () => {

        calculateStatistics()

        buildCharts()

    },

    {

        deep: true

    }

)

onMounted(() => {

    fetchExpenses()

})
</script>

<template>

    <Head title="Vegetable Expenses" />

    <AuthenticatedLayout>

        <!-- ================= Header ================= -->

        <div class="flex flex-col md:flex-row justify-between items-center mb-6">

            <div class="mt-6">

                <h1 class="text-3xl font-bold">

                    Vegetable Expense Dashboard

                </h1>

                <p class="text-gray-500">

                    Monitor production expenses, suppliers and operating costs.

                </p>

            </div>

            <a-space>

                <a-button @click="refresh">

                    <ReloadOutlined />

                    Refresh

                </a-button>

                <a-button @click="exportPdf">

                    <FilePdfOutlined />
                    PDF
                </a-button>

                <a-button type="primary" @click="showCreate = true">
                    <PlusOutlined />
                    New Expense
                </a-button>
            </a-space>

        </div>

        <!-- ================= Statistics ================= -->

        <a-row :gutter="16" class="mb-5">

            <a-col :xs="24" :sm="12" :md="6">

                <a-card>

                    <a-statistic title="Total Expenses" :value="statistics.totalExpenses" />

                </a-card>

            </a-col>

            <a-col :xs="24" :sm="12" :md="6">

                <a-card>

                    <a-statistic title="Total Cost" prefix="K" :value="statistics.totalCost" />

                </a-card>

            </a-col>

            <a-col :xs="24" :sm="12" :md="6">

                <a-card>

                    <a-statistic title="Production Batches" :value="statistics.totalBatches" />

                </a-card>

            </a-col>

            <a-col :xs="24" :sm="12" :md="6">

                <a-card>

                    <a-statistic title="Average Expense" prefix="K" :value="statistics.averageExpense" />

                </a-card>

            </a-col>

        </a-row>

        <!-- ================= Filters ================= -->

        <a-card class="mb-5">

            <a-row :gutter="16">

                <a-col :span="8">

                    <a-input v-model:value="search" placeholder="Search...">

                        <template #prefix>

                            <SearchOutlined />

                        </template>

                    </a-input>

                </a-col>

                <a-col :span="5">

                    <a-select v-model:value="categoryFilter" class="w-full">

                        <a-select-option value="All">All Categories</a-select-option>
                        <a-select-option value="Seeds">Seeds</a-select-option>
                        <a-select-option value="Fertilizer">Fertilizer</a-select-option>
                        <a-select-option value="Chemicals">Chemicals</a-select-option>
                        <a-select-option value="Labour">Labour</a-select-option>
                        <a-select-option value="Transport">Transport</a-select-option>
                        <a-select-option value="Equipment">Equipment</a-select-option>
                        <a-select-option value="Fuel">Fuel</a-select-option>
                        <a-select-option value="Water">Water</a-select-option>
                        <a-select-option value="Packaging">Packaging</a-select-option>

                    </a-select>

                </a-col>

                <a-col :span="5">

                    <a-select v-model:value="paymentFilter" class="w-full">

                        <a-select-option value="All">All Payments</a-select-option>
                        <a-select-option value="Cash">Cash</a-select-option>
                        <a-select-option value="Mobile Money">Mobile Money</a-select-option>
                        <a-select-option value="Bank Transfer">Bank Transfer</a-select-option>
                        <a-select-option value="Cheque">Cheque</a-select-option>

                    </a-select>

                </a-col>

                <a-col :span="6">

                    <a-select v-model:value="dateFilter" class="w-full">
                        <a-select-option value="All"> All Dates</a-select-option>
                        <a-select-option value="Today">Today</a-select-option>
                        <a-select-option value="Week">Week</a-select-option>
                        <a-select-option value="Month">Month</a-select-option>
                        <a-select-option value="Year">Year</a-select-option>
                        <a-select-option value="Custom">Custom</a-select-option>

                    </a-select>

                </a-col>

            </a-row>

        </a-card>


     <!-- ================= Charts ================= -->

        <div class="charts-container">

            <a-row :gutter="16" class="mb-5">

                <!-- Category Bar Chart -->
                <a-col :span="12">

                    <a-card title="Expense Category Comparison">

                        <VueApexCharts height="320" type="bar" :options="supplierOptions" :series="supplierSeries" />

                    </a-card>

                </a-col>


                <!-- Batch Expense Chart -->
                <a-col :span="12">

                    <a-card title="Expense By Batch">

                        <VueApexCharts height="320" type="bar" :options="batchOptions" :series="batchSeries" />

                    </a-card>

                </a-col>

                <!-- Expense Trend Line Chart -->
                <a-col :span="12">

                    <a-card title="Expense Trend">

                        <VueApexCharts height="320" type="line" :options="expenseTrendOptions"
                            :series="expenseTrendSeries" />

                    </a-card>

                </a-col>


                <!-- Expense Category Pie Chart -->
                <a-col :span="12">

                    <a-card title="Expense Categories">

                        <VueApexCharts height="320" type="pie" :options="categoryOptions" :series="categorySeries" />

                    </a-card>

                </a-col>





            </a-row>

        </div>

        <!-- ================= Table ================= -->

        <a-card>

            <a-table bordered row-key="id" :columns="columns" :data-source="filteredExpenses" :loading="loading"
                :pagination="{

                    pageSize: 10,

                    showSizeChanger: true,

                    showQuickJumper: true

                }" :scroll="{

                    x: 1800,

                    y: 550

                }">

                <template #bodyCell="{ column, record, index }">

                    <template v-if="column.key == 'index'">

                        {{ index + 1 }}

                    </template>

                    <template v-else-if="column.key == 'batch'">

                        {{ record.production?.batch_number }}

                    </template>

                    <template v-else-if="column.key == 'vegetable'">

                        {{ record.production?.vegetable_type?.name }}


                    </template>

                    <template v-else-if="column.key == 'amount'">

                        <strong>

                            K {{ Number(record.amount).toLocaleString() }}

                        </strong>

                    </template>

                    <template v-else-if="column.key == 'unit_cost'">

                        <strong>

                            K {{ Number(record.unit_cost).toLocaleString() }}

                        </strong>

                    </template>

                    <template v-else-if="column.key == 'status'">

                        {{ record.status }}

                    </template>

                    <template v-else-if="column.key == 'expense_date'">

                        {{ dayjs(record.expense_date).format('DD MMM YYYY') }}

                    </template>

                    <template v-else-if="column.key == 'actions'">

                        <a-space>

                            <a-button size="small"  @click="openView(record)">

                                <EyeOutlined />

                            </a-button>

                            <a-button size="small" type="primary" ghost @click="openEdit(record)">

                                <EditOutlined />

                            </a-button>

                            <a-popconfirm title="Delete this expense?" @confirm="deleteExpense(record)">

                                <a-button danger size="small">

                                    <DeleteOutlined />

                                </a-button>

                            </a-popconfirm>

                        </a-space>

                    </template>

                </template>

            </a-table>

        </a-card>

        <!-- ================= Modals ================= -->

        <CreateExpense v-model:open="showCreate" @success="fetchExpenses" />

        <EditExpense v-model:open="showEdit" :expense="selectedExpense" @success="fetchExpenses" />

        <ViewExpense v-model:open="showView" :expense="selectedExpense" />

    </AuthenticatedLayout>

</template>

<style scoped>

.charts-container {

    max-height: 400px;

    overflow-y: auto;

    padding-right: 8px;

}


/* Optional: make scrollbar cleaner */

.charts-container::-webkit-scrollbar {

    width: 8px;

}


.charts-container::-webkit-scrollbar-thumb {

    background: #bfbfbf;

    border-radius: 10px;

}


.charts-container::-webkit-scrollbar-track {

    background: #f5f5f5;

}


</style>