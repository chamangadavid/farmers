<script setup>
import { ref, onMounted, h } from 'vue'
import { Head } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

import CreateProduction from './CreateProduction.vue'
import EditProduction from './EditProduction.vue'
import ViewProduction from './ViewProduction.vue'
import ExpensesTab from '../Expenses/ExpensesTab.vue'

import axios from 'axios'
import { debounce } from 'lodash'
import { message, Modal, Tag, Space } from 'ant-design-vue'

import {
    PlusOutlined,
    ReloadOutlined,
    SearchOutlined,
    EyeOutlined,
    EditOutlined,
    DeleteOutlined
} from '@ant-design/icons-vue'

import dayjs from 'dayjs'


const loading = ref(false)
const productions = ref([])

const activeTab = ref('productions')

const statistics = ref({
    total: 0,
    planning: 0,
    growing: 0,
    harvesting: 0,
    completed: 0,
    expected_yield: 0,
    actual_yield: 0
})

const search = ref('')
const pagination = ref({
    current: 1,
    pageSize: 10,
    total: 0
})

const showCreate = ref(false)
const showEdit = ref(false)
const showView = ref(false)
const selectedProduction = ref({})

const number = (value) => {
    return Number(value ?? 0).toLocaleString(undefined, {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    })
}

const fetchStatistics = async () => {
    try {
        const { data } = await axios.get('/vegetable-productions/statistics')
        statistics.value = data
    }
    catch (error) {
        console.log(error)
    }
}

const fetchProductions = async (page = 1) => {
    loading.value = true
    try {
        const { data } = await axios.get('/vegetable-productions', {
            params: {
                page,
                search: search.value,
                perPage: pagination.value.pageSize
            }
        }
        )

        productions.value = data.data
        pagination.value.current = data.current_page
        pagination.value.total = data.total
        pagination.value.pageSize = data.per_page

    }
    catch (error) {
        console.log(error)
        message.error('Unable to load productions.')
    }
    finally {
        loading.value = false
    }
}


const refreshTable = () => {
    fetchProductions(pagination.value.current)
    fetchStatistics()
}

const searchRecords = debounce(() => {
    pagination.value.current = 1
    fetchProductions(1)
}, 500)


const handleTableChange = (pager) => {
    pagination.value.current = pager.current
    fetchProductions(pager.current)
}

const viewProduction = (record) => {
    selectedProduction.value = record
    showView.value = true
}

const editProduction = (record) => {
    selectedProduction.value = record
    showEdit.value = true
}

const deleteProduction = (record) => {
    Modal.confirm({
        title: 'Delete Production',
        content: `Delete batch ${record.batch_number}?`,
        okText: 'Delete',
        okType: 'danger',
        async onOk() {
            try {
                await axios.delete(`/vegetable-productions/${record.id}`)
                message.success('Production deleted.')
                refreshTable()
            }
            catch (error) {
                console.log(error)
                message.error('Unable to delete production.')
            }
        }
    })
}

/*
|--------------------------------------------------------------------------
| Status Color
|--------------------------------------------------------------------------
*/

