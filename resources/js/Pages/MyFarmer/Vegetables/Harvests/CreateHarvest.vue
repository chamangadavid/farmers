<!-- resources\js\Pages\MyFarmer\Vegetables\Harvests\CreateHarvest.vue -->
<script setup>

import { ref, reactive, watch } from 'vue'
import axios from 'axios'
import { message } from 'ant-design-vue'

const props = defineProps({

    open: Boolean

})

// const emit = defineEmits([

//     'close',

//     'created'

// ])

const emit = defineEmits([

    'update:open',

    'success'

])

const loading = ref(false)

const productions = ref([])

const remainingYield = ref({

    expected: 0,

    harvested: 0,

    remaining: 0

})

const form = reactive({

    vegetable_production_id: null,

    harvest_date: null,

    grade: 'Grade A',

    quantity: 0,

    unit_price: 0,

    estimated_value: 0,

    waste: 0,

    harvested_by: '',

    notes: ''

})

const rules = {

    vegetable_production_id: [

        {

            required: true,

            message: 'Production Batch is required'

        }

    ],

    harvest_date: [

        {

            required: true,

            message: 'Harvest Date is required'

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

            message: 'Price is required'

        }

    ]

}

const formRef = ref()


watch(

    () => form.quantity,

    (value) => {

        if (

            Number(value)

            >

            Number(remainingYield.value.remaining)

        ) {

            message.warning(

                'Harvest quantity exceeds remaining expected yield.'

            )

        }

    }

)


watch(

    () => form.vegetable_production_id,

    (id) => {

        const production =

            productions.value.find(

                x => x.id == id

            )

        if (!production) return

        form.unit_price =

            production.unit_price ?? 0

    }

)
/*
|--------------------------------------------------------------------------
| Calculate Estimated Value
|--------------------------------------------------------------------------
*/

watch(

    () => [

        form.quantity,

        form.unit_price

    ],

    () => {

        form.estimated_value =

            Number(form.quantity || 0)

            *

            Number(form.unit_price || 0)

    }

)

/*
|--------------------------------------------------------------------------
| Load Production Batches
|--------------------------------------------------------------------------
*/

