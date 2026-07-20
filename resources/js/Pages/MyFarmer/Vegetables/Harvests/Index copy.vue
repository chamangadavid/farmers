<!-- resources\js\Pages\MyFarmer\Vegetables\Harvests\Index.vue -->
<script setup>
import { Head } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import VueApexCharts from "vue3-apexcharts"
import {
    ref,
    computed,
    onMounted,
    watch
} from 'vue'
import axios from 'axios'
import dayjs from 'dayjs'

import {
    SearchOutlined,
    PlusOutlined,
    FileExcelOutlined,
    FilePdfOutlined,
    EyeOutlined,
    EditOutlined,
    DeleteOutlined,
    ReloadOutlined
} from '@ant-design/icons-vue'

import {
    message,
    Modal
} from 'ant-design-vue'


import CreateHarvest from './CreateHarvest.vue'
import EditHarvest from './EditHarvest.vue'
import ViewHarvest from './ViewHarvest.vue'

const app = VueApexCharts
/*
|--------------------------------------------------------------------------
| Reactive Data
|--------------------------------------------------------------------------
*/

const harvests = ref([])

const loading = ref(false)

const search = ref('')

const selectedHarvest = ref(null)


const showCreate = ref(false)
const showEdit = ref(false)
const showView = ref(false)


const dateFilter = ref('month')

const customDates = ref([])

/*
|--------------------------------------------------------------------------
| Dashboard Statistics
|--------------------------------------------------------------------------
*/


const statistics = ref({

    totalHarvests: 0,

    totalQuantity: 0,

    totalRevenue: 0,

    totalWaste: 0

})



/*
|--------------------------------------------------------------------------
| Table Columns
|--------------------------------------------------------------------------
*/


const columns = [

    {
        title: '#',
        key: 'index',
        width: 60
    },
    {
        title: 'Crop',
        key: 'crop'
    },

    {
        title: 'Harvest Date',
        dataIndex: 'harvest_date'
    },

    {
        title: 'Quantity',
        dataIndex: 'quantity'
    },

    {
        title: 'Grade',
        dataIndex: 'grade'
    },

    {
        title: 'Revenue',
        key: 'revenue'
    },

    {
        title: 'Status',
        dataIndex: 'status'
    },

    {
        title: 'Actions',
        key: 'actions',
        fixed: 'right',
        width: 160
    }


]


const buildCharts = () => {


    const data =
        dateFilteredHarvests.value



    /*
    |--------------------------------------------------------------------------
    | Harvest Trend
    |--------------------------------------------------------------------------
    */


    harvestTrendOptions.value.xaxis.categories =

        data.map(item =>

            dayjs(item.harvest_date)
                .format('MMM')

        )


    harvestTrendSeries.value[0].data =

        data.map(item =>

            Number(item.quantity ?? 0)

        )




    /*
    |--------------------------------------------------------------------------
    | Revenue
    |--------------------------------------------------------------------------
    */


    revenueOptions.value.xaxis.categories =

        data.map(item =>

            dayjs(item.harvest_date)
                .format('MMM')

        )


    revenueSeries.value[0].data =

        data.map(item =>

            Number(item.estimated_value ?? 0)

        )




    /*
    |--------------------------------------------------------------------------
    | Grade Distribution
    |--------------------------------------------------------------------------
    */


    const grades = {}


    data.forEach(item => {


        const grade =
            item.grade ?? 'Unknown'


        grades[grade] =
            (grades[grade] ?? 0) + 1


    })


    gradeOptions.value.labels =
        Object.keys(grades)


    gradeSeries.value =
        Object.values(grades)




    /*
    |--------------------------------------------------------------------------
    | Waste Analysis
    |--------------------------------------------------------------------------
    */


    wasteOptions.value.xaxis.categories =

        data.map(item =>

            item.production
                ?.vegetable_type
                ?.name
            ?? 'Unknown'

        )



    wasteSeries.value[0].data =

        data.map(item =>

            Number(item.waste ?? 0)

        )



}


watch(

    [
        dateFilter,
        customDates

    ],

    () => {

        buildCharts()

    },

    {
        deep: true
    }

)
/*
|--------------------------------------------------------------------------
| Fetch Harvest Data
|--------------------------------------------------------------------------
*/


