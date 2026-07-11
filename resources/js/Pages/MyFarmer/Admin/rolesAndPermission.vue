<!-- resources/js/Pages/MyAAIB/Admin/rolesAndPermission.vue -->
<script setup>
import { ref, onMounted, h, computed } from 'vue';
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Tabs, Table, Button, Modal, Input, Select, Tag, message, Popconfirm, Tooltip, Card } from 'ant-design-vue';
import { 
  SafetyOutlined, 
  PlusOutlined, 
  EditOutlined, 
  DeleteOutlined, 
  TeamOutlined,
  KeyOutlined,
  UserOutlined,
  ReloadOutlined,
  CheckCircleOutlined,
  CloseCircleOutlined
} from '@ant-design/icons-vue';
import axios from 'axios';

// State management
const activeTab = ref('roles');

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
const loading = ref(false);

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

// Color mapping for guard names
const guardColors = {
    web: 'teal',
    api: 'green',
    admin: 'purple',
    default: 'gray'
};

// Stats
const stats = computed(() => ({
    totalRoles: roles.value.length,
    totalPermissions: permissions.value.length,
    totalUsers: users.value.length,
    adminRoles: roles.value.filter(r => r.name.includes('Admin')).length
}));

// Row selection configuration
const rowSelection = computed(() => {
    return {
        selectedRowKeys: selectedRoleIds.value,
        onChange: (selectedRowKeys) => {
            selectedRoleIds.value = selectedRowKeys;
        },
        getCheckboxProps: (record) => ({
            disabled: record.name === 'Super Admin',
            name: record.name,
        }),
    };
});

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

// Role actions
const handleAddRole = async () => {
    try {
        const url = isEditingRole.value ? `/roles/${roleForm.value.id}` : '/roles';
        const method = isEditingRole.value ? 'put' : 'post';

        await axios[method](url, {
            name: roleForm.value.name,
            permissions: roleForm.value.permissions
        });

        message.success(`Role ${isEditingRole.value ? 'updated' : 'created'} successfully`);
        showRoleModal.value = false;
        roleForm.value = { id: null, name: '', permissions: [] };
        isEditingRole.value = false;
        fetchData();
    } catch (error) {
        message.error(error.response?.data?.message || `Failed to ${isEditingRole.value ? 'update' : 'create'} role`);
    }
};

const openEditRoleModal = (role) => {
    roleForm.value = {
        id: role.id,
        name: role.name,
        permissions: role.permissions.map(p => p.name)
    };
    isEditingRole.value = true;
    showRoleModal.value = true;
};

const deleteRole = async (roleId) => {
    try {
        await axios.delete(`/roles/${roleId}`);
        message.success('Role deleted successfully');
        fetchData();
    } catch (error) {
        message.error(error.response?.data?.message || 'Failed to delete role');
    }
};

const deleteSelectedRoles = async () => {
    if (selectedRoleIds.value.length === 0) {
        message.warning('Please select at least one role to delete');
        return;
    }

    try {
        await axios.post('/roles/bulk-delete', { ids: selectedRoleIds.value });
        message.success(`Deleted ${selectedRoleIds.value.length} roles successfully`);
        selectedRoleIds.value = [];
        fetchData();
    } catch (error) {
        message.error(error.response?.data?.message || 'Failed to delete selected roles');
    }
};

// Permission actions
const handleAddPermission = async () => {
    try {
        await axios.post('/permissions', {
            name: permissionForm.value.name,
            guard_name: permissionForm.value.guard_name
        });
        message.success('Permission created successfully');
        showPermissionModal.value = false;
        permissionForm.value = { name: '', guard_name: 'web' };
        fetchData();
    } catch (error) {
        message.error(error.response?.data?.message || 'Failed to create permission');
    }
};

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

