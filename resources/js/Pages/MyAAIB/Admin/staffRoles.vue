<script setup>
import { ref, onMounted, h, computed } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Tabs, Table, Button, Modal, Input, Select, Tag, message, Popconfirm, Tooltip, Card } from 'ant-design-vue';
import { 
  UserOutlined, 
  TeamOutlined, 
  PlusOutlined, 
  EditOutlined, 
  DeleteOutlined,
  MailOutlined,
  LockOutlined,
  SafetyOutlined,
  ReloadOutlined
} from '@ant-design/icons-vue';
import axios from 'axios';

// State management
const activeTab = ref('users');

// Roles state
const roles = ref([]);
const selectedRoleIds = ref([]);
const roleForm = ref({
    id: null,
    name: '',
    permissions: []
});
const showRoleModal = ref(false);
const isEditingRole = ref(false);

// Create Staff Modal
const showCreateUserModal = ref(false)

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
})

// Permissions state
const permissions = ref([]);
const permissionForm = ref({
    name: '',
    guard_name: 'web'
});
const showPermissionModal = ref(false);

// Users state
const users = ref([]);
const userForm = ref({
    userId: null,
    userName: '',
    roles: []
});
const showUserRoleModal = ref(false);
const loading = ref(false);

// Fetch data
const fetchData = async () => {
    loading.value = true;
    try {
        const [rolesRes, permissionsRes, usersRes] = await Promise.all([
            axios.get('/roles'),
            axios.get('/permissions'),
            axios.get('/users')
        ]);

        roles.value = rolesRes.data.roles || [];
        permissions.value = permissionsRes.data.permissions || [];
        users.value = usersRes.data.users || [];
        selectedRoleIds.value = [];
    } catch (error) {
        message.error('Failed to fetch data');
        console.error(error);
    } finally {
        loading.value = false;
    }
};

onMounted(fetchData);

// User role assignment
const openUserRoleModal = (user) => {
    userForm.value = {
        userId: user.id,
        userName: user.name,
        roles: user.roles.map(role => role.name)
    };
    showUserRoleModal.value = true;
};

const handleAssignRoles = async () => {
    try {
        await axios.post('/users/assign-role', {
            user_id: userForm.value.userId,
            roles: userForm.value.roles
        });
        message.success('Roles assigned successfully');
        showUserRoleModal.value = false;
        fetchData();
    } catch (error) {
        message.error(error.response?.data?.message || 'Failed to assign roles');
    }
};

const handleCreateUser = async () => {
    try {
        const res = await axios.post('/add-users', {
            name: form.name,
            email: form.email,
            password: form.password,
            password_confirmation: form.password_confirmation,
        })

        message.success(res.data.message)
        showCreateUserModal.value = false
        form.reset()
        fetchData()
    } catch (error) {
        if (error.response?.status === 422) {
            form.errors = error.response.data.errors
        } else {
            message.error('Failed to create user')
        }
    }
}

// Stats
const stats = computed(() => ({
    totalUsers: users.value.length,
    totalRoles: roles.value.length,
    totalPermissions: permissions.value.length,
    activeUsers: users.value.filter(u => u.status !== 'inactive').length
}));

// User table columns
const userColumns = [
    {
        title: 'ID',
        dataIndex: 'id',
        key: 'id',
        width: 70,
        align: 'center'
    },
    {
        title: 'Name',
        dataIndex: 'name',
        sorter: (a, b) => a.name.localeCompare(b.name),
        width: '20%'
    },
    {
        title: 'Email',
        dataIndex: 'email',
        sorter: (a, b) => a.email.localeCompare(b.email),
        width: '25%'
    },
    {
        title: 'Roles',
        dataIndex: 'roles',
        customRender: ({ text }) => h('div', { class: 'flex flex-wrap gap-1' },
            text.length > 0
                ? text.map(r => h(Tag, { color: 'teal', class: 'role-tag' }, r.name))
                : h(Tag, { color: 'default', class: 'role-tag' }, 'No roles assigned')),
        width: '30%'
    },
    {
        title: 'Actions',
        customRender: ({ record }) => {
            return  h(Tooltip, { title: 'Assign Roles' }, {
                default: () => h(Button, {
                   
                    size: 'small',
                     style: {
                            backgroundColor: '#14b8a6', // teal
                            borderColor: '#14b8a6',
                            color: '#fff'
                        },
                    onClick: () => openUserRoleModal(record),
                    class: 'assign-roles-btn',
                    icon: h(TeamOutlined)
                }, 'Assign Roles')
            });
        },
        width: '15%',
        align: 'center'
    }
];
</script>

