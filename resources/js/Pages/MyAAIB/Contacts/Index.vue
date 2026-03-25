<!-- resources/js/Pages/MyAAIB/Contacts/Index.vue -->
<script setup>
import { ref, onMounted, h } from 'vue';
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Table, Button, Popconfirm, message, Input, Tag, Space, Card } from 'ant-design-vue';
import { SearchOutlined, DeleteOutlined, EyeOutlined, ReloadOutlined } from '@ant-design/icons-vue';
import ViewContactModal from '@/Components/Contact/ViewContactModal.vue';
import axios from 'axios';
import { debounce } from 'lodash';

// State
const contacts = ref([]);
const loading = ref(false);
const showContactModal = ref(false);
const selectedContact = ref(null);
const searchTerm = ref('');

// Fetch contacts (with optional search)
const fetchContacts = async (search = '') => {
    loading.value = true;
    try {
        const res = await axios.get('/contacts', {
            params: { search }
        });
        contacts.value = res.data.contacts;
    } catch (error) {
        console.error(error);
        message.error('Failed to fetch contacts');
    } finally {
        loading.value = false;
    }
};

// Debounced search
const handleSearch = debounce(() => {
    fetchContacts(searchTerm.value);
}, 500);

// Delete contact
const deleteContact = async (id) => {
    try {
        await axios.delete(`/contacts/${id}`);
        message.success('Contact deleted successfully');
        fetchContacts(searchTerm.value);
    } catch (error) {
        console.error(error);
        message.error('Failed to delete contact');
    }
};

// View contact
const viewContact = (contact) => {
    selectedContact.value = contact;
    showContactModal.value = true;
};

// Refresh contacts
const refreshContacts = () => {
    searchTerm.value = '';
    fetchContacts('');
    message.success('Contacts refreshed');
};

// Get subject color based on type
const getSubjectColor = (subject) => {
    const subjectLower = subject?.toLowerCase() || '';
    if (subjectLower.includes('urgent') || subjectLower.includes('emergency')) return 'red';
    if (subjectLower.includes('complaint')) return 'orange';
    if (subjectLower.includes('inquiry') || subjectLower.includes('question')) return 'blue';
    if (subjectLower.includes('feedback')) return 'green';
    return 'teal';
};

// Format date for display
const formatDate = (date) => {
    if (!date) return 'N/A';
    const d = new Date(date);
    return d.toLocaleDateString('en-US', {
        month: 'short',
        day: 'numeric',
        year: 'numeric'
    });
};

onMounted(() => fetchContacts());

const columns = [
    {
        title: 'Full Name',
        dataIndex: 'name',
        key: 'name',
        sorter: (a, b) => a.name.localeCompare(b.name),
        width: '20%'
    },
    {
        title: 'Email',
        dataIndex: 'email',
        key: 'email',
        width: '25%'
    },
    {
        title: 'Subject',
        dataIndex: 'subject',
        key: 'subject',
        width: '20%',
        customRender: ({ text }) => {
            return h(Tag, {
                color: getSubjectColor(text)
            }, text);
        }
    },
    {
        title: 'Date',
        dataIndex: 'created_at',
        key: 'created_at',
        width: '15%',
        customRender: ({ text }) => formatDate(text)
    },
    {
        title: 'Actions',
        key: 'actions',
        width: '15%',
        align: 'center',
        customRender: ({ record }) =>
            h(Space, { size: 'small' }, [
                h(
                    Button,
                    {
                        type: 'link',
                        size: 'small',
                        onClick: () => viewContact(record),
                        title: 'View Details'
                    },
                    'View'
                ),
                h(
                    Popconfirm,
                    {
                        title: 'Are you sure to delete this contact?',
                        onConfirm: () => deleteContact(record.id),
                        okText: 'Yes',
                        cancelText: 'No',
                        okType: 'danger'
                    },
                    {
                        default: () =>
                            h(
                                Button,
                                {
                                    type: 'link',
                                    danger: true,
                                    size: 'small',

                                },
                                'Delete'
                            )
                    }
                )
            ])
    }
];
</script>