// Role table columns
const roleColumns = [
    {
        title: 'Role Name',
        dataIndex: 'name',
        sorter: (a, b) => a.name.localeCompare(b.name),
        width: '20%',
        customRender: ({ record }) => h('div', { class: 'flex items-center gap-2' }, [
            h(SafetyOutlined, { class: 'text-teal-600' }),
            h('span', { class: 'font-medium text-gray-900' }, record.name)
        ])
    },
    {
        title: 'Permissions',
        dataIndex: 'permissions',
        customRender: ({ text }) => {
            if (!text || text.length === 0) {
                return h(Tag, { color: 'default' }, 'No permissions');
            }
            return h('div', { class: 'flex flex-wrap gap-1' },
                text.map(p => h(Tag, {
                    color: guardColors[p.guard_name] || guardColors.default,
                    class: 'permission-tag'
                }, p.name))
            );
        }
    },
    {
        title: 'Actions',
        customRender: ({ record }) => {
            const actions = [];
            
            actions.push(h(Tooltip, { title: 'Edit Role' }, {
                default: () => h(Button, {
                  
                    size: 'small',
                    style: {
                        backgroundColor: '#14b8a6', // teal
                        borderColor: '#14b8a6',
                        color: '#fff'
                    },
                    onClick: () => openEditRoleModal(record),
                    class: 'edit-btn',
                    icon: h(EditOutlined)
                }, 'Edit')
            }));
            
            if (record.name !== 'Super Admin') {
                actions.push(h(Popconfirm, {
                    title: 'Delete Role',
                    description: `Are you sure you want to delete "${record.name}"? This action cannot be undone.`,
                    onConfirm: () => deleteRole(record.id),
                    okText: 'Yes, Delete',
                    cancelText: 'Cancel',
                    okType: 'danger'
                }, {
                    default: () => h(Tooltip, { title: 'Delete Role' }, {
                        default: () => h(Button, {
                            type: 'primary',
                            danger: true,
                            size: 'small',
                            class: 'delete-btn',
                            icon: h(DeleteOutlined)
                        }, 'Delete')
                    })
                }));
            }
            
            return h('div', { class: 'flex gap-2' }, actions);
        },
        width: '150px',
        align: 'center'
    }
];

// Permission table columns
const permissionColumns = [
    {
        title: 'Permission Name',
        dataIndex: 'name',
        sorter: (a, b) => a.name.localeCompare(b.name),
        width: '45%',
        customRender: ({ record }) => h('div', { class: 'flex items-center gap-2' }, [
            h(KeyOutlined, { class: 'text-teal-600' }),
            h('span', { class: 'font-mono text-sm text-gray-900' }, record.name)
        ])
    },
    {
        title: 'Guard',
        dataIndex: 'guard_name',
        customRender: ({ text }) => h(Tag, {
            color: guardColors[text] || guardColors.default,
            class: 'guard-tag'
        }, text.toUpperCase()),
        width: '20%'
    },
    {
        title: 'Created At',
        dataIndex: 'created_at',
        sorter: (a, b) => new Date(a.created_at) - new Date(b.created_at),
        customRender: ({ text }) => text ? new Date(text).toLocaleDateString() : 'N/A',
        width: '35%'
    }
];

