<!-- resources\js\Pages\MyFarmer\Chickens\CreateChickenSalesHistory.vue -->
<script setup>
import { ref, watch, h, computed } from 'vue'
import axios from 'axios'
import dayjs from 'dayjs'
import ViewChickenSale from './ViewChickenSale.vue'
import EditChickenSale from './EditChickenSale.vue'

import {
    message,
    Button,
    Tag,
    Tooltip,
    Popconfirm,
    Space
} from 'ant-design-vue'

import {

    EyeOutlined,
    EditOutlined,
    DeleteOutlined,
    PrinterOutlined,
    DownloadOutlined

} from '@ant-design/icons-vue'

const props = defineProps({

    open: Boolean,

    batch: Object

})

const emit = defineEmits([

    'update:open',

    'updated'

])

const loading = ref(false)

const sales = ref([])
const batchInfo = ref({})

const paymentStatusFilter = ref(null)

const paymentMethodFilter = ref(null)

const dateFilter = ref(null)

const summary = ref({

    batch_size: 0,

    mortality: 0,

    birds_sold: 0,

    birds_remaining: 0,

    total_expenses: 0,

    total_sales: 0,

    profit: 0

})

const selectedSale = ref(null)

const showEditModal = ref(false)

const showViewModal = ref(false)

const filteredSales = computed(() => {

    return sales.value.filter(sale => {

        // Payment status filter

        if (

            paymentStatusFilter.value &&

            sale.payment_status !== paymentStatusFilter.value

        ) {

            return false

        }


        // Payment method filter

        if (paymentMethodFilter.value) {

            const hasMethod =

                sale.payments?.some(

                    payment =>

                        payment.payment_method ===

                        paymentMethodFilter.value

                )

            if (!hasMethod) {

                return false

            }

        }


        // Date filter

        if (dateFilter.value) {

            const saleDate = dayjs(

                sale.sale_date

            ).format('YYYY-MM-DD')

            if (

                saleDate !==

                dateFilter.value.format('YYYY-MM-DD')

            ) {

                return false

            }

        }


        return true

    })

})

/*
|--------------------------------------------------------------------------
| Watch Modal
|--------------------------------------------------------------------------
*/

watch(() => props.open, (value) => {

    if (value && props.batch) {

        fetchSales()

    }

})

/*
|--------------------------------------------------------------------------
| Fetch Sales
|--------------------------------------------------------------------------
*/

const fetchSales = async () => {

    loading.value = true

    try {

        const { data } = await axios.get(

            `/chicken-sales/${props.batch.id}`

        )

        batchInfo.value = data.batch

        sales.value = data.sales

        summary.value = data.summary

    }

    catch (error) {

        console.log(error)

        message.error('Unable to load sales.')

    }

    finally {

        loading.value = false

    }

}

/*
|--------------------------------------------------------------------------
| Close Modal
|--------------------------------------------------------------------------
*/

const closeModal = () => {

    emit('update:open', false)

}


/*
|--------------------------------------------------------------------------
| Currency Formatter
|--------------------------------------------------------------------------
*/

const money = (amount) => {

    return Number(amount ?? 0).toLocaleString(undefined, {

        minimumFractionDigits: 2,

        maximumFractionDigits: 2

    })

}

/*
|--------------------------------------------------------------------------
| Print Receipt
|--------------------------------------------------------------------------
*/

const printReceipt = (sale) => {

    window.open(

        `/chicken-sales/${sale.id}/receipt`,

        '_blank'

    )

}


const downloadReceipt = (sale) => {

    window.open(

        `/chicken-sales/${sale.id}/receipt/download`,

        '_blank'

    )

}
/*
|--------------------------------------------------------------------------
| Reverse Sale
|--------------------------------------------------------------------------
*/

const reverseSale = async (sale) => {


    try {


        await axios.delete(
            `/chicken-sales/${sale.id}`
        )


        message.success(
            'Sale reversed successfully.'
        )


        await fetchSales()


        emit('updated')


    }


    catch (error) {


        console.log(error)


        message.error(
            error.response?.data?.message ??
            'Unable to reverse sale.'
        )


    }


}

