<!-- resources\js\Pages\MyFarmer\Vegetables\Expenses\EditExpense.vue -->
<script setup>
import { ref, reactive, watch } from 'vue'
import axios from 'axios'
import dayjs from 'dayjs'
import { message } from 'ant-design-vue'

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
    'update:open',
    'success'
])

const loading = ref(false)

const formRef = ref()

const productions = ref([])

/*
|--------------------------------------------------------------------------
| Form
|--------------------------------------------------------------------------
*/

const form = reactive({

    vegetable_production_id: null,

    expense_date: dayjs(),

    expense_category: '',

    description: '',

    quantity: 1,

    unit_cost: 0,

    total_cost: 0,

    supplier: '',

    payment_method: 'Cash',

    status: 'Paid',

    reference_number: '',

    notes: ''

})

/*
|--------------------------------------------------------------------------
| Validation
|--------------------------------------------------------------------------
*/

const rules = {

    vegetable_production_id: [
        {
            required: true,
            message: 'Production batch is required'
        }
    ],

    expense_date: [
        {
            required: true,
            message: 'Expense date is required'
        }
    ],

    expense_category: [
        {
            required: true,
            message: 'Expense category is required'
        }
    ],

    quantity: [
        {
            required: true,
            message: 'Quantity is required'
        }
    ],

    unit_cost: [
        {
            required: true,
            message: 'Unit cost is required'
        }
    ]

}

/*
|--------------------------------------------------------------------------
| Load Production Batches
|--------------------------------------------------------------------------
*/

const loadProductions = async () => {

    try {

        const { data } = await axios.get('/vegetable-productions-all')

        productions.value = data

    }

    catch {

        message.error('Unable to load production batches.')

    }

}

/*
|--------------------------------------------------------------------------
| Calculate Total
|--------------------------------------------------------------------------
*/

watch(

    [
        () => form.quantity,
        () => form.unit_cost
    ],

    () => {

        form.total_cost =
            Number(form.quantity || 0) *
            Number(form.unit_cost || 0)

    }

)

/*
|--------------------------------------------------------------------------
| Populate Form
|--------------------------------------------------------------------------
*/

const populateForm = () => {

    if (!props.expense) return

    form.vegetable_production_id = props.expense.vegetable_production_id

    form.expense_date = dayjs(props.expense.expense_date)

    form.expense_category = props.expense.expense_category

    form.description = props.expense.description

    form.quantity = Number(props.expense.quantity)

    form.unit_cost = Number(props.expense.unit_cost)

    form.total_cost = Number(props.expense.total_cost)

    form.supplier = props.expense.supplier

    form.payment_method = props.expense.payment_method

    form.status = props.expense.status

    form.reference_number = props.expense.reference_number

    form.notes = props.expense.notes

}

/*
|--------------------------------------------------------------------------
| Save Changes
|--------------------------------------------------------------------------
*/

const updateExpense = async () => {

    try {

        await formRef.value.validate()

        loading.value = true

        await axios.put(

            `/vegetable-expenses/${props.expense.id}`,

            {

                ...form,

                expense_date: dayjs(
                    form.expense_date
                ).format('YYYY-MM-DD')

            }

        )

        message.success('Expense updated successfully.')

        emit('success')

        closeModal()

    }

    catch (error) {

        console.log(error)

        message.error('Unable to update expense.')

    }

    finally {

        loading.value = false

    }

}

/*
|--------------------------------------------------------------------------
| Reset
|--------------------------------------------------------------------------
*/

const resetForm = () => {

    form.vegetable_production_id = null

    form.expense_date = dayjs()

    form.expense_category = ''

    form.description = ''

    form.quantity = 1

    form.unit_cost = 0

    form.total_cost = 0

    form.supplier = ''

    form.payment_method = 'Cash'

    form.reference_number = ''

    form.notes = ''

}

/*
|--------------------------------------------------------------------------
| Close
|--------------------------------------------------------------------------
*/

const closeModal = () => {

    resetForm()

    emit('update:open', false)

}

/*
|--------------------------------------------------------------------------
| Watch Modal
|--------------------------------------------------------------------------
*/

watch(

    () => props.open,

    async (value) => {

        if (value) {

            await loadProductions()

            populateForm()

        }

    }

)
</script>

