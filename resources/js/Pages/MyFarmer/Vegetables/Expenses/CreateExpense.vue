<script setup>

import { ref, reactive, watch } from 'vue'
import axios from 'axios'
import dayjs from 'dayjs'
import { message } from 'ant-design-vue'

/*
|--------------------------------------------------------------------------
| Props
|--------------------------------------------------------------------------
*/

const props = defineProps({

    open: {
        type: Boolean,
        default: false
    }

})

/*
|--------------------------------------------------------------------------
| Emits
|--------------------------------------------------------------------------
*/

const emit = defineEmits([

    'update:open',
    'success'

])

/*
|--------------------------------------------------------------------------
| Reactive Variables
|--------------------------------------------------------------------------
*/

const loading = ref(false)

const formRef = ref()

const productions = ref([])

const selectedProduction = ref(null)

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

    unit: 'Kg',

    unit_cost: 0,

    total_cost: 0,

    supplier: '',

    invoice_number: '',

    receipt_number: '',

    paid_by: '',

    payment_method: 'Cash',

     status: 'Paid',

    reference_number: '',

    notes: ''

})

/*
|--------------------------------------------------------------------------
| Validation Rules
|--------------------------------------------------------------------------
*/

const rules = {

    vegetable_production_id: [

        {
            required: true,
            message: 'Production batch is required.'
        }

    ],

    expense_date: [

        {
            required: true,
            message: 'Expense date is required.'
        }

    ],

    expense_category: [

        {
            required: true,
            message: 'Expense category is required.'
        }

    ],

    description: [

        {
            required: true,
            message: 'Description is required.'
        }

    ],

    quantity: [

        {
            required: true,
            message: 'Quantity is required.'
        }

    ],

    unit_cost: [

        {
            required: true,
            message: 'Unit cost is required.'
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

        const { data } = await axios.get(

            '/vegetable-productions-all'

        )

        productions.value = data

    }

    catch (error) {

        console.log(error)

        message.error(

            'Unable to load production batches.'

        )

    }

}

/*
|--------------------------------------------------------------------------
| Selected Production
|--------------------------------------------------------------------------
*/

watch(

    () => form.vegetable_production_id,

    (id) => {

        selectedProduction.value =

            productions.value.find(

                item => item.id === id

            ) || null

    }

)

/*
|--------------------------------------------------------------------------
| Auto Calculate Total
|--------------------------------------------------------------------------
*/

watch(

    [

        () => form.quantity,

        () => form.unit_cost

    ],

    () => {

        form.total_cost =

            Number(form.quantity || 0)

            *

            Number(form.unit_cost || 0)

    }

)

/*
|--------------------------------------------------------------------------
| Save Expense
|--------------------------------------------------------------------------
*/

const saveExpense = async () => {

    try {

        await formRef.value.validate()

        loading.value = true

        await axios.post(

            '/vegetable-expenses',

            {

                ...form,

                expense_date: dayjs(

                    form.expense_date

                ).format(

                    'YYYY-MM-DD'

                )

            }

        )

        message.success(

            'Expense recorded successfully.'

        )

        emit(

            'success'

        )

        closeModal()

    }

    catch (error) {

        console.log(error)

        message.error(

            error.response?.data?.message

            ||

            'Unable to save expense.'

        )

    }

    finally {

        loading.value = false

    }

}

/*
|--------------------------------------------------------------------------
| Reset Form
|--------------------------------------------------------------------------
*/

const resetForm = () => {

    form.vegetable_production_id = null

    form.expense_date = dayjs()

    form.expense_category = ''

    form.description = ''

    form.quantity = 1

    form.unit = 'Kg'

    form.unit_cost = 0

    form.total_cost = 0

    form.supplier = ''

    form.invoice_number = ''

    form.receipt_number = ''

    form.paid_by = ''

    form.payment_method = 'Cash'

    form.reference_number = ''

    form.notes = ''

    selectedProduction.value = null

}

/*
|--------------------------------------------------------------------------
| Close Modal
|--------------------------------------------------------------------------
*/

const closeModal = () => {

    resetForm()

    emit(

        'update:open',

        false

    )

}

/*
|--------------------------------------------------------------------------
| Watch Modal
|--------------------------------------------------------------------------
*/

watch(

    () => props.open,

    (value) => {

        if (value) {

            loadProductions()

        }

    }

)

</script>

<template>

    <a-modal
        :open="open"
        title="Record Vegetable Expense"
        :width="'75%'"
        :footer="null"
        :maskClosable="false"
        :confirmLoading="loading"
        @cancel="closeModal"
    >

        <!-- ==========================================
             Expense Summary
        =========================================== -->

        <a-card class="mb-5">

            <a-row :gutter="16">

                <a-col :span="8">

                    <a-statistic
                        title="Quantity"
                        :value="form.quantity"
                        :suffix="form.unit"
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
                        :valueStyle="{ color:'#cf1322' }"
                    />

                </a-col>

            </a-row>

        </a-card>

        <!-- ==========================================
             Production Information
        =========================================== -->

        <a-card
            v-if="selectedProduction"
            title="Production Information"
            class="mb-5"
        >

            <a-row :gutter="16">

                <a-col :span="8">

                    <strong>Crop</strong>

                    <br>

                    {{ selectedProduction.vegetable_type?.name }}

                </a-col>

                <a-col :span="8">

                    <strong>Batch Number</strong>

                    <br>

                    {{ selectedProduction.batch_number }}

                </a-col>

                <a-col :span="8">

                    <strong>Season</strong>

                    <br>

                    {{ selectedProduction.season }}

                </a-col>

                <a-col :span="8" class="mt-4">

                    <strong>Quarter</strong>

                    <br>

                    {{ selectedProduction.quarter }}

                </a-col>

                <a-col :span="8" class="mt-4">

                    <strong>Land Size</strong>

                    <br>

                    {{ selectedProduction.land_size }}
                    {{ selectedProduction.land_unit }}

                </a-col>

                <a-col :span="8" class="mt-4">

                    <strong>Expected Yield</strong>

                    <br>

                    {{ selectedProduction.expected_yield }}

                </a-col>

            </a-row>

        </a-card>

        <!-- ==========================================
             Expense Form
        =========================================== -->

        <a-form
            ref="formRef"
            :model="form"
            :rules="rules"
            layout="vertical"
        >

            <a-row :gutter="16">

                <!-- Production -->

                <a-col :span="12">

                    <a-form-item
                        label="Production Batch"
                        name="vegetable_production_id"
                    >

                        <a-select
                            v-model:value="form.vegetable_production_id"
                            show-search
                            placeholder="Select Production Batch"
                        >

                            <a-select-option
                                v-for="item in productions"
                                :key="item.id"
                                :value="item.id"
                            >

                                {{ item.batch_number }}

                                -

                                {{ item.vegetable_type?.name }}

                            </a-select-option>

                        </a-select>

                    </a-form-item>

                </a-col>

                <!-- Date -->

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
                        name="description"
                    >

                        <a-input
                            v-model:value="form.description"
                        />

                    </a-form-item>

                </a-col>

                <!-- Quantity -->

                <a-col :span="12">

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

                <!-- Unit -->

                <a-col :span="12">

                    <a-form-item
                        label="Unit"
                    >

                        <a-input
                            v-model:value="form.unit"
                        />

                    </a-form-item>

                </a-col>

                <!-- Unit Cost -->

                <a-col :span="12">

                    <a-form-item
                        label="Unit Cost"
                        name="unit_cost"
                    >

                        <a-input-number
                            v-model:value="form.unit_cost"
                            class="w-full"
                        />

                    </a-form-item>

                </a-col>

                <!-- Total -->

                <a-col :span="12">

                    <a-form-item
                        label="Total Cost"
                    >

                        <a-input-number
                            :value="form.total_cost"
                            disabled
                            class="w-full"
                        />

                    </a-form-item>

                </a-col>

                <!-- Supplier -->

                <a-col :span="12">

                    <a-form-item label="Supplier">

                        <a-input
                            v-model:value="form.supplier"
                        />

                    </a-form-item>

                </a-col>

                <!-- Paid By -->

                <a-col :span="12">

                    <a-form-item label="Paid By">

                        <a-input
                            v-model:value="form.paid_by"
                        />

                    </a-form-item>

                </a-col>

                <!-- Invoice -->

                <a-col :span="12">

                    <a-form-item label="Invoice Number">

                        <a-input
                            v-model:value="form.invoice_number"
                        />

                    </a-form-item>

                </a-col>

                <!-- Receipt -->

                <a-col :span="12">

                    <a-form-item label="Receipt Number">

                        <a-input
                            v-model:value="form.receipt_number"
                        />

                    </a-form-item>

                </a-col>

                <!-- Payment -->

                <a-col :span="12">

                    <a-form-item label="Payment Method">

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

                    <a-form-item label="Reference Number">

                        <a-input
                            v-model:value="form.reference_number"
                        />

                    </a-form-item>

                </a-col>

                <!-- Notes -->

                <a-col :span="24">

                    <a-form-item label="Notes">

                        <a-textarea
                            v-model:value="form.notes"
                            :rows="4"
                        />

                    </a-form-item>

                </a-col>

            </a-row>

            <a-divider />

            <div class="flex justify-end gap-3">

                <a-button @click="closeModal">

                    Cancel

                </a-button>

                <a-button
                    type="primary"
                    :loading="loading"
                    @click="saveExpense"
                >

                    Save Expense

                </a-button>

            </div>

        </a-form>

    </a-modal>

</template>