<template>
    <Head title="Contact Messages" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Contact Messages
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="bg-white p-4 shadow sm:rounded-lg sm:p-8">
                    <!-- Header Section -->
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">All Contact Messages</h3>
                            <p class="text-sm text-gray-500 mt-1">
                                Manage and respond to customer inquiries
                            </p>
                        </div>
                        
                        <!-- Stats Badge -->
                        <div class="flex items-center gap-3">
                            <div class="bg-teal-50 text-teal-700 px-3 py-1 rounded-full text-sm font-medium">
                                Total: {{ contacts.length }} messages
                            </div>
                        </div>
                    </div>

                    <!-- Search and Actions Bar -->
                    <div class="flex flex-col sm:flex-row justify-end items-stretch sm:items-center gap-3 mb-6">
                        <!-- Search Input with Icon -->
                        <div class="relative w-full sm:w-80">
                            <div class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                                <SearchOutlined />
                            </div>
                            <Input 
                                v-model:value="searchTerm" 
                                placeholder="Search by name, email, or subject..."
                                class="pl-9 pr-10 py-2 rounded-lg border-gray-200 focus:border-teal-500 focus:ring-teal-500"
                                allowClear 
                                @input="handleSearch"
                                :loading="loading"
                            />
                            <div v-if="searchTerm" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-xs">
                                {{ contacts.length }} results
                            </div>
                        </div>
                        
                        <!-- Refresh Button -->
                        <Button 
                            @click="refreshContacts"
                            class="border-gray-200 hover:border-teal-500 hover:text-teal-600"
                            :loading="loading"
                        >
                            <ReloadOutlined />
                            Refresh
                        </Button>
                    </div>

                    <!-- Table Container with Horizontal Scroll for Mobile -->
                    <div class="overflow-x-auto">
                        <a-table 
                            :dataSource="contacts" 
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
                                    <p class="text-gray-500 text-lg mb-2">No messages found</p>
                                    <p class="text-gray-400 text-sm">
                                        {{ searchTerm ? 'Try adjusting your search criteria' : 'Contact messages will appear here' }}
                                    </p>
                                    <Button 
                                        v-if="searchTerm" 
                                        type="link" 
                                        @click="searchTerm = ''; fetchContacts('')"
                                        class="mt-3"
                                    >
                                        Clear search
                                    </Button>
                                </div>
                            </template>
                        </a-table>
                    </div>

                    <!-- Mobile Card View Hint -->
                    <div v-if="contacts.length > 0" class="mt-4 text-center text-xs text-gray-400 sm:hidden">
                        ← Swipe horizontally to see more columns →
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <ViewContactModal 
            :visible="showContactModal" 
            :contact="selectedContact"
            @update:visible="showContactModal = $event" 
        />
    </AuthenticatedLayout>
</template>

<style scoped>
/* Professional Search Input Styling */
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

:deep(.ant-input) {
    font-size: 0.875rem;
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

/* Empty State Styling */
:deep(.ant-empty) {
    margin: 40px 0;
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

/* Animation for refresh button */
:deep(.ant-btn) {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

:deep(.ant-btn:active) {
    transform: scale(0.98);
}

/* Custom scrollbar for table */
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

/* Stats badge styling */
.bg-teal-50 {
    background-color: #f0fdfa;
    border: 1px solid #99f6e4;
}

/* Search input custom styling */
input::placeholder {
    color: #9ca3af;
    font-size: 0.8125rem;
}
</style>



<!-- <script setup>
import { ref, onMounted, h } from 'vue';
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Table, Button, Popconfirm, message, Input } from 'ant-design-vue';
import ViewContactModal from '@/Components/Contact/ViewContactModal.vue';
import axios from 'axios';
import { debounce } from 'lodash';

const contacts = ref([]);
const loading = ref(false);
const showContactModal = ref(false);
const selectedContact = ref(null);
const searchTerm = ref('');

const fetchContacts = async (search = '') => {
    loading.value = true;
    try {
        const res = await axios.get('/contacts', {
            params: { search } 
        });
        contacts.value = res.data.contacts;
    } catch (error) {
        console.error(error);
        message.error('Failed to fetch contacts');
    } finally {
        loading.value = false;
    }
};


const handleSearch = debounce(() => {
    fetchContacts(searchTerm.value);
}, 500);


const deleteContact = async (id) => {
    try {
        await axios.delete(`/contacts/${id}`);
        message.success('Contact deleted successfully');
        fetchContacts(searchTerm.value); 
    } catch (error) {
        console.error(error);
        message.error('Failed to delete contact');
    }
};

const viewContact = (contact) => {
    selectedContact.value = contact;
    showContactModal.value = true;
};

onMounted(() => fetchContacts());

const columns = [
    { title: 'Full Name', dataIndex: 'name', key: 'name' },
    { title: 'Email', dataIndex: 'email', key: 'email' },
    { title: 'Subject', dataIndex: 'subject', key: 'subject' },
    {
        title: 'Actions',
        key: 'actions',
        customRender: ({ record }) =>
            h('div', { class: 'flex gap-2' }, [
                h(
                    Button,
                    {
                        type: 'primary',
                        size: 'small',
                        onClick: () => viewContact(record)
                    },
                    'View'
                ),
                h(
                    Popconfirm,
                    {
                        title: 'Are you sure to delete this contact?',
                        onConfirm: () => deleteContact(record.id),
                        okText: 'Yes',
                        cancelText: 'No'
                    },
                    {
                        default: () =>
                            h(
                                Button,
                                {
                                    type: 'primary',
                                    danger: true,
                                    size: 'small'
                                },
                                'Delete'
                            )
                    }
                )
            ])
    }
];

</script>

<template>

    <Head title="Contact Messages" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Contact Messages
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="bg-white p-4 shadow sm:rounded-lg sm:p-8">
                    <h3 class="text-lg font-medium mb-4">All Contact Messages</h3>

                    <div class="flex justify-between items-center mb-4">
                        <Input v-model:value="searchTerm" placeholder="Search by name, email, or subject..."
                            style="width: 300px" allowClear @input="handleSearch" />
                    </div>

                    <a-table :dataSource="contacts" :loading="loading" rowKey="id" :pagination="{ pageSize: 10 }"
                        :columns="columns" />
                </div>
            </div>
        </div>

        <ViewContactModal :visible="showContactModal" :contact="selectedContact"
            @update:visible="showContactModal = $event" />
    </AuthenticatedLayout>
</template> -->