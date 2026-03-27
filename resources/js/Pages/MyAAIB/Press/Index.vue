<script setup>
import { ref, onMounted, h, computed } from 'vue';
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import CreatePress from './CreatePress.vue';
import EditPress from './EditPress.vue';
import ViewPress from './ViewPress.vue';
import { Tabs, Table, Button, Modal, Input, Select, Tag, message, Popconfirm, Tooltip, } from 'ant-design-vue';
import { SearchOutlined, PlusOutlined, ReloadOutlined, FilePdfOutlined, EyeOutlined, EditOutlined, DeleteOutlined, DownloadOutlined } from '@ant-design/icons-vue';
import axios from 'axios';
import { debounce } from 'lodash';

// State management
const activeTab = ref('press');

const presses = ref([]);
const showCreateModal = ref(false);
const showEditModal = ref(false);
const showViewModal = ref(false);
const selectedPress = ref(null);
const searchTerm = ref('');
const loading = ref(false);

const fetchPresses = async () => {
    loading.value = true;
    try {
        const res = await axios.get('/presses', {
            params: {
                search: searchTerm.value
            }
        });
        presses.value = res.data.presses;
    } catch (error) {
        console.error(error);
        message.error('Failed to fetch press releases');
    } finally {
        loading.value = false;
    }
};

const handleSearch = debounce(() => {
    fetchPresses();
}, 500);

const deletePress = async (id) => {
    try {
        await axios.delete(`/presses/${id}`);
        message.success('Deleted successfully');
        fetchPresses(); // refresh table
    } catch (error) {
        console.error(error);
        message.error('Failed to delete record');
    }
};

const refreshPresses = () => {
    searchTerm.value = '';
    fetchPresses();
    message.success('Press releases refreshed');
};

const columns = [
    { 
        title: 'Title', 
        dataIndex: 'title',
        width: '30%',
        ellipsis: true
    },
    { 
        title: 'Date', 
        dataIndex: 'date',
        width: '15%'
    },
    {
        title: 'Summary',
        dataIndex: 'summary',
        width: '35%',
        ellipsis: true,
        customRender: ({ record }) =>
            record.summary.length > 60
                ? record.summary.substring(0, 60) + '...'
                : record.summary
    },
    {
        title: 'PDF',
        width: '10%',
        align: 'center',
        customRender: ({ record }) =>
            h('a', { 
                href: `/storage/${record.pdf}`, 
                target: '_blank',
                class: 'text-teal-600 hover:text-teal-700'
            }, [
                h(FilePdfOutlined, { class: 'mr-1' }),
                
            ])
    },
    {
    title: 'Actions',
    width: '15%',
    align: 'center',
    customRender: ({ record }) => {
        return h('div', { class: 'flex gap-2 justify-center' }, [

            // 👁 VIEW
            h(Tooltip, { title: 'View Details' }, {
                default: () =>
                    h(Button, {
                        size: 'small',
                        type: 'link',
                        icon: h(EyeOutlined),
                        onClick: () => {
                            selectedPress.value = record;
                            showViewModal.value = true;
                        }
                    })
            }),

            // ✏️ EDIT
            h(Tooltip, { title: 'Edit Press Release' }, {
                default: () =>
                    h(Button, {
                        size: 'small',
                        type: 'link',
                        icon: h(EditOutlined),
                        onClick: () => {
                            selectedPress.value = record;
                            showEditModal.value = true;
                        }
                    })
            }),

            // 📥 DOWNLOAD
            h(Tooltip, { title: 'Download PDF' }, {
                default: () =>
                    h('a', {
                        href: `/presses/download/${record.id}`,
                        class: 'inline-block'
                    }, [
                        h(Button, {
                            size: 'small',
                            type: 'link',
                            icon: h(DownloadOutlined)
                        })
                    ])
            }),

            // 🗑 DELETE
            h(Popconfirm, {
                title: 'Are you sure you want to delete this record?',
                onConfirm: () => deletePress(record.id),
                okText: 'Yes',
                cancelText: 'No',
                okType: 'danger'
            }, {
                default: () =>
                    h(Tooltip, { title: 'Delete Press Release', color: 'red' }, {
                        default: () =>
                            h(Button, {
                                danger: true,
                                size: 'small',
                                type: 'link',
                                icon: h(DeleteOutlined)
                            })
                    })
            })

        ]);
    }
}

];

