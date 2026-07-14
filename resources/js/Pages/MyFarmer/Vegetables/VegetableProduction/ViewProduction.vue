<script setup>
import { computed } from 'vue'
import dayjs from 'dayjs'

const props = defineProps({

    open: Boolean,

    production: {

        type: Object,

        default: () => ({})

    }

})

const emit = defineEmits([

    'close'

])

const statusColor = computed(() => {

    switch (props.production.status) {

        case 'Planning':
            return 'default'

        case 'Growing':
            return 'processing'

        case 'Harvesting':
            return 'warning'

        case 'Completed':
            return 'success'

        default:
            return 'default'

    }

})

const yieldPercentage = computed(() => {

    const expected = Number(props.production.expected_yield ?? 0)

    const actual = Number(props.production.actual_yield ?? 0)

    if (expected <= 0) {

        return 0

    }

    return ((actual / expected) * 100).toFixed(2)

})

const daysSincePlanting = computed(() => {

    if (!props.production.planting_date) {

        return '-'

    }

    return dayjs().diff(

        dayjs(props.production.planting_date),

        'day'

    )

})

const daysUntilHarvest = computed(() => {

    if (!props.production.expected_harvest_date) {

        return '-'

    }

    return dayjs(

        props.production.expected_harvest_date

    ).diff(

        dayjs(),

        'day'

    )

})

const formatDate = (date) => {

    if (!date) return '-'

    return dayjs(date).format('DD MMM YYYY')

}

const money = (value)=>{

    return Number(value ?? 0).toLocaleString()

}
</script>

<template>

<a-modal

    :open="open"

    width="1000px"

    :footer="null"

    title="Vegetable Production Details"

    @cancel="$emit('close')">

<div class="space-y-6">

<!-- Statistics -->

<div class="grid grid-cols-2 md:grid-cols-4 gap-4">

<a-card>

<div class="text-center">

<p class="text-gray-500">

Expected Yield

</p>

<h2 class="text-2xl font-bold text-green-600">

{{ money(production.expected_yield) }} Kg

</h2>

</div>

</a-card>

<a-card>

<div class="text-center">

<p class="text-gray-500">

Actual Yield

</p>

<h2 class="text-2xl font-bold text-blue-600">

{{ money(production.actual_yield) }} Kg

</h2>

</div>

</a-card>

<a-card>

<div class="text-center">

<p class="text-gray-500">

Yield %

</p>

<h2 class="text-2xl font-bold text-purple-600">

{{ yieldPercentage }} %

</h2>

</div>

</a-card>

<a-card>

<div class="text-center">

<p class="text-gray-500">

Status

</p>

<a-tag :color="statusColor">

{{ production.status }}

</a-tag>

</div>

</a-card>

</div>

<!-- Production Information -->

<a-card title="Production Information">

<div class="grid grid-cols-1 md:grid-cols-2 gap-5">

<div>

<strong>Batch Number</strong>

<p>

{{ production.batch_number }}

</p>

</div>

<div>

<strong>Vegetable</strong>

<p>

{{ production.vegetable_type?.name }}

</p>

</div>

<div>

<strong>Season</strong>

<p>

{{ production.season }}

</p>

</div>

<div>

<strong>Quarter</strong>

<p>

{{ production.quarter }}

</p>

</div>

</div>

</a-card>

<!-- Planting -->

<a-card title="Planting Information">

<div class="grid grid-cols-1 md:grid-cols-2 gap-5">

<div>

<strong>Planting Date</strong>

<p>

{{ formatDate(production.planting_date) }}

</p>

</div>

<div>

<strong>Expected Harvest</strong>

<p>

{{ formatDate(production.expected_harvest_date) }}

</p>

</div>

<div>

<strong>Harvest End Date</strong>

<p>

{{ formatDate(production.harvest_end_date) }}

</p>

</div>

<div>

<strong>Land Size</strong>

<p>

{{ production.land_size }}

{{ production.land_unit }}

</p>

</div>

</div>

</a-card>

<!-- Progress -->

<a-card title="Production Progress">

<div class="grid grid-cols-2 md:grid-cols-4 gap-4">

<div>

<strong>Days Since Planting</strong>

<h2 class="text-xl font-bold text-blue-600">

{{ daysSincePlanting }}

</h2>

</div>

<div>

<strong>Days Until Harvest</strong>

<h2 class="text-xl font-bold text-orange-600">

{{ daysUntilHarvest }}

</h2>

</div>

<div>

<strong>Expected Yield</strong>

<h2 class="text-xl font-bold text-green-600">

{{ money(production.expected_yield) }} Kg

</h2>

</div>

<div>

<strong>Actual Yield</strong>

<h2 class="text-xl font-bold text-indigo-600">

{{ money(production.actual_yield) }} Kg

</h2>

</div>

</div>

</a-card>

<!-- Notes -->

<a-card title="Production Notes">

<p v-if="production.notes">

{{ production.notes }}

</p>

<p

v-else

class="text-gray-400">

No notes available.

</p>

</a-card>

<div class="flex justify-end">

<a-button

type="primary"

@click="$emit('close')">

Close

</a-button>

</div>

</div>

</a-modal>

</template>