<script setup>
import { ref, onMounted, h, computed } from 'vue';
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Table, Button, Popconfirm, message, Input, Select, Tag, Space, Card } from 'ant-design-vue';
import {
    SearchOutlined,
    PlusOutlined,
    EditOutlined,
    DeleteOutlined,
    EyeOutlined,
    ReloadOutlined,
    FilterOutlined,
    FolderOutlined
} from '@ant-design/icons-vue';
import axios from 'axios';
import { debounce } from 'lodash';

import CreateFAQs from '@/Components/Faqs/CreateFAQs.vue';
import EditFAQs from '@/Components/Faqs/EditFAQs.vue';
import ViewFAQs from '@/Components/Faqs/ViewFAQs.vue';

const faqs = ref([]);
const loading = ref(false);
const searchTerm = ref('');
const selectedCategory = ref('');
const showCreateModal = ref(false);
const showEditModal = ref(false);
const showViewModal = ref(false);
const selectedFaq = ref(null);

// Category options with icons
const categories = [
    { value: '', label: 'All Categories', icon: '📋' },
    { value: 'General Information', label: 'General Information', icon: '📚', color: 'blue' },
    { value: 'Report Accidents', label: 'Report Accidents', icon: '🚨', color: 'red' },
    { value: 'Incident', label: 'Incident', icon: '⚠️', color: 'orange' },
    { value: 'Investigations', label: 'Investigations', icon: '🔍', color: 'purple' },
    { value: 'Reports & Publications', label: 'Reports & Publications', icon: '📊', color: 'green' }
];

const fetchFaqs = async () => {
    loading.value = true;
    try {
        const res = await axios.get('/all-faqs', {
            params: {
                search: {
                    category: selectedCategory.value,
                    query: searchTerm.value
                }
            }
        });
        faqs.value = res.data.faqs;
    } catch (error) {
        console.error(error);
        message.error('Failed to fetch FAQs');
    } finally {
        loading.value = false;
    }
};

const handleSearch = debounce(() => {
    fetchFaqs();
}, 500);

const deleteFaq = async (id) => {
    try {
        await axios.delete(`/faqs/${id}`);
        message.success('FAQ deleted successfully');
        fetchFaqs();
    } catch (error) {
        console.error(error);
        message.error('Failed to delete FAQ');
    }
};

const refreshFaqs = () => {
    searchTerm.value = '';
    selectedCategory.value = '';
    fetchFaqs();
    message.success('FAQs refreshed');
};

// Get category color
const getCategoryColor = (category) => {
    const cat = categories.find(c => c.value === category);
    return cat?.color || 'teal';
};

const columns = [
    {
        title: 'Category',
        dataIndex: 'category',
        key: 'category',
        width: '15%',
        customRender: ({ record }) => {
            const cat = categories.find(c => c.value === record.category);
            return h(Tag, {
                color: getCategoryColor(record.category),
                class: 'px-3 py-1 rounded-full'
            }, [
                h('span', { class: 'mr-1' }, cat?.icon || '📄'),
                record.category
            ]);
        }
    },
    {
        title: 'Question',
        dataIndex: 'question',
        key: 'question',
        width: '30%',
        ellipsis: true,
        customRender: ({ record }) => {
            const text = record.question.length > 30
                ? record.question.substring(0, 30) + '...'
                : record.question;
            return h('div', {
                class: 'font-medium text-gray-800',
                title: record.question
            }, text);
        }
    },
    {
        title: 'Answer',
        dataIndex: 'answer',
        key: 'answer',
        width: '35%',
        ellipsis: true,
        customRender: ({ record }) => {
            // Strip HTML tags for display
            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = record.answer;
            const textContent = tempDiv.textContent || tempDiv.innerText || '';
            const text = textContent.length > 30
                ? textContent.substring(0, 30) + '...'
                : textContent;
            return h('div', {
                class: 'text-gray-600',
                title: textContent
            }, text);
        }
    },
    {
        title: 'Actions',
        key: 'actions',
        width: '15%',
        align: 'center',
        customRender: ({ record }) =>
            h(Space, { size: 'small' }, [
                h(Button, {
                    type: 'link',
                    size: 'small',
                    onClick: () => {
                        selectedFaq.value = record;
                        showViewModal.value = true;
                    },
                    title: 'View Details'
                }, 'View'),
                h(Button, {
                    type: 'link',
                    size: 'small',
                    onClick: () => {
                        selectedFaq.value = record;
                        showEditModal.value = true;
                    },
                    title: 'Edit FAQ'
                }, 'Edit'),
                h(Popconfirm, {
                    title: 'Are you sure you want to delete this FAQ?',
                    onConfirm: () => deleteFaq(record.id),
                    okText: 'Yes',
                    cancelText: 'No',
                    okType: 'danger'
                }, {
                    default: () => h(Button, {
                        type: 'link',
                        danger: true,
                        size: 'small',
                        title: 'Delete FAQ'
                    }, 'Delete')
                })
            ])
    }
];

