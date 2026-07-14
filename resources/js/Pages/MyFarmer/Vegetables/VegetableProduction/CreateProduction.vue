<script setup>
import { ref, reactive, onMounted } from 'vue'
import axios from 'axios'
import { message } from 'ant-design-vue'

const props = defineProps({

    open: Boolean

})

const emit = defineEmits([

    'close',
    'saved'

])

const loading = ref(false)

const vegetableTypes = ref([])

const form = reactive({

    vegetable_type_id: null,

    season: '',

    quarter: 'Q1',

    planting_date: '',

    expected_harvest_date: '',

    harvest_end_date: '',

    land_size: null,

    land_unit: 'Acres',

    expected_yield: null,

    actual_yield: 0,

    status: 'Planning',

    notes: ''

})

const fetchVegetableTypes = async () => {

    try {

        const { data } = await axios.get('/vegetable-productions/vegetable-types')

        vegetableTypes.value = data

    }
    catch {

        message.error('Unable to load vegetable types')

    }

}

const resetForm = () => {

    form.vegetable_type_id = null
    form.season = ''
    form.quarter = 'Q1'
    form.planting_date = ''
    form.expected_harvest_date = ''
    form.harvest_end_date = ''
    form.land_size = null
    form.land_unit = 'Acres'
    form.expected_yield = null
    form.actual_yield = 0
    form.status = 'Planning'
    form.notes = ''

}

const submit = async () => {

    loading.value = true

    try {

        await axios.post(

            '/vegetable-productions',

            form

        )

        message.success(

            'Production created successfully.'

        )

        emit('saved')

        emit('close')

        resetForm()

    }
    catch (error) {

        if (error.response?.data?.errors) {

            Object.values(error.response.data.errors)
                .forEach(e => {
                    message.error(e[0])
                })

        } else {

            message.error(

                'Unable to save production.'
            )
        }
    }
    finally {

        loading.value = false

    }

}

onMounted(() => {

    fetchVegetableTypes()

})
</script>

<template>

    <a-modal :open="open" width="900px" :footer="null" title="Create Vegetable Production" @cancel="$emit('close')">

        <a-form layout="vertical">

            <a-divider orientation="left">
                Production Information
            </a-divider>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <a-form-item label="Vegetable">
                    <a-select v-model:value="form.vegetable_type_id" placeholder="Select vegetable">
                        <a-select-option v-for="item in vegetableTypes" :key="item.id" :value="item.id">
                            {{ item.name }}
                        </a-select-option>
                    </a-select>

                </a-form-item>

                <a-form-item label="Season">
                    <a-input v-model:value="form.season" placeholder="Rainy Season" />
                </a-form-item>

                <a-form-item label="Quarter">

                    <a-select v-model:value="form.quarter">
                        <a-select-option value="Q1">Q1</a-select-option>
                        <a-select-option value="Q2">Q2</a-select-option>
                        <a-select-option value="Q3">Q3</a-select-option>
                        <a-select-option value="Q4">Q4</a-select-option>

                    </a-select>

                </a-form-item>

                <a-form-item label="Status">

                    <a-select v-model:value="form.status">
                        <a-select-option value="Planning">
                            Planning
                        </a-select-option>

                        <a-select-option value="Growing">
                            Growing
                        </a-select-option>

                        <a-select-option value="Harvesting">
                            Harvesting
                        </a-select-option>

                        <a-select-option value="Completed">
                            Completed
                        </a-select-option>
                    </a-select>
                </a-form-item>

            </div>

            <a-divider orientation="left">
                Planting Information
            </a-divider>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <a-form-item label="Planting Date">
                    <a-date-picker v-model:value="form.planting_date" style="width:100%" />
                </a-form-item>

                <a-form-item label="Expected Harvest">
                    <a-date-picker v-model:value="form.expected_harvest_date" style="width:100%" />
                </a-form-item>
            
                <a-form-item label="Harvest End Date">
                    <a-date-picker v-model:value="form.harvest_end_date" style="width:100%" />
                </a-form-item>

                <a-form-item label="Land Size">
                    <a-input-number v-model:value="form.land_size" :min="0" style="width:100%" />
                </a-form-item>

                <a-form-item label="Land Unit">

                    <a-select v-model:value="form.land_unit">
                        <a-select-option value="Acres">
                            Acres
                        </a-select-option>

                        <a-select-option value="Hectares">
                            Hectares
                        </a-select-option>
                    </a-select>
                </a-form-item>

            </div>

            <a-divider orientation="left">
                Yield Information
            </a-divider>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <a-form-item label="Expected Yield (Kg)">
                    <a-input-number v-model:value="form.expected_yield" :min="0" style="width:100%" />
                </a-form-item>

                <a-form-item label="Actual Yield (Kg)">
                    <a-input-number v-model:value="form.actual_yield" :min="0" style="width:100%" />
                </a-form-item>

            </div>

            <a-form-item label="Notes">
                <a-textarea v-model:value="form.notes" :rows="4" />
            </a-form-item>

            <div class="flex justify-end gap-3">
                <a-button @click="$emit('close')">
                    Cancel
                </a-button>

                <a-button type="primary" :loading="loading" @click="submit">
                    Save Production
                </a-button>
            </div>
        </a-form>

    </a-modal>

</template>