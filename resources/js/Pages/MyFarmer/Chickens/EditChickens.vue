<!-- resources\js\Pages\MyFarmer\Chickens\EditChickens.vue -->
<script setup>
import { ref, computed, watch } from 'vue'
import axios from 'axios'
import dayjs from 'dayjs'
import { message } from 'ant-design-vue'


const props = defineProps({
    open: Boolean,
    chicken: Object
})


const emit = defineEmits([
    'update:open',
    'updated'
])
const expenses = ref([])
const loading = ref(false)

const form = ref({

    arrival_date: '',
    estimated_sale_date: '',
    batch_number: '',
    batch_name: '',
    batch_size: '',
    mortality: 0,
    birds_sold: 0,
    birds_remaining: 0,
    breed: '',
    supplier: '',
    purchase_price: '',
    status: 'Growing',
    notes: ''

})



watch(
    () => props.chicken,
    (value) => {

        if (!value) return

        form.value = {
            id: value.id,
            arrival_date: value.arrival_date,
            estimated_sale_date: value.estimated_sale_date,
            batch_number: value.batch_number,
            batch_name: value.batch_name,
            batch_size: value.batch_size,
            mortality: value.mortality,
            birds_sold: value.birds_sold,
            birds_remaining: value.birds_remaining,
            breed: value.breed,
            supplier: value.supplier,
            purchase_price: value.purchase_price,
            status: value.status,
            notes: value.notes
        }

        expenses.value = value.expenses
            ? value.expenses.map(expense => ({
                id: expense.id,
                expense_date: expense.expense_date,
                item: expense.item,
                quantity: expense.quantity,
                unit_price: expense.unit_price,
                amount: expense.amount
            }))
            : []

    },
    {
        immediate: true
    }
)

// watch(
//     () => props.chicken,
//     (value) => {

//         if (!value) return

//         form.value = {
//             id: value.id,
//             arrival_date: value.arrival_date,
//             estimated_sale_date: value.estimated_sale_date,
//             batch_number: value.batch_number,
//             batch_name: value.batch_name,
//             batch_size: value.batch_size,
//             mortality: value.mortality,
//             birds_sold: value.birds_sold,
//             birds_remaining: value.birds_remaining,
//             breed: value.breed,
//             supplier: value.supplier,
//             purchase_price: value.purchase_price,
//             status: value.status,
//             notes: value.notes
//         }

//         expenses.value = value.expenses
//             ? value.expenses.map(expense => ({
//                 id: expense.id,
//                 expense_date: expense.expense_date,
//                 item: expense.item,
//                 quantity: expense.quantity,
//                 unit_price: expense.unit_price,
//                 amount: expense.amount
//             }))
//             : []

//     },
//     {
//         immediate: true
//     }
// )


/*
|--------------------------------------------------------------------------
| Automatically Calculate Sale Date (6 Weeks)
|--------------------------------------------------------------------------
*/

// watch(

//     () => form.value.arrival_date,

//     (value) => {

//         if (!value) return

//         form.value.estimated_sale_date = dayjs(value)

//             .add(42, 'day')

//             .format('YYYY-MM-DD')

//     }

// )

/*
|--------------------------------------------------------------------------
| Remaining Birds
|--------------------------------------------------------------------------
*/

// watch(

//     [

//         () => form.value.batch_size,

//         () => form.value.mortality,

//         () => form.value.birds_sold

//     ],

//     () => {

//         const batch = Number(form.value.batch_size || 0)

//         const mortality = Number(form.value.mortality || 0)

//         const sold = Number(form.value.birds_sold || 0)

//         form.value.birds_remaining =

//             batch - mortality - sold

//     }

// )

/*
|--------------------------------------------------------------------------
| Add Expense
|--------------------------------------------------------------------------
*/

const addExpense = () => {

    expenses.value.push({

        expense_date: '',

        item: '',

        quantity: 1,

        unit_price: 0,

        amount: 0

    })

}

/*
|--------------------------------------------------------------------------
| Remove Expense
|--------------------------------------------------------------------------
*/

const removeExpense = (index) => {

    expenses.value.splice(index, 1)

}

/*
|--------------------------------------------------------------------------
| Calculate Expense Amount
|--------------------------------------------------------------------------
*/

const calculateAmount = (expense) => {

    expense.amount =

        Number(expense.quantity || 0)

        *

        Number(expense.unit_price || 0)

}

/*
|--------------------------------------------------------------------------
| Total Expenses
|--------------------------------------------------------------------------
*/

const totalExpenses = computed(() => {

    return expenses.value.reduce(

        (sum, item) =>

            sum + Number(item.amount || 0),

        0

    )

})

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
| Reset Form
|--------------------------------------------------------------------------
*/

const resetForm = () => {

    form.value = {

        arrival_date: '',

        estimated_sale_date: '',

        batch_number: '',

        batch_name: '',

        batch_size: '',

        mortality: 0,

        birds_sold: 0,

        birds_remaining: 0,

        breed: '',

        supplier: '',

        purchase_price: '',

        status: 'Growing',

        notes: ''

    }

    expenses.value = [

        {

            expense_date: '',

            item: '',

            quantity: 1,

            unit_price: 0,

            amount: 0

        }

    ]

}