/*
|--------------------------------------------------------------------------
| Table Columns
|--------------------------------------------------------------------------
*/

// const columns = [

//     {

//         title: 'Sale Date',

//         dataIndex: 'sale_date',

//         customRender: ({ record }) =>

//             dayjs(record.sale_date).format('DD MMM YYYY')

//     },

//     {

//         title: 'Customer',

//         dataIndex: 'customer_name',

//         customRender: ({ record }) =>

//             record.customer_name || '-'

//     },

//     {

//         title: 'Phone',

//         dataIndex: 'customer_phone',

//         customRender: ({ record }) =>

//             record.customer_phone || '-'

//     },

//     {

//         title: 'Quantity',

//         dataIndex: 'quantity'

//     },

//     {

//         title: 'Price',

//         dataIndex: 'unit_price',

//         customRender: ({ record }) =>

//             `K ${money(record.unit_price)}`

//     },

//     {

//         title: 'Total',

//         dataIndex: 'total_amount',

//         customRender: ({ record }) =>

//             h(

//                 'span',

//                 {

//                     class: 'font-bold text-green-600'

//                 },

//                 `K ${money(record.total_amount)}`

//             )

//     },

//     {

//         title: 'Actions',

//         align: 'center',

//         width: 220,

//         customRender: ({ record }) =>

//             h(

//                 Space,

//                 {},

//                 () => [

//                     h(

//                         Tooltip,

//                         {

//                             title: 'View'

//                         },

//                         {

//                             default: () =>

//                                 h(

//                                     Button,

//                                     {

//                                         type: 'link',

//                                         icon: h(EyeOutlined),

//                                         onClick: () => {

//                                             selectedSale.value = record

//                                             showViewModal.value = true

//                                         }

//                                     }

//                                 )

//                         }

//                     ),

//                     h(

//                         Tooltip,

//                         {

//                             title: 'Edit'

//                         },

//                         {

//                             default: () =>

//                                 h(

//                                     Button,

//                                     {

//                                         type: 'link',

//                                         icon: h(EditOutlined),

//                                         onClick: () => {

//                                             selectedSale.value = record

//                                             showEditModal.value = true

//                                         }

//                                     }

//                                 )

//                         }

//                     ),

//                     h(

//                         Tooltip,

//                         {

//                             title: 'Print Receipt'

//                         },

//                         {

//                             default: () =>

//                                 h(

//                                     Button,

//                                     {

//                                         type: 'link',

//                                         icon: h(PrinterOutlined),

//                                         onClick: () =>

//                                             printReceipt(record)

//                                     }

//                                 )

//                         }

//                     ),

//                     h(

//     Tooltip,

//     {

//         title:'Download Receipt'

//     },

//     {

//         default:()=>


//             h(

//                 Button,

//                 {

//                     type:'link',

//                     icon:h(DownloadOutlined),

//                     onClick:()=>downloadReceipt(record)

//                 }

//             )


//     }

// ),

//                     h(

//                         Popconfirm,

//                         {

//                             title: 'Reverse this sale?',

//                             okText: 'Yes',

//                             cancelText: 'No',

//                             onConfirm: () =>

//                                 reverseSale(record)

//                         },

//                         {

//                             default: () =>

//                                 h(

//                                     Button,

//                                     {

//                                         danger: true,

//                                         type: 'link',

//                                         icon: h(DeleteOutlined)

//                                     }

//                                 )

//                         }

//                     )

//                 ]

//             )

//     }

// ]

