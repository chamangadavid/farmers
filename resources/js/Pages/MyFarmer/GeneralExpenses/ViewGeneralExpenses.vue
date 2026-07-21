<script setup>
import { computed } from 'vue'
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


const formattedDate = computed(() => {
    if (!props.expense?.expense_date) {
        return '-'
    }

    return dayjs(props.expense.expense_date).format('DD MMMM YYYY')

})


const formattedAmount = computed(() => {
    return Number(props.expense?.amount ?? 0).toLocaleString(undefined, {

        minimumFractionDigits: 2,
        maximumFractionDigits: 2

    }
    )
})

const statusColor = computed(() => {

    switch (props.expense?.payment_status) {

        case 'Paid':
            return 'green'

        case 'Partial':
            return 'orange'

        case 'Pending':
            return 'blue'

        case 'Cancelled':
            return 'red'

        default:
            return 'default'

    }

})


</script>


<template>

    <a-modal :open="open" title="General Expense Details" :width="900" :footer="null" @cancel="closeModal">
        <div class="flex items-center justify-between mb-6">
            <div>
                <div class="text-gray-500 text-sm">
                    Expense Name
                </div>
                <div class="text-2xl font-bold">
                    {{ expense.expense_name || '-' }}
                </div>
            </div>

            <a-tag :color="statusColor" class="text-sm px-3 py-1">
                {{ expense.payment_status || 'Unknown' }}
            </a-tag>
        </div>

        <a-descriptions bordered :column="2" size="small">
            <a-descriptions-item label="Expense Date">
                {{ formattedDate }}
            </a-descriptions-item>

            <a-descriptions-item label="Category">
                <a-tag>
                    {{ expense.expense_category || '-' }}
                </a-tag>
            </a-descriptions-item>

            <a-descriptions-item label="Amount">
                <strong class="text-lg">
                    K {{ formattedAmount }}
                </strong>
            </a-descriptions-item>

            <a-descriptions-item label="Payment Method">
                {{ expense.payment_method || '-' }}
            </a-descriptions-item>

            <a-descriptions-item label="Vendor / Payee">
                {{ expense.vendor || '-' }}
            </a-descriptions-item>

            <a-descriptions-item label="Reference Number">
                {{ expense.reference_number || '-' }}
            </a-descriptions-item>

        </a-descriptions>

        <a-card class="mt-5" size="small" title="Description">
            <p class="text-gray-600 whitespace-pre-line">
                {{ expense.description || 'No description provided.' }}
            </p>
        </a-card>

        <a-card class="mt-4" size="small" title="Notes">
            <p class="text-gray-600 whitespace-pre-line">
                {{ expense.notes || 'No additional notes.' }}
            </p>
        </a-card>

        <div class="flex justify-end mt-6">
            <a-button @click="closeModal">
                Close
            </a-button>
        </div>
    </a-modal>

</template>


<style scoped>
:deep(.ant-descriptions-item-label) {
    font-weight: 600;
}

:deep(.ant-card) {
    border-radius: 10px;
}
</style>