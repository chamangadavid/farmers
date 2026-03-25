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
            disabled: record.name === 'Super Admin',
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

const openEditRoleModal = (role) => {
    roleForm.value = {
        id: role.id,
        name: role.name,
        permissions: role.permissions.map(p => p.name)
    };
    isEditingRole.value = true;
    showRoleModal.value = true;
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

        <div class="py-12">
            <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
                <div class="bg-white p-4 shadow sm:rounded-lg sm:p-8">

                    <div class="p-6">
                        <a-tabs v-model:activeKey="activeTab">
                            <!-- Roles Tab -->
                            <a-tab-pane key="roles" tab="Frequent Ask Question">
                                <div class="flex justify-between items-center mb-4">
                                    <h3 class="text-lg font-medium">Manage FAQs</h3>
                                    <div class="flex gap-2">
                                        <Button type="primary">
                                            Add FAQs
                                        </Button>
                                    </div>
                                </div>

                                <a-table :dataSource="roles" :columns="[
                                    {
                                        title: 'fullname',
                                        dataIndex: 'name',
                                        sorter: (a, b) => a.name.localeCompare(b.name),
                                        width: '20%'
                                    },
                                    {
                                        title: 'Position',
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
                                ]" :rowSelection="rowSelection" rowKey="id"
                                    :pagination="{ pageSize: 10, showSizeChanger: true }" size="small" />
                            </a-tab-pane>
                        </a-tabs>
                    </div>

                    <!-- Create/Edit Role Modal -->
                    <a-modal v-model:open="showRoleModal" :title="isEditingRole ? 'Edit Role' : 'Create New Role'"
                        @ok="handleAddRole" :ok-text="isEditingRole ? 'Update' : 'Create'" cancel-text="Cancel"
                        :maskClosable="false">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Role Name</label>
                                <a-input v-model:value="roleForm.name" placeholder="Enter role name" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Permissions</label>
                                <a-select v-model:value="roleForm.permissions" mode="multiple"
                                    placeholder="Select permissions" style="width: 100%" :options="permissions.map(p => ({
                                        label: `${p.name} (${p.guard_name})`,
                                        value: p.name
                                    }))" />
                            </div>
                        </div>
                    </a-modal>

                    <!-- Create Permission Modal -->
                    <a-modal v-model:open="showPermissionModal" title="Create New Permission" @ok="handleAddPermission"
                        ok-text="Create" cancel-text="Cancel" :maskClosable="false">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Permission Name</label>
                                <a-input v-model:value="permissionForm.name" placeholder="Enter permission name" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Guard Name</label>
                                <a-select v-model:value="permissionForm.guard_name" style="width: 100%" :options="[
                                    { label: 'Web', value: 'web' },
                                    { label: 'API', value: 'api' },
                                    { label: 'Admin', value: 'admin' }
                                ]" />
                            </div>
                        </div>
                    </a-modal>

                    <!-- Assign Roles Modal -->
                    <a-modal v-model:open="showUserRoleModal" :title="`Assign Roles to ${userForm.userName}`"
                        @ok="handleAssignRoles" ok-text="Assign" cancel-text="Cancel" :maskClosable="false">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Select Roles</label>
                            <a-select v-model:value="userForm.roles" mode="multiple" placeholder="Select roles"
                                style="width: 100%" :options="roles.map(r => ({
                                    label: r.name,
                                    value: r.name
                                }))" />
                        </div>
                    </a-modal>

                </div>

            </div>
        </div>



    </AuthenticatedLayout>
</template>

<style scoped>
/* Custom table styling */
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