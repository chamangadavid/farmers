<script setup>
import { ref, onMounted, h, computed } from 'vue';
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

// Import announcement modals
import CreateAnnouncement from './CreateAnnouncements.vue';
import EditAnnouncement from './EditAnnouncements.vue';
import ViewAnnouncement from './ViewAnnouncements.vue';

import { Tabs, Table, Button, Input, message, Popconfirm, Tag, Space } from 'ant-design-vue';
import { 
  SearchOutlined, 
  PlusOutlined, 
  ReloadOutlined, 
  EyeOutlined, 
  EditOutlined, 
  DeleteOutlined,
  CalendarOutlined,
  FlagOutlined
} from '@ant-design/icons-vue';
import axios from 'axios';
import { debounce } from 'lodash';

// Tabs
const activeTab = ref('announcement');

// State
const announcements = ref([]);
const showCreateModal = ref(false);
const showEditModal = ref(false);
const showViewModal = ref(false);
const selectedAnnouncement = ref(null);
const searchTerm = ref('');
const loading = ref(false);

// Fetch announcements with optional search
const fetchAnnouncements = async () => {
    loading.value = true;
    try {
        const res = await axios.get('/announcements', {
            params: { search: searchTerm.value }
        });
        announcements.value = res.data.announcements;
    } catch (error) {
        console.error(error);
        message.error('Failed to fetch announcements');
    } finally {
        loading.value = false;
    }
};

// Search handler (debounced)
const handleSearch = debounce(() => {
    fetchAnnouncements();
}, 500);

// Refresh announcements
const refreshAnnouncements = () => {
    searchTerm.value = '';
    fetchAnnouncements();
    message.success('Announcements refreshed');
};

// Delete announcement
const deleteAnnouncement = async (id) => {
    try {
        await axios.delete(`/announcements/${id}`);
        message.success('Deleted successfully');
        fetchAnnouncements();
    } catch (error) {
        message.error('Failed to delete announcement');
    }
};

// Get priority color
const getPriorityColor = (priority) => {
    const priorityLower = priority?.toLowerCase() || '';
    if (priorityLower.includes('high') || priorityLower.includes('urgent')) return 'red';
    if (priorityLower.includes('medium')) return 'orange';
    if (priorityLower.includes('low')) return 'green';
    return 'teal';
};

// Get priority icon
const getPriorityIcon = (priority) => {
    const priorityLower = priority?.toLowerCase() || '';
    if (priorityLower.includes('high') || priorityLower.includes('urgent')) return '🔴';
    if (priorityLower.includes('medium')) return '🟠';
    if (priorityLower.includes('low')) return '🟢';
    return '📌';
};

// Format date
const formatDate = (date) => {
    if (!date) return 'N/A';
    return new Date(date).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    });
};

// Table columns with professional styling
const columns = [
    { 
        title: 'Title', 
        dataIndex: 'title',
        width: '25%',
        ellipsis: true
    },
    { 
        title: 'Category', 
        dataIndex: 'category',
        width: '15%',
        customRender: ({ record }) => {
            return h(Tag, { color: 'teal' }, record.category);
        }
    },
    { 
        title: 'Priority', 
        dataIndex: 'priority',
        width: '12%',
        customRender: ({ record }) => {
            return h(Tag, { 
                color: getPriorityColor(record.priority),
                class: 'px-2 py-1'
            }, [
                h('span', { class: 'mr-1' }, getPriorityIcon(record.priority)),
                record.priority
            ]);
        }
    },
    { 
        title: 'Date', 
        dataIndex: 'date',
        width: '12%',
        customRender: ({ record }) => {
            return h('div', { class: 'flex items-center gap-1 text-gray-600' }, [
                formatDate(record.date)
            ]);
        }
    },
    {
        title: 'Summary',
        dataIndex: 'summary',
        width: '28%',
        ellipsis: true,
        customRender: ({ record }) => {
            const text = record.summary.length > 30
                ? record.summary.substring(0, 30) + '...'
                : record.summary;
            return h('div', { 
                class: 'text-gray-600',
                title: record.summary
            }, text);
        }
    },
    {
        title: 'Actions',
        key: 'actions',
        width: '15%',
        align: 'center',
        customRender: ({ record }) => {
            return h(Space, { size: 'small' }, [
                h(Button, {
                    type: 'link',
                    size: 'small',
                    icon: h(EyeOutlined),
                    onClick: () => {
                        selectedAnnouncement.value = record;
                        showViewModal.value = true;
                    },
                    title: 'View Details'
                }, ),
                h(Button, {
                    type: 'link',
                    size: 'small',
                    icon: h(EditOutlined),
                    onClick: () => {
                        selectedAnnouncement.value = record;
                        showEditModal.value = true;
                    },
                    title: 'Edit Announcement'
                }, ),
                h(Popconfirm, {
                    title: 'Are you sure you want to delete this announcement?',
                    onConfirm: () => deleteAnnouncement(record.id),
                    okText: 'Yes',
                    cancelText: 'No',
                    okType: 'danger'
                }, {
                    default: () => h(Button, { 
                        type: 'link', 
                        danger: true, 
                        size: 'small', 
                        icon: h(DeleteOutlined),
                        title: 'Delete Announcement'
                    },)
                })
            ]);
        }
    }
];