<template>
    <Head title="Staff Register" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800 dark:text-white">
                        Staff Management
                    </h2>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                        Manage users, roles, and permissions
                    </p>
                </div>
                <div class="flex gap-3">
                    <Tooltip title="Refresh Data">
                        <Button 
                            @click="fetchData" 
                            :loading="loading"
                            class="border-teal-600 text-teal-600 hover:bg-teal-50"
                        >
                            <ReloadOutlined />
                            Refresh
                        </Button>
                    </Tooltip>
                </div>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
                <!-- Stats Cards -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="bg-gradient-to-br from-teal-50 to-emerald-50 dark:from-teal-950/30 dark:to-emerald-950/30 rounded-xl p-4 border border-teal-100 dark:border-teal-800">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-teal-600 dark:text-teal-400 font-medium">Total Users</p>
                                <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ stats.totalUsers }}</p>
                            </div>
                            <div class="w-10 h-10 bg-teal-100 dark:bg-teal-900/50 rounded-full flex items-center justify-center">
                                <UserOutlined class="text-teal-600 dark:text-teal-400 text-xl" />
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gradient-to-br from-teal-50 to-emerald-50 dark:from-teal-950/30 dark:to-emerald-950/30 rounded-xl p-4 border border-teal-100 dark:border-teal-800">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-teal-600 dark:text-teal-400 font-medium">Total Roles</p>
                                <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ stats.totalRoles }}</p>
                            </div>
                            <div class="w-10 h-10 bg-teal-100 dark:bg-teal-900/50 rounded-full flex items-center justify-center">
                                <TeamOutlined class="text-teal-600 dark:text-teal-400 text-xl" />
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gradient-to-br from-teal-50 to-emerald-50 dark:from-teal-950/30 dark:to-emerald-950/30 rounded-xl p-4 border border-teal-100 dark:border-teal-800">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-teal-600 dark:text-teal-400 font-medium">Permissions</p>
                                <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ stats.totalPermissions }}</p>
                            </div>
                            <div class="w-10 h-10 bg-teal-100 dark:bg-teal-900/50 rounded-full flex items-center justify-center">
                                <SafetyOutlined class="text-teal-600 dark:text-teal-400 text-xl" />
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gradient-to-br from-teal-50 to-emerald-50 dark:from-teal-950/30 dark:to-emerald-950/30 rounded-xl p-4 border border-teal-100 dark:border-teal-800">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-teal-600 dark:text-teal-400 font-medium">Active Users</p>
                                <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ stats.activeUsers }}</p>
                            </div>
                            <div class="w-10 h-10 bg-teal-100 dark:bg-teal-900/50 rounded-full flex items-center justify-center">
                                <UserOutlined class="text-teal-600 dark:text-teal-400 text-xl" />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main Card -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-xl border border-gray-100 dark:border-gray-700">
                    <div class="p-6">
                        <!-- Tabs -->
                        <a-tabs v-model:activeKey="activeTab" class="custom-tabs">
                            <!-- Users Tab -->
                            <a-tab-pane key="users">
                                <template #tab>
                                    <div class="flex items-center gap-2">
                                        <UserOutlined class="text-teal-500" />
                                        <span>Users</span>
                                    </div>
                                </template>
                                
                                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6 pb-4 border-b border-gray-200 dark:border-gray-700">
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Manage Staff Users</h3>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Assign roles and manage user permissions</p>
                                    </div>
                                    <Button 
                                        type="primary" 
                                        @click="showCreateUserModal = true"
                                        class="bg-teal-600 hover:bg-teal-700 border-teal-600 shadow-sm"
                                    >
                                        <PlusOutlined class="mr-2" />
                                        Add Staff
                                    </Button>
                                </div>

                                <!-- Users Table - Responsive -->
                                <div class="table-container">
                                    <a-table 
                                        :dataSource="users" 
                                        :columns="userColumns" 
                                        :loading="loading"
                                        rowKey="id" 
                                        :pagination="{ 
                                            pageSize: 10, 
                                            showSizeChanger: true, 
                                            showTotal: (total) => `Total ${total} users`,
                                            pageSizeOptions: ['10', '20', '50'],
                                            showQuickJumper: true
                                        }" 
                                        :scroll="{ x: 800 }"
                                        class="users-table"
                                    >
                                        <template #emptyText>
                                            <div class="py-16 text-center">
                                                <div class="text-6xl mb-4">👥</div>
                                                <p class="text-gray-500 dark:text-gray-400 text-lg font-medium">No users found</p>
                                                <p class="text-gray-400 dark:text-gray-500 text-sm mt-2">Click "Add Staff" to create your first user</p>
                                            </div>
                                        </template>
                                    </a-table>
                                </div>
                            </a-tab-pane>
                        </a-tabs>
                    </div>
                </div>
            </div>
        </div>

        <!-- Assign Roles Modal -->
        <a-modal 
            v-model:open="showUserRoleModal" 
            :title="`Assign Roles to ${userForm.userName}`"
            @ok="handleAssignRoles" 
            ok-text="Assign Roles" 
            cancel-text="Cancel" 
            :maskClosable="false"
            width="500px"
            class="custom-modal"
        >
            <div class="space-y-4 py-4">
                <div class="bg-teal-50 dark:bg-teal-950/30 rounded-lg p-4 border border-teal-200 dark:border-teal-800">
                    <div class="flex items-center gap-2 mb-2">
                        <TeamOutlined class="text-teal-600" />
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Role Assignment</span>
                    </div>
                    <p class="text-xs text-gray-600 dark:text-gray-400">Select one or more roles for this user</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Select Roles</label>
                    <a-select 
                        v-model:value="userForm.roles" 
                        mode="multiple" 
                        placeholder="Select roles"
                        style="width: 100%" 
                        :options="roles.map(r => ({
                            label: r.name,
                            value: r.name
                        }))" 
                        class="role-select"
                    />
                </div>
            </div>
        </a-modal>

        <!-- Create Staff Modal -->
        <a-modal
            v-model:open="showCreateUserModal"
            title="Create Staff User"
            @ok="handleCreateUser"
            ok-text="Create User"
            cancel-text="Cancel"
            :confirm-loading="form.processing"
            :maskClosable="false"
            width="550px"
            class="custom-modal"
        >
            <div class="space-y-5 py-4">
                <div class="bg-gradient-to-r from-teal-50 to-emerald-50 dark:from-teal-950/30 dark:to-emerald-950/30 rounded-lg p-4 border border-teal-200 dark:border-teal-800 mb-4">
                    <div class="flex items-center gap-2">
                        <UserOutlined class="text-teal-600 text-lg" />
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Staff Information</span>
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Full Name <span class="text-red-500">*</span>
                    </label>
                    <a-input 
                        v-model:value="form.name" 
                        placeholder="Enter full name"
                        size="large"
                        class="rounded-lg"
                    />
                    <div v-if="form.errors.name" class="text-red-500 text-sm mt-1">
                        {{ form.errors.name }}
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Email Address <span class="text-red-500">*</span>
                    </label>
                    <a-input 
                        v-model:value="form.email" 
                        placeholder="Enter email address"
                        size="large"
                        class="rounded-lg"
                    />
                    <div v-if="form.errors.email" class="text-red-500 text-sm mt-1">
                        {{ form.errors.email }}
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Password <span class="text-red-500">*</span>
                    </label>
                    <a-input-password 
                        v-model:value="form.password" 
                        placeholder="Enter password"
                        size="large"
                        class="rounded-lg"
                    />
                    <div v-if="form.errors.password" class="text-red-500 text-sm mt-1">
                        {{ form.errors.password }}
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Confirm Password <span class="text-red-500">*</span>
                    </label>
                    <a-input-password 
                        v-model:value="form.password_confirmation" 
                        placeholder="Confirm password"
                        size="large"
                        class="rounded-lg"
                    />
                </div>
            </div>
        </a-modal>
    </AuthenticatedLayout>