// User table columns
const userColumns = [
    {
        title: 'Name',
        dataIndex: 'name',
        sorter: (a, b) => a.name.localeCompare(b.name),
        width: '25%',
        customRender: ({ record }) => h('div', { class: 'flex items-center gap-2' }, [
            h(UserOutlined, { class: 'text-teal-600' }),
            h('span', { class: 'font-medium text-gray-900' }, record.name)
        ])
    },
    {
        title: 'Email',
        dataIndex: 'email',
        sorter: (a, b) => a.email.localeCompare(b.email),
        width: '30%'
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
            return h(Tooltip, { title: 'Assign Roles' }, {
                default: () => h(Button, {
               
                    size: 'small',
                    style: {
                        backgroundColor: '#14b8a6', // teal
                        borderColor: '#14b8a6',
                        color: '#fff'
                    },
                    onClick: () => openUserRoleModal(record),
                    class: 'assign-btn',
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
    <Head title="Roles & Permissions" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800 dark:text-white">
                        Roles & Permissions
                    </h2>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                        Manage user roles, permissions, and access control
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
                                <p class="text-sm text-teal-600 dark:text-teal-400 font-medium">Total Roles</p>
                                <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ stats.totalRoles }}</p>
                            </div>
                            <div class="w-10 h-10 bg-teal-100 dark:bg-teal-900/50 rounded-full flex items-center justify-center">
                                <SafetyOutlined class="text-teal-600 dark:text-teal-400 text-xl" />
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
                                <KeyOutlined class="text-teal-600 dark:text-teal-400 text-xl" />
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gradient-to-br from-teal-50 to-emerald-50 dark:from-teal-950/30 dark:to-emerald-950/30 rounded-xl p-4 border border-teal-100 dark:border-teal-800">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-teal-600 dark:text-teal-400 font-medium">Total Users</p>
                                <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ stats.totalUsers }}</p>
                            </div>
                            <div class="w-10 h-10 bg-teal-100 dark:bg-teal-900/50 rounded-full flex items-center justify-center">
                                <TeamOutlined class="text-teal-600 dark:text-teal-400 text-xl" />
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gradient-to-br from-teal-50 to-emerald-50 dark:from-teal-950/30 dark:to-emerald-950/30 rounded-xl p-4 border border-teal-100 dark:border-teal-800">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-teal-600 dark:text-teal-400 font-medium">Admin Roles</p>
                                <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ stats.adminRoles }}</p>
                            </div>
                            <div class="w-10 h-10 bg-teal-100 dark:bg-teal-900/50 rounded-full flex items-center justify-center">
                                <CheckCircleOutlined class="text-teal-600 dark:text-teal-400 text-xl" />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main Card -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-xl border border-gray-100 dark:border-gray-700">
                    <div class="p-6">
                        <!-- Tabs -->
                        <a-tabs v-model:activeKey="activeTab" class="custom-tabs">
                            <!-- Roles Tab -->
                            <a-tab-pane key="roles">
                                <template #tab>
                                    <div class="flex items-center gap-2">
                                        <SafetyOutlined class="text-teal-500" />
                                        <span>Roles</span>
                                        <Tag color="teal" class="ml-2">{{ roles.length }}</Tag>
                                    </div>
                                </template>
                                
                                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6 pb-4 border-b border-gray-200 dark:border-gray-700">
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Manage Roles</h3>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Create, edit, and manage user roles with permissions</p>
                                    </div>
                                    <div class="flex gap-2">
                                        <Button 
                                            danger 
                                            :disabled="selectedRoleIds.length === 0"
                                            @click="deleteSelectedRoles"
                                            class="border-red-600 text-red-600 hover:bg-red-50"
                                        >
                                            <DeleteOutlined class="mr-1" />
                                            Delete Selected ({{ selectedRoleIds.length }})
                                        </Button>
                                        <Button 
                                            type="primary" 
                                            @click="showRoleModal = true; isEditingRole = false;"
                                            class="bg-teal-600 hover:bg-teal-700 border-teal-600"
                                        >
                                            <PlusOutlined class="mr-1" />
                                            Create Role
                                        </Button>
                                    </div>
                                </div>

                                <!-- Roles Table -->
                                <div class="table-container">
                                    <a-table 
                                        :dataSource="roles" 
                                        :columns="roleColumns" 
                                        :rowSelection="rowSelection"
                                        :loading="loading"
                                        rowKey="id"
                                        :pagination="{ 
                                            pageSize: 10, 
                                            showSizeChanger: true, 
                                            showTotal: (total) => `Total ${total} roles`,
                                            pageSizeOptions: ['10', '20', '50']
                                        }" 
                                        :scroll="{ x: 800 }"
                                        class="roles-table"
                                    >
                                        <template #emptyText>
                                            <div class="py-16 text-center">
                                                <div class="text-6xl mb-4">🔐</div>
                                                <p class="text-gray-500 dark:text-gray-400 text-lg font-medium">No roles found</p>
                                                <p class="text-gray-400 dark:text-gray-500 text-sm mt-2">Click "Create Role" to add your first role</p>
                                            </div>
                                        </template>
                                    </a-table>
                                </div>
                            </a-tab-pane>

                            <!-- Permissions Tab -->
                            <a-tab-pane key="permissions">
                                <template #tab>
                                    <div class="flex items-center gap-2">
                                        <KeyOutlined class="text-teal-500" />
                                        <span>Permissions</span>
                                        <Tag color="teal" class="ml-2">{{ permissions.length }}</Tag>
                                    </div>
                                </template>
                                
                                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6 pb-4 border-b border-gray-200 dark:border-gray-700">
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Manage Permissions</h3>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Create and manage system permissions</p>
                                    </div>
                                    <Button 
                                        type="primary" 
                                        @click="showPermissionModal = true"
                                        class="bg-teal-600 hover:bg-teal-700 border-teal-600"
                                    >
                                        <PlusOutlined class="mr-1" />
                                        Create Permission
                                    </Button>
                                </div>

                                <!-- Permissions Table -->
                                <div class="table-container">
                                    <a-table 
                                        :dataSource="permissions" 
                                        :columns="permissionColumns"
                                        :loading="loading"
                                        rowKey="id"
                                        :pagination="{ 
                                            pageSize: 10, 
                                            showSizeChanger: true, 
                                            showTotal: (total) => `Total ${total} permissions`,
                                            pageSizeOptions: ['10', '20', '50']
                                        }" 
                                        :scroll="{ x: 600 }"
                                        class="permissions-table"
                                    >
                                        <template #emptyText>
                                            <div class="py-16 text-center">
                                                <div class="text-6xl mb-4">🔑</div>
                                                <p class="text-gray-500 dark:text-gray-400 text-lg font-medium">No permissions found</p>
                                                <p class="text-gray-400 dark:text-gray-500 text-sm mt-2">Click "Create Permission" to add your first permission</p>
                                            </div>
                                        </template>
                                    </a-table>
                                </div>
                            </a-tab-pane>

                            <!-- Users Tab -->
                            <a-tab-pane key="users">
                                <template #tab>
                                    <div class="flex items-center gap-2">
                                        <TeamOutlined class="text-teal-500" />
                                        <span>Users</span>
                                        <Tag color="teal" class="ml-2">{{ users.length }}</Tag>
                                    </div>
                                </template>
                                
                                <div class="mb-6 pb-4 border-b border-gray-200 dark:border-gray-700">
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Manage User Roles</h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Assign and manage roles for system users</p>
                                </div>

                                <!-- Users Table -->
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
                                            pageSizeOptions: ['10', '20', '50']
                                        }" 
                                        :scroll="{ x: 800 }"
                                        class="users-table"
                                    >
                                        <template #emptyText>
                                            <div class="py-16 text-center">
                                                <div class="text-6xl mb-4">👥</div>
                                                <p class="text-gray-500 dark:text-gray-400 text-lg font-medium">No users found</p>
                                                <p class="text-gray-400 dark:text-gray-500 text-sm mt-2">Users will appear here once registered</p>
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

        <!-- Create/Edit Role Modal -->
        <a-modal 
            v-model:open="showRoleModal" 
            :title="isEditingRole ? 'Edit Role' : 'Create New Role'"
            @ok="handleAddRole" 
            :ok-text="isEditingRole ? 'Update Role' : 'Create Role'" 
            cancel-text="Cancel"
            :maskClosable="false"
            width="600px"
            class="custom-modal"
        >
            <div class="space-y-5 py-4">
                <div class="bg-gradient-to-r from-teal-50 to-emerald-50 dark:from-teal-950/30 dark:to-emerald-950/30 rounded-lg p-4 border border-teal-200 dark:border-teal-800">
                    <div class="flex items-center gap-2">
                        <SafetyOutlined class="text-teal-600 text-lg" />
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Role Information</span>
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Role Name <span class="text-red-500">*</span>
                    </label>
                    <a-input 
                        v-model:value="roleForm.name" 
                        placeholder="Enter role name (e.g., Editor, Viewer)"
                        size="large"
                        class="rounded-lg"
                    />
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Permissions</label>
                    <a-select 
                        v-model:value="roleForm.permissions" 
                        mode="multiple"
                        placeholder="Select permissions for this role"
                        style="width: 100%" 
                        :options="permissions.map(p => ({
                            label: `${p.name} (${p.guard_name})`,
                            value: p.name
                        }))"
                        size="large"
                        class="rounded-lg"
                    />
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Select one or more permissions to assign to this role</p>
                </div>
            </div>
        </a-modal>

        <!-- Create Permission Modal -->
        <a-modal 
            v-model:open="showPermissionModal" 
            title="Create New Permission"
            @ok="handleAddPermission" 
            ok-text="Create Permission" 
            cancel-text="Cancel"
            :maskClosable="false"
            width="500px"
            class="custom-modal"
        >
            <div class="space-y-5 py-4">
                <div class="bg-gradient-to-r from-teal-50 to-emerald-50 dark:from-teal-950/30 dark:to-emerald-950/30 rounded-lg p-4 border border-teal-200 dark:border-teal-800">
                    <div class="flex items-center gap-2">
                        <KeyOutlined class="text-teal-600 text-lg" />
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Permission Details</span>
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Permission Name <span class="text-red-500">*</span>
                    </label>
                    <a-input 
                        v-model:value="permissionForm.name" 
                        placeholder="Enter permission name (e.g., edit articles)"
                        size="large"
                        class="rounded-lg"
                    />
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Use format: action-resource (e.g., create-users, edit-settings)</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Guard Name</label>
                    <a-select 
                        v-model:value="permissionForm.guard_name" 
                        style="width: 100%" 
                        :options="[
                            { label: 'Web', value: 'web' },
                            { label: 'API', value: 'api' },
                            { label: 'Admin', value: 'admin' }
                        ]"
                        size="large"
                        class="rounded-lg"
                    />
                </div>
            </div>
        </a-modal>

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
.roles-table :deep(.ant-table),
.permissions-table :deep(.ant-table),
.users-table :deep(.ant-table) {
    font-family: inherit;
    border-radius: 12px;
    overflow: hidden;
    min-width: 600px;
}