// On mount, fetch announcements
onMounted(fetchAnnouncements);
</script>

<template>
    <Head title="Announcements" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Announcements
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
                <div class="bg-white p-4 shadow sm:rounded-lg sm:p-8">
                    <div class="p-6">
                        <a-tabs v-model:activeKey="activeTab">
                            <a-tab-pane key="announcement" tab="Announcements Management">
                                <!-- Header with Stats and Add Button -->
                                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
                                    <div>
                                        <h3 class="text-lg font-medium text-gray-900">Manage Announcements</h3>
                                        <p class="text-sm text-gray-500 mt-1">
                                            Create, edit, and manage official announcements
                                        </p>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <div class="bg-teal-50 text-teal-700 px-3 py-1 rounded-full text-sm font-medium">
                                            Total: {{ announcements.length }} Announcements
                                        </div>
                                        <Button 
                                            type="primary" 
                                            @click="showCreateModal = true"
                                            class="bg-gradient-to-r from-teal-600 to-emerald-600 hover:from-teal-700 hover:to-emerald-700"
                                        >
                                            <PlusOutlined />
                                            Add Announcement
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
                                                {{ announcements.length }} results
                                            </div>
                                        </div>
                                        
                                        <!-- Refresh Button -->
                                        <Button 
                                            @click="refreshAnnouncements"
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
                                        :dataSource="announcements"
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
                                        class="announcements-table"
                                    >
                                        <template #emptyText>
                                            <div class="py-12 text-center">
                                                <div class="text-6xl mb-4">📢</div>
                                                <p class="text-gray-500 text-lg mb-2">No announcements found</p>
                                                <p class="text-gray-400 text-sm">
                                                    {{ searchTerm ? 'Try adjusting your search criteria' : 'Create your first announcement to get started' }}
                                                </p>
                                                <Button 
                                                    v-if="searchTerm" 
                                                    type="link" 
                                                    @click="searchTerm = ''; fetchAnnouncements()" 
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
                                                    Create First Announcement
                                                </Button>
                                            </div>
                                        </template>
                                    </a-table>
                                </div>

                                <!-- Mobile Scroll Hint -->
                                <div v-if="announcements.length > 0" class="mt-4 text-center text-xs text-gray-400 sm:hidden">
                                    ← Swipe horizontally to see more columns →
                                </div>
                            </a-tab-pane>
                        </a-tabs>
                    </div>

                    <!-- Modals -->
                    <CreateAnnouncement :open="showCreateModal" @update:open="showCreateModal = $event"
                        @created="fetchAnnouncements" />

                    <EditAnnouncement :open="showEditModal" :announcement="selectedAnnouncement" @updated="fetchAnnouncements"
                        @update:open="showEditModal = $event" />

                    <ViewAnnouncement :open="showViewModal" :announcement="selectedAnnouncement" 
                        @update:open="showViewModal = $event" />
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


<!-- 
<script setup>
import { ref, onMounted, h } from 'vue';
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import CreateAnnouncement from './CreateAnnouncements.vue';
import EditAnnouncement from './EditAnnouncements.vue';
import ViewAnnouncement from './ViewAnnouncements.vue';

import { Tabs, Table, Button, Input, message, Popconfirm } from 'ant-design-vue';
import axios from 'axios';
import { debounce } from 'lodash';


const activeTab = ref('announcement');
const announcements = ref([]);
const showCreateModal = ref(false);
const showEditModal = ref(false);
const showViewModal = ref(false);
const selectedAnnouncement = ref(null);
const searchTerm = ref('');
const loading = ref(false);