onMounted(fetchPresses);
</script>

<template>
    <Head title="Press" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Press Releases
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
                <div class="bg-white p-4 shadow sm:rounded-lg sm:p-8">
                    <div class="p-6">
                        <a-tabs v-model:activeKey="activeTab">
                            <!-- Press Tab -->
                            <a-tab-pane key="press" tab="Press Management">
                                <!-- Header with Stats and Add Button -->
                                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
                                    <div>
                                        <h3 class="text-lg font-medium text-gray-900">Manage Press Releases</h3>
                                        <p class="text-sm text-gray-500 mt-1">
                                            Upload, edit, and manage press releases
                                        </p>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <div class="bg-teal-50 text-teal-700 px-3 py-1 rounded-full text-sm font-medium">
                                            Total: {{ presses.length }} Releases
                                        </div>
                                        <Button 
                                            type="primary" 
                                            @click="showCreateModal = true"
                                            class="bg-gradient-to-r from-teal-600 to-emerald-600 hover:from-teal-700 hover:to-emerald-700"
                                        >
                                            <PlusOutlined />
                                            Upload Press Release
                                        </Button>
                                    </div>
                                </div>

                                <!-- Search and Actions Bar - Search aligned to right -->
                                <div class="flex flex-col sm:flex-row justify-between items-stretch sm:items-center gap-3 mb-6">
                                    <div class="flex-1"></div> <!-- Empty div to push search to right -->
                                    <div class="flex gap-3 items-center">
                                        <!-- Search Input with Icon -->
                                        <div class="relative w-full sm:w-80">
                                            <div class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                                                <SearchOutlined />
                                            </div>
                                            <Input 
                                                v-model:value="searchTerm" 
                                                placeholder="Search by title..."
                                                class="pl-9 pr-10 py-2 rounded-lg border-gray-200 focus:border-teal-500"
                                                allowClear
                                                @input="handleSearch"
                                            />
                                            <div v-if="searchTerm" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-xs">
                                                {{ presses.length }} results
                                            </div>
                                        </div>
                                        
                                        <!-- Refresh Button -->
                                        <Button 
                                            @click="refreshPresses"
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
                                        :dataSource="presses" 
                                        :loading="loading" 
                                        rowKey="id" 
                                        :pagination="{ 
                                            pageSize: 10, 
                                            showSizeChanger: true,
                                            showTotal: (total) => `Total ${total} items`,
                                            size: 'default'
                                        }" 
                                        :columns="columns" 
                                        :scroll="{ x: 900 }"
                                        class="press-table"
                                    >
                                        <template #emptyText>
                                            <div class="py-12 text-center">
                                                <div class="text-6xl mb-4">📄</div>
                                                <p class="text-gray-500 text-lg mb-2">No press releases found</p>
                                                <p class="text-gray-400 text-sm">
                                                    {{ searchTerm ? 'Try adjusting your search criteria' : 'Upload your first press release to get started' }}
                                                </p>
                                                <Button 
                                                    v-if="searchTerm" 
                                                    type="link" 
                                                    @click="searchTerm = ''; fetchPresses()" 
                                                    class="mt-3"
                                                >
                                                    Clear search
                                                </Button>
                                                <Button 
                                                    v-else
                                                    type="primary" 
                                                    @click="showCreateModal = true"
                                                    class="mt-3 bg-gradient-to-r from-teal-600 to-emerald-600"
                                                >
                                                    <PlusOutlined />
                                                    Upload First Press Release
                                                </Button>
                                            </div>
                                        </template>
                                    </a-table>
                                </div>

                                <!-- Mobile Scroll Hint -->
                                <div v-if="presses.length > 0" class="mt-4 text-center text-xs text-gray-400 sm:hidden">
                                    ← Swipe horizontally to see more columns →
                                </div>
                            </a-tab-pane>
                        </a-tabs>
                    </div>
                    
                    <!-- Modals -->
                    <CreatePress :open="showCreateModal" @update:open="showCreateModal = $event" @created="fetchPresses" />
                    <EditPress :open="showEditModal" :press="selectedPress" @updated="fetchPresses" @update:open="showEditModal = $event" />
                    <ViewPress :open="showViewModal" :press="selectedPress" @update:open="showViewModal = $event" />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