</template>

<style scoped>
/* Table Container for responsive scrolling */
.table-container {
    width: 100%;
    overflow-x: auto;
    position: relative;
}

/* Custom Table Styling */
.users-table :deep(.ant-table) {
    font-family: inherit;
    border-radius: 12px;
    overflow: hidden;
    min-width: 600px;
}

.users-table :deep(.ant-table-thead > tr > th) {
    background: #f8fafc;
    font-weight: 600;
    color: #1e293b;
    border-bottom: 2px solid #e2e8f0;
    padding: 14px 16px;
    font-size: 0.875rem;
}

.dark .users-table :deep(.ant-table-thead > tr > th) {
    background: #1f2937;
    color: #f3f4f6;
    border-bottom-color: #374151;
}

.users-table :deep(.ant-table-tbody > tr > td) {
    padding: 14px 16px;
    border-bottom: 1px solid #f1f5f9;
}

.dark .users-table :deep(.ant-table-tbody > tr > td) {
    border-bottom-color: #374151;
}

.users-table :deep(.ant-table-tbody > tr:hover > td) {
    background: #fafbff;
}

.dark .users-table :deep(.ant-table-tbody > tr:hover > td) {
    background: #1f2937;
}

/* Role Tag Styling */
.role-tag {
    border-radius: 6px;
    padding: 2px 10px;
    font-weight: 500;
    border: none;
    margin: 2px;
}

