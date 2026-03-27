<script setup>
import { ref, onMounted, h, computed } from 'vue';
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ViewAccident from './ViewAccident.vue';
import ResolveAccident from './ResolveAccident.vue';
import { Tabs, Button, Input, message, Popconfirm, Tag, Space, Badge, Tooltip, } from 'ant-design-vue';

import {
    SearchOutlined,
    ReloadOutlined,
    EyeOutlined,
    DeleteOutlined,
    FileTextOutlined,
    CalendarOutlined,
    PhoneOutlined,
    MailOutlined,
    EnvironmentOutlined,
    AlertOutlined,
    EditOutlined
} from '@ant-design/icons-vue';
import axios from 'axios';
import { debounce } from 'lodash';

const activeTab = ref('reports');
const reports = ref([]);
const selectedReport = ref(null);
const showViewModal = ref(false);
const searchTerm = ref('');
const loading = ref(false);
const showResolveModal = ref(false);

const fetchReports = async () => {
    loading.value = true;
    try {
        const res = await axios.get('/accident-reports', {
            params: { search: searchTerm.value }
        });
        reports.value = res.data.reports;
    } catch (e) {
        console.error(e);
        message.error('Failed to fetch reports');
    } finally {
        loading.value = false;
    }
};

const deleteReport = async (id) => {
    try {
        await axios.delete(`/accident-reports/${id}`);
        message.success('Report deleted successfully');
        fetchReports();
    } catch (error) {
        message.error('Failed to delete report');
    }
};

const handleSearch = debounce(() => {
    fetchReports();
}, 500);

const refreshReports = () => {
    searchTerm.value = '';
    fetchReports();
    message.success('Reports refreshed');
};

const truncate = (text, length = 30) => {
    if (!text) return '';
    return text.length > length ? text.substring(0, length) + '...' : text;
};

const formatDate = (date) => {
    if (!date) return 'N/A';
    return new Date(date).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    });
};

// Get report type badge color
const getReportTypeColor = (type) => {
    const typeLower = type?.toLowerCase() || '';
    if (typeLower.includes('accident')) return 'red';
    if (typeLower.includes('incident')) return 'orange';
    if (typeLower.includes('serious')) return 'purple';
    return 'teal';
};


const columns = [
    {
        title: 'Type',
        dataIndex: 'report_type',
        width: 120,
        customRender: ({ record }) =>
            h(Tag, {
                color: getReportTypeColor(record.report_type),
                class: 'px-2 py-1 text-xs'
            }, () => record.report_type)
    },

    {
        title: 'Reporter',
        dataIndex: 'reporter_name',
        width: 150,
        ellipsis: true
    },

    {
        title: 'Contact',
        dataIndex: 'reporter_email',
        width: 200,
        customRender: ({ record }) =>
            h('div', { class: 'text-xs leading-tight' }, [

                // Email
                h('div', { class: 'flex items-center gap-1 text-gray-600' }, [
                    h(MailOutlined, { class: 'text-teal-500' }),
                    h('span', truncate(record.reporter_email, 18))
                ]),

                // Phone
                h('div', { class: 'flex items-center gap-1 text-gray-400 mt-1' }, [
                    h(PhoneOutlined, { class: 'text-teal-500' }),
                    h('span', record.reporter_phone || 'N/A')
                ])
            ])
    },

    {
        title: 'Date & Time',
        dataIndex: 'accident_date',
        width: 160,
        customRender: ({ record }) =>
            h('div', { class: 'text-xs leading-tight' }, [
                h('div', formatDate(record.accident_date)),
                h('div', { class: 'text-gray-400 mt-1' }, record.accident_time || 'N/A')
            ])
    },

    {
        title: 'Location',
        dataIndex: 'location',
        width: 200,
        ellipsis: true,
        customRender: ({ record }) =>
            h('div', { class: 'flex items-center gap-1 text-xs' }, [
                h(EnvironmentOutlined, { class: 'text-teal-500' }),
                h('span', truncate(record.location, 30))
            ])
    },
    {
    title: 'Status',
    dataIndex: 'status',
    width: 180,
    customRender: ({ record }) =>
        h(Tag, {
            color:
                record.status === 'Closed' ? 'green' :
                record.status.includes('Investigation') ? 'blue' :
                record.status.includes('Analysis') ? 'orange' :
                'purple'
        }, () => record.status)
},

    // {
    //     title: 'Description',
    //     dataIndex: 'description',
    //     ellipsis: true,
    //     customRender: ({ record }) =>
    //         h('div', {
    //             class: 'text-gray-600 text-xs',
    //             title: record.description
    //         }, truncate(record.description, 40))
    // },
    {
        title: 'Actions',
        key: 'actions',
        width: 120,
        align: 'center',
        customRender: ({ record }) =>
            h(Space, { size: 'small' }, [

                // 👁 VIEW
                h(Tooltip, { title: 'View Accident Report' }, {
                    default: () =>
                        h(Button, {
                            type: 'text',
                            size: 'small',
                            icon: h(EyeOutlined),
                            onClick: () => {
                                selectedReport.value = record;
                                showViewModal.value = true;
                            }
                        })
                }),

                h(Tooltip, { title: 'Update Status' }, {
                    default: () =>
                        h(Button, {
                            type: 'text',
                            size: 'small',
                            icon: h(EditOutlined),
                            onClick: () => {
                                selectedReport.value = record;
                                showResolveModal.value = true;
                            }
                        })
                }),

                // 🗑 DELETE
                h(Popconfirm, {
                    title: 'Delete this report?',
                    onConfirm: () => deleteReport(record.id),
                    okText: 'Yes',
                    cancelText: 'No',
                    okType: 'danger'
                }, {
                    default: () =>
                        h(Tooltip, { title: 'Delete Accident Report' }, {
                            default: () =>
                                h(Button, {
                                    type: 'text',
                                    danger: true,
                                    size: 'small',
                                    icon: h(DeleteOutlined)
                                })
                        })
                })
            ])
    }

];

