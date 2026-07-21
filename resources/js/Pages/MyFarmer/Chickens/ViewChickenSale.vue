<!-- resources\js\Pages\MyFarmer\Chickens\ViewChickenSale.vue -->
<script setup>

import { computed } from 'vue'

import dayjs from 'dayjs'


const props = defineProps({

    open: Boolean,

    sale: {

        type: Object,

        default: () => ({})

    },

    batchInfo: {

        type: Object,

        default: () => ({})

    }

})


const emit = defineEmits([

    'update:open'

])


const closeModal = () => {

    emit(
        'update:open',
        false
    )

}


const money = (value) => {

    return Number(
        value ?? 0
    ).toLocaleString(
        undefined,
        {

            minimumFractionDigits: 2,

            maximumFractionDigits: 2

        }
    )

}


const totalPaid = computed(() => {

    return Number(
        props.sale?.amount_paid ?? 0
    )

})


const balance = computed(() => {

    return Number(
        props.sale?.balance ?? 0
    )

})


const paymentStatus = computed(() => {

    const totalAmount = Number(
        props.sale?.total_amount ?? 0
    )


    const paidAmount = Number(
        props.sale?.amount_paid ?? 0
    )


    if (paidAmount <= 0) {

        return 'Credit'

    }


    if (paidAmount < totalAmount) {

        return 'Partially Paid'

    }


    return 'Paid'

})


const paymentStatusColor = computed(() => {

    switch (paymentStatus.value) {

        case 'Paid':
            return 'green'

        case 'Partially Paid':
            return 'orange'

        case 'Credit':
            return 'red'

        default:
            return 'default'

    }

})


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


const printReceipt = () => {

    window.open(

        `/chicken-sales/${props.sale.id}/receipt`,

        '_blank'

    )

}

