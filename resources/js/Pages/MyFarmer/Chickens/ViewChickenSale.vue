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

    emit('update:open', false)

}

const money = (value) => {

    return Number(value ?? 0).toLocaleString(undefined, {

        minimumFractionDigits: 2,
        maximumFractionDigits: 2

    })

}

const paymentColor = computed(() => {

    switch (props.sale?.payment_method) {

        case 'Cash':
            return 'green'

        case 'Mobile Money':
            return 'blue'

        case 'Bank Transfer':
            return 'purple'

        case 'Credit':
            return 'orange'

        default:
            return 'default'

    }

})

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

                    <a-input :value="sale?.sale_date
                        ? dayjs(sale.sale_date).format('DD MMM YYYY')
                        : ''" readonly />

                </div>

                <div>

                    <label>Quantity Sold</label>

                    <a-input :value="sale?.quantity" readonly />

                </div>

                <div>

                    <label>Price Per Bird</label>

                    <a-input :value="`K ${money(sale?.unit_price)}`" readonly />

                </div>

                <div>

                    <label>Total Amount</label>

                    <a-input :value="`K ${money(sale?.total_amount)}`" readonly />

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
                    <a-input :value="batchInfo?.batch_number" readonly />
                </div>

                <div>
                    <label>Batch Name</label>
                    <a-input :value="batchInfo?.batch_name" readonly />
                </div>

                <div>
                    <label>Breed</label>
                    <a-input :value="batchInfo?.breed" readonly />
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

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

                <div>

                    <label>Customer Name</label>

                    <a-input :value="sale?.customer_name" readonly />

                </div>

                <div>

                    <label>Phone Number</label>

                    <a-input :value="sale?.customer_phone" readonly />

                </div>

                <div>

                    <label>Payment Method</label>

                    <a-tag :color="paymentColor">

                        {{ sale?.payment_method ?? 'Not Specified' }}

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