const loadProductions = async () => {

    try {

        const { data } = await axios.get(

            '/vegetable-harvests/productions'

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
| Remaining Yield
|--------------------------------------------------------------------------
*/

const getRemainingYield = async () => {

    if (!form.vegetable_production_id) {

        return

    }

    try {

        const { data } = await axios.get(

            `/vegetable-productions/${form.vegetable_production_id}/remaining-yield`

        )

        remainingYield.value = data

    }

    catch (error) {

        console.log(error)

    }

}

/*
|--------------------------------------------------------------------------
| Save Harvest
|--------------------------------------------------------------------------
*/

const saveHarvest = () => {

    formRef.value

        .validate()

        .then(async () => {

            loading.value = true

            try {

                await axios.post(

                    '/vegetable-harvests',

                    form

                )

                message.success(

                    'Harvest recorded successfully.'

                )

                resetForm()

                emit('success')
                emit('update:open', false)


            }

            catch (error) {

                console.log(error)

                message.error(

                    'Unable to save harvest.'

                )

            }

            finally {

                loading.value = false

            }

        })

}

/*
|--------------------------------------------------------------------------
| Reset
|--------------------------------------------------------------------------
*/

const resetForm = () => {

    form.vegetable_production_id = null

    form.harvest_date = null

    form.grade = 'Grade A'

    form.quantity = 0

    form.unit_price = 0

    form.estimated_value = 0

    form.waste = 0

    form.harvested_by = ''

    form.notes = ''

    remainingYield.value = {

        expected: 0,

        harvested: 0,

        remaining: 0

    }

}

/*
|--------------------------------------------------------------------------
| Close Modal
|--------------------------------------------------------------------------
*/

// const closeModal = () => {

//     resetForm()

//     emit(

//         'close'

//     )

// }

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

    (value) => {

        if (value) {

            loadProductions()

        }

    }

)

/*
|--------------------------------------------------------------------------
| Watch Production Change
|--------------------------------------------------------------------------
*/

watch(

    () => form.vegetable_production_id,

    () => {

        getRemainingYield()

    }

)

</script>

<template>

    <a-modal :open="open" title="Record Vegetable Harvest" :width="900" :footer="null" :maskClosable="false"
        :confirmLoading="loading" :destroyOnClose="true" :centered="true" @cancel="closeModal">

        <!-- ===================================================== -->
        <!-- Remaining Yield Dashboard -->
        <!-- ===================================================== -->

        <a-row :gutter="16" class="mb-6">

            <a-col :span="8">

                <a-statistic title="Expected Yield" :value="remainingYield.expected" suffix="Kg" />

            </a-col>

            <a-col :span="8">

                <a-statistic title="Harvested" :value="remainingYield.harvested" suffix="Kg"
                    valueStyle="color:#52c41a" />

            </a-col>

            <a-col :span="8">

                <a-statistic title="Remaining" :value="remainingYield.remaining" suffix="Kg"
                    valueStyle="color:#fa541c" />

            </a-col>

        </a-row>

        <!-- <a-row
        :gutter="16"
        class="mb-5">

        <a-col :span="8">

            <a-card>

                <div class="text-center">

                    <p class="text-gray-500">

                        Expected Yield

                    </p>

                    <h2 class="text-2xl font-bold text-blue-600">

                        {{ remainingYield.expected }}

                        Kg

                    </h2>

                </div>

            </a-card>

        </a-col>

        <a-col :span="8">

            <a-card>

                <div class="text-center">

                    <p class="text-gray-500">

                        Harvested

                    </p>

                    <h2 class="text-2xl font-bold text-green-600">

                        {{ remainingYield.harvested }}

                        Kg

                    </h2>

                </div>

            </a-card>

        </a-col>

        <a-col :span="8">

            <a-card>

                <div class="text-center">

                    <p class="text-gray-500">

                        Remaining

                    </p>

                    <h2 class="text-2xl font-bold text-red-600">

                        {{ remainingYield.remaining }}

                        Kg

                    </h2>

                </div>

            </a-card>

        </a-col>

    </a-row> -->

        <!-- ===================================================== -->
        <!-- Harvest Form -->
        <!-- ===================================================== -->

        <a-form layout="vertical" ref="formRef" :model="form" :rules="rules">

            <a-row :gutter="16">

                <!-- Production -->

                <a-col :span="12">

                    <a-form-item label="Production Batch" name="vegetable_production_id">

                        <a-select v-model:value="form.vegetable_production_id" placeholder="Select Production Batch"
                            show-search :filter-option="false">

                            <a-select-option v-for="production in productions" :key="production.id"
                                :value="production.id">

                                {{ production.batch_number }}

                                -

                                {{ production.vegetable_type?.name }}

                            </a-select-option>

                        </a-select>

                    </a-form-item>

                </a-col>

                <!-- Harvest Date -->

                <a-col :span="12">

                    <a-form-item label="Harvest Date" name="harvest_date">

                        <a-date-picker v-model:value="form.harvest_date" class="w-full" value-format="YYYY-MM-DD" />

                    </a-form-item>

                </a-col>

                <!-- Grade -->

                <a-col :span="12">

                    <a-form-item label="Grade" name="grade">

                        <a-select v-model:value="form.grade">

                            <a-select-option value="Grade A">

                                Grade A

                            </a-select-option>

                            <a-select-option value="Grade B">

                                Grade B

                            </a-select-option>

                            <a-select-option value="Grade C">

                                Grade C

                            </a-select-option>

                        </a-select>

                    </a-form-item>

                </a-col>

                <!-- Quantity -->

                <a-col :span="12">

                    <a-form-item label="Harvest Quantity (Kg)" name="quantity">

                        <a-input-number v-model:value="form.quantity" class="w-full" :min="0" />

                    </a-form-item>

                </a-col>

                <!-- Unit Price -->

                <a-col :span="12">

                    <a-form-item label="Price Per Kg" name="unit_price">

                        <a-input-number v-model:value="form.unit_price" class="w-full" :min="0" />

                    </a-form-item>

                </a-col>

                <!-- Estimated -->

                <a-col :span="12">

                    <a-form-item label="Estimated Value">

                        <a-input-number :value="form.estimated_value" class="w-full" readonly />

                    </a-form-item>

                </a-col>

                <!-- Waste -->

                <a-col :span="12">

                    <a-form-item label="Waste (Kg)" name="waste">

                        <a-input-number v-model:value="form.waste" class="w-full" :min="0" />

                    </a-form-item>

                </a-col>

                <!-- Harvested By -->

                <a-col :span="12">

                    <a-form-item label="Harvested By" name="harvested_by">

                        <a-input v-model:value="form.harvested_by" placeholder="Enter staff name" />

                    </a-form-item>

                </a-col>

                <!-- Notes -->

                <a-col :span="24">

                    <a-form-item label="Notes" name="notes">

                        <a-textarea v-model:value="form.notes" :rows="4" placeholder="Additional notes" />

                    </a-form-item>

                </a-col>

            </a-row>

            <!-- ===================================================== -->
            <!-- Buttons -->
            <!-- ===================================================== -->

            <div class="flex justify-end gap-3 mt-5">

                <a-button @click="closeModal">

                    Cancel

                </a-button>

                <!-- <a-button

                type="primary"

                :loading="loading"

                @click="saveHarvest">

                Save Harvest

            </a-button> -->

                <a-button type="primary" :loading="loading" :disabled="loading" @click="saveHarvest">

                    Save Harvest

                </a-button>

            </div>

        </a-form>

    </a-modal>

</template>