:deep(.ant-tag-teal) {
    background: #ccfbf1;
    color: #0f766e;
}

/* Assign Roles Button */
.assign-roles-btn {
    background: linear-gradient(135deg, #14b8a6, #10b981);
    border: none;
    transition: all 0.3s ease;
}

.assign-roles-btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(20, 184, 166, 0.3);
}

/* Tabs Styling */
.custom-tabs :deep(.ant-tabs-nav) {
    margin-bottom: 24px;
}

.custom-tabs :deep(.ant-tabs-tab) {
    padding: 12px 20px;
    font-size: 0.95rem;
    font-weight: 500;
    transition: all 0.3s;
}

.custom-tabs :deep(.ant-tabs-tab-active) {
    color: #14b8a6;
}

.custom-tabs :deep(.ant-tabs-tab-active .ant-tabs-tab-btn) {
    color: #14b8a6;
    font-weight: 600;
}

.custom-tabs :deep(.ant-tabs-ink-bar) {
    background: linear-gradient(90deg, #14b8a6, #10b981);
    height: 3px;
    border-radius: 3px;
}

/* Modal Styling */
.custom-modal :deep(.ant-modal-header) {
    border-bottom: 2px solid #e2e8f0;
    padding: 20px 24px;
    background: linear-gradient(135deg, #f8fafc, #ffffff);
}

.custom-modal :deep(.ant-modal-title) {
    font-size: 1.25rem;
    font-weight: 600;
    color: #1f2937;
}

.custom-modal :deep(.ant-modal-footer) {
    border-top: 1px solid #e2e8f0;
    padding: 16px 24px;
}

.custom-modal :deep(.ant-btn-primary) {
    background: #14b8a6;
    border-color: #14b8a6;
}

.custom-modal :deep(.ant-btn-primary:hover) {
    background: #0d9488;
    border-color: #0d9488;
}

/* Select Dropdown Styling */
.role-select :deep(.ant-select-selector) {
    border-radius: 8px;
    border-color: #e2e8f0;
}

.role-select :deep(.ant-select-selector:hover) {
    border-color: #14b8a6;
}

.role-select :deep(.ant-select-focused) {
    border-color: #14b8a6;
    box-shadow: 0 0 0 2px rgba(20, 184, 166, 0.1);
}

/* Input Styling */
:deep(.ant-input),
:deep(.ant-input-password) {
    border-radius: 8px;
    border-color: #e2e8f0;
}

:deep(.ant-input:hover),
:deep(.ant-input-password:hover) {
    border-color: #14b8a6;
}

:deep(.ant-input:focus),
:deep(.ant-input-password:focus) {
    border-color: #14b8a6;
    box-shadow: 0 0 0 2px rgba(20, 184, 166, 0.1);
}

/* Stats Cards Animation */
.bg-gradient-to-br {
    transition: all 0.3s ease;
}

.bg-gradient-to-br:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

/* Pagination Styling */
.users-table :deep(.ant-pagination) {
    margin-top: 24px;
}

.users-table :deep(.ant-pagination-item-active) {
    border-color: #14b8a6;
    background: #14b8a6;
}

.users-table :deep(.ant-pagination-item-active a) {
    color: white;
}

.users-table :deep(.ant-pagination-item:hover) {
    border-color: #14b8a6;
}

.users-table :deep(.ant-pagination-item:hover a) {
    color: #14b8a6;
}

/* Scrollbar Styling */
.table-container::-webkit-scrollbar {
    height: 8px;
}

.table-container::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 4px;
}

.table-container::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 4px;
}

