<!-- resources\js\Pages\MyFarmer\Vegetables\Sales\Index.vue -->
<script setup>
import { Head } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import VueApexCharts from "vue3-apexcharts"
import {
    ref,
    computed,
    onMounted,
    watch
} from 'vue'
import axios from 'axios'
import dayjs from 'dayjs'

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

import CreateSale from './CreateSale.vue'
import EditSale from './EditSale.vue'
import ViewSale from './ViewSale.vue'

/*
|--------------------------------------------------------------------------
| Reactive Data
|--------------------------------------------------------------------------
*/

const loading = ref(false)

const sales = ref([])

const selectedSale = ref(null)

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

    totalSales: 0,

    totalQuantity: 0,

    totalRevenue: 0,

    totalCustomers: 0

})

/*
|--------------------------------------------------------------------------
| Search & Filters
|--------------------------------------------------------------------------
*/

const search = ref('')

const statusFilter = ref('All')

const dateFilter = ref('Month')

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
        title: 'Invoice',
        dataIndex: 'invoice_number',
        key: 'invoice_number',
        width: 140
    },

    {
        title: 'Crop',
        key: 'crop',
        width: 180
    },

    {
        title: 'Batch',
        key: 'batch',
        width: 150
    },

    {
        title: 'Customer',
        dataIndex: 'customer_name',
        key: 'customer_name',
        width: 180
    },

    {
        title: 'Sale Date',
        dataIndex: 'sale_date',
        key: 'sale_date',
        width: 140
    },

    {
        title: 'Quantity',
        dataIndex: 'quantity',
        key: 'quantity',
        width: 120
    },

    {
        title: 'Unit Price',
        dataIndex: 'unit_price',
        key: 'unit_price',
        width: 120
    },

    {
        title: 'Total',
        key: 'total_amount',
        width: 150
    },

    {
        title: 'Payment',
        dataIndex: 'payment_method',
        key: 'payment_method',
        width: 140
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
| Harvest Revenue Trend Chart
|--------------------------------------------------------------------------
*/

const revenueTrendSeries = ref([])

const revenueTrendOptions = ref({

    chart: {

        toolbar: {

            show: false

        }

    },

    xaxis: {

        categories: []

    },

    stroke: {

        curve: 'smooth'

    },

    dataLabels: {

        enabled: false

    },

    colors: ['#16a34a']

})

/*
|--------------------------------------------------------------------------
| Revenue By Crop
|--------------------------------------------------------------------------
*/

const cropRevenueSeries = ref([])

const cropRevenueOptions = ref({

    labels: []

})

/*
|--------------------------------------------------------------------------
| Monthly Sales
|--------------------------------------------------------------------------
*/

const monthlySalesSeries = ref([])

const monthlySalesOptions = ref({

    chart: {

        toolbar: {

            show: false

        }

    },

    xaxis: {

        categories: []

    },

    colors: ['#1677ff']

})

/*
|--------------------------------------------------------------------------
| Customer Sales
|--------------------------------------------------------------------------
*/

const customerSeries = ref([])

const customerOptions = ref({

    chart: {

        toolbar: {

            show: false

        }

    },

    xaxis: {

        categories: []

    },

    colors: ['#fa8c16']

})

/*
|--------------------------------------------------------------------------
| Payment Method Pie Chart
|--------------------------------------------------------------------------
*/

const paymentSeries = ref([])

const paymentOptions = ref({

    labels: []

})

/*
|--------------------------------------------------------------------------
| Payment Status Chart
|--------------------------------------------------------------------------
*/

const statusSeries = ref([])

const statusOptions = ref({

    chart: {

        toolbar: {

            show: false

        }

    },

    xaxis: {

        categories: []

    },

    colors: ['#52c41a']

})

/*
|--------------------------------------------------------------------------
| Inventory Summary
|--------------------------------------------------------------------------
*/

const inventorySummary = ref({

    available: 0,

    sold: 0,

    remaining: 0

})

/*
|--------------------------------------------------------------------------
| Fetch Sales
|--------------------------------------------------------------------------
*/


const fetchSales = async () => {

    loading.value = true

    try {

        const { data } = await axios.get('/vegetable-sales')

        sales.value = data.data ?? data

        calculateStatistics()

        buildCharts()

    }
    catch (error) {

        console.error(error)

        message.error('Unable to load vegetable sales.')

    }
    finally {

        loading.value = false

    }

}

/*
|--------------------------------------------------------------------------
| Dashboard Statistics
|--------------------------------------------------------------------------
*/

const calculateStatistics = () => {

    statistics.value.totalSales = sales.value.length

    statistics.value.totalQuantity = sales.value.reduce(

        (sum, sale) =>

            sum + Number(sale.quantity ?? 0),

        0

    )

    statistics.value.totalRevenue = sales.value.reduce(

        (sum, sale) =>

            sum + Number(

                sale.total_amount

                ??

                (

                    Number(sale.quantity ?? 0)

                    *

                    Number(sale.unit_price ?? 0)

                )

            ),

        0

    )

    statistics.value.totalCustomers = [

        ...new Set(

            sales.value

            .map(

                sale => sale.customer_name

            )

            .filter(Boolean)

        )

    ].length

}

/*
|--------------------------------------------------------------------------
| Search + Filters
|--------------------------------------------------------------------------
*/

const filteredSales = computed(() => {

    return sales.value.filter((sale) => {

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        const keyword = search.value.trim().toLowerCase()

        const crop =

            sale.harvest

                ?.production

                ?.vegetableType

                ?.name

                ?.toLowerCase()

            ??

            ''

        const batch =

            sale.harvest

                ?.production

                ?.batch_number

                ?.toLowerCase()

            ??

            ''

        const customer =

            sale.customer_name

                ?.toLowerCase()

            ??

            ''

        const invoice =

            sale.invoice_number

                ?.toLowerCase()

            ??

            ''

        const searchMatch =

            !keyword

            ||

            crop.includes(keyword)

            ||

            batch.includes(keyword)

            ||

            customer.includes(keyword)

            ||

            invoice.includes(keyword)

        /*
        |--------------------------------------------------------------------------
        | Payment Status
        |--------------------------------------------------------------------------
        */

        const statusMatch =

            statusFilter.value === 'All'

            ||

            sale.payment_status === statusFilter.value

        /*
        |--------------------------------------------------------------------------
        | Date Filter
        |--------------------------------------------------------------------------
        */

        let dateMatch = true

        const saleDate = dayjs(sale.sale_date)

        switch (dateFilter.value) {

            case 'Today':

                dateMatch =

                    saleDate.isSame(

                        dayjs(),

                        'day'

                    )

                break

            case 'Week':

                dateMatch =

                    saleDate.isSame(

                        dayjs(),

                        'week'

                    )

                break

            case 'Month':

                dateMatch =

                    saleDate.isSame(

                        dayjs(),

                        'month'

                    )

                break

            case 'Year':

                dateMatch =

                    saleDate.isSame(

                        dayjs(),

                        'year'

                    )

                break

            case 'Custom':

                if (

                    customDates.value.length === 2

                ) {

                    dateMatch =

                        saleDate.isBetween(

                            customDates.value[0],

                            customDates.value[1],

                            'day',

                            '[]'

                        )

                }

                break

        }

        return (

            searchMatch

            &&

            statusMatch

            &&

            dateMatch

        )

    })

})

/*
|--------------------------------------------------------------------------
| Refresh Dashboard
|--------------------------------------------------------------------------
*/

const refresh = () => {

    fetchSales()

}


/*
|--------------------------------------------------------------------------
| Revenue Trend (Line Chart)
|--------------------------------------------------------------------------
*/

const buildRevenueTrend = () => {

    const grouped = {}

    filteredSales.value.forEach(sale => {

        const month = dayjs(sale.sale_date).format('MMM')

        if (!grouped[month]) {

            grouped[month] = 0

        }

        grouped[month] += Number(
            sale.total_amount ??
            (
                Number(sale.quantity ?? 0) *
                Number(sale.unit_price ?? 0)
            )
        )

    })

    revenueTrendSeries.value = [

        {

            name: 'Revenue',

            data: Object.values(grouped)

        }

    ]

    revenueTrendOptions.value = {

        chart: {

            toolbar: {

                show: false

            }

        },

        xaxis: {

            categories: Object.keys(grouped)

        },

        stroke: {

            curve: 'smooth',

            width: 3

        },

        dataLabels: {

            enabled: false

        },

        yaxis: {

            labels: {

                formatter: value => `K ${Number(value).toLocaleString()}`

            }

        }

    }

}

/*
|--------------------------------------------------------------------------
| Revenue By Crop (Pie)
|--------------------------------------------------------------------------
*/
const buildCropChart = () => {

    const grouped = {}

    filteredSales.value.forEach(sale => {

        const crop =
            sale.harvest
            ?.production
            ?.vegetable_type
            ?.name
            ??
            'Unknown'


        if (!grouped[crop]) {

            grouped[crop] = 0

        }


        grouped[crop] += Number(
            sale.total_amount ??
            (
                Number(sale.quantity ?? 0) *
                Number(sale.unit_price ?? 0)
            )
        )

    })


    cropRevenueSeries.value = Object.values(grouped)


    cropRevenueOptions.value = {

        labels: Object.keys(grouped),

        legend: {

            position: 'bottom'

        }

    }

}

/*
|--------------------------------------------------------------------------
| Monthly Sales (Bar)
|--------------------------------------------------------------------------
*/

const buildMonthlyChart = () => {

    const grouped = {}

    filteredSales.value.forEach(sale => {

        const month = dayjs(sale.sale_date).format('MMM')

        if (!grouped[month]) {

            grouped[month] = 0

        }

        grouped[month]++

    })

    monthlySalesSeries.value = [

        {

            name: 'Sales',

            data: Object.values(grouped)

        }

    ]

    monthlySalesOptions.value = {

        chart: {

            toolbar: {

                show: false

            }

        },

        xaxis: {

            categories: Object.keys(grouped)

        },

        dataLabels: {

            enabled: false

        }

    }

}

/*
|--------------------------------------------------------------------------
| Top Customers
|--------------------------------------------------------------------------
*/

const buildCustomerChart = () => {

    const grouped = {}

    filteredSales.value.forEach(sale => {

        const customer = sale.customer_name ?? 'Walk-in'

        if (!grouped[customer]) {

            grouped[customer] = 0

        }

        grouped[customer] += Number(
            sale.total_amount ??
            (
                Number(sale.quantity ?? 0) *
                Number(sale.unit_price ?? 0)
            )
        )

    })

    const sorted = Object.entries(grouped)

        .sort((a, b) => b[1] - a[1])

        .slice(0, 10)

    customerSeries.value = [

        {

            name: 'Revenue',

            data: sorted.map(item => item[1])

        }

    ]

    customerOptions.value = {

        chart: {

            toolbar: {

                show: false

            }

        },

        plotOptions: {

            bar: {

                horizontal: true

            }

        },

        xaxis: {

            categories: sorted.map(item => item[0])

        },

        dataLabels: {

            enabled: false

        }

    }

}

/*
|--------------------------------------------------------------------------
| Payment Methods
|--------------------------------------------------------------------------
*/

const buildPaymentMethodChart = () => {

    const grouped = {}

    filteredSales.value.forEach(sale => {

        const method = sale.payment_method ?? 'Unknown'

        if (!grouped[method]) {

            grouped[method] = 0

        }

        grouped[method]++

    })

    paymentSeries.value = Object.values(grouped)

    paymentOptions.value = {

        labels: Object.keys(grouped),

        legend: {

            position: 'bottom'

        }

    }

}

/*
|--------------------------------------------------------------------------
| Payment Status
|--------------------------------------------------------------------------
*/

const buildPaymentStatusChart = () => {

    const grouped = {}

    filteredSales.value.forEach(sale => {

        const status = sale.payment_status ?? 'Pending'

        if (!grouped[status]) {

            grouped[status] = 0

        }

        grouped[status]++

    })

    statusSeries.value = Object.values(grouped)

    statusOptions.value = {

        labels: Object.keys(grouped),

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

    buildRevenueTrend()

    buildCropChart()

    buildMonthlyChart()

    buildCustomerChart()

    buildPaymentMethodChart()

    buildPaymentStatusChart()

}

/*
|--------------------------------------------------------------------------
| Refresh Charts whenever filters change
|--------------------------------------------------------------------------
*/

watch(

    filteredSales,

    () => {

        calculateStatistics()

        buildCharts()

    },

    {

        deep: true

    }

)

const openView = (record) => {

    selectedSale.value = record

    showView.value = true

}

const closeView = () => {

    selectedSale.value = null

    showView.value = false

}

const openEdit = (record) => {

    selectedSale.value = record

    showEdit.value = true

}


const closeEdit = () => {

    selectedSale.value = null

    showEdit.value = false

}

/*
|--------------------------------------------------------------------------
| Export PDF
|--------------------------------------------------------------------------
*/

const exportPdf = () => {

    window.open(

        '/vegetable-sales/report/pdf',

        '_blank'

    )

}

onMounted(() => {

    fetchSales()

})

</script>
<template>
    <Head title="Sales" />
    <AuthenticatedLayout>
        <div class="flex flex-col md:flex-row justify-between items-center mb-6">

    <div class="mt-6">

        <h1 class="text-3xl font-bold">
            Vegetable Sales Dashboard
        </h1>

        <p class="text-gray-500">
            Monitor vegetable sales, revenue, customers and payment performance.
        </p>

    </div>

    <a-space>

        <a-button
            @click="refresh">

            <ReloadOutlined />

            Refresh

        </a-button>

        <a-button
            @click="exportPdf">

            <FilePdfOutlined />

            PDF

        </a-button>

        <a-button
            type="primary"
            @click="showCreate=true">

            <PlusOutlined />

            New Sale

        </a-button>

    </a-space>

</div>

<a-card class="mt-5">

    <a-table

        :columns="columns"

        :data-source="filteredSales"

        :loading="loading"

        row-key="id"

        bordered

        :pagination="{

            pageSize:10,

            showSizeChanger:true,

            showQuickJumper:true

        }"

        :scroll="{

            x:1800,

            y:500

        }"

    >

        <template #bodyCell="{ column, record, index }">

            <!-- Row Number -->

            <template v-if="column.key==='index'">

                {{ index + 1 }}

            </template>

            <!-- Crop -->

            <template v-else-if="column.key==='crop'">

                {{

                    record.harvest?.production?.vegetable_type?.name

                    ??

                    '-'

                }}

            </template>

            <!-- Batch -->

            <template v-else-if="column.key==='batch'">

                {{
                    record.harvest?.production?.batch_number
                    ??
                    '-'
                }}

            </template>

            <!-- Date -->

            <template v-else-if="column.dataIndex==='sale_date'">

                {{ dayjs(record.sale_date).format('DD MMM YYYY') }}

            </template>

            <!-- Quantity -->

            <template v-else-if="column.dataIndex==='quantity'">

                {{ Number(record.quantity).toLocaleString() }} Kg

            </template>

            <!-- Unit Price -->

            <template v-else-if="column.key==='unit_price'">

                K {{ Number(record.unit_price).toLocaleString() }}

            </template>

            <!-- Total -->

            <template v-else-if="column.key==='total_amount'">

                <strong>

                    K {{ Number(record.total_amount).toLocaleString() }}

                </strong>

            </template>

            <!-- Payment Status -->

            <template v-else-if="column.key==='payment_status'">

                <a-tag

                    v-if="record.payment_status==='Paid'"

                    color="green"

                >

                    Paid

                </a-tag>

                <a-tag

                    v-else-if="record.payment_status==='Partial'"

                    color="orange"

                >

                    Partial

                </a-tag>

                <a-tag

                    v-else

                    color="red"

                >

                    Unpaid

                </a-tag>

            </template>

            <!-- Actions -->

            <template v-else-if="column.key==='actions'">

                <a-space>

                    <a-tooltip title="View">

                        <a-button

                            size="small"

                            @click="openView(record)"

                        >

                            <EyeOutlined />

                        </a-button>

                    </a-tooltip>

                    <a-tooltip title="Edit">

                        <a-button

                            type="primary"

                            ghost

                            size="small"

                            @click="openEdit(record)"

                        >

                            <EditOutlined />

                        </a-button>

                    </a-tooltip>

                    <a-tooltip title="Delete">

                        <a-popconfirm

                            title="Delete this sale?"

                            ok-text="Yes"

                            cancel-text="No"

                            @confirm="deleteSale(record)"

                        >

                            <a-button

                                danger

                                size="small"

                            >

                                <DeleteOutlined />

                            </a-button>

                        </a-popconfirm>

                    </a-tooltip>

                </a-space>

            </template>

        </template>

    </a-table>

</a-card>

<CreateSale

    v-model:open="showCreate"

    @success="fetchSales"

/>


<EditSale

    v-model:open="showEdit"

    :sale="selectedSale"

    @success="fetchSales"

    @close="closeEdit"

/>


<ViewSale

    v-model:open="showView"

    :sale="selectedSale"

/>

    </AuthenticatedLayout>
</template>