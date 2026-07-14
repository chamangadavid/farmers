<!-- resources\js\Pages\MyFarmer\Vegetables\Harvests\EditHarvest.vue -->
<script setup>

import { ref, reactive, watch } from 'vue'
import axios from 'axios'
import { message } from 'ant-design-vue'
import dayjs from 'dayjs'


/*
|--------------------------------------------------------------------------
| Props
|--------------------------------------------------------------------------
*/

const props = defineProps({

    open: {

        type: Boolean,

        default: false

    },

    harvest: {

        type: Object,

        default: () => ({})

    }

})

/*
|--------------------------------------------------------------------------
| Emits
|--------------------------------------------------------------------------
*/

// const emit = defineEmits([

//     'close',

//     'updated'

// ])

const emit = defineEmits([

    'update:open',

    'success'

])


/*
|--------------------------------------------------------------------------
| Loading
|--------------------------------------------------------------------------
*/

const loading = ref(false)

const productions = ref([])

const formRef = ref()

const remainingYield = ref({

    expected: 0,

    harvested: 0,

    remaining: 0

})



/*
|--------------------------------------------------------------------------
| Load Production Batches
|--------------------------------------------------------------------------
*/
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
| Load Remaining Yield
|--------------------------------------------------------------------------
*/

const loadRemainingYield = async () => {

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
| Populate Form
|--------------------------------------------------------------------------
*/

const populateForm = () => {

    if (!props.harvest?.id) {

        return

    }

    form.vegetable_production_id = props.harvest.vegetable_production_id

    form.harvest_date = props.harvest.harvest_date
        ? dayjs(props.harvest.harvest_date)
        : null

    form.grade = props.harvest.grade ?? 'Grade A'

    form.quantity = Number(props.harvest.quantity ?? 0)

    form.unit_price = Number(props.harvest.unit_price ?? 0)

    form.estimated_value = Number(props.harvest.estimated_value ?? 0)

    form.waste = Number(props.harvest.waste ?? 0)

    form.harvested_by = props.harvest.harvested_by ?? ''

    form.notes = props.harvest.notes ?? ''

}

/*
|--------------------------------------------------------------------------
| Watch Modal Open
|--------------------------------------------------------------------------
*/

watch(

    () => props.open,

    async (open) => {

        if (!open) {

            return

        }

        await loadProductions()

        populateForm()

        await loadRemainingYield()

    }

)

/*
|--------------------------------------------------------------------------
| Watch Selected Harvest
|--------------------------------------------------------------------------
*/

watch(

    () => props.harvest,

    (value) => {

        if (value?.id) {

            populateForm()

        }

    },

    {

        deep: true

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

        loadRemainingYield()

    }

)
/*
|--------------------------------------------------------------------------
| Validation Rules
|--------------------------------------------------------------------------
*/

const rules = {

    vegetable_production_id: [

        {

            required: true,

            message: 'Production Batch is required.',

            trigger: 'change'

        }

    ],

    harvest_date: [

        {

            required: true,

            message: 'Harvest Date is required.',

            trigger: 'change'

        }

    ],

    quantity: [

        {

            required: true,

            message: 'Harvest Quantity is required.',

            trigger: 'blur'

        }

    ],

    unit_price: [

        {

            required: true,

            message: 'Price per Kg is required.',

            trigger: 'blur'

        }

    ]

}

/*
|--------------------------------------------------------------------------
| Helper Functions
|--------------------------------------------------------------------------
*/

const money = (value) => {

    return Number(value ?? 0)

        .toLocaleString(

            undefined,

            {

                minimumFractionDigits: 2,

                maximumFractionDigits: 2

            }

        )

}

const number = (value) => {

    return Number(value ?? 0)

        .toLocaleString()

}

/*
|--------------------------------------------------------------------------
| Auto Calculate Estimated Value
|--------------------------------------------------------------------------
*/

watch(

    [

        () => form.quantity,

        () => form.unit_price

    ],

    () => {

        form.estimated_value =

            Number(form.quantity ?? 0)

            *

            Number(form.unit_price ?? 0)

    }

)

/*
|--------------------------------------------------------------------------
| Warn User If Harvest Exceeds Remaining Yield
|--------------------------------------------------------------------------
*/

watch(

    () => form.quantity,

    (value) => {

        if (

            Number(value)

            >

            (

                Number(remainingYield.value.remaining)

                +

                Number(props.harvest.quantity ?? 0)

            )

        ) {

            message.warning(

                'Harvest quantity exceeds the remaining expected yield.'

            )

        }

    }

)

/*
|--------------------------------------------------------------------------
| Update Harvest
|--------------------------------------------------------------------------
*/

const updateHarvest = async () => {

    if (loading.value) {

        return

    }

    try {

        await formRef.value.validate()

    }
    catch {

        return

    }

    loading.value = true

    try {

        await axios.put(

            `/vegetable-harvests/${props.harvest.id}`,

            {

                vegetable_production_id:

                    form.vegetable_production_id,

                harvest_date:

                    form.harvest_date

                        ? dayjs(form.harvest_date).format('YYYY-MM-DD')

                        : null,

                grade:

                    form.grade,

                quantity:

                    form.quantity,

                unit_price:

                    form.unit_price,

                estimated_value:

                    form.estimated_value,

                waste:

                    form.waste,

                harvested_by:

                    form.harvested_by,

                notes:

                    form.notes

            }

        )

        message.success({

            content: 'Harvest updated successfully.',

            duration: 2

        })

        emit('success')

        closeModal()

    }
    catch (error) {

        console.log(error)

        message.error({

            content:

                error.response?.data?.message

                ??

                'Unable to update harvest.',

            duration: 3

        })

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

//     formRef.value?.resetFields()

//     resetForm()

//     emit(

//         'close'

//     )

// }

const closeModal = () => {

    formRef.value?.resetFields()

    resetForm()

    emit('update:open', false)

}

</script>
<template>
    <a-modal :open="open" title="Edit Vegetable Harvest" :width="950" :footer="null" :maskClosable="false"
        :confirmLoading="loading" :destroyOnClose="true" :centered="true" @cancel="closeModal">

        <a-card class="mb-5">

            <a-row :gutter="16">

                <a-col :xs="24" :sm="12" :md="6">

                    <a-statistic title="Expected Yield" :value="remainingYield.expected" suffix="Kg" :precision="2" />

                </a-col>

                <a-col :xs="24" :sm="12" :md="6">

                    <a-statistic title="Harvested" :value="remainingYield.harvested" suffix="Kg" :precision="2"
                        :valueStyle="{

                            color: '#52c41a'

                        }" />

                </a-col>

                <a-col :xs="24" :sm="12" :md="6">

                    <a-statistic title="Remaining" :value="remainingYield.remaining" suffix="Kg" :precision="2"
                        :valueStyle="{

                            color: '#fa541c'

                        }" />

                </a-col>

                <a-col :xs="24" :sm="12" :md="6">

                    <a-statistic title="Estimated Value" :value="form.estimated_value" prefix="K" :precision="2"
                        :valueStyle="{

                            color: '#1677ff',

                            fontWeight: 'bold'

                        }" />

                </a-col>

            </a-row>

        </a-card>

        <a-alert class="mb-5" show-icon type="info"
            message="Update the harvest information below. Estimated value is calculated automatically from Quantity × Unit Price." />

        <a-form ref="formRef" :model="form" :rules="rules" layout="vertical">
            <a-row :gutter="16">

                <!-- Production Batch -->
                <a-col :xs="24" :md="12">

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
                <a-col :xs="24" :md="12">

                    <a-form-item label="Harvest Date" name="harvest_date">

                        <a-date-picker v-model:value="form.harvest_date" class="w-full" />

                    </a-form-item>

                </a-col>

                <!-- Grade -->
                <a-col :xs="24" :md="12">

                    <a-form-item label="Grade">

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
                <a-col :xs="24" :md="12">

                    <a-form-item label="Harvest Quantity (Kg)" name="quantity">

                        <a-input-number v-model:value="form.quantity" class="w-full" :min="0" :precision="2" />

                    </a-form-item>

                </a-col>

                <!-- Unit Price -->
                <a-col :xs="24" :md="12">

                    <a-form-item label="Unit Price (K)" name="unit_price">

                        <a-input-number v-model:value="form.unit_price" class="w-full" :min="0" :precision="2" />

                    </a-form-item>

                </a-col>

                <!-- Estimated Value -->
                <a-col :xs="24" :md="12">

                    <a-form-item label="Estimated Value">

                        <a-input-number :value="form.estimated_value" class="w-full" disabled :precision="2" />

                    </a-form-item>

                </a-col>

                <!-- Waste -->
                <a-col :xs="24" :md="12">

                    <a-form-item label="Waste (Kg)">

                        <a-input-number v-model:value="form.waste" class="w-full" :min="0" :precision="2" />

                    </a-form-item>

                </a-col>

                <!-- Harvested By -->
                <a-col :xs="24" :md="12">

                    <a-form-item label="Harvested By">

                        <a-input v-model:value="form.harvested_by" placeholder="Enter employee name" />

                    </a-form-item>

                </a-col>

                <!-- Notes -->
                <a-col :span="24">

                    <a-form-item label="Notes">

                        <a-textarea v-model:value="form.notes" :rows="4" placeholder="Additional notes..." />

                    </a-form-item>

                </a-col>

            </a-row>

            <a-divider />

            <a-alert type="success" show-icon :message="`Estimated Harvest Value: K ${money(form.estimated_value)}`" />

            <a-alert class="mt-3" type="warning" show-icon
                :message="`Remaining Yield: ${remainingYield.remaining} Kg`" />

            <!-- =============================== -->
            <!-- Action Buttons -->
            <!-- =============================== -->

            <a-divider />

            <div class="flex justify-end gap-3">

                <a-button size="large" @click="closeModal">

                    Cancel

                </a-button>

                <a-button type="primary" size="large" :loading="loading" :disabled="loading" @click="updateHarvest">

                    <template #icon>

                        <SaveOutlined />

                    </template>

                    Update Harvest

                </a-button>

            </div>

        </a-form>

    </a-modal>
</template>

<style scoped>
.w-full {

    width: 100%;

}

.mb-5 {

    margin-bottom: 20px;

}

.mt-3 {

    margin-top: 15px;

}

:deep(.ant-statistic) {

    text-align: center;

}

:deep(.ant-statistic-content) {

    font-size: 24px;

    font-weight: 600;

}

:deep(.ant-form-item) {

    margin-bottom: 18px;

}

:deep(.ant-card) {

    border-radius: 10px;

}

:deep(.ant-modal-content) {

    border-radius: 12px;

}

:deep(.ant-input-number) {

    width: 100%;

}
</style>