onMounted(fetchReports);

</script>

<template>
    <Head title="Accident Report" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Accident & Incident Reports
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
                <div class="bg-white p-4 shadow sm:rounded-lg sm:p-8">
                    <div class="p-6">
                        <a-tabs v-model:activeKey="activeTab">
                            <a-tab-pane key="reports" tab="Accident Reports">
                                <!-- Header with Stats -->
                                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
                                    <div>
                                        <h3 class="text-lg font-medium text-gray-900">Manage Accident & Incident Reports</h3>
                                        <p class="text-sm text-gray-500 mt-1">
                                            View, manage, and analyze accident and incident reports
                                        </p>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <div class="bg-teal-50 text-teal-700 px-3 py-1 rounded-full text-sm font-medium">
                                            Total: {{ reports.length }} Reports
                                        </div>
                                        <Badge :count="reports.filter(r => r.report_type === 'Accident').length" :number-style="{ backgroundColor: '#ef4444' }">
                                            <span class="text-sm text-gray-600 mr-2">Accidents</span>
                                        </Badge>
                                        <Badge :count="reports.filter(r => r.report_type === 'Incident').length" :number-style="{ backgroundColor: '#f59e0b' }">
                                            <span class="text-sm text-gray-600">Incidents</span>
                                        </Badge>
                                    </div>
                                </div>

                                <!-- Search and Actions Bar -->
                                <div class="flex flex-col sm:flex-row justify-between items-stretch sm:items-center gap-3 mb-6">
                                    <div class="flex-1"></div>
                                    <div class="flex gap-3 items-center">
                                        <!-- Search Input -->
                                        <div class="relative w-full sm:w-80">
                                            <div class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                                                <SearchOutlined />
                                            </div>
                                            <Input 
                                                v-model:value="searchTerm" 
                                                placeholder="Search by reporter, location, or description..."
                                                class="pl-9 pr-10 py-2 rounded-lg border-gray-200 focus:border-teal-500"
                                                allowClear
                                                @input="handleSearch"
                                            />
                                            <div v-if="searchTerm" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-xs">
                                                {{ reports.length }} results
                                            </div>
                                        </div>
                                        
                                        <!-- Refresh Button -->
                                        <Button 
                                            @click="refreshReports"
                                            class="border-gray-200 hover:border-teal-500 hover:text-teal-600"
                                            :loading="loading"
                                        >
                                            <ReloadOutlined />
                                            Refresh
                                        </Button>
                                    </div>
                                </div>

                                <!-- Table Container -->
                                <div class="overflow-x-auto">
                                    <a-table 
                                        :dataSource="reports" 
                                        :loading="loading" 
                                        rowKey="id" 
                                        :columns="columns"
                                        :pagination="{
                                            pageSize: 10,
                                            showSizeChanger: true,
                                            showTotal: (total) => `Total ${total} items`,
                                            size: 'default'
                                        }" 
                                        :scroll="{ x: 1100 }"
                                        class="reports-table"
                                    >
                                        <template #emptyText>
                                            <div class="py-12 text-center">
                                                <div class="text-6xl mb-4">🚨</div>
                                                <p class="text-gray-500 text-lg mb-2">No reports found</p>
                                                <p class="text-gray-400 text-sm">
                                                    {{ searchTerm ? 'Try adjusting your search criteria' : 'Accident reports will appear here' }}
                                                </p>
                                                <Button 
                                                    v-if="searchTerm" 
                                                    type="link" 
                                                    @click="searchTerm = ''; fetchReports()" 
                                                    class="mt-3"
                                                >
                                                    Clear search
                                                </Button>
                                            </div>
                                        </template>
                                    </a-table>
                                </div>

                                <!-- Mobile Scroll Hint -->
                                <div v-if="reports.length > 0" class="mt-4 text-center text-xs text-gray-400 sm:hidden">
                                    ← Swipe horizontally to see more columns →
                                </div>
                            </a-tab-pane>
                        </a-tabs>
                    </div>

                    <!-- View Modal -->
                    <ViewAccident :open="showViewModal" :report="selectedReport"
                        @update:open="showViewModal = $event" />

                  <ResolveAccident :open="showResolveModal" :report="selectedReport"
                        @update:open="showResolveModal = $event" @updated="fetchReports" />

                    
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
/* Table Styling */
:deep(.ant-table) {
    border-radius: 0.5rem;
    overflow: hidden;
}

