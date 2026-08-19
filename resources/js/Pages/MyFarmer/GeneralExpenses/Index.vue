<script setup>

import { Head } from '@inertiajs/vue3'

import AuthenticatedLayout
    from '@/Layouts/AuthenticatedLayout.vue'

import VueApexCharts
    from 'vue3-apexcharts'

import {
    ref,
    computed,
    onMounted,
    watch
} from 'vue'

import axios from 'axios'

import dayjs from 'dayjs'

import isBetween from 'dayjs/plugin/isBetween'

dayjs.extend(isBetween)

import {
    SearchOutlined,
    PlusOutlined,
    ReloadOutlined,
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


import CreateGeneralExpenses
    from './CreateGeneralExpenses.vue'

import EditGeneralExpenses
    from './EditGeneralExpenses.vue'

import ViewGeneralExpenses
    from './ViewGeneralExpenses.vue'


/*
|--------------------------------------------------------------------------
| Reactive Data
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
| Search & Filters
|--------------------------------------------------------------------------
*/


const search = ref('')

const categoryFilter = ref('All')

const statusFilter = ref('All')

// const dateFilter = ref('Month')
const dateFilter = ref('All')

const customDates = ref([])


/*
|--------------------------------------------------------------------------
| Expense Categories
|--------------------------------------------------------------------------
*/


const categories = [

    'Transport',

    'Salaries',

    'Fuel',

    'Utilities',

    'Rent',

    'Office Supplies',

    'Repairs & Maintenance',

    'Insurance',

    'Marketing',

    'Communication',

    'Taxes',

    'Bank Charges',

    'Other'

]


/*
|--------------------------------------------------------------------------
| Payment Statuses
|--------------------------------------------------------------------------
*/


const paymentStatuses = [

    'Paid',

    'Partial',

    'Pending',

    'Cancelled'

]


/*
|--------------------------------------------------------------------------
| Dashboard Statistics
|--------------------------------------------------------------------------
*/


const statistics = ref({

    totalExpenses: 0,

    totalAmount: 0,

    paidAmount: 0,

    pendingAmount: 0

})


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

        title: 'Category',

        dataIndex: 'expense_category',

        key: 'expense_category',

        width: 180

    },

    {

        title: 'Expense Name',

        dataIndex: 'expense_name',

        key: 'expense_name',

        width: 220

    },

    {

        title: 'Vendor',

        dataIndex: 'vendor',

        key: 'vendor',

        width: 180

    },

    {

        title: 'Amount',

        key: 'amount',

        width: 150

    },

    {

        title: 'Payment Method',

        dataIndex: 'payment_method',

        key: 'payment_method',

        width: 160

    },

    {

        title: 'Status',

        dataIndex: 'payment_status',

        key: 'payment_status',

        width: 130

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
| Fetch Expenses
|--------------------------------------------------------------------------
*/


const fetchExpenses = async () => {

    loading.value = true

    try {

        const response =
            await axios.get(
                '/general-expenses'
            )


        expenses.value =
            response.data.data
            ??
            response.data


        calculateStatistics()

        buildCharts()

    }

    catch (error) {

        console.error(error)

        message.error(
            'Unable to load general expenses.'
        )

    }

    finally {

        loading.value = false

    }

}


/*
|--------------------------------------------------------------------------
| Filtered Expenses
|--------------------------------------------------------------------------
*/


const filteredExpenses = computed(() => {


    console.log('TODAY:', dayjs().format('YYYY-MM-DD'))

console.log(
    'WEEK:',
    dayjs().startOf('week').format('YYYY-MM-DD'),
    '→',
    dayjs().endOf('week').format('YYYY-MM-DD')
)

console.log(
    'EXPENSE DATES:',
    expenses.value.map(e => ({
        id: e.id,
        date: dayjs(e.expense_date).format('YYYY-MM-DD')
    }))
)


    return expenses.value.filter(expense => {


        const keyword =
            search.value
                .trim()
                .toLowerCase()


        const expenseName =
            expense.expense_name
                ?.toLowerCase()
            ??
            ''


        const category =
            expense.expense_category
                ?.toLowerCase()
            ??
            ''


        const vendor =
            expense.vendor
                ?.toLowerCase()
            ??
            ''


        const reference =
            expense.reference_number
                ?.toLowerCase()
            ??
            ''


        const searchMatch =

            !keyword

            ||

            expenseName.includes(keyword)

            ||

            category.includes(keyword)

            ||

            vendor.includes(keyword)

            ||

            reference.includes(keyword)


        /*
        |--------------------------------------------------------------------------
        | Category Filter
        |--------------------------------------------------------------------------
        */


        const categoryMatch =

            categoryFilter.value === 'All'

            ||

            expense.expense_category ===
            categoryFilter.value


        /*
        |--------------------------------------------------------------------------
        | Payment Status Filter
        |--------------------------------------------------------------------------
        */


        const statusMatch =

            statusFilter.value === 'All'

            ||

            expense.payment_status ===
            statusFilter.value


        /*
        |--------------------------------------------------------------------------
        | Date Filter
        |--------------------------------------------------------------------------
        */


let dateMatch = true

if (
    dateFilter.value !== 'All' &&
    expense.expense_date
) {

    const expenseDate =
        dayjs(expense.expense_date)

    switch (dateFilter.value) {

        case 'Today': {
            const today =
                dayjs().format('YYYY-MM-DD')

            const expenseDay =
                expenseDate.format('YYYY-MM-DD')

            dateMatch =
                expenseDay === today

            break
        }

        case 'Week': {
            const today = dayjs()

            const startOfWeek =
                today
                    .startOf('week')
                    .format('YYYY-MM-DD')

            const endOfWeek =
                today
                    .endOf('week')
                    .format('YYYY-MM-DD')

            const expenseDay =
                expenseDate.format('YYYY-MM-DD')

            dateMatch =
                expenseDay >= startOfWeek &&
                expenseDay <= endOfWeek

            break
        }

        case 'Month':
            dateMatch =
                expenseDate.isSame(
                    dayjs(),
                    'month'
                )
            break

        case 'Year':
            dateMatch =
                expenseDate.isSame(
                    dayjs(),
                    'year'
                )
            break

        case 'Custom':
            if (
                customDates.value.length === 2
            ) {
                dateMatch =
                    expenseDate.isBetween(
                        customDates.value[0],
                        customDates.value[1],
                        'day',
                        '[]'
                    )
            }
            break
    }
}

//         let dateMatch = true


//         // const expenseDate =
//         //     dayjs(
//         //         expense.expense_date
//         //     )
//         const expenseDate = dayjs(
//             expense.expense_date
//         ).startOf('day')

//         switch (dateFilter.value) {
            

//             case 'Today': {
//     const today = dayjs().format('YYYY-MM-DD')

//     dateMatch =
//         expenseDate.format('YYYY-MM-DD') === today

//     break
// }

// case 'Week': {
//     const startOfWeek =
//         dayjs().startOf('week').format('YYYY-MM-DD')

//     const endOfWeek =
//         dayjs().endOf('week').format('YYYY-MM-DD')

//     const expenseDay =
//         expenseDate.format('YYYY-MM-DD')

//     dateMatch =
//         expenseDay >= startOfWeek &&
//         expenseDay <= endOfWeek

//     break
// }


     

            // case 'Today':

            //     dateMatch =
            //         expenseDate.isSame(
            //             dayjs(),
            //             'day'
            //         )

            //     break


            // case 'Week':

            //     dateMatch =
            //         expenseDate.isSame(
            //             dayjs(),
            //             'week'
            //         )

            //     break


        //     case 'Month':

        //         dateMatch =
        //             expenseDate.isSame(
        //                 dayjs(),
        //                 'month'
        //             )

        //         break


        //     case 'Year':

        //         dateMatch =
        //             expenseDate.isSame(
        //                 dayjs(),
        //                 'year'
        //             )

        //         break


        //     case 'Custom':

        //         if (

        //             customDates.value.length === 2

        //         ) {

        //             dateMatch =
        //                 expenseDate.isBetween(

        //                     customDates.value[0],

        //                     customDates.value[1],

        //                     'day',

        //                     '[]'

        //                 )

        //         }

        //         break

        // }


        return (

            searchMatch

            &&

            categoryMatch

            &&

            statusMatch

            &&

            dateMatch

        )


    })


})


/*
|--------------------------------------------------------------------------
| Statistics
|--------------------------------------------------------------------------
*/


const calculateStatistics = () => {


    const data =
        filteredExpenses.value


    statistics.value.totalExpenses =
        data.length


    statistics.value.totalAmount =

        data.reduce(

            (sum, expense) =>

                sum +

                Number(
                    expense.amount
                    ??
                    0
                ),

            0

        )


    statistics.value.paidAmount =

        data

            .filter(

                expense =>
                    expense.payment_status
                    ===
                    'Paid'

            )

            .reduce(

                (sum, expense) =>

                    sum +

                    Number(
                        expense.amount
                        ??
                        0
                    ),

                0

            )


    statistics.value.pendingAmount =

        data

            .filter(

                expense =>

                    expense.payment_status
                    ===
                    'Pending'

                    ||

                    expense.payment_status
                    ===
                    'Partial'

            )

            .reduce(

                (sum, expense) =>

                    sum +

                    Number(
                        expense.amount
                        ??
                        0
                    ),

                0

            )

}


/*
|--------------------------------------------------------------------------
| Monthly Expense Chart
|--------------------------------------------------------------------------
*/


const monthlyExpenseSeries = ref([])


const monthlyExpenseOptions = ref({

    chart: {

        toolbar: {

            show: false

        }

    },

    xaxis: {

        categories: []

    },

    dataLabels: {

        enabled: false

    },

    stroke: {

        curve: 'smooth'

    },

    yaxis: {

        labels: {

            formatter: value =>

                `K ${Number(value).toLocaleString()}`

        }

    }

})


/*
|--------------------------------------------------------------------------
| Expense Category Chart
|--------------------------------------------------------------------------
*/


const categoryExpenseSeries = ref([])


const categoryExpenseOptions = ref({

    labels: [],

    legend: {

        position: 'bottom'

    }

})


/*
|--------------------------------------------------------------------------
| Build Monthly Chart
|--------------------------------------------------------------------------
*/


const buildMonthlyExpenseChart = () => {


    const grouped = {}


    filteredExpenses.value.forEach(expense => {


        const month =

            dayjs(
                expense.expense_date
            )

                .format('MMM YYYY')


        if (!grouped[month]) {

            grouped[month] = 0

        }


        grouped[month] +=

            Number(
                expense.amount
                ??
                0
            )


    })


    monthlyExpenseSeries.value = [

        {

            name: 'Expenses',

            data: Object.values(grouped)

        }

    ]


    monthlyExpenseOptions.value = {

        chart: {

            toolbar: {

                show: false

            }

        },

        xaxis: {

            categories:
                Object.keys(grouped)

        },

        dataLabels: {

            enabled: false

        },

        stroke: {

            curve: 'smooth',

            width: 3

        },

        yaxis: {

            labels: {

                formatter: value =>

                    `K ${Number(value).toLocaleString()}`

            }

        }

    }


}


/*
|--------------------------------------------------------------------------
| Build Category Chart
|--------------------------------------------------------------------------
*/


const buildCategoryExpenseChart = () => {


    const grouped = {}


    filteredExpenses.value.forEach(expense => {


        const category =

            expense.expense_category

            ??

            'Other'


        if (!grouped[category]) {

            grouped[category] = 0

        }


        grouped[category] +=

            Number(
                expense.amount
                ??
                0
            )


    })


    categoryExpenseSeries.value =

        Object.values(grouped)


    categoryExpenseOptions.value = {

        labels:

            Object.keys(grouped),

        legend: {

            position: 'bottom'

        }

    }


}


/*
|--------------------------------------------------------------------------
| Build All Charts
|--------------------------------------------------------------------------
*/


const buildCharts = () => {


    buildMonthlyExpenseChart()


    buildCategoryExpenseChart()


}


/*
|--------------------------------------------------------------------------
| Watch Filters
|--------------------------------------------------------------------------
*/


watch(

    [

        search,

        categoryFilter,

        statusFilter,

        dateFilter,

        customDates

    ],

    () => {


        calculateStatistics()


        buildCharts()


    },

    {

        deep: true

    }

)


/*
|--------------------------------------------------------------------------
| Open View
|--------------------------------------------------------------------------
*/


const openView = (record) => {


    selectedExpense.value =
        record


    showView.value =
        true


}


/*
|--------------------------------------------------------------------------
| Open Edit
|--------------------------------------------------------------------------
*/


const openEdit = (record) => {


    selectedExpense.value =
        record


    showEdit.value =
        true


}


/*
|--------------------------------------------------------------------------
| Delete Expense
|--------------------------------------------------------------------------
*/


const deleteExpense = (record) => {


    Modal.confirm({

        title:
            'Delete General Expense',

        content:

            `Are you sure you want to delete "${record.expense_name}"?`,

        okText:
            'Yes, Delete',

        okType:
            'danger',

        cancelText:
            'Cancel',


        async onOk() {


            try {


                await axios.delete(

                    `/general-expenses/${record.id}`

                )


                message.success(

                    'General expense deleted successfully.'

                )


                fetchExpenses()


            }

            catch (error) {


                console.error(error)


                message.error(

                    'Failed to delete expense.'

                )


            }


        }

    })


}


/*
|--------------------------------------------------------------------------
| Refresh
|--------------------------------------------------------------------------
*/


const refreshTable = () => {


    fetchExpenses()


}


/*
|--------------------------------------------------------------------------
| Export PDF
|--------------------------------------------------------------------------
*/

const buildExportParams = () => {

    const params = new URLSearchParams()


    /*
    |--------------------------------------------------------------------------
    | Search
    |--------------------------------------------------------------------------
    */

    if (search.value) {

        params.append(
            'search',
            search.value
        )

    }


    /*
    |--------------------------------------------------------------------------
    | Category
    |--------------------------------------------------------------------------
    */

    if (

        categoryFilter.value &&

        categoryFilter.value !== 'All'

    ) {

        params.append(

            'category',

            categoryFilter.value

        )

    }


    /*
    |--------------------------------------------------------------------------
    | Payment Status
    |--------------------------------------------------------------------------
    */

    if (

        statusFilter.value &&

        statusFilter.value !== 'All'

    ) {

        params.append(

            'status',

            statusFilter.value

        )

    }


    /*
    |--------------------------------------------------------------------------
    | Date Filter
    |--------------------------------------------------------------------------
    */

    if (

        dateFilter.value &&

        dateFilter.value !== 'All'

    ) {

        params.append(

            'date_filter',

            dateFilter.value

        )

    }


    /*
    |--------------------------------------------------------------------------
    | Custom Dates
    |--------------------------------------------------------------------------
    */

    if (

        dateFilter.value === 'Custom' &&

        customDates.value.length === 2

    ) {

        params.append(

            'date_from',

            dayjs(
                customDates.value[0]
            )
                .format('YYYY-MM-DD')

        )


        params.append(

            'date_to',

            dayjs(
                customDates.value[1]
            )
                .format('YYYY-MM-DD')

        )

    }


    return params.toString()

}

const exportPdf = () => {

    const params =
        buildExportParams()


    window.open(

        `/general-expenses/report/pdf?${params}`,

        '_blank'

    )

}

// const exportPdf = () => {


//     window.open(

//         '/general-expenses/report/pdf',

//         '_blank'

//     )


// }


/*
|--------------------------------------------------------------------------
| Export Excel
|--------------------------------------------------------------------------
*/


const exportExcel = () => {

    const params =
        buildExportParams()


    window.open(

        `/general-expenses/report/excel?${params}`,

        '_blank'

    )

}

// const exportExcel = () => {


//     window.open(

//         '/general-expenses/report/excel',

//         '_blank'

//     )


// }


/*
|--------------------------------------------------------------------------
| Initial Load
|--------------------------------------------------------------------------
*/



onMounted(() => {


    fetchExpenses()


})


</script>


<template>


    <Head title="General Expenses" />


    <AuthenticatedLayout>


        <div class="p-4">


            <!-- HEADER -->


            <div class="flex flex-col md:flex-row justify-between items-center mb-6">


                <div>


                    <h1 class="text-3xl font-bold">

                        General Expenses

                    </h1>


                    <p class="text-gray-500">

                        Manage and monitor your business operating expenses.

                    </p>


                </div>


                <a-space>


                    <a-tooltip title="Refresh expenses">


                        <a-button @click="refreshTable">

                            <template #icon>

                                <ReloadOutlined />

                            </template>

                            Refresh

                        </a-button>


                    </a-tooltip>


                    <a-tooltip title="Export PDF">


                        <a-button @click="exportPdf">

                            <template #icon>

                                <FilePdfOutlined />

                            </template>

                            PDF

                        </a-button>


                    </a-tooltip>


                    <a-tooltip title="Export Excel">


                        <a-button @click="exportExcel">

                            <template #icon>

                                <FileExcelOutlined />

                            </template>

                            Excel

                        </a-button>


                    </a-tooltip>


                    <a-button type="primary" @click="showCreate = true">

                        <template #icon>

                            <PlusOutlined />

                        </template>

                        Add Expense

                    </a-button>


                </a-space>


            </div>


            <!-- KPI CARDS -->


            <a-row :gutter="[16, 16]">


                <!-- TOTAL EXPENSES -->


                <a-col :xs="24" :sm="12" :lg="6">


                    <a-card>


                        <div class="text-gray-500">

                            Total Expenses

                        </div>


                        <div class="text-3xl font-bold">

                            {{
                                statistics.totalExpenses
                            }}

                        </div>


                    </a-card>


                </a-col>


                <!-- TOTAL AMOUNT -->


                <a-col :xs="24" :sm="12" :lg="6">


                    <a-card>


                        <div class="text-gray-500">

                            Total Amount

                        </div>


                        <div class="text-3xl font-bold">

                            K

                            {{
                                Number(
                                    statistics.totalAmount
                                )
                                    .toLocaleString()
                            }}

                        </div>


                    </a-card>


                </a-col>


                <!-- PAID -->


                <a-col :xs="24" :sm="12" :lg="6">


                    <a-card>


                        <div class="text-gray-500">

                            Paid Amount

                        </div>


                        <div class="text-3xl font-bold text-green-600">

                            K

                            {{
                                Number(
                                    statistics.paidAmount
                                )
                                    .toLocaleString()
                            }}

                        </div>


                    </a-card>


                </a-col>


                <!-- PENDING -->


                <a-col :xs="24" :sm="12" :lg="6">


                    <a-card>


                        <div class="text-gray-500">

                            Pending Amount

                        </div>


                        <div class="text-3xl font-bold text-orange-500">

                            K

                            {{
                                Number(
                                    statistics.pendingAmount
                                )
                                    .toLocaleString()
                            }}

                        </div>


                    </a-card>


                </a-col>


            </a-row>

            <!-- FILTERS -->


            <a-card class="mt-5">


                <div class="flex flex-wrap gap-3">


                    <!-- SEARCH -->


                    <a-input v-model:value="search" placeholder="Search expenses..." style="width: 260px" allow-clear>

                        <template #prefix>

                            <SearchOutlined />

                        </template>


                    </a-input>


                    <!-- CATEGORY -->


                    <a-select v-model:value="categoryFilter" style="width: 200px">

                        <a-select-option value="All">
                            All Categories
                        </a-select-option>

                        <a-select-option v-for="category in categories" :key="category" :value="category">
                            {{ category }}
                        </a-select-option>
                    </a-select>


                    <!-- STATUS -->
                    <a-select v-model:value="statusFilter" style="width: 170px">
                        <a-select-option value="All"> All Statuses </a-select-option>
                        <a-select-option v-for="status in paymentStatuses" :key="status" :value="status">
                            {{ status }}
                        </a-select-option>
                    </a-select>


                    <!-- DATE -->
                    <a-select v-model:value="dateFilter" style="width: 150px">
                        <a-select-option value="All"> All Dates </a-select-option>
                        <a-select-option value="Today"> Today </a-select-option>
                        <a-select-option value="Week"> This Week </a-select-option>
                        <a-select-option value="Month"> This Month </a-select-option>
                        <a-select-option value="Year"> This Year </a-select-option>
                        <a-select-option value="Custom"> Custom </a-select-option>
                        
                    </a-select>
                    <span style="margin-left: 10px">
                        {{ filteredExpenses.length }} records
                    </span>
                    <!-- CUSTOM DATE -->
                    <a-range-picker v-if=" dateFilter === 'Custom' " v-model:value="customDates" />
                </div>
            </a-card>


            <!-- TABLE -->


            <a-card class="mt-5">


                <a-table :columns="columns" :data-source="filteredExpenses" :loading="loading" row-key="id" bordered
                    :pagination="{

                        pageSize: 10,

                        showSizeChanger: true,

                        showQuickJumper: true

                    }" :scroll="{

                        x: 1500,

                        y: 500

                    }">


                    <template #bodyCell="{
                        column,
                        record,
                        index
                    }">


                        <!-- INDEX -->


                        <template v-if="
                            column.key === 'index'
                        ">

                            {{
                                index + 1
                            }}

                        </template>


                        <!-- DATE -->


                        <template v-else-if="
                            column.key
                            ===
                            'expense_date'
                        ">

                            {{
                                dayjs(
                                    record.expense_date
                                )
                                    .format(
                                        'DD MMM YYYY'
                                    )
                            }}

                        </template>


                        <!-- AMOUNT -->


                        <template v-else-if="
                            column.key
                            ===
                            'amount'
                        ">

                            <strong>

                                K

                                {{
                                    Number(
                                        record.amount
                                    )
                                        .toLocaleString()
                                }}

                            </strong>

                        </template>


                        <!-- STATUS -->


                        <template v-else-if="
                            column.key
                            ===
                            'payment_status'
                        ">


                            <a-tag v-if="
                                record.payment_status
                                ===
                                'Paid'
                            " color="green">

                                Paid

                            </a-tag>


                            <a-tag v-else-if="
                                record.payment_status
                                ===
                                'Partial'
                            " color="orange">

                                Partial

                            </a-tag>


                            <a-tag v-else-if="
                                record.payment_status
                                ===
                                'Pending'
                            " color="blue">

                                Pending

                            </a-tag>


                            <a-tag v-else color="red">

                                Cancelled

                            </a-tag>


                        </template>


                        <!-- ACTIONS -->


                        <template v-else-if="
                            column.key
                            ===
                            'actions'
                        ">


                            <a-space>


                                <a-tooltip title="View expense">

                                    <a-button size="small" @click="
                                        openView(
                                            record
                                        )
                                        ">

                                        <EyeOutlined />

                                    </a-button>


                                </a-tooltip>


                                <a-tooltip title="Edit expense">

                                    <a-button type="primary" ghost size="small" @click="
                                        openEdit(
                                            record
                                        )
                                        ">

                                        <EditOutlined />

                                    </a-button>


                                </a-tooltip>


                                <a-tooltip title="Delete expense">

                                    <a-button danger size="small" @click="
                                        deleteExpense(
                                            record
                                        )
                                        ">

                                        <DeleteOutlined />

                                    </a-button>


                                </a-tooltip>


                            </a-space>


                        </template>


                    </template>


                </a-table>


            </a-card>


            <!-- CHARTS -->


            <a-row :gutter="[16, 16]" class="mt-5">


                <!-- MONTHLY EXPENSES -->


                <a-col :xs="24" :lg="14">


                    <a-card title="Monthly Expenses">


                        <apexchart type="line" height="320" :options="monthlyExpenseOptions
                            " :series="monthlyExpenseSeries
                                " />


                    </a-card>


                </a-col>


                <!-- CATEGORY EXPENSES -->


                <a-col :xs="24" :lg="10">


                    <a-card title="Expenses by Category">


                        <apexchart type="donut" height="320" :options="categoryExpenseOptions
                            " :series="categoryExpenseSeries
                                " />


                    </a-card>


                </a-col>


            </a-row>





            <!-- MODALS -->


            <CreateGeneralExpenses v-model:open="showCreate" @success="fetchExpenses" />


            <EditGeneralExpenses v-model:open="showEdit" :expense="selectedExpense" @success="fetchExpenses" />


            <ViewGeneralExpenses v-model:open="showView" :expense="selectedExpense" />


        </div>


    </AuthenticatedLayout>


</template>


<style scoped>
:deep(.ant-card) {

    border-radius: 14px;

}
</style>