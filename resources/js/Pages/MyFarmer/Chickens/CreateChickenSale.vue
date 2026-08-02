<!-- resources\js\Pages\MyFarmer\Chickens\CreateChickenSale.vue -->
<script setup>
import { ref, computed, watch } from 'vue'
import axios from 'axios'
import dayjs from 'dayjs'
import { message } from 'ant-design-vue'

const props = defineProps({
    open: Boolean,
    batch: Object

})


const emit = defineEmits([

    'update:open',

    'created'

])

const loading = ref(false)

const form = ref({

    chicken_batch_id: null,
    sale_type: 'Per Bird',
    sale_date: '',
    quantity: 1,
    total_weight: null,
    unit_price: 0,

    price_per_kg: 0,
    payment_method: 'Cash',
    initial_payment: null,

    customer_name: '',
    customer_phone: '',
    notes: ''

})

const isCredit = computed(() => {
    return form.value.payment_method === 'Credit'

})

// watch(() => props.batch, (batch) => {

//     if (batch) {
//         form.value.chicken_batch_id = batch.id

//     }

// })

watch(
    () => props.batch,

    (batch) => {

        if (batch?.id) {

            form.value.chicken_batch_id = batch.id

        }

    },

    {
        immediate: true
    }

)

//Calculate total automatically
// const totalAmount = computed(() => {
//     return form.value.quantity * form.value.unit_price

// })

const totalAmount = computed(() => {

    if (form.value.sale_type === 'Per Kg') {

        return (

            Number(form.value.total_weight || 0) *

            Number(form.value.price_per_kg || 0)

        )

    }


    return (

        Number(form.value.quantity || 0) *

        Number(form.value.unit_price || 0)

    )

})


const isPerKg = computed(() => {
    return form.value.sale_type === 'Per Kg'

})

//close modal
const closeModal = () => {
    emit('update:open', false)

}


//reset form
const resetForm = () => {

    form.value = {

        chicken_batch_id: props.batch?.id ?? null,

        sale_type: 'Per Bird',

        sale_date: '',

        quantity: 1,

        total_weight: null,

        unit_price: 0,

        price_per_kg: 0,

        payment_method: 'Cash',

        initial_payment: null,

        customer_name: '',

        customer_phone: '',

        notes: ''

    }

}

// const resetForm = () => {

//     form.value = {

//         chicken_batch_id: null,
//         sale_date: '',
//         quantity: 1,
//         unit_price: 0,
//         payment_method: 'Cash',
//         initial_payment: null,
//         customer_name: '',
//         customer_phone: '',
//         notes: ''

//     }


// }


//save sales
const submit = async () => {

    loading.value = true

    try {

        await axios.post('/chicken-sales', {

            // Always use the selected batch ID
            chicken_batch_id: props.batch?.id,

            sale_date: form.value.sale_date,

            sale_type: form.value.sale_type,

            quantity: form.value.quantity,

            total_weight:

                form.value.sale_type === 'Per Kg'

                    ? form.value.total_weight

                    : null,

            price_per_kg:

                form.value.sale_type === 'Per Kg'

                    ? form.value.price_per_kg

                    : null,

            unit_price:

                form.value.sale_type === 'Per Bird'

                    ? form.value.unit_price

                    : null,

            payment_method: form.value.payment_method,

            initial_payment: form.value.initial_payment,

            customer_name: form.value.customer_name,

            customer_phone: form.value.customer_phone,

            notes: form.value.notes

        })

        message.success(
            'Chicken sale recorded successfully.'
        )

        emit('created')

        closeModal()

        resetForm()

    }

    catch (error) {

        console.log(
            error.response?.data
        )

        message.error(

            error.response?.data?.message ??

            'Unable to save sale.'

        )

    }

    finally {

        loading.value = false

    }

}

</script>



<template>

    <a-modal :open="open" width="1300px" :footer="null" :maskClosable="false" @cancel="closeModal">

        <form @submit.prevent="submit">

            <!-- Header -->
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-800">
                    Chicken Sell Batch Information
                </h2>

                <p class="text-gray-500 mt-1">
                    Capture information about a new sale of batch of broiler chickens.
                </p>
            </div>

            <!-- Batch Details -->

            <a-card title="Sold Details" class="mb-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label>Batch Number</label>
                        <a-input :value="batch?.batch_number" readonly 
                        style="border: 1px solid #e9e9e9; border-radius: 8px;"/>
                    </div>

                    <div>

                        <label>Sale Date</label>
                        <a-date-picker v-model:value="form.sale_date" value-format="YYYY-MM-DD" class="w-full" 
                        style="border: 1px solid #e9e9e9; border-radius: 8px;"/>
                    </div>

                    <div>
                        <label>Birds Remaining</label>
                        <a-input :value="batch?.birds_remaining" readonly 
                        style="border: 1px solid #e9e9e9; border-radius: 8px;"/>
                    </div>

                    <!-- <div>
                        <label>Quantity Sold</label>
                        <a-input-number v-model:value="form.quantity" :min="1" :max="batch?.birds_remaining"
                            class="w-full" />
                    </div>

                    <div>
                        <label>Price Per Bird</label>
                        <a-input-number v-model:value="form.unit_price" :min="0" class="w-full" />
                    </div>

                    <div>
                        <label>Total Amount</label>
                        <a-input :value="Number(totalAmount).toLocaleString()" readonly 
                        style="border: 1px solid #e9e9e9; border-radius: 8px;"/>
                    </div> -->

                    <!-- Sale Type -->