/*
|--------------------------------------------------------------------------
| Submit
|--------------------------------------------------------------------------
*/

const submit = async () => {

    loading.value = true

    try {

        await axios.put(
            `/chickens/${form.value.id}`,
            {
                ...form.value,
                expenses: expenses.value
            }
        )

        message.success('Chicken batch updated successfully.')

        emit('updated')

        closeModal()

        resetForm()

    } catch (error) {

        if (error.response?.data?.errors) {

            Object.values(
                error.response.data.errors
            ).forEach(err => {
                message.error(err[0])
            })

        } else {

            message.error(
                error.response?.data?.message ||
                'Failed to update batch.'
            )

        }

    } finally {

        loading.value = false

    }
}


// const submit = async () => {

//     loading.value = true

//     try {

//         await axios.post(

//             '/chickens/${form.value.id}',

//             {

//                 ...form.value,

//                 expenses: expenses.value

//             }

//         )

//         message.success(

//             'Chicken batch updated successfully.'

//         )

//         emit('updated')

//         closeModal()

//         resetForm()

//     }

//     catch (error) {

//         if (error.response?.data?.errors) {

//             Object.values(

//                 error.response.data.errors

//             ).forEach(err => {

//                 message.error(err[0])

//             })

//         }

//         else {

//             message.error(

//                 'Failed to save batch.'

//             )

//         }

//     }

//     finally {

//         loading.value = false

//     }

// }
</script>



