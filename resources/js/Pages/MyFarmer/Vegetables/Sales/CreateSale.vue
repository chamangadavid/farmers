<!-- resources\js\Pages\MyFarmer\Vegetables\Sales\CreateSale.vue -->
<script setup>

import { ref, reactive, watch } from 'vue'
import axios from 'axios'
import dayjs from 'dayjs'
import { message } from 'ant-design-vue'

const props = defineProps({

    open: {

        type: Boolean,

        default: false

    }

})

// const props = defineProps({

//     open:Boolean

// })

const emit = defineEmits([

    'update:open',
    'success'

])

const loading = ref(false)

const formRef = ref()

const harvests = ref([])

const selectedHarvest = ref(null)

const availableQuantity = ref(0)

/*
|--------------------------------------------------------------------------
| Form
|--------------------------------------------------------------------------
*/

const form = reactive({

    vegetable_harvest_id: null,

    sale_date: dayjs(),

    customer_name: '',

    customer_phone: '',

    customer_email: '',

    quantity: 0,

    unit_price: 0,

    total_amount: 0,

    payment_method: 'Cash',

    payment_status: 'Paid',

    invoice_number: '',

    receipt_number: '',

    notes: ''

})


/*
|--------------------------------------------------------------------------
| Validation
|--------------------------------------------------------------------------
*/

const rules = {

    vegetable_harvest_id: [

        {

            required: true,
            message: 'Harvest is required'

        }

    ],

    sale_date: [

        {

            required: true,
            message: 'Sale date is required'

        }

    ],

    customer_name: [

        {

            required: true,
            message: 'Customer is required'

        }

    ],

    quantity: [

        {

            required: true,
            message: 'Quantity is required'

        }

    ],

    unit_price: [

        {

            required: true,
            message: 'Unit price is required'

        }

    ]

}

/*
|--------------------------------------------------------------------------
| Load Harvests
|--------------------------------------------------------------------------
*/

const loadHarvests = async () => {

    try {

        const { data } = await axios.get(

            '/all-vegetable-sales/harvests'

        )

        harvests.value = data

    }

    catch {

        message.error(

            'Unable to load harvests.'

        )

    }

}

/*
|--------------------------------------------------------------------------
| Harvest Selected
|--------------------------------------------------------------------------
*/

watch(

    () => form.vegetable_harvest_id,

    (id) => {

        const harvest =

            harvests.value.find(

                x => x.id === id

            )

        selectedHarvest.value = harvest

        if (!harvest) {

            return

        }

        availableQuantity.value =

            Number(harvest.quantity)

        form.unit_price =

            Number(harvest.unit_price)

    }

)

/*
|--------------------------------------------------------------------------
| Calculate Total
|--------------------------------------------------------------------------
*/

watch(

    [

        () => form.quantity,

        () => form.unit_price

    ],

    () => {

        form.total_amount =

            Number(form.quantity || 0)

            *

            Number(form.unit_price || 0)

    }

)

/*
|--------------------------------------------------------------------------
| Quantity Validation
|--------------------------------------------------------------------------
*/

watch(

    () => form.quantity,

    (value) => {

        if (

            Number(value)

            >

            availableQuantity.value

        ) {

            message.warning(

                'Quantity exceeds harvested quantity.'

            )

        }

    }

)

/*
|--------------------------------------------------------------------------
| Save
|--------------------------------------------------------------------------
*/