const fetchHarvests = async () => {

    loading.value = true

    try {


        const response = await axios.get('/vegetable-harvests')


        harvests.value = response.data.data ?? response.data
        buildCharts()


        calculateStatistics()


    }
    catch (error) {

        message.error(
            'Failed to load harvest records'
        )

    }
    finally {

        loading.value = false

    }

}




/*
|--------------------------------------------------------------------------
| Statistics Calculation
|--------------------------------------------------------------------------
*/


const calculateStatistics = () => {


    statistics.value.totalHarvests =
        harvests.value.length



    statistics.value.totalQuantity =
        harvests.value.reduce(
            (sum, item) =>
                sum + Number(item.quantity ?? 0),
            0
        )

    statistics.value.totalRevenue =
        harvests.value.reduce(
            (sum, item) =>
                sum + Number(item.estimated_value ?? 0),
            0
        )


    statistics.value.totalWaste =
        harvests.value.reduce(
            (sum, item) =>
                sum + Number(item.waste ?? 0),
            0
        )

}




/*
|--------------------------------------------------------------------------
| Search Filter
|--------------------------------------------------------------------------
*/


const filteredHarvests = computed(() => {


    if (!search.value)
        return harvests.value



    return harvests.value.filter(item => {


        return (

            item.crop_name
                ?.toLowerCase()
                .includes(
                    search.value.toLowerCase()
                )

            ||

            item.grade
                ?.toLowerCase()
                .includes(
                    search.value.toLowerCase()
                )

        )

    })


})




/*
|--------------------------------------------------------------------------
| CRUD Actions
|--------------------------------------------------------------------------
*/


const openView = (record) => {

    selectedHarvest.value = record

    showView.value = true

}



const openEdit = (record) => {

    selectedHarvest.value = record

    showEdit.value = true

}




const deleteHarvest = (record) => {


    Modal.confirm({

        title: 'Delete Harvest',

        content:
            `Are you sure you want to delete ${record.crop_name}?`,


        async onOk() {


            try {


                await axios.delete(
                    `/harvests/${record.id}`
                )


                message.success(
                    'Harvest deleted successfully'
                )


                fetchHarvests()


            }
            catch (error) {

                message.error(
                    'Delete failed'
                )

            }


        }


    })


}





/*
|--------------------------------------------------------------------------
| Export Functions
|--------------------------------------------------------------------------
*/




const exportPDF = () => {

    window.open(
        '/vegetable-harvests/export/pdf',
        '_blank'
    )

}





/*
|--------------------------------------------------------------------------
| Modal Events
|--------------------------------------------------------------------------
*/


const refresh = () => {

    fetchHarvests()

}



/*
|--------------------------------------------------------------------------
| Charts Data Placeholder
|--------------------------------------------------------------------------
*/


const harvestTrend = ref([])

const revenueChart = ref([])
const harvestTrendOptions = ref({

    chart: {
        type: 'line',
        height: 300
    },

    xaxis: {
        categories: []
    },

    stroke: {
        curve: 'smooth'
    },

    title: {
        text: 'Harvest Quantity Trend'
    }

})


const harvestTrendSeries = ref([
    {
        name: 'Quantity (Kg)',
        data: []
    }
])



const revenueOptions = ref({

    chart: {
        type: 'area',
        height: 300
    },

    xaxis: {
        categories: []
    },

    title: {
        text: 'Revenue Analysis'
    }

})


const revenueSeries = ref([

    {
        name: 'Revenue (K)',
        data: []
    }

])

const gradeOptions = ref({

    chart: {
        type: 'pie',
        height: 300
    },

    labels: []

})


const gradeSeries = ref([])



const wasteOptions = ref({

    chart: {
        type: 'bar',
        height: 300
    },

    plotOptions: {
        bar: {
            horizontal: false
        }
    },

    xaxis: {
        categories: []
    }


})


const wasteSeries = ref([

    {
        name: 'Waste Kg',
        data: []
    }

])

