<!-- resources\js\Pages\MyFarmer\Vegetables\Expenses\ViewExpense.vue -->
<script setup>
import dayjs from 'dayjs'

const props = defineProps({

    open: {
        type: Boolean,
        default: false
    },

    expense: {
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

const currency = (value) => {

    return Number(value ?? 0).toLocaleString()

}
</script>

<template>

    <a-modal
        :open="open"
        title="Vegetable Expense Details"
        :width="'80%'"
        :footer="null"
        @cancel="closeModal"
    >

        <template v-if="expense">

            <!-- Header -->

            <a-card class="mb-4">

                <div class="flex justify-between items-center">

                    <div>

                        <h2 class="text-2xl font-bold text-green-700">

                            {{ expense.category }}

                        </h2>

                        <div class="text-gray-500">

                            Expense Date

                            •

                            {{ dayjs(expense.expense_date).format('DD MMMM YYYY') }}

                        </div>

                    </div>

                    <a-tag color="red">

                        K {{ currency(expense.unit_cost) }}

                    </a-tag>

                </div>

            </a-card>

            <!-- Statistics -->

            <a-row :gutter="16" class="mb-5">

                <a-col :span="8">

                    <a-card>

                        <a-statistic

                            title="Quantity"

                            :value="expense.quantity"

                        />

                    </a-card>

                </a-col>

                <a-col :span="8">

                    <a-card>

                        <a-statistic

                            title="Unit Cost"

                            prefix="K"

                            :value="expense.unit_cost"

                        />

                    </a-card>

                </a-col>

                <a-col :span="8">

                    <a-card>

                        <a-statistic

                            title="Total Amount"

                            prefix="K"

                            :value="expense.amount"

                            :valueStyle="{ color:'#cf1322' }"

                        />

                    </a-card>

                </a-col>

            </a-row>

            <!-- Details -->

            <a-descriptions
                bordered
                :column="2"
                size="middle"
            >

                <a-descriptions-item label="Production Batch">

                    {{ expense.production?.batch_number ?? '-' }}

                </a-descriptions-item>

                <a-descriptions-item label="Vegetable">

                    {{ expense.production?.vegetable_type?.name ?? '-' }}

                </a-descriptions-item>

                <a-descriptions-item label="Season">

                    {{ expense.production?.season ?? '-' }}

                </a-descriptions-item>

                <a-descriptions-item label="Quarter">

                    {{ expense.production?.quarter ?? '-' }}

                </a-descriptions-item>

                <a-descriptions-item label="Expense Category">

                    {{ expense.expense_category }}

                </a-descriptions-item>

                <a-descriptions-item label="Supplier">

                    {{ expense.supplier || '-' }}

                </a-descriptions-item>

                <a-descriptions-item label="Payment Method">

                    {{ expense.payment_method }}

                </a-descriptions-item>

                <a-descriptions-item label="Reference Number">

                    {{ expense.reference_number || '-' }}

                </a-descriptions-item>

                <a-descriptions-item label="Description" :span="2">

                    {{ expense.description || '-' }}

                </a-descriptions-item>

                <a-descriptions-item label="Notes" :span="2">

                    {{ expense.notes || '-' }}

                </a-descriptions-item>

            </a-descriptions>

            <!-- Footer -->

            <div class="flex justify-end mt-6">

                <a-button
                    @click="closeModal"
                >

                    Close

                </a-button>

            </div>

        </template>

    </a-modal>

</template>