onMounted(() => fetchFaqs());
</script>

<template>
    <Head title="FAQs" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Frequently Asked Questions
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="bg-white p-4 shadow sm:rounded-lg sm:p-8">
                    <!-- Header Section -->
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">Manage FAQs</h3>
                            <p class="text-sm text-gray-500 mt-1">
                                Create, edit, and manage frequently asked questions
                            </p>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="bg-teal-50 text-teal-700 px-3 py-1 rounded-full text-sm font-medium">
                                Total: {{ faqs.length }} FAQs
                            </div>
                            <Button 
                                type="primary" 
                                @click="showCreateModal = true"
                                class="bg-gradient-to-r from-teal-600 to-emerald-600 hover:from-teal-700 hover:to-emerald-700"
                            >
                                <PlusOutlined />
                                Create FAQ
                            </Button>
                        </div>
                    </div>

                    <!-- Search and Filter Bar -->
                    <div class="flex flex-col sm:flex-row justify-between items-stretch sm:items-center gap-3 mb-6">
                        <!-- Category Filter -->
                        <div class="relative w-full sm:w-64">
                            <div class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                                <FolderOutlined />
                            </div>
                            <Select
                                v-model:value="selectedCategory"
                                @change="fetchFaqs"
                                placeholder="Filter by category"
                                class="category-filter"
                                style="width: 100%"
                                allowClear
                            >
                                <Select.Option v-for="cat in categories" :key="cat.value" :value="cat.value">
                                    <div class="flex items-center gap-2">
                                        <span>{{ cat.icon }}</span>
                                        <span>{{ cat.label }}</span>
                                    </div>
                                </Select.Option>
                            </Select>
                        </div>

                        <!-- Search Input -->
                        <div class="relative flex-1 max-w-md ml-auto">
                            <div class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                                <SearchOutlined />
                            </div>
                            <Input 
                                v-model:value="searchTerm" 
                                placeholder="Search by question or answer..."
                                class="pl-9 pr-10 py-2 rounded-lg"
                                allowClear
                                @input="handleSearch"
                            />
                            <div v-if="searchTerm" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-xs">
                                {{ faqs.length }} results
                            </div>
                        </div>

                        <!-- Refresh Button -->
                        <Button 
                            @click="refreshFaqs"
                            class="border-gray-200 hover:border-teal-500 hover:text-teal-600"
                            :loading="loading"
                        >
                            <ReloadOutlined />
                            Refresh
                        </Button>
                    </div>

                    <!-- Table Container -->
                    <div class="overflow-x-auto">
                        <a-table 
                            :dataSource="faqs" 
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
                            class="faqs-table"
                        >
                            <template #emptyText>
                                <div class="py-12 text-center">
                                    <div class="text-6xl mb-4">📚</div>
                                    <p class="text-gray-500 text-lg mb-2">No FAQs found</p>
                                    <p class="text-gray-400 text-sm">
                                        {{ searchTerm || selectedCategory ? 'Try adjusting your search or filter criteria' : 'Create your first FAQ to get started' }}
                                    </p>
                                    <Button 
                                        v-if="searchTerm || selectedCategory" 
                                        type="link" 
                                        @click="refreshFaqs"
                                        class="mt-3"
                                    >
                                        Clear all filters
                                    </Button>
                                    <Button 
                                        v-else
                                        type="primary" 
                                        @click="showCreateModal = true"
                                        class="mt-3 bg-gradient-to-r from-teal-600 to-emerald-600"
                                    >
                                        <PlusOutlined />
                                        Create First FAQ
                                    </Button>
                                </div>
                            </template>
                        </a-table>
                    </div>

                    <!-- Mobile Scroll Hint -->
                    <div v-if="faqs.length > 0" class="mt-4 text-center text-xs text-gray-400 sm:hidden">
                        ← Swipe horizontally to see more columns →
                    </div>
                </div>
            </div>
        </div>

        <!-- Modals -->
        <CreateFAQs v-model:open="showCreateModal" @created="fetchFaqs" />
        <EditFAQs v-model:open="showEditModal" :faq="selectedFaq" @updated="fetchFaqs" />
        <ViewFAQs v-model:open="showViewModal" :faq="selectedFaq" />
    </AuthenticatedLayout>
</template>

<style scoped>
/* Category Filter Styling */
:deep(.category-filter .ant-select-selector) {
    border-radius: 0.5rem;
    border: 1px solid #e2e8f0;
    transition: all 0.3s ease;
    height: 40px;
    padding: 0 12px;
}

:deep(.category-filter:hover .ant-select-selector) {
    border-color: #14b8a6;
}