const saveSale = async () => {

    try {

        await formRef.value.validate()

        loading.value = true

        await axios.post(

            '/vegetable-sales',

            {

                ...form,

                sale_date: dayjs(

                    form.sale_date

                ).format(

                    'YYYY-MM-DD'

                )

            }

        )

        message.success(

            'Sale recorded successfully.'

        )



        emit(

            'success'

        )

        closeModal()

    }

    catch (error) {

        console.log(error)

        message.error(

            'Unable to save sale.'

        )

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

    form.vegetable_harvest_id = null

    form.sale_date = dayjs()

    form.customer_name = ''

    form.customer_phone = ''

    form.customer_email = ''

    form.quantity = 0

    form.unit_price = 0

    form.total_amount = 0

    form.payment_method = 'Cash'

    form.payment_status = 'Paid'

    form.invoice_number = ''

    form.receipt_number = ''

    form.notes = ''

    availableQuantity.value = 0

}

/*
|--------------------------------------------------------------------------
| Close
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
| Watch
|--------------------------------------------------------------------------
*/

watch(

    () => props.open,

    (value) => {

        if (value) {

            loadHarvests()

        }

    }

)

</script>

<template>

    <a-modal :open="open" title="Record Vegetable Sale" :width="'70%'" :footer="null" :maskClosable="false"
        :confirmLoading="loading" @cancel="closeModal">

        <a-card class="mb-5">

            <a-row :gutter="16">

                <a-col :span="8">

                    <a-statistic title="Available Quantity" :value="availableQuantity" suffix="Kg" />

                </a-col>

                <a-col :span="8">

                    <a-statistic title="Unit Price" prefix="K" :value="form.unit_price" />

                </a-col>

                <a-col :span="8">

                    <a-statistic title="Sale Total" prefix="K" :value="form.total_amount"
                        :valueStyle="{ color: '#52c41a' }" />

                </a-col>

            </a-row>

        </a-card>

        <a-form ref="formRef" :model="form" :rules="rules" layout="vertical">

            <a-row :gutter="16">

                <a-col :span="12">

                    <a-form-item label="Harvest" name="vegetable_harvest_id">

                        <a-select v-model:value="form.vegetable_harvest_id" show-search placeholder="Select Harvest">

                            <a-select-option v-for="item in harvests" :key="item.id" :value="item.id">

                                {{ item.production?.vegetableType?.name }}

                                -

                                {{ item.production?.batch_number }}

                                -

                                {{ item.quantity }} Kg

                            </a-select-option>

                        </a-select>

                    </a-form-item>

                </a-col>

                <a-col :span="12">

                    <a-form-item label="Sale Date" name="sale_date">

                        <a-date-picker v-model:value="form.sale_date" class="w-full" />

                    </a-form-item>

                </a-col>

                <a-col :span="12">

                    <a-form-item label="Customer">

                        <a-input v-model:value="form.customer_name" />

                    </a-form-item>

                </a-col>

                <a-col :span="12">

                    <a-form-item label="Phone">

                        <a-input v-model:value="form.customer_phone" />

                    </a-form-item>

                </a-col>

                <a-col :span="12">

                    <a-form-item label="Email">

                        <a-input v-model:value="form.customer_email" />

                    </a-form-item>

                </a-col>

                <a-col :span="12">

                    <a-form-item label="Quantity" name="quantity">

                        <a-input-number v-model:value="form.quantity" class="w-full" :min="0" />

                    </a-form-item>

                </a-col>

                <a-col :span="12">

                    <a-form-item label="Unit Price" name="unit_price">

                        <a-input-number v-model:value="form.unit_price" class="w-full" />

                    </a-form-item>

                </a-col>

                <a-col :span="12">

                    <a-form-item label="Total Amount">

                        <a-input-number :value="form.total_amount" disabled class="w-full" />

                    </a-form-item>

                </a-col>

                <a-col :span="12">

                    <a-form-item label="Payment Method">

                        <a-select v-model:value="form.payment_method">

                            <a-select-option value="Cash">Cash</a-select-option>

                            <a-select-option value="Mobile Money">Mobile Money</a-select-option>

                            <a-select-option value="Bank Transfer">Bank Transfer</a-select-option>

                            <a-select-option value="Cheque">Cheque</a-select-option>

                        </a-select>

                    </a-form-item>

                </a-col>

                <a-col :span="12">

                    <a-form-item label="Payment Status">

                        <a-select v-model:value="form.payment_status">

                            <a-select-option value="Paid">Paid</a-select-option>

                            <a-select-option value="Pending">Pending</a-select-option>

                            <a-select-option value="Partial">Partial</a-select-option>

                        </a-select>

                    </a-form-item>

                </a-col>

                <a-col :span="12">

                    <a-form-item label="Invoice No">

                        <a-input v-model:value="form.invoice_number" />

                    </a-form-item>

                </a-col>

                <a-col :span="12">

                    <a-form-item label="Receipt No">

                        <a-input v-model:value="form.receipt_number" />

                    </a-form-item>

                </a-col>

                <a-col :span="24">

                    <a-form-item label="Notes">

                        <a-textarea v-model:value="form.notes" :rows="4" />

                    </a-form-item>

                </a-col>

            </a-row>

            <a-divider />

            <div class="flex justify-end gap-3">

                <a-button @click="closeModal">

                    Cancel

                </a-button>

                <a-button type="primary" :loading="loading" @click="saveSale">

                    Save Sale

                </a-button>

            </div>

        </a-form>

    </a-modal>

</template>