<script setup>
import { ref, onMounted, h, computed } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Tabs, Table, Button, Modal, Input, Select, Tag, message, Popconfirm } from 'ant-design-vue';
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

        fetchData() // refresh table

    } catch (error) {
        if (error.response?.status === 422) {
            form.errors = error.response.data.errors
        } else {
            message.error('Failed to create user')
        }
    }
}

</script>

<template>

    <Head title="Staff Register" />

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
                            <!-- Users Tab -->
                            <a-tab-pane key="users" tab="Users">

                                <div class="flex justify-between items-center mb-4">
    <h3 class="text-lg font-medium">Manage User Roles</h3>

    <a-button type="primary" @click="showCreateUserModal = true">
        Add Staff
    </a-button>
</div>

                                <a-table :dataSource="users" :columns="[
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
                                ]" rowKey="id" :pagination="{ pageSize: 10, showSizeChanger: true }" size="small" />
                            </a-tab-pane>
                        </a-tabs>
                    </div>

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

                    <a-modal
    v-model:open="showCreateUserModal"
    title="Create Staff User"
    @ok="handleCreateUser"
    ok-text="Create"
    cancel-text="Cancel"
    :confirm-loading="form.processing"
    :maskClosable="false"
>
    <div class="space-y-4">
        
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Name
            </label>
            <a-input v-model:value="form.name" placeholder="Enter name" />
            <div v-if="form.errors.name" class="text-red-500 text-sm">
                {{ form.errors.name }}
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Email
            </label>
            <a-input v-model:value="form.email" placeholder="Enter email" />
            <div v-if="form.errors.email" class="text-red-500 text-sm">
                {{ form.errors.email }}
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Password
            </label>
            <a-input-password v-model:value="form.password" placeholder="Enter password" />
            <div v-if="form.errors.password" class="text-red-500 text-sm">
                {{ form.errors.password }}
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Confirm Password
            </label>
            <a-input-password v-model:value="form.password_confirmation" placeholder="Confirm password" />
        </div>

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