const columns = [

    {
        title: 'Sale Date',

        dataIndex: 'sale_date',

        customRender: ({ record }) =>

            dayjs(record.sale_date)
                .format('DD MMM YYYY')

    },

    {
        title: 'Sale Type',

        dataIndex: 'sale_type',

        customRender: ({ record }) =>

            record.sale_type || '-'

    },

       {
        title: 'Customer',

        dataIndex: 'customer_name',

        customRender: ({ record }) =>

            record.customer_name || '-'

    },

    

    {
        title: 'Phone',

        dataIndex: 'customer_phone',

        customRender: ({ record }) =>

            record.customer_phone || '-'

    },

    {
        title: 'Quantity',

        dataIndex: 'quantity'

    },

     {
        title: 'Total Weight',
        dataIndex: 'total_weight'

    },

     {
        title: 'Price per Kg',
        dataIndex: 'price_per_kg'

    },

    

    

    {
        title: 'Unit Price',

        dataIndex: 'unit_price',

        customRender: ({ record }) =>

            `K ${money(record.unit_price)}`

    },

    {
        title: 'Total',

        dataIndex: 'total_amount',

        customRender: ({ record }) =>

            h(

                'span',

                {
                    class: 'font-bold text-blue-600'
                },

                `K ${money(record.total_amount)}`

            )

    },

    {
        title: 'Amount Paid',

        dataIndex: 'amount_paid',

        customRender: ({ record }) =>

            h(

                'span',

                {
                    class: 'font-bold text-green-600'
                },

                `K ${money(record.amount_paid)}`

            )

    },

    {
        title: 'Balance',

        dataIndex: 'balance',

        customRender: ({ record }) =>

            h(

                'span',

                {
                    class: record.balance > 0

                        ? 'font-bold text-red-600'

                        : 'font-bold text-green-600'

                },

                `K ${money(record.balance)}`

            )

    },


{
    title: 'Payment',
    key: 'payment_methods',
    width: 220,

    customRender: ({ record }) => {

        const payments = record.payments || []

        if (!payments.length) {
            return h(
                Tag,
                { color: 'red' },
                () => 'No Payment'
            )
        }

        const trimPaymentMethod = (method) => {

            if (!method) {
                return '-'
            }

            return method.length > 10
                ? method.substring(0, 10) + '...'
                : method
        }

        return h(
            'div',
            {
                style: {
                    display: 'flex',
                    alignItems: 'center',
                    gap: '4px',
                    overflowX: 'auto',
                    whiteSpace: 'nowrap',
                    maxWidth: '100%',
                    paddingBottom: '2px'
                }
            },

            payments.map(payment =>

                h(
                    Tooltip,
                    {
                        title: payment.payment_method
                    },

                    {
                        default: () =>
                            h(
                                Tag,
                                {
                                    color: getPaymentColor(
                                        payment.payment_method
                                    ),
                                    style: {
                                        flexShrink: 0,
                                        margin: 0,
                                        cursor: 'pointer'
                                    }
                                },
                                () =>
                                    trimPaymentMethod(
                                        payment.payment_method
                                    )
                            )
                    }
                )

            )
        )
    }
},

    // {
    //     title: 'Payment Methods',

    //     key: 'payment_methods',

    //     customRender: ({ record }) => {

    //         const payments = record.payments || []

    //         if (!payments.length) {

    //             return h(

    //                 Tag,

    //                 { color: 'red' },

    //                 () => 'No Payment'

    //             )

    //         }

    //         return h(

    //             Space,

    //             { wrap: true },

    //             () => payments.map(payment =>

    //                 h(

    //                     Tag,

    //                     {
    //                         color: getPaymentColor(
    //                             payment.payment_method
    //                         )
    //                     },

    //                     () => payment.payment_method

    //                 )

    //             )

    //         )

    //     }

    // },


    {
        title: 'Actions',

        align: 'center',

        width: 220,

        customRender: ({ record }) =>

            h(

                Space,

                {},

                () => [

                    // View

                    h(

                        Tooltip,

                        {
                            title: 'View'
                        },

                        {

                            default: () =>

                                h(

                                    Button,

                                    {

                                        type: 'link',

                                        icon: h(EyeOutlined),

                                        onClick: () => {

                                            selectedSale.value = record

                                            showViewModal.value = true

                                        }

                                    }

                                )

                        }

                    ),

                    // Edit

                    h(

                        Tooltip,

                        {
                            title: 'Edit'
                        },

                        {

                            default: () =>

                                h(

                                    Button,

                                    {

                                        type: 'link',

                                        icon: h(EditOutlined),

                                        onClick: () => {

                                            selectedSale.value = record

                                            showEditModal.value = true

                                        }

                                    }

                                )

                        }

                    ),

                    // Print

                    h(

                        Tooltip,

                        {
                            title: 'Print Receipt'
                        },

                        {

                            default: () =>

                                h(

                                    Button,

                                    {

                                        type: 'link',

                                        icon: h(PrinterOutlined),

                                        onClick: () =>

                                            printReceipt(record)

                                    }

                                )

                        }

                    ),

                    // Download

                    h(

                        Tooltip,

                        {
                            title: 'Download Receipt'
                        },

                        {

                            default: () =>

                                h(

                                    Button,

                                    {

                                        type: 'link',

                                        icon: h(DownloadOutlined),

                                        onClick: () =>

                                            downloadReceipt(record)

                                    }

                                )

                        }

                    ),

                    // Delete

                    h(

                        Popconfirm,

                        {

                            title: 'Reverse this sale?',

                            okText: 'Yes',

                            cancelText: 'No',

                            onConfirm: () =>

                                reverseSale(record)

                        },

                        {

                            default: () =>

                                h(

                                    Button,

                                    {

                                        danger: true,

                                        type: 'link',

                                        icon: h(DeleteOutlined)

                                    }

                                )

                        }

                    )

                ]

            )

    }

]