.roles-table :deep(.ant-table-thead > tr > th),
.permissions-table :deep(.ant-table-thead > tr > th),
.users-table :deep(.ant-table-thead > tr > th) {
    background: #f8fafc;
    font-weight: 600;
    color: #1e293b;
    border-bottom: 2px solid #e2e8f0;
    padding: 14px 16px;
    font-size: 0.875rem;
}

.dark .roles-table :deep(.ant-table-thead > tr > th),
.dark .permissions-table :deep(.ant-table-thead > tr > th),
.dark .users-table :deep(.ant-table-thead > tr > th) {
    background: #1f2937;
    color: #f3f4f6;
    border-bottom-color: #374151;
}

.roles-table :deep(.ant-table-tbody > tr > td),
.permissions-table :deep(.ant-table-tbody > tr > td),
.users-table :deep(.ant-table-tbody > tr > td) {
    padding: 14px 16px;
    border-bottom: 1px solid #f1f5f9;
}

.dark .roles-table :deep(.ant-table-tbody > tr > td),
.dark .permissions-table :deep(.ant-table-tbody > tr > td),
.dark .users-table :deep(.ant-table-tbody > tr > td) {
    border-bottom-color: #374151;
}

.roles-table :deep(.ant-table-tbody > tr:hover > td),
.permissions-table :deep(.ant-table-tbody > tr:hover > td),
.users-table :deep(.ant-table-tbody > tr:hover > td) {
    background: #fafbff;
}

