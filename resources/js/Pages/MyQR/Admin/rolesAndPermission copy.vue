<script setup>
import { ref, onMounted, h, computed } from 'vue';
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Tabs, Table, Button, Modal, Input, Select, Tag, message, Popconfirm } from 'ant-design-vue';
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
    web: 'blue',
    api: 'green',
    admin: 'purple',
    default: 'gray'
};

// Row selection configuration
const rowSelection = computed(() => {
    return {
        selectedRowKeys: selectedRoleIds.value,
        onChange: (selectedRowKeys) => {
            selectedRoleIds.value = selectedRowKeys;
        },
        getCheckboxProps: (record) => ({
            disabled: record.name === 'Super Admin', // Column configuration not to be checked
            name: record.name,
        }),
    };
});

// Fetch data
const fetchData = async () => {
    try {
        const [rolesRes, permissionsRes, usersRes] = await Promise.all([
            axios.get('/roles'),
            axios.get('/permissions'),
            axios.get('/users')
        ]);
        
        roles.value = rolesRes.data.roles || [];
        permissions.value = permissionsRes.data.permissions || [];
        users.value = usersRes.data.users || [];
        selectedRoleIds.value = []; // Clear selection on refresh
    } catch (error) {
        message.error('Failed to fetch data');
        console.error(error);
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
</script>

<template>
    <Head title="Roles & Permissions" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Roles & Permissions
            </h2>
        </template>

        <div class="p-6">
            <a-tabs v-model:activeKey="activeTab" type="card">
                <!-- Roles Tab -->
                <a-tab-pane key="roles" tab="Roles">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium">Manage Roles</h3>
                        <div class="flex gap-2">
                            <Button 
                                type="primary" 
                                danger 
                                :disabled="selectedRoleIds.length === 0"
                                @click="deleteSelectedRoles"
                            >
                                Delete Selected ({{ selectedRoleIds.length }})
                            </Button>
                            <Button type="primary" @click="showRoleModal = true; isEditingRole = false;">
                                Create Role
                            </Button>
                        </div>
                    </div>
                    
                    <a-table 
                        :dataSource="roles" 
                        :columns="[
                            { 
                                title: 'Role Name', 
                                dataIndex: 'name',
                                sorter: (a, b) => a.name.localeCompare(b.name),
                                width: '20%'
                            },
                            { 
                                title: 'Permissions', 
                                dataIndex: 'permissions',
                                customRender: ({ text }) => {
                                    return h('div', { class: 'flex flex-wrap gap-1' }, 
                                        text.map(p => h(Tag, { 
                                            color: guardColors[p.guard_name] || guardColors.default 
                                        }, p.name))
                                    );
                                }
                            },
                            {
                                title: 'Actions',
                                customRender: ({ record }) => {
                                    return h('div', { class: 'flex gap-2' }, [
                                        h(Button, {
                                            type: 'primary',
                                            size: 'small',
                                            onClick: () => openEditRoleModal(record)
                                        }, 'Edit'),
                                        
                                        record.name !== 'Super Admin' && h(Popconfirm, {
                                            title: 'Are you sure to delete this role?',
                                            onConfirm: () => deleteRole(record.id),
                                            okText: 'Yes',
                                            cancelText: 'No'
                                        }, {
                                            default: () => h(Button, {
                                                type: 'primary',
                                                danger: true,
                                                size: 'small'
                                            }, 'Delete')
                                        })
                                    ]);
                                },
                                width: '150px',
                                align: 'center'
                            }
                        ]" 
                        :rowSelection="rowSelection"
                        rowKey="id"
                        :pagination="{ pageSize: 10, showSizeChanger: true }"
                        bordered
                        size="middle"
                    />
                </a-tab-pane>

                <!-- Permissions Tab -->
                <a-tab-pane key="permissions" tab="Permissions">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium">Manage Permissions</h3>
                        <Button type="primary" @click="showPermissionModal = true">Create Permission</Button>
                    </div>
                    
                    <a-table 
                        :dataSource="permissions" 
                        :columns="[
                            { 
                                title: 'Permission Name', 
                                dataIndex: 'name',
                                sorter: (a, b) => a.name.localeCompare(b.name),
                                width: '30%'
                            },
                            { 
                                title: 'Guard', 
                                dataIndex: 'guard_name',
                                customRender: ({ text }) => h(Tag, { 
                                    color: guardColors[text] || guardColors.default 
                                }, text),
                                width: '20%'
                            },
                            { 
                                title: 'Created At', 
                                dataIndex: 'created_at',
                                sorter: (a, b) => new Date(a.created_at) - new Date(b.created_at),
                                width: '25%'
                            },
                           
                        ]" 
                        rowKey="id"
                        :pagination="{ pageSize: 10, showSizeChanger: true }"
                        bordered
                        size="middle"
                    />
                </a-tab-pane>

                <!-- Users Tab -->
                <a-tab-pane key="users" tab="Users">
                    <div class="mb-4">
                        <h3 class="text-lg font-medium">Manage User Roles</h3>
                    </div>
                    
                    <a-table 
                        :dataSource="users" 
                        :columns="[
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
                                        ? text.map(r => h(Tag, { color: 'blue' }, r.name))
                                        : h(Tag, { color: 'gray' }, 'No roles')),
                                width: '30%'
                            },
                            {
                                title: 'Actions',
                                customRender: ({ record }) => {
                                    return h(Button, {
                                        type: 'primary',
                                        size: 'small',
                                        onClick: () => openUserRoleModal(record)
                                    }, 'Assign Roles');
                                },
                                width: '25%',
                                align: 'center'
                            }
                        ]" 
                        rowKey="id"
                        :pagination="{ pageSize: 10, showSizeChanger: true }"
                        bordered
                        size="middle"
                    />
                </a-tab-pane>
            </a-tabs>
        </div>

        <!-- Create/Edit Role Modal -->
        <a-modal 
            v-model:open="showRoleModal" 
            :title="isEditingRole ? 'Edit Role' : 'Create New Role'"
            @ok="handleAddRole"
            :ok-text="isEditingRole ? 'Update' : 'Create'"
            cancel-text="Cancel"
            :maskClosable="false"
        >
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Role Name</label>
                    <a-input 
                        v-model:value="roleForm.name" 
                        placeholder="Enter role name"
                    />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Permissions</label>
                    <a-select
                        v-model:value="roleForm.permissions"
                        mode="multiple"
                        placeholder="Select permissions"
                        style="width: 100%"
                        :options="permissions.map(p => ({ 
                            label: `${p.name} (${p.guard_name})`, 
                            value: p.name 
                        }))"
                    />
                </div>
            </div>
        </a-modal>

        <!-- Create Permission Modal -->
        <a-modal 
            v-model:open="showPermissionModal" 
            title="Create New Permission"
            @ok="handleAddPermission"
            ok-text="Create"
            cancel-text="Cancel"
            :maskClosable="false"
        >
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Permission Name</label>
                    <a-input 
                        v-model:value="permissionForm.name" 
                        placeholder="Enter permission name"
                    />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Guard Name</label>
                    <a-select
                        v-model:value="permissionForm.guard_name"
                        style="width: 100%"
                        :options="[
                            { label: 'Web', value: 'web' },
                            { label: 'API', value: 'api' },
                            { label: 'Admin', value: 'admin' }
                        ]"
                    />
                </div>
            </div>
        </a-modal>

        <!-- Assign Roles Modal -->
        <a-modal 
            v-model:open="showUserRoleModal" 
            :title="`Assign Roles to ${userForm.userName}`"
            @ok="handleAssignRoles"
            ok-text="Assign"
            cancel-text="Cancel"
            :maskClosable="false"
        >
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Select Roles</label>
                <a-select
                    v-model:value="userForm.roles"
                    mode="multiple"
                    placeholder="Select roles"
                    style="width: 100%"
                    :options="roles.map(r => ({ 
                        label: r.name, 
                        value: r.name 
                    }))"
                />
            </div>
        </a-modal>
    </AuthenticatedLayout>
</template>

<style scoped>
/* Custom table styling */
:deep(.ant-table) {
    border-radius: 8px;
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

/* Tab styling */
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

/* Modal styling */
:deep(.ant-modal-header) {
    border-bottom: 1px solid #e2e8f0;
}

:deep(.ant-modal-footer) {
    border-top: 1px solid #e2e8f0;
    padding: 16px 24px;
}

/* Action buttons styling */
:deep(.ant-btn-sm) {
    font-size: 12px;
    padding: 0 8px;
    height: 24px;
}
</style>