const getPaymentColor = (method) => {

    switch (method) {

        case 'Cash':
            return 'green'

        case 'Mobile Money':
            return 'blue'

        case 'Bank Transfer':
            return 'purple'

        case 'Card':
            return 'cyan'

        case 'Cheque':
            return 'orange'

        default:
            return 'default'

    }

}


const clearFilters = () => {

    paymentStatusFilter.value = null

    paymentMethodFilter.value = null

    dateFilter.value = null

}

const filteredTotalSales = computed(() => {

    return filteredSales.value.reduce(

        (total, sale) =>

            total +

            Number(sale.total_amount || 0),

        0

    )

})


const filteredTotalPaid = computed(() => {

    return filteredSales.value.reduce(

        (total, sale) =>

            total +

            Number(sale.amount_paid || 0),

        0

    )

})


const filteredBalance = computed(() => {

    return filteredSales.value.reduce(

        (total, sale) =>

            total +

            Number(sale.balance || 0),

        0

    )

})

const downloadFilteredSales = () => {

    if (!filteredSales.value.length) {

        message.warning(
            'There are no filtered sales records to download.'
        )

        return

    }


    const rows = []


    // Header row

    rows.push([

        'Sale Date',

        'Customer',

        'Phone',

        'Quantity',

        'Price Per Bird',

        'Sale Total',

        'Amount Paid',

        'Balance',

        'Payment Status',

        'Payment Methods'

    ])


    // Filtered sales records

    filteredSales.value.forEach(sale => {

        const paymentMethods = sale.payments?.length

            ? sale.payments

                .map(payment => payment.payment_method)

                .join(', ')

            : 'No Payment'


        rows.push([

            dayjs(sale.sale_date)

                .format('DD MMM YYYY'),

            sale.customer_name || '-',

            sale.sale_type || '-',

            sale.customer_phone || '-',

            sale.quantity,

            Number(sale.unit_price || 0)

                .toFixed(2),

            Number(sale.total_amount || 0)

                .toFixed(2),

            Number(sale.amount_paid || 0)

                .toFixed(2),

            Number(sale.balance || 0)

                .toFixed(2),

            sale.payment_status || '-',

            paymentMethods

        ])

    })


    // Add filtered totals

    rows.push([])


    rows.push([

        '',

        '',

        '',

        '',

        'FILTERED TOTALS',

        filteredTotalSales.value.toFixed(2),

        filteredTotalPaid.value.toFixed(2),

        filteredBalance.value.toFixed(2),

        '',

        ''

    ])


    // Convert to CSV

    const csvContent = rows

        .map(row =>

            row.map(value =>

                `"${String(value).replace(/"/g, '""')}"`

            ).join(',')

        )

        .join('\n')


    // Create file

    const blob = new Blob(

        [csvContent],

        {

            type: 'text/csv;charset=utf-8;'

        }

    )


    const url = URL.createObjectURL(blob)


    const link = document.createElement('a')


    link.href = url


    link.download =

        `Filtered-Sales-${batchInfo.value.batch_number || 'Batch'}-${dayjs().format('YYYY-MM-DD')}.csv`


    link.click()


    URL.revokeObjectURL(url)


    message.success(

        'Filtered sales downloaded successfully.'

    )

}