const fetchAnnouncements = async () => {
    loading.value = true;
    try {
        const res = await axios.get('/announcements', {
            params: { search: searchTerm.value }
        });
        announcements.value = res.data.announcements;
    } catch (error) {
        console.error(error);
        message.error('Failed to fetch announcements');
    } finally {
        loading.value = false;
    }
};

const handleSearch = debounce(() => {
    fetchAnnouncements();
}, 500);


const deleteAnnouncement = async (id) => {
    try {
        await axios.delete(`/announcements/${id}`);
        message.success('Deleted successfully');
        fetchAnnouncements();
    } catch (error) {
        message.error('Failed to delete announcement');
    }
};

const columns = [
    { title: 'Title', dataIndex: 'title' },
    { title: 'Category', dataIndex: 'category' },
    { title: 'Priority', dataIndex: 'priority' },
    { title: 'Date', dataIndex: 'date' },
    {
        title: 'Summary',
        customRender: ({ record }) =>
            record.summary.length > 50
                ? record.summary.substring(0, 50) + '...'
                : record.summary
    },
    {
        title: 'Actions',
        customRender: ({ record }) => {
            return h('div', { class: 'flex gap-2' }, [

                h(Button, {
                    size: 'small',
                    onClick: () => {
                        selectedAnnouncement.value = record;
                        showViewModal.value = true;
                    }
                }, 'View'),

                h(Button, {
                    size: 'small',
                    onClick: () => {
                        selectedAnnouncement.value = record;
                        showEditModal.value = true;
                    }
                }, 'Edit'),

                h(Popconfirm, {
                    title: 'Are you sure you want to delete this announcement?',
                    onConfirm: () => deleteAnnouncement(record.id)
                }, {
                    default: () => h(Button, { danger: true, size: 'small' }, 'Delete')
                })
            ]);
        }
    }
];

onMounted(fetchAnnouncements);
</script>

<template>
<Head title="Announcements" />

<AuthenticatedLayout>
    <template #header>
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            Announcements
        </h2>
    </template>

    <div class="py-12">
        <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
            <div class="bg-white p-4 shadow sm:rounded-lg sm:p-8">

                <div class="p-6">
                    <a-tabs v-model:activeKey="activeTab">

                        <a-tab-pane key="announcement" tab="Announcements Management">

                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-medium">Manage Announcements</h3>
                                <div class="flex gap-2">
                                    <Button type="primary" @click="showCreateModal = true">
                                        Add Announcement
                                    </Button>
                                </div>
                            </div>

                            <div class="flex justify-between items-center mb-4">
                                <Input v-model:value="searchTerm" placeholder="Search by title..."
                                    style="width: 300px" allowClear @input="handleSearch" />
                            </div>

                            <a-table
                                :dataSource="announcements"
                                :loading="loading"
                                rowKey="id"
                                :pagination="{
                                    pageSize: 10,
                                    showSizeChanger: true,
                                    showTotal: (total) => `Total ${total} items`,
                                    size: 'default'
                                }"
                                :columns="columns"
                                :scroll="{ x: 768 }"
                                class="contacts-table"
                            >
                                <template #emptyText>
                                    <div class="py-12 text-center">
                                        <div class="text-6xl mb-4">📭</div>
                                        <p class="text-gray-500 text-lg mb-2">No announcements found</p>
                                        <p class="text-gray-400 text-sm">
                                            {{ searchTerm ? 'Try adjusting your search criteria' : 'Announcements will appear here' }}
                                        </p>
                                        <Button v-if="searchTerm" type="link"
                                            @click="searchTerm = ''; fetchAnnouncements()" class="mt-3"> Clear search
                                        </Button>
                                    </div>
                                </template>
                            </a-table>

                        </a-tab-pane>
                    </a-tabs>
                </div>

                <CreateAnnouncement :open="showCreateModal" @update:open="showCreateModal = $event"
                    @created="fetchAnnouncements" />

                <EditAnnouncement :open="showEditModal" :announcement="selectedAnnouncement" @updated="fetchAnnouncements"
                    @update:open="showEditModal = $event" />

                <ViewAnnouncement :open="showViewModal" :announcement="selectedAnnouncement" @update:open="showViewModal = $event" />

            </div>
        </div>
    </div>

</AuthenticatedLayout>
</template> -->