const dateFilteredHarvests = computed(() => {


    let data = [...harvests.value]


    if (dateFilter.value === 'today') {

        return data.filter(item =>

            dayjs(item.harvest_date)
                .isSame(dayjs(), 'day')

        )

    }


    if (dateFilter.value === 'week') {


        return data.filter(item =>

            dayjs(item.harvest_date)
                .isAfter(
                    dayjs().subtract(7, 'day')
                )

        )

    }



    if (dateFilter.value === 'month') {


        return data.filter(item =>

            dayjs(item.harvest_date)
                .isSame(
                    dayjs(),
                    'month'
                )

        )

    }



    if (
        dateFilter.value === 'custom'
        &&
        customDates.value.length === 2
    ) {


        return data.filter(item => {


            const date =
                dayjs(item.harvest_date)


            return (

                date.isAfter(
                    customDates.value[0],
                    'day'
                )

                &&

                date.isBefore(
                    customDates.value[1],
                    'day'
                )

            )


        })


    }



    return data


})


// const gradeDistribution = ref([])

// const wasteAnalysis = ref([])

onMounted(() => {

    fetchHarvests()

})



</script>

<template>


    <Head title="Vegetable Production Management" />

    <AuthenticatedLayout>
        <div class="p-4">


            <!-- HEADER -->

            <div class="flex justify-between items-center mb-6">


                <div>

                    <h1 class="text-2xl font-bold">
                        Harvest Dashboard
                    </h1>

                    <p class="text-gray-500">
                        Monitor crop production, revenue and quality
                    </p>

                </div>



                <a-button type="primary" @click="showCreate = true">

                    <PlusOutlined />

                    Add Harvest

                </a-button>


            </div>




            <!-- KPI CARDS -->

            <a-row :gutter="16">


                <a-col :xs="24" :sm="12" :lg="6">


                    <a-card>

                        <div class="text-gray-500">
                            Total Harvests
                        </div>

                        <div class="text-3xl font-bold">

                            {{ statistics.totalHarvests }}

                        </div>


                    </a-card>


                </a-col>



                <a-col :xs="24" :sm="12" :lg="6">

                    <a-card>

                        <div>
                            Total Quantity
                        </div>

                        <div class="text-3xl font-bold">

                            {{ statistics.totalQuantity }}

                        </div>


                    </a-card>

                </a-col>




                <a-col :xs="24" :sm="12" :lg="6">

                    <a-card>

                        <div>
                            Revenue
                        </div>

                        <div class="text-3xl font-bold">

                            K {{ statistics.totalRevenue }}

                        </div>


                    </a-card>

                </a-col>




                <a-col :xs="24" :sm="12" :lg="6">

                    <a-card>

                        <div>
                            Waste
                        </div>

                        <div class="text-3xl font-bold">

                            {{ statistics.totalWaste }}

                        </div>


                    </a-card>


                </a-col>


            </a-row>





            <!-- CHART AREA -->



            <!-- ===================================================== -->
            <!-- CHART AREA WITH SCROLL -->
            <!-- ===================================================== -->

            <div class="chart-scroll-container">


                <a-row :gutter="16">


                    <a-col :xs="24" :lg="12">


                        <a-card title="Harvest Trend">


                            <apexchart type="line" height="300" :options="harvestTrendOptions"
                                :series="harvestTrendSeries" />


                        </a-card>


                    </a-col>



                    <a-col :xs="24" :lg="12">


                        <a-card title="Revenue Analysis">


                            <apexchart type="area" height="300" :options="revenueOptions" :series="revenueSeries" />


                        </a-card>


                    </a-col>



                    <a-col :xs="24" :lg="12">


                        <a-card title="Grade Distribution">


                            <apexchart type="pie" height="300" :options="gradeOptions" :series="gradeSeries" />


                        </a-card>


                    </a-col>



                    <a-col :xs="24" :lg="12">


                        <a-card title="Waste Analysis">


                            <apexchart type="bar" height="300" :options="wasteOptions" :series="wasteSeries" />


                        </a-card>


                    </a-col>


                </a-row>


            </div>

            <!-- <a-row
:gutter="16"
class="mt-5"
>


<a-col :xs="24" :lg="12">


<a-card title="Harvest Trend">

<apexchart

type="line"

height="300"

:options="harvestTrendOptions"

:series="harvestTrendSeries"

/>

</a-card>

</a-col>