/* Table Styling */
:deep(.ant-table) {
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    border-radius: 0.5rem;
    overflow: hidden;
}

:deep(.ant-table-thead > tr > th) {
    background-color: #f8fafc;
    font-weight: 600;
    color: #334155;
    font-size: 0.875rem;
    border-bottom: 2px solid #e2e8f0;
    padding: 12px 16px;
}

:deep(.ant-table-tbody > tr > td) {
    padding: 12px 16px;
    border-bottom: 1px solid #f1f5f9;
}

:deep(.ant-table-tbody > tr:hover > td) {
    background-color: #f1f5f9 !important;
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

/* Modal styling */
:deep(.ant-modal-header) {
    border-bottom: 1px solid #e2e8f0;
    border-radius: 8px 8px 0 0;
}

:deep(.ant-modal-footer) {
    border-top: 1px solid #e2e8f0;
    padding: 16px 24px;
}

/* Button styling */
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
}
</style>



<!-- <script setup>
import { ref, onMounted, h, computed } from 'vue';
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import CreatePress from './CreatePress.vue';
import EditPress from './EditPress.vue';
import ViewPress from './ViewPress.vue';
import { Tabs, Table, Button, Modal, Input, Select, Tag, message, Popconfirm } from 'ant-design-vue';
import axios from 'axios';
import { debounce } from 'lodash';

const activeTab = ref('press');

const presses = ref([]);
const showCreateModal = ref(false);
const showEditModal = ref(false);
const showViewModal = ref(false);
const selectedPress = ref(null);
const searchTerm = ref('');
const loading = ref(false);

const fetchPresses = async () => {
    loading.value = true;
    try {
        const res = await axios.get('/presses', {
            params: {
                search: searchTerm.value
            }
        });
        presses.value = res.data.presses;
    } catch (error) {
        console.error(error);
        message.error('Failed to fetch press releases');
    } finally {
        loading.value = false;
    }
};

const handleSearch = debounce(() => {
    fetchPresses();
}, 500);

const deletePress = async (id) => {
    try {
        await axios.delete(`/presses/${id}`);

        message.success('Deleted successfully');

        fetchPresses(); 
    } catch (error) {
        console.error(error);
        message.error('Failed to delete record');
    }
};

const columns = [
    { title: 'Title', dataIndex: 'title' },
    { title: 'Date', dataIndex: 'date' },
    {
        title: 'Summary',
        customRender: ({ record }) =>
            record.summary.length > 50
                ? record.summary.substring(0, 50) + '...'
                : record.summary
    },
    {
        title: 'PDF',
        customRender: ({ record }) =>
            h('a', { href: `/storage/${record.pdf}`, target: '_blank' }, 'View PDF')
    },
    {
    title: 'Actions',
    customRender: ({ record }) => {
        return h('div', { class: 'flex gap-2' }, [

            h(Button, {
                size: 'small',
                onClick: () => {
                    selectedPress.value = record;
                    showViewModal.value = true;
                }
            }, 'View'),

            h(Button, {
                size: 'small',
                onClick: () => {
                    selectedPress.value = record;
                    showEditModal.value = true;
                }
            }, 'Edit'),

            h('a', {
                href: `/presses/download/${record.id}`
                }, [
                    h(Button, { size: 'small' }, 'Download')
            ]),

            h(Popconfirm, {
                title: 'Are you sure you want to delete this record?',
                onConfirm: () => deletePress(record.id)
            }, {
                default: () =>
                    h(Button, { danger: true, size: 'small' }, 'Delete')
            })
        ]);
    }
}
];

