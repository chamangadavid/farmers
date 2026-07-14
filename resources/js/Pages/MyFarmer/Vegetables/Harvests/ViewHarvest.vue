<!-- resources\js\Pages\MyFarmer\Vegetables\Harvests\ViewHarvest.vue -->
<script setup>

import { computed } from 'vue'
import dayjs from 'dayjs'

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

const emit = defineEmits([

    'update:open'

])

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

const percentage = computed(() => {


    const expected =
        Number(
            props.harvest.production?.expected_yield ?? 0
        )


    const harvested =
        Number(
            props.harvest.quantity ?? 0
        )


    if (!expected) {

        return 0

    }


    return Number(
        ((harvested / expected) * 100)
            .toFixed(2)
    )

})


const closeModal = () => {

    emit('update:open', false)

}

const printReceipt = () => {

    window.open(

        `/vegetable-harvests/${props.harvest.id}/receipt`,

        '_blank'

    )

}

const downloadPdf = () => {

    window.open(

        `/vegetable-harvests/${props.harvest.id}/pdf`,

        '_blank'

    )

}


const remainingYield = computed(() => {


    const expected =
        Number(props.harvest.production?.expected_yield ?? 0)


    const harvested =
        Number(props.harvest.quantity ?? 0)


    return expected - harvested


})

const harvestStatus = computed(() => {


    const remaining =
        remainingYield.value


    if (remaining <= 0) {

        return 'Completed'

    }


    return 'In Progress'


})

</script>
<template>

    <a-modal :open="open" title="Harvest Details" :width="1100" :footer="null" :maskClosable="false" :centered="true"
        @cancel="closeModal">

        <!-- =============================== -->
        <!-- Harvest Summary -->
        <!-- =============================== -->

        <a-card class="mb-5">

            <template #title>

                🌱 Harvest Summary

            </template>

            <a-row :gutter="16">

                <a-col :xs="24" :md="12">

                    <p><strong>Vegetable</strong> : {{ harvest.production?.vegetable_type?.name ?? 'N/A' }}</p>

                </a-col>

                <a-col :xs="24" :md="12">

                    <p><strong>Production Batch</strong> : {{ harvest.production?.batch_number }}</p>

                </a-col>

                <a-col :xs="24" :md="12">

                    <p><strong>Harvest Date</strong> : {{ dayjs(harvest.harvest_date).format('DD MMM YYYY') }}</p>

                </a-col>

                <a-col :xs="24" :md="12">

                    <p><strong>Harvested By</strong> : {{ harvest.harvested_by }}</p>

                </a-col>

            </a-row>

        </a-card>



        <!-- =============================== -->
        <!-- Statistics -->
        <!-- =============================== -->

        <a-row :gutter="16" class="mb-5">

            <a-col :xs="24" :sm="12" :md="6">

                <a-card>

                    <a-statistic title="Expected Yield" :value="harvest.production?.expected_yield" suffix="Kg" />

                </a-card>

            </a-col>

            <a-col :xs="24" :sm="12" :md="6">

                <a-card>

                    <a-statistic title="Harvested" :value="harvest.quantity" suffix="Kg" :valueStyle="{

                        color: '#52c41a'

                    }" />

                </a-card>

            </a-col>

            <a-col :xs="24" :sm="12" :md="6">

                <a-card>

                    <a-statistic title="Remaining" :value="remainingYield" suffix="Kg" :valueStyle="{

                        color: '#1677ff'

                    }" />

                </a-card>

            </a-col>

            <a-col :xs="24" :sm="12" :md="6">

                <a-card>

                    <a-statistic title="Waste" :value="harvest.waste" suffix="Kg" :valueStyle="{

                        color: '#ff4d4f'

                    }" />

                </a-card>

            </a-col>

        </a-row>



        <!-- =============================== -->
        <!-- Harvest Progress -->
        <!-- =============================== -->

        <a-card class="mb-5">

            <template #title>

                Harvest Progress

            </template>

            <a-progress :percent="percentage" status="active" />

        </a-card>



        <!-- =============================== -->
        <!-- Financial Information -->
        <!-- =============================== -->

        <a-card class="mb-5">

            <template #title>

                Financial Information

            </template>

            <a-row :gutter="16">

                <a-col :xs="24" :md="6">

                    <a-statistic title="Unit Price" prefix="K" :value="harvest.unit_price" :precision="2" />

                </a-col>

                <a-col :xs="24" :md="6">

                    <a-statistic title="Estimated Value" prefix="K" :value="harvest.estimated_value" :precision="2"
                        :valueStyle="{

                            color: '#52c41a'

                        }" />

                </a-col>

                <a-col :xs="24" :md="6">

                    <a-statistic title="Grade" :value="harvest.grade" />

                </a-col>


                <a-col :xs="24" :md="6">

                    <a-statistic title="Status" :value="harvestStatus" />

                </a-col>
            </a-row>

        </a-card>



        <!-- =============================== -->
        <!-- Notes -->
        <!-- =============================== -->

        <a-card class="mb-5">

            <template #title>

                Additional Notes

            </template>

            <p>

                {{ harvest.notes || 'No notes available.' }}

            </p>

        </a-card>



        <!-- =============================== -->
        <!-- Action Buttons -->
        <!-- =============================== -->

        <div class="flex justify-end gap-3">

            <a-button @click="printReceipt">

                🖨 Print Receipt

            </a-button>

            <a-button type="primary" ghost @click="downloadPdf">

                📄 Download PDF

            </a-button>

            <a-button type="primary" danger @click="closeModal">

                Close

            </a-button>

        </div>

    </a-modal>

</template>

<style scoped>
.mb-5 {

    margin-bottom: 20px;

}

:deep(.ant-statistic) {

    text-align: center;

}

:deep(.ant-statistic-content) {

    font-size: 24px;

    font-weight: 600;

}

:deep(.ant-card) {

    border-radius: 10px;

}

:deep(.ant-progress) {

    margin-top: 15px;

}
</style>