<template>

    <a-modal :open="open" width="1300px" :footer="null" :maskClosable="false" @cancel="closeModal">

        <form @submit.prevent="submit">
            <!-- Header -->
            <div class="mb-8">

                <h2 class="text-2xl font-bold text-gray-800">

                    Edit Chicken Batch Information

                </h2>

                <p class="text-gray-500 mt-1">

                    Capture information about a new batch of broiler chickens.

                </p>

            </div>

            <!-- Batch Details -->

            <a-card title="Batch Details" class="mb-6">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                    <!-- Arrival Date -->

                    <div>

                        <label class="font-medium">

                            Arrival Date

                        </label>

                        <a-input type="date" v-model:value="form.arrival_date"
                            style="border: 1px solid #e9e9e9; border-radius: 8px;" />

                    </div>

                    <!-- Estimated Sale -->

                    <div>

                        <label class="font-medium">

                            Estimated Sale Date

                        </label>

                        <a-input disabled type="date" v-model:value="form.estimated_sale_date" />

                    </div>

                    <!-- Batch Number -->

                    <div>

                        <label class="font-medium">

                            Batch Number

                        </label>

                        <a-input placeholder="Batch-001" v-model:value="form.batch_number"
                            style="border: 1px solid #e9e9e9; border-radius: 8px;" />

                    </div>

                    <!-- Batch Name -->

                    <div>

                        <label class="font-medium">

                            Batch Name

                        </label>

                        <a-input placeholder="January Batch" v-model:value="form.batch_name"
                            style="border: 1px solid #e9e9e9; border-radius: 8px;" />

                    </div>

                    <!-- Batch Size -->

                    <div>

                        <label class="font-medium">

                            Batch Size

                        </label>

                        <a-input-number class="w-full" :min="1" v-model:value="form.batch_size" />

                    </div>

                    <!-- Breed -->

                    <div>

                        <label class="font-medium">

                            Breed

                        </label>

                        <a-select v-model:value="form.breed" style="width: 100%;">
                            <a-select-option value="Ross Breeders"> Ross Breeders </a-select-option>
                            <a-select-option value="Hybrid"> Hybrid </a-select-option>
                            <a-select-option value="Tiger"> Tiger </a-select-option>
                            <a-select-option value="Zamchick"> Zamchick </a-select-option>
                            <a-select-option value="Other"> Other </a-select-option>
                        </a-select>

                    </div>

                    <!-- Supplier -->

                    <div>

                        <label class="font-medium">

                            Supplier / Hatchery

                        </label>

                        <a-input v-model:value="form.supplier" placeholder="Supplier Name"
                            style="border: 1px solid #e9e9e9; border-radius: 8px;" />

                    </div>

                    <!-- Purchase Price -->

                    <div>

                        <label class="font-medium">

                            Purchase Price Per Chick

                        </label>

                        <a-input-number class="w-full" :min="0" :precision="2" v-model:value="form.purchase_price" />

                    </div>

                    <!-- Status -->

                    <div>

                        <label class="font-medium">

                            Status

                        </label>

                        <a-select v-model:value="form.status" style="width: 100%;">

                            <a-select-option value="Growing">

                                Growing

                            </a-select-option>

                            <a-select-option value="Ready for Sale">

                                Ready for Sale

                            </a-select-option>

                            <a-select-option value="Selling In Progress">

                                Selling In Progress

                            </a-select-option>

                            <a-select-option value="Sold Out">

                                Sold Out

                            </a-select-option>

                            <a-select-option value="Closed">

                                Closed

                            </a-select-option>

                        </a-select>

                    </div>

                </div>

            </a-card>


            <!-- Batch Statistics -->

            <a-card title="Batch Statistics" class="mb-6">

                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

                    <!-- Mortality -->

                    <div>

                        <label class="font-medium">

                            Mortality

                        </label>

                        <a-input-number class="w-full" :min="0" v-model:value="form.mortality" />

                    </div>

                    <!-- Birds Sold -->

                    <div>

                        <label class="font-medium">

                            Birds Sold

                        </label>

                        <a-input-number class="w-full" :min="0" v-model:value="form.birds_sold" />

                    </div>

                    <!-- Remaining -->

                    <div>

                        <label class="font-medium">

                            Birds Remaining

                        </label>

                        <a-input-number class="w-full" disabled v-model:value="form.birds_remaining" />

                    </div>

                </div>

                <div class="mt-6">

                    <label class="font-medium">

                        Notes

                    </label>

                    <a-textarea v-model:value="form.notes" :rows="3" placeholder="Additional notes about this batch" />

                </div>

            </a-card>





            <!-- Expenses -->

            <a-card>

                <template #title>

                    <div class="flex justify-between items-center">

                        <span>

                            Batch Expenses

                        </span>

                        <a-button type="primary" @click="addExpense">

                            Add More

                        </a-button>

                    </div>

                </template>

                <a-table bordered :pagination="false" :data-source="expenses" rowKey="index">

                    <!-- Date -->

                    <a-table-column title="Expense Date">

                        <template #default="{ record }">

                            <a-input type="date" v-model:value="record.expense_date"
                                style="border: 1px solid #e9e9e9; border-radius: 8px;" />

                        </template>

                    </a-table-column>

                    <!-- Item -->

                    <a-table-column title="Expense">

                        <template #default="{ record }">

                            <a-input v-model:value="record.item" placeholder="Feed, Vaccine, Transport..."
                                style="border: 1px solid #e9e9e9; border-radius: 8px;" />

                        </template>

                    </a-table-column>

                    <!-- Quantity -->

                    <a-table-column title="Qty">

                        <template #default="{ record }">

                            <a-input-number class="w-full" :min="1" v-model:value="record.quantity"
                                @change="calculateAmount(record)" />

                        </template>

                    </a-table-column>

                    <!-- Unit Price -->

                    <a-table-column title="Unit Price">

                        <template #default="{ record }">

                            <a-input-number class="w-full" :precision="2" :min="0" v-model:value="record.unit_price"
                                @change="calculateAmount(record)" />

                        </template>

                    </a-table-column>

                    <!-- Amount -->

                    <a-table-column title="Amount">

                        <template #default="{ record }">

                            <a-input-number class="w-full" disabled v-model:value="record.amount" />

                        </template>

                    </a-table-column>

                    <!-- Delete -->

                    <a-table-column title="Action" width="90">

                        <template #default="{ index }">

                            <a-button danger type="link" @click="removeExpense(index)" :disabled="expenses.length == 1">

                                Remove

                            </a-button>

                        </template>

                    </a-table-column>

                </a-table>

            </a-card>

            <!-- Summary -->

            <a-card class="mt-6">

                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">

                    <!-- Batch Size -->

                    <div class="bg-blue-50 rounded-xl p-5 text-center">

                        <p class="text-gray-500">

                            Batch Size

                        </p>

                        <h2 class="text-3xl font-bold text-blue-600">

                            {{ form.batch_size || 0 }}

                        </h2>

                    </div>

                    <!-- Mortality -->

                    <div class="bg-red-50 rounded-xl p-5 text-center">

                        <p class="text-gray-500">

                            Mortality

                        </p>

                        <h2 class="text-3xl font-bold text-red-600">

                            {{ form.mortality || 0 }}

                        </h2>

                    </div>

                    <!-- Remaining -->

                    <div class="bg-green-50 rounded-xl p-5 text-center">

                        <p class="text-gray-500">

                            Birds Remaining

                        </p>

                        <h2 class="text-3xl font-bold text-green-600">

                            {{ form.birds_remaining || 0 }}

                        </h2>

                    </div>

                    <!-- Expenses -->

                    <div class="bg-yellow-50 rounded-xl p-5 text-center">
                        <p class="text-gray-500"> Total Expenses </p>

                        <h2 class="text-3xl font-bold text-yellow-600">
                            K{{ Number(totalExpenses).toLocaleString() }}
                        </h2>
                    </div>
                </div>
            </a-card>
            <!-- Footer Buttons -->
            <div class="flex justify-end gap-4 mt-8">
                <a-button size="large" @click="closeModal">
                    Cancel
                </a-button>

                <a-button type="primary" size="large" :loading="loading" @click="submit">
                    Update Chicken Batch
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