<div>

    <label>Sale Type</label>

    <a-select
        v-model:value="form.sale_type"
        class="w-full">

        <a-select-option value="Per Bird">

            Per Bird

        </a-select-option>

        <a-select-option value="Per Kg">

            Per Kg

        </a-select-option>

    </a-select>

</div>


<!-- Number of Chickens -->

<div>

    <label>Number of Chickens Sold</label>

    <a-input-number
        v-model:value="form.quantity"
        :min="1"
        :max="batch?.birds_remaining"
        class="w-full" />

</div>


<!-- Per Bird -->

<div v-if="!isPerKg">

    <label>Price Per Bird</label>

    <a-input-number
        v-model:value="form.unit_price"
        :min="0"
        class="w-full" />

</div>


<!-- Per Kg -->

<div v-if="isPerKg">

    <label>Total Weight (Kg)</label>

    <a-input-number
        v-model:value="form.total_weight"
        :min="0.01"
        :precision="2"
        class="w-full"
        placeholder="e.g. 25.50" />

</div>


<div v-if="isPerKg">

    <label>Price Per Kg</label>

    <a-input-number
        v-model:value="form.price_per_kg"
        :min="0"
        :precision="2"
        class="w-full" />

</div>


<!-- Total -->

<div>

    <label>Total Amount</label>

    <a-input
        :value="`K ${Number(totalAmount).toLocaleString(
            undefined,
            {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }
        )}`"
        readonly
        style="border: 1px solid #e9e9e9; border-radius: 8px;" />

</div>
                    <div>
                        <label>Payment Method</label>

                        <a-select v-model:value="form.payment_method" class="w-full"
                            placeholder="Select payment method">

                            <a-select-option value="Cash"> Cash </a-select-option>
                            <a-select-option value="Credit"> Credit </a-select-option>
                            <a-select-option value="Card"> Card </a-select-option>
                            <a-select-option value="Cheque"> Cheque </a-select-option>
                            <a-select-option value="Mobile Money"> Mobile Money </a-select-option>
                            <a-select-option value="Bank Transfer"> Bank Transfer </a-select-option>

                        </a-select>
                    </div>

                  <div v-if="isCredit">
                        <label> Down Payment <span class="text-gray-400"> (Optional) </span></label>

                        <a-input-number v-model:value="form.initial_payment" :min="0" :max="totalAmount" class="w-full"
                            placeholder="Enter amount paid now" />

                        <small class="text-gray-500">
                            Leave empty or enter 0 if the customer will pay later.
                        </small>

                    </div>

                    <div>
                        <label>Customer Name</label>
                        <a-input v-model:value="form.customer_name" placeholder="Enter customer name.."
                        style="border: 1px solid #e9e9e9; border-radius: 8px;"/>
                    </div>

                    <div>
                        <label>Phone Number</label>
                        <a-input v-model:value="form.customer_phone" placeholder="Enter mobile number"
                        style="border: 1px solid #e9e9e9; border-radius: 8px;"/>
                    </div>

                    <div class="md:col-span-2">
                        <label>Notes</label>
                        <a-textarea v-model:value="form.notes" :rows="4" placeholder="Enter notes..."/>
                    </div>
                </div>
            </a-card>


            <!-- Footer Buttons -->
            <div class="flex justify-end gap-4 mt-8">
                <a-button size="large" @click="closeModal">
                    Cancel
                </a-button>

                <a-button type="primary" size="large" :loading="loading" @click="submit">
                    Save Chicken Batch
                </a-button>
            </div>
        </form>
    </a-modal>

</template>

<style scoped>
:deep(.ant-modal-header) {

    border-bottom: 1px solid #f0f0f0;

}

:deep(.ant-modal-title) {

    font-size: 24px;

    font-weight: 700;

}

:deep(.ant-card) {

    border-radius: 12px;

    margin-bottom: 20px;

}

:deep(.ant-card-head) {

    background: #fafafa;

    font-weight: 600;

}

:deep(.ant-input),

:deep(.ant-input-number),

:deep(.ant-select-selector),

:deep(.ant-picker) {

    border-radius: 8px !important;

}

:deep(.ant-btn-primary) {

    background: #16a34a;

    border-color: #16a34a;

}

:deep(.ant-btn-primary:hover) {

    background: #15803d !important;

    border-color: #15803d !important;

}

label {

    display: block;

    margin-bottom: 6px;

    font-weight: 600;

    color: #374151;

}
</style>