:deep(.ant-table-thead > tr > th) {
    background-color: #f8fafc;
    font-weight: 600;
    color: #1f2937;
    font-size: 0.875rem;
    border-bottom: 2px solid #e2e8f0;
    padding: 12px 16px;
}

:deep(.ant-table-tbody > tr > td) {
    padding: 12px 16px;
    border-bottom: 1px solid #f1f5f9;
}

:deep(.ant-table-tbody > tr:hover > td) {
    background-color: #f8fafc !important;
}

/* Tab styling */
:deep(.ant-tabs-card > .ant-tabs-nav .ant-tabs-tab) {
    background: #f8fafc;
    border-color: #e2e8f0;
    font-weight: 500;
    border-radius: 8px 8px 0 0;
}

:deep(.ant-tabs-card > .ant-tabs-nav .ant-tabs-tab-active) {
    background: #ffffff;
    border-bottom-color: #ffffff;
    color: #14b8a6;
    border-top: 2px solid #14b8a6;
}

/* Button Styling */
:deep(.ant-btn) {
    border-radius: 0.5rem;
    font-weight: 500;
    transition: all 0.3s ease;
}

:deep(.ant-btn-link) {
    padding: 4px 8px;
    height: auto;
}

:deep(.ant-btn-link:hover) {
    transform: translateY(-1px);
}

