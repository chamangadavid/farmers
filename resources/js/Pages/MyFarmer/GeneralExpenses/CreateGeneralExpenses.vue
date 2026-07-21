<script setup>
import { reactive, ref, watch } from 'vue'
import axios from 'axios'
import { message } from 'ant-design-vue'
import { PlusOutlined } from '@ant-design/icons-vue'


const props = defineProps({

    open: {
        type: Boolean,
        default: false

    }

})


const emit = defineEmits([

    'update:open',
    'success'

])


const loading = ref(false)
const formRef = ref(null)


const form = reactive({

    expense_date: null,
    expense_category: undefined,
    expense_name: '',
    description: '',
    amount: null,
    payment_method: undefined,
    payment_status: 'Paid',
    reference_number: '',
    vendor: '',
    notes: ''

})

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

const paymentMethods = [

    'Cash',
    'Credit',
    'Bank Transfer',
    'Mobile Money',
    'Card',
    'Cheque'

]

const paymentStatuses = [

    'Paid',
    'Partial',
    'Pending',
    'Cancelled'

]


const rules = {

    expense_date: [
        {
            required: true,
            message: 'Please select the expense date'
        }
    ],

    expense_category: [
        {
            required: true,
            message: 'Please select an expense category'
        }
    ],

    expense_name: [
        {
            required: true,
            message: 'Please enter the expense name'
        },

        {
            min: 2,
            message: 'Expense name must be at least 2 characters'
        }

    ],

    amount: [
        {
            required: true,
            message: 'Please enter the expense amount'
        }
    ],

    payment_status: [
        {
            required: true,
            message: 'Please select payment status'
        }
    ]
}

const resetForm = () => {

    form.expense_date = null
    form.expense_category = undefined
    form.expense_name = ''
    form.description = ''
    form.amount = null
    form.payment_method = undefined
    form.payment_status = 'Paid'
    form.reference_number = ''
    form.vendor = ''
    form.notes = ''

    formRef.value?.clearValidate()


}

const closeModal = () => {
    emit('update:open', false)
}


const submit = async () => {
    try {
        await formRef.value.validate()
        loading.value = true
        const payload = {
            expense_date: form.expense_date,
            expense_category: form.expense_category,
            expense_name: form.expense_name,
            description: form.description || null,
            amount: Number(form.amount),
            payment_method: form.payment_method || null,
            payment_status: form.payment_status,
            reference_number: form.reference_number || null,
            vendor: form.vendor || null,
            notes: form.notes || null

        }

        await axios.post('/general-expenses', payload)
        message.success('General expense created successfully.')

        emit('success')
        closeModal()
        resetForm()

    }

    catch (error) {
        console.error(error)
        if (error?.errorFields) {
            return

        }


        if (error.response?.status === 422) {
            const errors = error.response.data.errors
            Object.values(errors).flat()
                .forEach(errorMessage => {
                    message.error(
                        errorMessage
                    )
                })
        }

        else {
            message.error(
                'Failed to create general expense.'
            )
        }
    }

    finally {
        loading.value = false
    }

}

//reset when modal opens
watch(
    () => props.open,
    (value) => {
        if (value) {
            resetForm()
        }
    }
)


</script>

<template>
    <a-modal :open="open" title="Add General Expense" :width="700" :confirm-loading="loading" ok-text="Save Expense"
        cancel-text="Cancel" @ok="submit" @cancel="closeModal">

        <a-form ref="formRef" :model="form" :rules="rules" layout="vertical">

            <a-row :gutter="16">
                <a-col :xs="24" :md="12">
                    <a-form-item label="Expense Date" name="expense_date">
                        <a-date-picker v-model:value="form.expense_date" style="width: 100%" format="YYYY-MM-DD"
                            value-format="YYYY-MM-DD" />
                    </a-form-item>
                </a-col>

                <a-col :xs="24" :md="12">
                    <a-form-item label="Expense Category" name="expense_category">
                        <a-select v-model:value="form.expense_category" placeholder=" Select category"
                            style="width: 100%" allow-clear>
                            <a-select-option v-for="category in categories" :key="category" :value="category">
                                {{ category }}
                            </a-select-option>
                        </a-select>
                    </a-form-item>
                </a-col>
            </a-row>

            <a-form-item label="Expense Name" name="expense_name">
                <a-input v-model:value="form.expense_name" placeholder="e.g. Toyota Hilux Full Service" maxlength="255"
                    show-count />
            </a-form-item>

            <a-form-item label="Description" name="description">
                <a-textarea v-model:value="form.description" placeholder="Describe the expense..." :rows="3" show-count
                    :maxlength="1000" />
            </a-form-item>

            <a-row :gutter="16">
                <a-col :xs="24" :md="8">
                    <a-form-item label="Amount (K)" name="amount">
                        <a-input-number v-model:value="form.amount" :min="0" :precision="2" style="width: 100%"
                            placeholder="0.00" />
                    </a-form-item>
                </a-col>

                <a-col :xs="24" :md="8">
                    <a-form-item label="Payment Method" name="payment_method">
                        <a-select v-model:value="form.payment_method" placeholder=" Select method" style="width: 100%"
                            allow-clear>
                            <a-select-option v-for="method in paymentMethods" :key="method" :value="method">
                                {{ method }}
                            </a-select-option>
                        </a-select>
                    </a-form-item>
                </a-col>

                <a-col :xs="24" :md="8">
                    <a-form-item label="Payment Status" name="payment_status">
                        <a-select v-model:value="form.payment_status" style="width: 100%">
                            <a-select-option v-for="status in paymentStatuses" :key="status" :value="status">
                                {{ status }}
                            </a-select-option>
                        </a-select>
                    </a-form-item>
                </a-col>
            </a-row>

            <a-row :gutter="16">
                <a-col :xs="24" :md="12">
                    <a-form-item label="Reference Number" name="reference_number">
                        <a-input v-model:value="form.reference_number" placeholder="e.g. INV-001" />
                    </a-form-item>
                </a-col>

                <a-col :xs="24" :md="12">
                    <a-form-item label="Vendor / Payee" name="vendor">
                        <a-input v-model:value="form.vendor" placeholder="e.g. ABC Motors" />
                    </a-form-item>
                </a-col>
            </a-row>

            <a-form-item label="Notes" name="notes">
                <a-textarea v-model:value="form.notes" placeholder="Additional notes..." :rows="3" show-count
                    :maxlength="1000" />
            </a-form-item>
        </a-form>
    </a-modal>
</template>