:deep(.category-filter.ant-select-focused .ant-select-selector) {
    border-color: #14b8a6;
    box-shadow: 0 0 0 2px rgba(20, 184, 166, 0.1);
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

/* Button Styling */
:deep(.ant-btn) {
    border-radius: 0.5rem;
    font-weight: 500;
    transition: all 0.3s ease;
}

:deep(.ant-btn-link) {
    padding: 4px 12px;
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

/* Tag Styling */
:deep(.ant-tag) {
    border-radius: 1rem;
    padding: 4px 12px;
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
        padding: 2px 8px;
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
import { Table, Button, Popconfirm, message, Input } from 'ant-design-vue';
import axios from 'axios';
import { debounce } from 'lodash';

import CreateFAQs from '@/Components/Faqs/CreateFAQs.vue';
import EditFAQs from '@/Components/Faqs/EditFAQs.vue';
import ViewFAQs from '@/Components/Faqs/ViewFAQs.vue';

const faqs = ref([]);
const loading = ref(false);
const searchTerm = ref('');
const selectedCategory = ref('');
const showCreateModal = ref(false);
const showEditModal = ref(false);
const showViewModal = ref(false);
const selectedFaq = ref(null);

const fetchFaqs = async () => {
    loading.value = true;
    try {
        const res = await axios.get('/all-faqs', {
            params: {
                search: {
                    category: selectedCategory.value,
                    query: searchTerm.value
                }
            }
        });
        faqs.value = res.data.faqs;
    } catch (error) {
        console.error(error);
        message.error('Failed to fetch FAQs');
    } finally {
        loading.value = false;
    }
};

const handleSearch = debounce(() => {
    fetchFaqs();
}, 500);

const deleteFaq = async (id) => {
    try {
        await axios.delete(`/faqs/${id}`);
        message.success('FAQ deleted successfully');
        fetchFaqs(searchTerm.value);
    } catch (error) {
        console.error(error);
        message.error('Failed to delete FAQ');
    }
};

const columns = [
    { 
        title: 'Category', 
        dataIndex: 'category', 
        key: 'category' 
    },
    { 
        title: 'Question', 
        dataIndex: 'question', 
        key: 'question',
        customRender: ({ record }) => {
            return record.question.length > 25
                ? record.question.substring(0, 25) + '...'
                : record.question;
        }
    },
    { 
        title: 'Answer', 
        dataIndex: 'answer', 
        key: 'answer',
        customRender: ({ record }) => {
            return record.answer.length > 40
                ? record.answer.substring(0, 40) + '...'
                : record.answer;
        }
    },
    {
        title: 'Actions',
        key: 'actions',
        customRender: ({ record }) =>
            h('div', { class: 'flex gap-2' }, [
                h(Button, { type: 'primary', size: 'small', onClick: () => { selectedFaq.value = record; showViewModal.value = true; } }, 'View'),
                h(Button, { type: 'default', size: 'small', onClick: () => { selectedFaq.value = record; showEditModal.value = true; } }, 'Edit'),
                h(Popconfirm, { title: 'Delete FAQ?', onConfirm: () => deleteFaq(record.id), okText: 'Yes', cancelText: 'No' }, { default: () => h(Button, { type: 'primary', danger: true, size: 'small' }, 'Delete') })
            ])
    }
];

onMounted(() => fetchFaqs());
</script>

<template>

    <Head title="FAQs" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">FAQs</h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="bg-white p-4 shadow sm:rounded-lg sm:p-8">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium">Manage FAQs</h3>
                        <Button type="primary" @click="showCreateModal = true">Create FAQ</Button>
                    </div>

                    <div class="flex gap-2 mb-4">
                        <select v-model="selectedCategory" @change="fetchFaqs" class="border rounded px-2 py-1">
                            <option value="">All Categories</option>
                            <option value="General Information">General Information</option>
                            <option value="Report Accidents">Report Accidents</option>
                            <option value="Incident">Incident</option>
                            <option value="Investigations">Investigations</option>
                            <option value="Reports & Publications">Reports & Publications</option>
                        </select>
                    </div>

                    <div class="flex justify-between items-center mb-4">
                        <Input v-model:value="searchTerm" placeholder="Search FAQs..." style="width: 300px" allowClear
                            @input="handleSearch" />
                    </div>

                    <a-table :dataSource="faqs" :loading="loading" rowKey="id" :pagination="{ pageSize: 10 }"
                        :columns="columns" />
                </div>
            </div>
        </div>

        <CreateFAQs v-model:open="showCreateModal" @created="fetchFaqs" />
        <EditFAQs v-model:open="showEditModal" :faq="selectedFaq" @updated="fetchFaqs" />
        <ViewFAQs v-model:open="showViewModal" :faq="selectedFaq" />
    </AuthenticatedLayout>
</template> -->