:deep(.ant-btn-primary) {
    background: linear-gradient(135deg, #14b8a6 0%, #10b981 100%);
    border: none;
}

:deep(.ant-btn-primary:hover) {
    background: linear-gradient(135deg, #0d9488 0%, #059669 100%);
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(20, 184, 166, 0.3);
}

/* Search Input Styling */
:deep(.ant-input-affix-wrapper) {
    border-radius: 0.5rem;
    border: 1px solid #e2e8f0;
    transition: all 0.3s ease;
}

:deep(.ant-input-affix-wrapper:hover) {
    border-color: #14b8a6;
}

:deep(.ant-input-affix-wrapper:focus),
:deep(.ant-input-affix-wrapper-focused) {
    border-color: #14b8a6;
    box-shadow: 0 0 0 2px rgba(20, 184, 166, 0.1);
}

/* Tag Styling */
:deep(.ant-tag) {
    border-radius: 1rem;
    padding: 2px 10px;
    font-size: 0.75rem;
    font-weight: 500;
    border: none;
}

/* Badge Styling */
:deep(.ant-badge) {
    margin-right: 8px;
}

:deep(.ant-badge-count) {
    font-size: 11px;
    min-width: 18px;
    height: 18px;
    line-height: 18px;
    padding: 0 5px;
}

/* Pagination Styling */
:deep(.ant-pagination) {
    margin-top: 16px;
}

:deep(.ant-pagination-item-active) {
    background: linear-gradient(135deg, #14b8a6 0%, #10b981 100%);
    border-color: transparent;
}

:deep(.ant-pagination-item-active a) {
    color: white;
}

:deep(.ant-pagination-item-active:hover) {
    background: linear-gradient(135deg, #0d9488 0%, #059669 100%);
}

:deep(.ant-pagination-item:hover) {
    border-color: #14b8a6;
}

:deep(.ant-pagination-item:hover a) {
    color: #14b8a6;
}

/* Scrollbar Styling */
.overflow-x-auto::-webkit-scrollbar {
    height: 6px;
}

.overflow-x-auto::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

.overflow-x-auto::-webkit-scrollbar-thumb {
    background: linear-gradient(135deg, #14b8a6, #10b981);
    border-radius: 10px;
}

.overflow-x-auto::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(135deg, #0d9488, #059669);
}

/* Stats Badge */
.bg-teal-50 {
    background-color: #f0fdfa;
    border: 1px solid #99f6e4;
}

/* Responsive Adjustments */
@media (max-width: 768px) {
    :deep(.ant-table-thead > tr > th) {
        padding: 8px 12px;
        font-size: 0.75rem;
    }
    
    :deep(.ant-table-tbody > tr > td) {
        padding: 8px 12px;
        font-size: 0.8125rem;
    }
    
    :deep(.ant-btn-link) {
        padding: 2px 6px;
    }
    
    :deep(.ant-tag) {
        font-size: 0.6875rem;
        padding: 2px 8px;
    }
}
</style>


<!-- <script setup>
import { ref, onMounted, h } from 'vue';
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ViewAccident from './ViewAccident.vue'
import { Tabs, Button, Input, message, Popconfirm } from 'ant-design-vue';
import axios from 'axios';
import { debounce } from 'lodash';


const activeTab = ref('reports');

const reports = ref([]);
const selectedReport = ref(null);


const fetchReports = async () => {
    loading.value = true;
    try {
        const res = await axios.get('/accident-reports', {
            params: { search: searchTerm.value }
        });

        reports.value = res.data.reports;
    } catch (e) {
        message.error('Failed to fetch reports');
    } finally {
        loading.value = false;
    }
};

const deleteReport = async (id) => {
    await axios.delete(`/accident-reports/${id}`);
    fetchReports();
};


const columns = [
    { title: 'Type', dataIndex: 'report_type' },
    { title: 'Reporter', dataIndex: 'reporter_name' },
    { title: 'Email', dataIndex: 'reporter_email' },
    { title: 'Phone', dataIndex: 'reporter_phone' },
    { title: 'Date', dataIndex: 'accident_date' },
    { title: 'Time', dataIndex: 'accident_time' },
    { title: 'Location', dataIndex: 'location' },

    {
        title: 'Description',
        customRender: ({ record }) => truncate(record.description, 30)
    },

    {
        title: 'Actions',
        customRender: ({ record }) =>
            h('div', { class: 'flex gap-2' }, [
                h(Button, {
                    onClick: () => {
                        selectedReport.value = record; 
                        showViewModal.value = true;   
                    }
                }, 'View'),

                h(Popconfirm, {
                    title: 'Delete?',
                    onConfirm: () => deleteReport(record.id)
                }, {
                    default: () => h(Button, { danger: true }, 'Delete')
                })
            ])
    },

];

const showViewModal = ref(false);
const searchTerm = ref('');
const loading = ref(false);

const handleSearch = debounce(() => {
    fetchReports();
}, 500);

const truncate = (text, length = 20) => {
    if (!text) return '';
    return text.length > length ? text.substring(0, length) + '...' : text;
};

onMounted(fetchReports);
</script>

<template>

    <Head title="Accident Report" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Accident & Incident Reports
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">

                <div class="bg-white p-4 shadow sm:rounded-lg sm:p-8">

                    <div class="p-6">
                        <a-tabs v-model:activeKey="activeTab">

                            <a-tab-pane key="reports" tab="Accident Reports">

                                <div class="flex justify-between items-center mb-4">
                                    <h3 class="text-lg font-medium">Manage Accident and Incident Reports</h3>

                                </div>

                                <div class="flex justify-between items-center mb-4">
                                    <Input v-model:value="searchTerm" placeholder="Search news..." style="width: 300px"
                                        allowClear @input="handleSearch" />
                                </div>

                                <a-table :dataSource="reports" :loading="loading" rowKey="id" :columns="columns"
                                    :pagination="{
                                        pageSize: 10,
                                        showSizeChanger: true,
                                        showTotal: (total) => `Total ${total} items`
                                    }" :scroll="{ x: 768 }">
                                    <template #emptyText>
                                        <div class="py-12 text-center">
                                            <div class="text-6xl mb-4">📰</div>
                                            <p class="text-gray-500 text-lg mb-2">No news found</p>
                                            <p class="text-gray-400 text-sm">
                                                {{ searchTerm ? 'Try adjusting your search' : 'News will appear here' }}
                                            </p>

                                            <Button v-if="searchTerm" type="link"
                                                @click="searchTerm = ''; fetchRegulations()" class="mt-3">
                                                Clear search
                                            </Button>
                                        </div>
                                    </template>
                                </a-table>

                            </a-tab-pane>

                        </a-tabs>
                    </div>

                    <ViewAccident :open="showViewModal" :report="selectedReport"
                        @update:open="showViewModal = $event" />

                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template> -->