</script>

<template>

    <a-modal :open="open" width="1400px" :footer="null" :maskClosable="false" @cancel="closeModal">

        <!-- Header -->

        <div class="mb-8">
            <h2 class="text-3xl font-bold text-gray-800">
                Chicken Sales History
            </h2>

            <p class="text-gray-500 mt-2">
                View all sales, financial performance and inventory movement for this batch.
            </p>
        </div>

        <!-- ========================= -->
        <!-- Batch Information -->
        <!-- ========================= -->

        <a-card title="Batch Information" class="mb-6">

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">

                <div>
                    <label>Batch Number</label>
                    <a-input :value="batchInfo.batch_number" readonly
                        style="border: 1px solid #e9e9e9; border-radius: 8px;" />
                </div>

                <div>
                    <label>Batch Name</label>
                    <a-input :value="props.batch.batch_name" readonly
                        style="border: 1px solid #e9e9e9; border-radius: 8px;" />
                </div>

                <div>
                    <label>Breed</label>
                    <a-input :value="props.batch.breed" readonly
                        style="border: 1px solid #e9e9e9; border-radius: 8px;" />
                </div>

                <div>
                    <label>Arrival Date</label>
                    <a-input :value="props.batch.arrival_date" readonly
                        style="border: 1px solid #e9e9e9; border-radius: 8px;" />
                </div>

                <div>
                    <label>Batch Size</label>
                    <a-input :value="summary.batch_size" readonly
                        style="border: 1px solid #e9e9e9; border-radius: 8px;" />
                </div>

                <div>
                    <label>Mortality</label>
                    <a-input :value="summary.mortality" readonly
                        style="border: 1px solid #e9e9e9; border-radius: 8px;" />
                </div>

                <div>
                    <label>Birds Sold</label>
                    <a-input :value="summary.birds_sold" readonly
                        style="border: 1px solid #e9e9e9; border-radius: 8px;" />
                </div>

                <div>
                    <label>Birds Remaining</label>
                    <a-input :value="summary.birds_remaining" readonly
                        style="border: 1px solid #e9e9e9; border-radius: 8px;" />
                </div>

            </div>

        </a-card>

        <!-- ========================= -->
        <!-- Financial Summary -->
        <!-- ========================= -->

        <div class="grid grid-cols-1 md:grid-cols-4 gap-5 mb-6">

            <a-card>

                <div class="text-center">

                    <div class="text-gray-500">

                        Total Expenses

                    </div>

                    <div class="text-3xl font-bold text-red-600 mt-2">

                        K {{ money(summary.total_expenses) }}

                    </div>

                </div>

            </a-card>

            <a-card>

                <div class="text-center">

                    <div class="text-gray-500">

                        Total Sales

                    </div>

                    <div class="text-3xl font-bold text-blue-600 mt-2">

                        K {{ money(summary.total_sales) }}

                    </div>

                </div>

            </a-card>

            <a-card>

                <div class="text-center">

                    <div class="text-gray-500">

                        Profit / Loss

                    </div>

                    <div class="text-3xl font-bold mt-2" :class="summary.profit >= 0
                        ? 'text-green-600'
                        : 'text-red-600'">

                        K {{ money(summary.profit) }}

                    </div>

                </div>

            </a-card>

            <a-card>

                <div class="text-center">

                    <div class="text-gray-500">

                        Total Sales Records

                    </div>

                    <div class="text-3xl font-bold text-indigo-600 mt-2">

                        {{ sales.length }}

                    </div>

                </div>

            </a-card>

        </div>

        <!-- ========================= -->
        <!-- Sales Table -->
        <!-- ========================= -->

        <a-card title="Sales Transactions">
            <div class="mb-5">

                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

                    <!-- Payment Status -->

                    <a-select v-model:value="paymentStatusFilter" placeholder="Filter Payment Status" allow-clear
                        class="w-full">

                        <a-select-option value="Paid">

                            Paid

                        </a-select-option>

                        <a-select-option value="Partially Paid">

                            Partially Paid

                        </a-select-option>

                        <a-select-option value="Credit">

                            Credit

                        </a-select-option>

                    </a-select>


                    <!-- Payment Method -->

                    <a-select v-model:value="paymentMethodFilter" placeholder="Filter Payment Method" allow-clear
                        class="w-full">

                        <a-select-option value="Cash">

                            Cash

                        </a-select-option>

                        <a-select-option value="Credit">

                            Credit

                        </a-select-option>

                        <a-select-option value="Mobile Money">

                            Mobile Money

                        </a-select-option>

                        <a-select-option value="Bank Transfer">

                            Bank Transfer

                        </a-select-option>

                        <a-select-option value="Card">

                            Card

                        </a-select-option>

                        <a-select-option value="Cheque">

                            Cheque
                        </a-select-option>
                    </a-select>


                    <!-- Sale Date -->
                    <a-date-picker v-model:value="dateFilter" placeholder="Filter Sale Date" class="w-full" />
                    <!-- Clear -->
                    <a-button @click="clearFilters">
                        Clear Filters
                    </a-button>

                    <!-- Download -->
                    <a-button type="primary" @click="downloadFilteredSales">

                        <DownloadOutlined />

                        Download Filtered

                    </a-button>

                </div>

            </div>

            <!-- <a-table

                :columns="columns"

                :data-source="filteredSales"

                :loading="loading"

                row-key="id"

                bordered

                :pagination="{

                    pageSize:8,

                    showSizeChanger:true,

                    showTotal:(total)=>`Total ${total} sales`

                }"

            /> -->

            <a-table :columns="columns" :data-source="filteredSales" :loading="loading" row-key="id" bordered :scroll="{
                x: 1800,
                y: 400
            }" :pagination="{

                pageSize: 8,

                showSizeChanger: true,

                showTotal: (total) =>

                    `Showing ${total} filtered sales`

            }" />


        </a-card>


        <!-- payment totals to your financial summary -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-6">

            <a-card>

                <div class="text-center">

                    <div class="text-gray-500">

                        Filtered Sales Total

                    </div>

                    <div class="text-3xl font-bold text-blue-600 mt-2">

                        K {{ money(filteredTotalSales) }}

                    </div>

                </div>

            </a-card>


            <a-card>

                <div class="text-center">

                    <div class="text-gray-500">

                        Filtered Amount Paid

                    </div>

                    <div class="text-3xl font-bold text-green-600 mt-2">

                        K {{ money(filteredTotalPaid) }}

                    </div>

                </div>

            </a-card>


            <a-card>

                <div class="text-center">

                    <div class="text-gray-500">

                        Filtered Balance

                    </div>

                    <div class="text-3xl font-bold text-red-600 mt-2">

                        K {{ money(filteredBalance) }}

                    </div>

                </div>

            </a-card>

        </div>

        <!-- ========================= -->
        <!-- Footer -->
        <!-- ========================= -->

        <div class="flex justify-end mt-8">

            <a-button size="large" @click="closeModal">

                Close

            </a-button>

        </div>

        <ViewChickenSale v-model:open="showViewModal" :sale="selectedSale" :batchInfo="batchInfo" />

        <EditChickenSale v-model:open="showEditModal" :sale="selectedSale" @updated="fetchSales" />

    </a-modal>

</template>