.table-container::-webkit-scrollbar-thumb:hover {
    background: #14b8a6;
}

/* Responsive Adjustments */
@media (max-width: 768px) {
    .p-6 {
        padding: 1rem;
    }
    
    .users-table :deep(.ant-table) {
        font-size: 12px;
    }
    
    .users-table :deep(.ant-table-thead > tr > th),
    .users-table :deep(.ant-table-tbody > tr > td) {
        padding: 8px 12px;
    }
    
    .assign-roles-btn {
        font-size: 12px;
        padding: 4px 8px;
    }
}

/* Dark mode support */
.dark .users-table :deep(.ant-table) {
    background: #1f2937;
}

.dark .users-table :deep(.ant-table-tbody > tr) {
    background: #1f2937;
}

.dark .custom-modal :deep(.ant-modal-header) {
    background: #1f2937;
    border-bottom-color: #374151;
}

.dark .custom-modal :deep(.ant-modal-title) {
    color: #f3f4f6;
}

.dark .custom-modal :deep(.ant-modal-content) {
    background: #1f2937;
}
</style>

<style>
/* Global dark mode support */
.dark .ant-empty-description {
    color: #9ca3af;
}

.dark .ant-pagination-item {
    background: #374151;
    border-color: #4b5563;
}

.dark .ant-pagination-item a {
    color: #f3f4f6;
}

.dark .ant-pagination-item:hover a {
    color: #14b8a6;
}

.dark .ant-pagination-prev .ant-pagination-item-link,
.dark .ant-pagination-next .ant-pagination-item-link {
    background: #374151;
    border-color: #4b5563;
    color: #f3f4f6;
}

.dark .ant-pagination-options-quick-jumper {
    color: #f3f4f6;
}

.dark .ant-pagination-options-quick-jumper input {
    background: #374151;
    border-color: #4b5563;
    color: #f3f4f6;
}

.dark .ant-select-selector {
    background: #374151 !important;
    border-color: #4b5563 !important;
    color: #f3f4f6 !important;
}

.dark .ant-select-selection-placeholder {
    color: #9ca3af !important;
}
</style>