</script>
<template>

    <a-modal :open="open" width="1200px" :footer="null" :maskClosable="false" @cancel="closeModal">

        <!-- Header -->

        <div class="mb-8">
            <h2 class="text-3xl font-bold text-gray-800">
                Chicken Sale Details
            </h2>
            <p class="text-gray-500 mt-2">
                View complete information about this chicken sale transaction.
            </p>
        </div>

        <!-- ================================================= -->
        <!-- Sale Information -->
        <!-- ================================================= -->

        <a-card title="Sale Information" class="mb-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-5">
                <div>
                    <label>Sale Date</label>
                    <a-input :value="sale?.sale_date ? dayjs(sale.sale_date).format('DD MMM YYYY') : ''" readonly
                        style="border: 1px solid #e9e9e9; border-radius: 8px;" />
                </div>

                <div>
                    <label>Quantity Sold</label>
                    <a-input :value="sale?.quantity" readonly style="border: 1px solid #e9e9e9; border-radius: 8px;" />
                </div>

                <div>
                    <label>Price Per Bird</label>
                    <a-input :value="`K ${money(sale?.unit_price)}`" readonly
                        style="border: 1px solid #e9e9e9; border-radius: 8px;" />
                </div>

                <div>
                    <label>Total Amount</label>
                    <a-input :value="`K ${money(sale?.total_amount)}`" readonly
                        style="border: 1px solid #e9e9e9; border-radius: 8px;" />
                </div>
            </div>

        </a-card>

        <!-- ================================================= -->
        <!-- Batch Information -->
        <!-- ================================================= -->

        <a-card title="Batch Information" class="mb-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-5">
                <div>
                    <label>Batch Number</label>
                    <a-input :value="batchInfo?.batch_number" readonly
                        style="border: 1px solid #e9e9e9; border-radius: 8px;" />
                </div>

                <div>
                    <label>Batch Name</label>
                    <a-input :value="batchInfo?.batch_name" readonly
                        style="border: 1px solid #e9e9e9; border-radius: 8px;" />
                </div>

                <div>
                    <label>Breed</label>
                    <a-input :value="batchInfo?.breed" readonly
                        style="border: 1px solid #e9e9e9; border-radius: 8px;" />
                </div>

                <div>
                    <label>Status</label>
                    <a-tag :color="batchInfo?.status == 'Growing'
                        ? 'blue'
                        : batchInfo?.status == 'Ready for Sale'
                            ? 'green'
                            : batchInfo?.status == 'Selling'
                                ? 'orange'
                                : batchInfo?.status == 'Completed'
                                    ? 'purple'
                                    : 'default'
                        ">

                        {{ batchInfo?.status }}

                    </a-tag>
                </div>
            </div>

        </a-card>

        <!-- ================================================= -->
        <!-- Customer Information -->
        <!-- ================================================= -->

        <a-card title="Customer Information" class="mb-6">

            <div class="grid grid-cols-1 md:grid-cols-4 gap-5">
                <div>
                    <label>Customer Name</label>
                    <a-input :value="sale?.customer_name" readonly
                        style="border: 1px solid #e9e9e9; border-radius: 8px;" />
                </div>

                <div>
                    <label>Phone Number</label>
                    <a-input :value="sale?.customer_phone" readonly
                        style="border: 1px solid #e9e9e9; border-radius: 8px;" />
                </div>

                  <div>
                    <label>Sale Type</label>
                    <a-input :value="sale?.sale_type" readonly
                        style="border: 1px solid #e9e9e9; border-radius: 8px;" />
                </div>

                <!-- <div>
                    <label>Payment Method</label>
                    <a-tag :color="paymentColor">
                        {{ sale?.payment_method ?? 'Not Specified' }}
                    </a-tag>
                </div> -->

                <div>
                    <label>Payment Status : </label>
                    <a-tag :color="paymentStatusColor">
                        {{ paymentStatus }}
                    </a-tag>
                </div>

            </div>

        </a-card>

        <!-- ================================================= -->
        <!-- Notes -->
        <!-- ================================================= -->

        <a-card title="Notes" class="mb-6">

            <a-textarea :value="sale?.notes" :rows="5" readonly />

        </a-card>

        <!-- ================================================= -->
        <!-- Summary -->
        <!-- ================================================= -->

        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-6">

            <a-card>

                <div class="text-center">

                    <div class="text-gray-500">

                        Birds Sold

                    </div>

                    <div class="text-3xl font-bold text-blue-600 mt-2">

                        {{ sale?.quantity }}

                    </div>

                </div>

            </a-card>

            <a-card>

                <div class="text-center">

                    <div class="text-gray-500">

                        Unit Price

                    </div>

                    <div class="text-3xl font-bold text-indigo-600 mt-2">

                        K {{ money(sale?.unit_price) }}

                    </div>

                </div>

            </a-card>

            <a-card>

                <div class="text-center">

                    <div class="text-gray-500">

                        Sale Value

                    </div>

                    <div class="text-3xl font-bold text-green-600 mt-2">

                        K {{ money(sale?.total_amount) }}

                    </div>

                </div>

            </a-card>


            <a-card title="Customer Information" class="mb-6">

                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

                    <div>
                        <label>Customer Name</label>

                        <a-input :value="sale?.customer_name" readonly
                            style="border: 1px solid #e9e9e9; border-radius: 8px;" />

                    </div>


                    <div>
                        <label>Phone Number</label>

                        <a-input :value="sale?.customer_phone" readonly
                            style="border: 1px solid #e9e9e9; border-radius: 8px;" />

                    </div>


                    <div>
                        <label>Payment Status</label>

                        <a-tag :color="paymentStatusColor">

                            {{ paymentStatus }}

                        </a-tag>

                    </div>

                </div>

            </a-card>




            <!-- ================================================= -->
            <!-- Payment Summary -->
            <!-- ================================================= -->

            <a-card title="Payment Summary" class="mb-6">

                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

                    <div>

                        <label>Total Amount</label>

                        <a-input :value="`K ${money(sale?.total_amount)}`" readonly
                            style="border: 1px solid #e9e9e9; border-radius: 8px;" />

                    </div>


                    <div>

                        <label>Total Amount Paid</label>

                        <a-input :value="`K ${money(totalPaid)}`" readonly
                            style="border: 1px solid #e9e9e9; border-radius: 8px;" />

                    </div>


                    <div>

                        <label>Balance</label>

                        <a-input :value="`K ${money(balance)}`" readonly
                            style="border: 1px solid #e9e9e9; border-radius: 8px;" />

                    </div>

                </div>

            </a-card>
            <!-- ================================================= -->
            <!-- Payment History -->
            <!-- ================================================= -->

            <a-card title="Payment History" class="mb-6">

                <a-empty v-if="!sale?.payments || sale.payments.length === 0"
                    description="No payments have been made yet." />

                <div v-else class="payment-history-wrapper">

                    <a-table :data-source="sale.payments" :pagination="false" row-key="id" bordered
                        :scroll="{ x: 800 }">

                        <a-table-column title="#" key="index" width="70px" fixed="left">

                            <template #default="{ index }">

                                {{ index + 1 }}

                            </template>

                        </a-table-column>


                        <a-table-column title="Payment Date" key="payment_date" width="160px">

                            <template #default="{ record }">

                                {{
                                    record.payment_date
                                        ? dayjs(record.payment_date)
                                            .format('DD MMM YYYY')
                                        : '-'
                                }}

                            </template>

                        </a-table-column>


                        <a-table-column title="Amount Paid" key="amount" width="160px">

                            <template #default="{ record }">

                                <strong>

                                    K {{ money(record.amount) }}

                                </strong>

                            </template>

                        </a-table-column>


                        <a-table-column title="Payment Method" key="payment_method" width="180px">

                            <template #default="{ record }">

                                <a-tag :color="getPaymentColor(
                                    record.payment_method
                                )
                                    ">

                                    {{ record.payment_method }}

                                </a-tag>

                            </template>

                        </a-table-column>


                        <a-table-column title="Notes" key="notes" width="300px">

                            <template #default="{ record }">

                                <span class="payment-notes">

                                    {{ record.notes || '-' }}

                                </span>

                            </template>

                        </a-table-column>

                    </a-table>

                </div>

            </a-card>
        </div>

        <!-- ================================================= -->
        <!-- Footer -->
        <!-- ================================================= -->

        <div class="flex justify-end gap-4 mt-8">

            <a-button size="large" @click="closeModal">

                Close

            </a-button>

            <a-button type="primary" size="large" @click="printReceipt">

                Print Receipt

            </a-button>

        </div>

    </a-modal>

</template>

<style scoped>
/* Payment history scroll container */

.payment-history-wrapper {

    width: 100%;

    max-width: 100%;

    max-height: 350px;

    overflow-x: auto;

    overflow-y: auto;

}


/* Keep the table wide enough for horizontal scrolling */

.payment-history-wrapper :deep(.ant-table) {

    min-width: 800px;

}


/* Prevent long notes from breaking the layout */

.payment-notes {

    display: block;

    max-width: 280px;

    white-space: normal;

    word-break: break-word;

}


/* Better scrollbar appearance */

.payment-history-wrapper::-webkit-scrollbar {

    width: 8px;

    height: 8px;

}

.payment-history-wrapper::-webkit-scrollbar-thumb {

    background: #cbd5e1;

    border-radius: 10px;

}

.payment-history-wrapper::-webkit-scrollbar-track {

    background: #f1f5f9;

}
</style>