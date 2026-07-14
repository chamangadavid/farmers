<script setup>
import { ref, reactive, watch } from 'vue'
import axios from 'axios'
import { message } from 'ant-design-vue'
import dayjs from 'dayjs'

const props = defineProps({

    open: Boolean,

    production: {
        type: Object,
        default: () => ({})
    }

})

const emit = defineEmits([

    'close',
    'updated'

])

const loading = ref(false)

const vegetableTypes = ref([])

const form = reactive({

    id: null,

    vegetable_type_id: null,

    batch_number: '',

    season: '',

    quarter: 'Q1',

    planting_date: null,

    expected_harvest_date: null,

    harvest_end_date: null,

    land_size: null,

    land_unit: 'Acres',

    expected_yield: 0,

    actual_yield: 0,

    status: 'Planning',

    notes: ''

})

const loadVegetables = async () => {

    try{

        const {data} = await axios.get('/vegetable-productions/vegetable-types')

        vegetableTypes.value = data

    }
    catch{

        message.error('Unable to load vegetable types.')

    }

}

watch(

    () => props.production,

    (value)=>{

        if(!value?.id) return

        form.id=value.id

        form.vegetable_type_id=value.vegetable_type_id

        form.batch_number=value.batch_number

        form.season=value.season

        form.quarter=value.quarter

        form.planting_date=value.planting_date
            ? dayjs(value.planting_date)
            : null

        form.expected_harvest_date=value.expected_harvest_date
            ? dayjs(value.expected_harvest_date)
            : null

        form.harvest_end_date=value.harvest_end_date
            ? dayjs(value.harvest_end_date)
            : null

        form.land_size=value.land_size

        form.land_unit=value.land_unit

        form.expected_yield=value.expected_yield

        form.actual_yield=value.actual_yield

        form.status=value.status

        form.notes=value.notes

    },

    {

        immediate:true

    }

)

watch(

    ()=>props.open,

    (value)=>{

        if(value){

            loadVegetables()

        }

    }

)

const updateProduction = async()=>{

    loading.value=true

    try{

        await axios.put(

            `/vegetable-productions/${form.id}`,

            {

                vegetable_type_id:form.vegetable_type_id,

                season:form.season,

                quarter:form.quarter,

                planting_date:form.planting_date
                    ? form.planting_date.format('YYYY-MM-DD')
                    : null,

                expected_harvest_date:form.expected_harvest_date
                    ? form.expected_harvest_date.format('YYYY-MM-DD')
                    : null,

                harvest_end_date:form.harvest_end_date
                    ? form.harvest_end_date.format('YYYY-MM-DD')
                    : null,

                land_size:form.land_size,

                land_unit:form.land_unit,

                expected_yield:form.expected_yield,

                actual_yield:form.actual_yield,

                status:form.status,

                notes:form.notes

            }

        )

        message.success(

            'Production updated successfully.'

        )

        emit('updated')

        emit('close')

    }
    catch(error){

        console.log(error)

        message.error(

            'Unable to update production.'

        )

    }
    finally{

        loading.value=false

    }

}
</script>

<template>

<a-modal

    :open="open"

    width="950px"

    :footer="null"

    title="Edit Vegetable Production"

    @cancel="$emit('close')">

<a-form layout="vertical">

<a-divider orientation="left">

Production Information

</a-divider>

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">

<a-form-item label="Batch Number">

<a-input

v-model:value="form.batch_number"

disabled

/>

</a-form-item>

<a-form-item label="Vegetable">

<a-select

v-model:value="form.vegetable_type_id">

<a-select-option

v-for="item in vegetableTypes"

:key="item.id"

:value="item.id">

{{ item.name }}

</a-select-option>

</a-select>

</a-form-item>

<a-form-item label="Season">

<a-input

v-model:value="form.season"/>

</a-form-item>

<a-form-item label="Quarter">

<a-select

v-model:value="form.quarter">

<a-select-option value="Q1">Q1</a-select-option>

<a-select-option value="Q2">Q2</a-select-option>

<a-select-option value="Q3">Q3</a-select-option>

<a-select-option value="Q4">Q4</a-select-option>

</a-select>

</a-form-item>

<a-form-item label="Status">

<a-select

v-model:value="form.status">

<a-select-option value="Planning">Planning</a-select-option>

<a-select-option value="Growing">Growing</a-select-option>

<a-select-option value="Harvesting">Harvesting</a-select-option>

<a-select-option value="Completed">Completed</a-select-option>

</a-select>

</a-form-item>

</div>

<a-divider orientation="left">

Planting Information

</a-divider>

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">

<a-form-item label="Planting Date">

<a-date-picker

v-model:value="form.planting_date"

style="width:100%"/>

</a-form-item>

<a-form-item label="Expected Harvest">

<a-date-picker

v-model:value="form.expected_harvest_date"

style="width:100%"/>

</a-form-item>

<a-form-item label="Harvest End">

<a-date-picker

v-model:value="form.harvest_end_date"

style="width:100%"/>

</a-form-item>

<a-form-item label="Land Size">

<a-input-number

v-model:value="form.land_size"

:min="0"

style="width:100%"/>

</a-form-item>

<a-form-item label="Land Unit">

<a-select

v-model:value="form.land_unit">

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

<a-input-number

v-model:value="form.expected_yield"

:min="0"

style="width:100%"/>

</a-form-item>

<a-form-item label="Actual Yield (Kg)">

<a-input-number

v-model:value="form.actual_yield"

:min="0"

style="width:100%"/>

</a-form-item>

</div>

<a-form-item label="Notes">

<a-textarea

v-model:value="form.notes"

:rows="4"/>

</a-form-item>

<div class="flex justify-end gap-3 mt-4">

<a-button

@click="$emit('close')">

Cancel

</a-button>

<a-button

type="primary"

:loading="loading"

@click="updateProduction">

Update Production

</a-button>

</div>

</a-form>

</a-modal>

</template>