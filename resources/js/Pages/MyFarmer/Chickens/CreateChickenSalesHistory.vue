<!-- resources\js\Pages\MyFarmer\Chickens\CreateChickenSalesHistory.vue -->
<script setup>
import { ref, watch, h } from 'vue'
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


    catch(error){


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

const columns = [

    {

        title: 'Sale Date',

        dataIndex: 'sale_date',

        customRender: ({ record }) =>

            dayjs(record.sale_date).format('DD MMM YYYY')

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

        title: 'Price',

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

                    class: 'font-bold text-green-600'

                },

                `K ${money(record.total_amount)}`

            )

    },

    {

        title: 'Actions',

        align: 'center',

        width: 220,

        customRender: ({ record }) =>

            h(

                Space,

                {},

                () => [

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

                    h(

    Tooltip,

    {

        title:'Download Receipt'

    },

    {

        default:()=>


            h(

                Button,

                {

                    type:'link',

                    icon:h(DownloadOutlined),

                    onClick:()=>downloadReceipt(record)

                }

            )


    }

),

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
</script>

<template>

    <a-modal
        :open="open"
        width="1400px"
        :footer="null"
        :maskClosable="false"
        @cancel="closeModal">

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

        <a-card
            title="Batch Information"
            class="mb-6">

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">

                <div>

                    <label>Batch Number</label>

                    <a-input
                       
                        :value="batchInfo.batch_number"
                        readonly />

                </div>

                <div>

                    <label>Batch Name</label>

                    <a-input
                        :value="props.batch.batch_name"
                        readonly />

                </div>

                <div>

                    <label>Breed</label>

                    <a-input
                        :value="props.batch.breed"
                        readonly />

                </div>

                <div>

                    <label>Arrival Date</label>

                    <a-input
                        :value="props.batch.arrival_date"
                        readonly />

                </div>

                <div>

                    <label>Batch Size</label>

                    <a-input
                        :value="summary.batch_size"
                        readonly />

                </div>

                <div>

                    <label>Mortality</label>

                    <a-input
                        :value="summary.mortality"
                        readonly />

                </div>

                <div>

                    <label>Birds Sold</label>

                    <a-input
                        :value="summary.birds_sold"
                        readonly />

                </div>

                <div>

                    <label>Birds Remaining</label>

                    <a-input
                        :value="summary.birds_remaining"
                        readonly />

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

                    <div
                        class="text-3xl font-bold mt-2"
                        :class="summary.profit >= 0
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

            <a-table

                :columns="columns"

                :data-source="sales"

                :loading="loading"

                row-key="id"

                bordered

                :pagination="{

                    pageSize:8,

                    showSizeChanger:true,

                    showTotal:(total)=>`Total ${total} sales`

                }"

            />

        </a-card>

        <!-- ========================= -->
        <!-- Footer -->
        <!-- ========================= -->

        <div class="flex justify-end mt-8">

            <a-button
                size="large"
                @click="closeModal">

                Close

            </a-button>

        </div>

        <!-- ========================= -->
        <!-- View Sale Modal -->
        <!-- (Part 3) -->
        <!-- ========================= -->


       <ViewChickenSale v-model:open="showViewModal" :sale="selectedSale" :batchInfo="batchInfo" />
        <!-- ========================= -->
        <!-- Edit Sale Modal -->
        <!-- (Part 3) -->
        <!-- ========================= -->

        
        <EditChickenSale

    v-model:open="showEditModal"

    :sale="selectedSale"

    @updated="fetchSales"

/>


       

    </a-modal>

</template>