.dark .roles-table :deep(.ant-table-tbody > tr:hover > td),
.dark .permissions-table :deep(.ant-table-tbody > tr:hover > td),
.dark .users-table :deep(.ant-table-tbody > tr:hover > td) {
    background: #1f2937;
}

/* Tag Styling */
.permission-tag,
.guard-tag,
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

:deep(.ant-tag-green) {
    background: #dcfce7;
    color: #166534;
}

:deep(.ant-tag-purple) {
    background: #f3e8ff;
    color: #6b21a5;
}

/* Button Styling */
.edit-btn,
.delete-btn,
.assign-btn {
    transition: all 0.3s ease;
}

.edit-btn:hover,
.assign-btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(20, 184, 166, 0.2);
}

.delete-btn:hover {
    transform: translateY(-1px);
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

.dark .custom-modal :deep(.ant-modal-header) {
    background: #1f2937;
    border-bottom-color: #374151;
}

.custom-modal :deep(.ant-modal-title) {
    font-size: 1.25rem;
    font-weight: 600;
    color: #1f2937;
}

.dark .custom-modal :deep(.ant-modal-title) {
    color: #f3f4f6;
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

/* Input and Select Styling */
:deep(.ant-input),
:deep(.ant-input-password),
:deep(.ant-select-selector) {
    border-radius: 8px;
    border-color: #e2e8f0;
}

:deep(.ant-input:hover),
:deep(.ant-select-selector:hover) {
    border-color: #14b8a6;
}

:deep(.ant-input:focus),
:deep(.ant-select-focused .ant-select-selector) {
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
:deep(.ant-pagination) {
    margin-top: 24px;
}

:deep(.ant-pagination-item-active) {
    border-color: #14b8a6;
    background: #14b8a6;
}

:deep(.ant-pagination-item-active a) {
    color: white;
}

:deep(.ant-pagination-item:hover) {
    border-color: #14b8a6;
}

:deep(.ant-pagination-item:hover a) {
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
    
    .custom-tabs :deep(.ant-tabs-tab) {
        padding: 8px 12px;
        font-size: 0.85rem;
    }
    
    :deep(.ant-table) {
        font-size: 12px;
    }
    
    :deep(.ant-table-thead > tr > th),
    :deep(.ant-table-tbody > tr > td) {
        padding: 8px 12px;
    }
}

/* Dark mode support */
.dark .bg-white {
    background: #1f2937;
}

.dark .text-gray-900 {
    color: #f3f4f6;
}

.dark .border-gray-200 {
    border-color: #374151;
}

.dark .bg-gray-50 {
    background: #374151;
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

.dark .ant-modal-content {
    background: #1f2937;
}

.dark .ant-modal-close {
    color: #f3f4f6;
}

.dark .ant-modal-close:hover {
    color: #14b8a6;
}
</style>