const statusColor = (status) => {

    switch (status) {

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

}

/*
|--------------------------------------------------------------------------
| Table Columns
|--------------------------------------------------------------------------
*/

const columns = [

    {

        title: 'Batch',

        dataIndex: 'batch_number',

        width: 150

    },

    {

        title: 'Vegetable',

        dataIndex: ['vegetable_type', 'name'],

        width: 180

    },

    {

        title: 'Quarter',

        dataIndex: 'quarter',

        width: 90

    },

    {

        title: 'Planting',

        dataIndex: 'planting_date',

        width: 140,

        customRender: ({ text }) =>

            dayjs(text).format('DD MMM YYYY')

    },

    {

        title: 'Expected Harvest',

        dataIndex: 'expected_harvest_date',

        width: 150,

        customRender: ({ text }) =>

            dayjs(text).format('DD MMM YYYY')

    },

    {

        title: 'Expected Yield',

        dataIndex: 'expected_yield',

        width: 140,

        customRender: ({ text }) =>

            `${number(text)} Kg`

    },

    {

        title: 'Actual Yield',

        dataIndex: 'actual_yield',

        width: 140,

        customRender: ({ text }) =>

            `${number(text)} Kg`

    },

    {

        title: 'Status',

        dataIndex: 'status',

        width: 120,

        customRender: ({ text }) =>

            h(

                Tag,

                {

                    color: statusColor(text)

                },

                () => text

            )

    },

    {

        title: 'Actions',

        key: 'actions',

        fixed: 'right',

        width: 180,

        customRender: ({ record }) =>

            h(

                Space,

                {},

                () => [

                    h(

                        EyeOutlined,

                        {

                            style: {

                                color: '#1677ff',

                                cursor: 'pointer',

                                fontSize: '18px'

                            },

                            onClick: () => viewProduction(record)

                        }

                    ),

                    h(

                        EditOutlined,

                        {

                            style: {

                                color: '#52c41a',

                                cursor: 'pointer',

                                fontSize: '18px'

                            },

                            onClick: () => editProduction(record)

                        }

                    ),

                    h(

                        DeleteOutlined,

                        {

                            style: {

                                color: '#ff4d4f',

                                cursor: 'pointer',

                                fontSize: '18px'

                            },

                            onClick: () => deleteProduction(record)

                        }

                    )

                ]

            )

    }]


/*
|--------------------------------------------------------------------------
| Mounted
|--------------------------------------------------------------------------
*/

onMounted(() => {

    refreshTable()

})


</script>



<template>

    <Head title="Vegetable Production Management" />

    <AuthenticatedLayout>
        <a-tabs v-model:activeKey="activeTab">
            <!-- HARVEST TAB -->
            <a-tab-pane key="productions" tab="productions Records Management">

                <!-- YOUR EXISTING HARVEST DASHBOARD GOES HERE -->
                <div class="flex justify-between items-center pl-10 pr-10 mt-5">
                    <h2 class="text-xl font-semibold">
                        Production Records
                    </h2>

                    <div class="flex gap-2">
                        <a-button type="primary" @click="showCreate = true">
                            <template #icon>
                                <PlusOutlined />
                            </template>
                            New Production
                        </a-button>
                       <a-tooltip title="Refresh Table">
                            <a-button @click="refreshTable" shape="circle">
                                <ReloadOutlined />
                            </a-button>
                        </a-tooltip>
                    </div>
                </div>


                <div class="p-6">

                    <!-- ================================================= -->
                    <!-- SEARCH -->
                    <!-- ================================================= -->

                    <a-card class="mb-6">

                        <div class="flex flex-col md:flex-row justify-between gap-4">

                            <a-input v-model:value="search" placeholder="Search batch, vegetable, season..." allow-clear
                                style="max-width:400px" @input="searchRecords">

                                <template #prefix>

                                    <SearchOutlined />

                                </template>

                            </a-input>

                            <div class="text-gray-500">

                                Total Records

                                <strong>

                                    {{ pagination.total }}

                                </strong>

                            </div>

                        </div>

                    </a-card>

                    <!-- ================================================= -->
                    <!-- DASHBOARD -->
                    <!-- ================================================= -->

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-7 gap-4 mb-6">

                        <a-card hoverable>

                            <div class="text-center">

                                <p class="text-gray-500">

                                    Total Productions

                                </p>

                                <h2 class="text-3xl font-bold text-blue-600">

                                    {{ statistics.total }}

                                </h2>

                            </div>

                        </a-card>

                        <a-card hoverable>

                            <div class="text-center">

                                <p class="text-gray-500">

                                    Planning

                                </p>

                                <h2 class="text-3xl font-bold text-gray-600">

                                    {{ statistics.planning }}

                                </h2>

                            </div>

                        </a-card>

                        <a-card hoverable>

                            <div class="text-center">

                                <p class="text-gray-500">

                                    Growing

                                </p>

                                <h2 class="text-3xl font-bold text-green-600">

                                    {{ statistics.growing }}

                                </h2>

                            </div>

                        </a-card>

                        <a-card hoverable>

                            <div class="text-center">

                                <p class="text-gray-500">

                                    Harvesting

                                </p>

                                <h2 class="text-3xl font-bold text-orange-500">

                                    {{ statistics.harvesting }}

                                </h2>

                            </div>

                        </a-card>

                        <a-card hoverable>

                            <div class="text-center">

                                <p class="text-gray-500">

                                    Completed

                                </p>

                                <h2 class="text-3xl font-bold text-purple-600">

                                    {{ statistics.completed }}

                                </h2>

                            </div>

                        </a-card>

                        <a-card hoverable>

                            <div class="text-center">

                                <p class="text-gray-500">

                                    Expected Yield

                                </p>

                                <h2 class="text-xl font-bold text-indigo-600">

                                    {{ number(statistics.expected_yield) }}

                                </h2>

                                <small>Kg</small>

                            </div>

                        </a-card>

                        <a-card hoverable>

                            <div class="text-center">

                                <p class="text-gray-500">

                                    Actual Yield

                                </p>

                                <h2 class="text-xl font-bold text-red-600">

                                    {{ number(statistics.actual_yield) }}

                                </h2>

                                <small>Kg</small>

                            </div>

                        </a-card>

                    </div>

                    <!-- ================================================= -->
                    <!-- PRODUCTION TABLE -->
                    <!-- ================================================= -->

                    <a-card title="Vegetable Production Records" :bordered="false">

                        <a-table :columns="columns" :data-source="productions" :loading="loading" rowKey="id" bordered
                            size="middle" :pagination="{

                                current: pagination.current,

                                pageSize: pagination.pageSize,

                                total: pagination.total,

                                showSizeChanger: true,

                                pageSizeOptions: ['10', '20', '50', '100'],

                                showTotal: (total) => `Total ${total} Records`

                            }" @change="handleTableChange" :scroll="{

                        x: 1800,

                        y: 600

                    }">

                            <!-- ====================================== -->
                            <!-- Batch -->
                            <!-- ====================================== -->

                            <template #bodyCell="{ column, record }">

                                <template v-if="column.dataIndex == 'batch_number'">

                                    <strong>

                                        {{ record.batch_number }}

                                    </strong>

                                </template>

                                <!-- ====================================== -->
                                <!-- Vegetable -->
                                <!-- ====================================== -->

                                <template v-else-if="column.dataIndex?.[0] == 'vegetable_type'">

                                    {{ record.vegetable_type?.name }}

                                </template>

                                <!-- ====================================== -->
                                <!-- Expected Yield -->
                                <!-- ====================================== -->

                                <template v-else-if="column.dataIndex == 'expected_yield'">

                                    {{ number(record.expected_yield) }} Kg

                                </template>

                                <!-- ====================================== -->
                                <!-- Actual Yield -->
                                <!-- ====================================== -->

                                <template v-else-if="column.dataIndex == 'actual_yield'">

                                    {{ number(record.actual_yield) }} Kg

                                </template>

                                <!-- ====================================== -->
                                <!-- Status -->
                                <!-- ====================================== -->

                                <template v-else-if="column.dataIndex == 'status'">

                                    <a-tag :color="statusColor(record.status)">

                                        {{ record.status }}

                                    </a-tag>

                                </template>

                                <!-- ====================================== -->
                                <!-- Actions -->
                                <!-- ====================================== -->

                                <template v-else-if="column.key == 'actions'">

                                    <a-space>

                                        <a-tooltip title="View">

                                            <a-button type="link" @click="viewProduction(record)">

                                                <EyeOutlined />

                                            </a-button>

                                        </a-tooltip>

                                        <a-tooltip title="Edit">

                                            <a-button type="link" @click="editProduction(record)">

                                                <EditOutlined />

                                            </a-button>

                                        </a-tooltip>

                                        <a-popconfirm title="Delete this production?" ok-text="Yes" cancel-text="No"
                                            @confirm="deleteProduction(record)">

                                            <a-tooltip title="Delete">

                                                <a-button danger type="link">

                                                    <DeleteOutlined />

                                                </a-button>

                                            </a-tooltip>

                                        </a-popconfirm>

                                    </a-space>

                                </template>

                            </template>

                        </a-table>

                    </a-card>

                    <!-- ================================================= -->
                    <!-- EMPTY STATE -->
                    <!-- ================================================= -->

                    <a-empty v-if="!loading && productions.length === 0" description="No production records found."
                        class="mt-8" />

                </div>


            </a-tab-pane>

            <!-- SALES TAB -->
            <a-tab-pane key="sales" tab="Expenses Record Management">
                <ExpensesTab />
            </a-tab-pane>
        </a-tabs>


        <!-- ================================================= -->
        <!-- MODELS -->
        <!-- ================================================= -->

        <CreateProduction :open="showCreate" @close="showCreate = false" @created="() => {

            showCreate = false

            fetchProductions()

            fetchStatistics()

        }" />

        <EditProduction :open="showEdit" :production="selectedProduction" @close="showEdit = false" @updated="() => {

            showEdit = false

            fetchProductions()

            fetchStatistics()

        }" />

        <ViewProduction :open="showView" :production="selectedProduction" @close="showView = false" />


    </AuthenticatedLayout>

</template>