onMounted(fetchPresses);


</script>

<template>

    <Head title="Press" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Press Releases
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
                <div class="bg-white p-4 shadow sm:rounded-lg sm:p-8">

                    <div class="p-6">
                        <a-tabs v-model:activeKey="activeTab">
                           
                            <a-tab-pane key="press" tab="Press Management">
                               <div class="flex justify-between items-center mb-4">
                                    <h3 class="text-lg font-medium">Manage Press Releases</h3>
                                    <div class="flex gap-2">
                                        <Button type="primary" @click="showCreateModal = true">
                                            Upload Press Release
                                        </Button>
                                    </div>
                                </div>
                               <div class="flex justify-between items-center mb-4">
                                    <Input v-model:value="searchTerm" placeholder="Search by title..."
                                        style="width: 300px" allowClear @input="handleSearch" />
                                </div>

                              <a-table :dataSource="presses" :loading="loading" rowKey="id" :pagination="{
                                    pageSize: 10,
                                    showSizeChanger: true,
                                    showTotal: (total) => `Total ${total} items`,
                                    size: 'default'
                                }" :columns="columns" :scroll="{ x: 768 }" class="contacts-table">
      
                            <template #emptyText>
                                <div class="py-12 text-center">
                                    <div class="text-6xl mb-4">📭</div>
                                    <p class="text-gray-500 text-lg mb-2">No messages found</p>
                                    <p class="text-gray-400 text-sm">
                                        {{ searchTerm ? 'Try adjusting your search criteria' : 'Contact messages will appear here' }}
                                    </p>
                                    <Button v-if="searchTerm" type="link"
                                        @click="searchTerm = ''; fetchContacts()" class="mt-3"> Clear search
                                    </Button>
                                </div>
                            </template>
                        </a-table>
                            </a-tab-pane>
                        </a-tabs>
                    </div>
                    
                  
                  <CreatePress :open="showCreateModal" @update:open="showCreateModal = $event"
                        @created="fetchPresses" />

                
                    <EditPress :open="showEditModal" :press="selectedPress" @updated="fetchPresses"
                        @update:open="showEditModal = $event" />

                  
                    <ViewPress :open="showViewModal" :press="selectedPress" @update:open="showViewModal = $event" />
                </div>
            </div>
        </div>

    </AuthenticatedLayout>
</template>

<style scoped>
:deep(.ant-table) {

    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

:deep(.ant-table-thead > tr > th) {
    background-color: #f8fafc;
    font-weight: 600;
    color: #334155;
}

:deep(.ant-table-tbody > tr:hover > td) {
    background-color: #f1f5f9 !important;
}


:deep(.ant-tabs-card > .ant-tabs-nav .ant-tabs-tab) {
    background: #f8fafc;
    border-color: #e2e8f0;
    font-weight: 500;
}

:deep(.ant-tabs-card > .ant-tabs-nav .ant-tabs-tab-active) {
    background: #ffffff;
    border-bottom-color: #ffffff;
    color: #a515dd;
}


:deep(.ant-modal-header) {
    border-bottom: 1px solid #e2e8f0;
}

:deep(.ant-modal-footer) {
    border-top: 1px solid #e2e8f0;
    padding: 16px 24px;
}


:deep(.ant-btn-sm) {
    font-size: 12px;
    padding: 0 8px;
    height: 24px;
}
</style> -->