<a-col :xs="24" :lg="12">

<a-card title="Revenue Analysis">

<apexchart

type="area"

height="300"

:options="revenueOptions"

:series="revenueSeries"

/>


</a-card>

</a-col>


</a-row>

<a-row
:gutter="16"
class="mt-5"
>


<a-col :xs="24" :lg="12">


<a-card title="Grade Distribution">


<apexchart

type="pie"

height="300"

:options="gradeOptions"

:series="gradeSeries"

/>


</a-card>


</a-col>
</a-row> -->


            <!-- TOOLBAR -->


            <a-card class="mt-5">


                <div class="flex flex-wrap gap-3">


                    <a-input v-model:value="search" placeholder="Search crop or grade" style="width:250px">

                        <template #prefix>

                            <SearchOutlined />

                        </template>

                    </a-input>



                    <a-button @click="refresh">

                        <ReloadOutlined />

                        Refresh

                    </a-button>


                    <a-button @click="exportPDF">

                        <FilePdfOutlined />

                        PDF

                    </a-button>


                    <a-select v-model:value="dateFilter" style="width:160px">

                        <a-select-option value="today">

                            Today

                        </a-select-option>


                        <a-select-option value="week">

                            Last 7 Days

                        </a-select-option>


                        <a-select-option value="month">

                            This Month

                        </a-select-option>


                        <a-select-option value="custom">

                            Custom

                        </a-select-option>


                    </a-select>

                    <a-range-picker v-if="dateFilter === 'custom'" v-model:value="customDates" />
                </div>


            </a-card>





            <!-- TABLE -->


            <a-card class="mt-5">


                <a-table :columns="columns" :data-source="filteredHarvests" :loading="loading"
                    :scroll="{ x: 1000, y: 450 }" row-key="id">


                    <template #bodyCell="{ column, record, index }">


                        <template v-if="column.key === 'index'">

                            {{ index + 1 }}

                        </template>

                        <template v-if="column.key === 'crop'">

                            {{
                                record.production?.vegetable_type?.name
                                ?? 'N/A'
                            }}

                        </template>

                        <template v-else-if="column.key === 'revenue'">

                            K {{ Number(record.estimated_value).toLocaleString() }}

                        </template>


                        <template v-else-if="column.key === 'revenue'">

                            K {{ Number(record.estimated_value).toLocaleString() }}

                        </template>

                        <template v-if="column.key === 'status'">


                            <a-tag v-if="record.remaining_yield > 0" color="blue">

                                Partial

                            </a-tag>


                            <a-tag v-else color="green">

                                Completed

                            </a-tag>


                        </template>

                        <template v-else-if="column.key === 'actions'">


                            <a-space>


                                <a-button size="small" @click="openView(record)">

                                    <EyeOutlined />

                                </a-button>



                                <a-button size="small" @click="openEdit(record)">

                                    <EditOutlined />

                                </a-button>



                                <a-button danger size="small" @click="deleteHarvest(record)">

                                    <DeleteOutlined />

                                </a-button>


                            </a-space>


                        </template>



                    </template>


                </a-table>


            </a-card>




            <!-- MODALS -->


            <CreateHarvest v-model:open="showCreate" @success="fetchHarvests" />



            <EditHarvest v-model:open="showEdit" :harvest="selectedHarvest" @success="fetchHarvests" />




            <ViewHarvest v-model:open="showView" :harvest="selectedHarvest" />



        </div>

    </AuthenticatedLayout>


</template>

<style scoped>
:deep(.ant-card) {
    border-radius: 14px;
}

.chart-scroll-container {

    max-height: 390px;

    overflow-y: auto;

    overflow-x: hidden;

    padding-right: 10px;

}



/* scrollbar styling */

.chart-scroll-container::-webkit-scrollbar {

    width: 8px;

}


.chart-scroll-container::-webkit-scrollbar-track {

    background: #f1f1f1;

    border-radius: 10px;

}


.chart-scroll-container::-webkit-scrollbar-thumb {

    background: #bfbfbf;

    border-radius: 10px;

}


.chart-scroll-container::-webkit-scrollbar-thumb:hover {

    background: #888;

}



:deep(.ant-card) {

    border-radius: 14px;

}
</style>