<template>

    <a-modal
        :open="open"
        title="Edit Vegetable Expense"
        :width="'75%'"
        :footer="null"
        :maskClosable="false"
        :confirmLoading="loading"
        @cancel="closeModal"
    >

        <!-- Summary Cards -->
        <a-card class="mb-5">

            <a-row :gutter="16">

                <a-col :span="8">

                    <a-statistic
                        title="Quantity"
                        :value="form.quantity"
                    />

                </a-col>

                <a-col :span="8">

                    <a-statistic
                        title="Unit Cost"
                        prefix="K"
                        :value="form.unit_cost"
                    />

                </a-col>

                <a-col :span="8">

                    <a-statistic
                        title="Total Expense"
                        prefix="K"
                        :value="form.total_cost"
                        :valueStyle="{ color: '#cf1322' }"
                    />

                </a-col>

            </a-row>

        </a-card>

        <a-form

            ref="formRef"
            :model="form"
            :rules="rules"
            layout="vertical"

        >

            <a-row :gutter="16">

                <!-- Production Batch -->
                <a-col :span="12">

                    <a-form-item
                        label="Production Batch"
                        name="vegetable_production_id"
                    >

                        <a-select
                            v-model:value="form.vegetable_production_id"
                            show-search
                            placeholder="Select Production Batch"
                            option-filter-prop="label"
                        >

                            <a-select-option
                                v-for="item in productions"
                                :key="item.id"
                                :value="item.id"
                                :label="`${item.batch_number} - ${item.vegetableType?.name}`"
                            >

                                {{ item.batch_number }}

                                -

                                {{ item.vegetableType?.name }}

                                -

                                {{ item.season }}

                            </a-select-option>

                        </a-select>

                    </a-form-item>

                </a-col>

                <!-- Expense Date -->
                <a-col :span="12">

                    <a-form-item
                        label="Expense Date"
                        name="expense_date"
                    >

                        <a-date-picker
                            v-model:value="form.expense_date"
                            class="w-full"
                        />

                    </a-form-item>

                </a-col>

                <!-- Category -->
                <a-col :span="12">

                    <a-form-item
                        label="Expense Category"
                        name="category"
                    >

                        <a-select
                            v-model:value="form.expense_category"
                        >

                        <a-select-option value="Seeds">Seeds</a-select-option>
                            <a-select-option value="Seedlings">Seedlings</a-select-option>
                            <a-select-option value="Fertilizer">Fertilizer</a-select-option>
                            <a-select-option value="Chemicals">Chemicals</a-select-option>
                            <a-select-option value="Pesticides">Pesticides</a-select-option>
                            <a-select-option value="Herbicides">Herbicides</a-select-option>
                            <a-select-option value="Labour">Labour</a-select-option>
                            <a-select-option value="Transport">Transport</a-select-option>
                            <a-select-option value="Fuel">Fuel</a-select-option>
                            <a-select-option value="Machinery">Machinery</a-select-option>
                            <a-select-option value="Water">Water</a-select-option>
                            <a-select-option value="Packaging">Packaging</a-select-option>
                            <a-select-option value="Equipment">Equipment</a-select-option>
                            <a-select-option value="Electricity">Electricity</a-select-option>
                            <a-select-option value="Marketing">Marketing</a-select-option>
                            <a-select-option value="Repairs">Repairs</a-select-option>
                             <a-select-option value="Maintenance">Maintenance</a-select-option>
                             <a-select-option value="Rent">Rent</a-select-option>
                            <a-select-option value="Other">Other</a-select-option>

                        </a-select>

                    </a-form-item>

                </a-col>

                <!-- Description -->
                <a-col :span="12">

                    <a-form-item
                        label="Description"
                    >

                        <a-input
                            v-model:value="form.description"
                            placeholder="Expense description"
                        />

                    </a-form-item>

                </a-col>

                <!-- Quantity -->
                <a-col :span="8">

                    <a-form-item
                        label="Quantity"
                        name="quantity"
                    >

                        <a-input-number
                            v-model:value="form.quantity"
                            class="w-full"
                            :min="1"
                        />

                    </a-form-item>

                </a-col>

                <!-- Unit Cost -->
                <a-col :span="8">

                    <a-form-item
                        label="Unit Cost"
                        name="unit_cost"
                    >

                        <a-input-number
                            v-model:value="form.unit_cost"
                            class="w-full"
                            :min="0"
                        />

                    </a-form-item>

                </a-col>

                <!-- Total -->
                <a-col :span="8">

                    <a-form-item
                        label="Total Cost"
                    >

                        <a-input-number
                            :value="form.total_cost"
                            class="w-full"
                            disabled
                        />

                    </a-form-item>

                </a-col>

                <!-- Supplier -->
                <a-col :span="12">

                    <a-form-item
                        label="Supplier"
                    >

                        <a-input
                            v-model:value="form.supplier"
                            placeholder="Supplier name"
                        />

                    </a-form-item>

                </a-col>

                <!-- Payment -->
                <a-col :span="12">

                    <a-form-item
                        label="Payment Method"
                    >

                        <a-select
                            v-model:value="form.payment_method"
                        >

                            <a-select-option value="Cash">
                                Cash
                            </a-select-option>

                            <a-select-option value="Mobile Money">
                                Mobile Money
                            </a-select-option>

                            <a-select-option value="Bank Transfer">
                                Bank Transfer
                            </a-select-option>

                            <a-select-option value="Cheque">
                                Cheque
                            </a-select-option>

                        </a-select>

                    </a-form-item>

                </a-col>

              <a-col :span="12">
                    <a-form-item label="Status" name="status">
                        <a-select v-model:value="form.status">
                            <a-select-option value="Paid">Paid</a-select-option>
                            <a-select-option value="Pending">Pending</a-select-option>
                            <a-select-option value="Partial">Partial</a-select-option>
                        </a-select>
                    </a-form-item>
                </a-col>

                <!-- Reference -->
                <a-col :span="12">

                    <a-form-item
                        label="Reference Number"
                    >

                        <a-input
                            v-model:value="form.reference_number"
                            placeholder="Receipt / Invoice Number"
                        />

                    </a-form-item>

                </a-col>

                <!-- Notes -->
                <a-col :span="24">

                    <a-form-item
                        label="Notes"
                    >

                        <a-textarea
                            v-model:value="form.notes"
                            :rows="4"
                            placeholder="Additional notes..."
                        />

                    </a-form-item>

                </a-col>

            </a-row>

            <a-divider />

            <div class="flex justify-end gap-3">

                <a-button
                    @click="closeModal"
                >

                    Cancel

                </a-button>

                <a-button
                    type="primary"
                    :loading="loading"
                    @click="updateExpense"
                >

                    Update Expense

                </a-button>

            </div>

        </a-form>

    </a-modal>

</template>