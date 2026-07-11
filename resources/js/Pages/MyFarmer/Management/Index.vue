<!-- resources/js/Pages/MyAAIB/Management/Index.vue -->
<script setup>
import { ref, onMounted, h, computed } from 'vue';
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Table, Button, Tag, message, Popconfirm, Avatar, Space, Card, Tooltip, } from 'ant-design-vue';
import { EyeOutlined, EditOutlined, DeleteOutlined, PlusOutlined, UserOutlined } from '@ant-design/icons-vue';
import axios from 'axios';
import CreateMemberModal from '@/Components/Management/CreateMemberModal.vue';
import EditMemberModal from '@/Components/Management/EditMemberModal.vue';
import ViewMemberModal from '@/Components/Management/ViewMemberModal.vue';

// State management
const members = ref([]);
const loading = ref(false);
const isMobile = ref(false);

// Modal states
const showCreateModal = ref(false);
const showEditModal = ref(false);
const showViewModal = ref(false);
const selectedMember = ref(null);

// Check if device is mobile
const checkMobile = () => {
    isMobile.value = window.innerWidth < 768;
};

// Fetch members
const fetchMembers = async () => {
    try {
        loading.value = true;
        const response = await axios.get('/members');
        members.value = response.data.members || [];
    } catch (error) {
        console.error('Error fetching members:', error);
        message.error('Failed to fetch members');
    } finally {
        loading.value = false;
    }
};

// Delete member
const deleteMember = async (id) => {
    try {
        const response = await axios.delete(`/members/${id}`);
        if (response.data.success) {
            message.success('Member deleted successfully');
            await fetchMembers();
        } else {
            message.error(response.data.message || 'Failed to delete member');
        }
    } catch (error) {
        console.error('Error deleting member:', error);
        message.error(error.response?.data?.message || 'Failed to delete member');
    }
};

// Open edit modal
const openEditModal = (member) => {
    selectedMember.value = member;
    showEditModal.value = true;
};

// Open view modal
const openViewModal = (member) => {
    selectedMember.value = member;
    showViewModal.value = true;
};

// Handle successful create/edit
const handleSuccess = () => {
    fetchMembers();
};

// Responsive table columns
const columns = computed(() => {
    const baseColumns = [
        {
            title: 'Profile',
            dataIndex: 'image',
            key: 'image',
            width: isMobile.value ? '70px' : '80px',
            customRender: ({ record }) => {
                return h('div', { class: 'flex justify-center' }, [
                    h(Avatar, {
                        src: record.image ? `/storage/${record.image}` : null,
                        icon: !record.image ? h(UserOutlined) : null,
                        size: isMobile.value ? 32 : 40,
                        style: { backgroundColor: '#14b8a6' }
                    })
                ]);
            }
        },
        {
            title: 'Full Name',
            dataIndex: 'fullname',
            key: 'fullname',
            sorter: (a, b) => a.fullname.localeCompare(b.fullname),
            width: isMobile.value ? '120px' : '20%',
            ellipsis: true,
            customRender: ({ record }) => {
                return h('div', {
                    class: 'font-medium',
                    style: { maxWidth: isMobile.value ? '100px' : 'none' }
                }, record.fullname);
            }
        },
        {
            title: 'Position',
            dataIndex: 'position',
            key: 'position',
            sorter: (a, b) => a.position.localeCompare(b.position),
            width: isMobile.value ? '100px' : '20%',
            ellipsis: true,
            customRender: ({ record }) => {
                return h(Tag, {
                    color: 'teal',
                    style: { maxWidth: isMobile.value ? '80px' : 'none', overflow: 'hidden', textOverflow: 'ellipsis' }
                }, record.position);
            }
        },
        {
            title: 'Content/Bio',
            dataIndex: 'content',
            key: 'content',
            width: isMobile.value ? '150px' : '40%',
            ellipsis: true,
            customRender: ({ record }) => {
                // Create a temporary element to strip tags safely
                const tempDiv = document.createElement('div');
                tempDiv.innerHTML = record.content;

                // Get text content (plain text) and trim based on device
                let textContent = tempDiv.textContent || tempDiv.innerText || '';
                const maxLength = isMobile.value ? 30 : 45;
                if (textContent.length > maxLength) {
                    textContent = textContent.substring(0, maxLength) + '...';
                }

                return h('div', {
                    class: 'text-gray-600',
                    style: {
                        maxWidth: isMobile.value ? '120px' : '300px',
                        overflow: 'hidden',
                        textOverflow: 'ellipsis'
                    }
                }, textContent);
            }
        },
        {
            title: 'Actions',
            key: 'actions',
            width: isMobile.value ? '120px' : '150px',
            align: 'center',
            fixed: isMobile.value ? false : 'right',
            customRender: ({ record }) => {
                return h(Space, {
                    size: 'small',
                    direction: isMobile.value ? 'vertical' : 'horizontal',
                    style: { width: '100%', justifyContent: 'center' }
                }, [

                    // 👁 VIEW
                    h(Tooltip, { title: 'View Details' }, {
                        default: () =>
                            h(Button, {
                                type: 'link',
                                size: 'small',
                                icon: h(EyeOutlined),
                                onClick: () => openViewModal(record),
                                style: isMobile.value ? { padding: '4px 0' } : {}
                            })
                    }),

                    // ✏️ EDIT
                    h(Tooltip, { title: 'Edit Member' }, {
                        default: () =>
                            h(Button, {
                                type: 'link',
                                size: 'small',
                                icon: h(EditOutlined),
                                onClick: () => {
                                    selectedMember.value = record;
                                    showEditModal.value = true;
                                },
                                style: isMobile.value ? { padding: '4px 0' } : {}
                            })
                    }),

                    // 🗑 DELETE
                    h(Popconfirm, {
                        title: 'Are you sure you want to delete this member?',
                        onConfirm: () => deleteMember(record.id),
                        okText: 'Yes',
                        cancelText: 'No',
                        okType: 'danger'
                    }, {
                        default: () =>
                            h(Tooltip, { title: 'Delete Member', color: 'red' }, {
                                default: () =>
                                    h(Button, {
                                        type: 'link',
                                        danger: true,
                                        size: 'small',
                                        icon: h(DeleteOutlined),
                                        style: isMobile.value ? { padding: '4px 0' } : {}
                                    })
                            })
                    })

                ]);
            }
        }

    ];

    return baseColumns;
});

// Handle window resize
const handleResize = () => {
    checkMobile();
};

onMounted(() => {
    fetchMembers();
    checkMobile();
    window.addEventListener('resize', handleResize);
});

// Cleanup
import { onUnmounted } from 'vue';
onUnmounted(() => {
    window.removeEventListener('resize', handleResize);
});
</script>

<template>

    <Head title="Management Team" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Management Team
            </h2>
        </template>

        <div class="py-6 md:py-12">
            <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
                <div class="bg-white p-4 shadow sm:rounded-lg sm:p-8">
                    <div class="p-4 md:p-6">
                        <!-- Header with Add Button -->
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
                            <div>
                                <h3 class="text-lg font-medium text-gray-900">Management Team Members</h3>
                                <p class="text-sm text-gray-500 mt-1">
                                    Manage your organization's leadership and management team
                                </p>
                            </div>
                            <Button type="primary" @click="showCreateModal = true"
                                class="bg-gradient-to-r from-teal-600 to-emerald-600 hover:from-teal-700 hover:to-emerald-700 w-full sm:w-auto"
                                :class="{ 'w-full': isMobile }">
                                <PlusOutlined />
                                Add New Member
                            </Button>
                        </div>

                        <!-- Members Table - Responsive with horizontal scroll -->
                        <div class="table-responsive-container overflow-x-auto">
                            <a-table :dataSource="members" :columns="columns" :loading="loading" rowKey="id"
                                :pagination="{
                                    pageSize: isMobile ? 5 : 10,
                                    showSizeChanger: !isMobile,
                                    showTotal: (total) => `Total ${total} members`,
                                    size: isMobile ? 'small' : 'default'
                                }" :size="isMobile ? 'small' : 'middle'" :scroll="{ x: isMobile ? 600 : false }">
                                <template #emptyText>
                                    <div class="py-8 text-center">
                                        <UserOutlined class="text-4xl text-gray-400 mb-2" />
                                        <p class="text-gray-500">No members found</p>
                                        <Button type="link" @click="showCreateModal = true" class="mt-2">
                                            Click here to add your first member
                                        </Button>
                                    </div>
                                </template>
                            </a-table>
                        </div>

                        <!-- Mobile Card View -->
                        <div v-if="isMobile && members.length > 0" class="mt-6 block md:hidden">
                            <div class="text-sm text-gray-500 mb-3">Swipe horizontally to see all columns →</div>
                            <div class="text-xs text-gray-400 italic">Tip: Rotate device for better view</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modals -->
        <CreateMemberModal :open="showCreateModal" @close="showCreateModal = false" @success="handleSuccess" />
        <EditMemberModal :open="showEditModal" :member="selectedMember" @close="showEditModal = false"
            @success="handleSuccess" />
        <ViewMemberModal :open="showViewModal" :member="selectedMember" @close="showViewModal = false" />
    </AuthenticatedLayout>
</template>

<style scoped>
/* Responsive table container */
.table-responsive-container {
    width: 100%;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}

/* Custom table styling */
:deep(.ant-table) {
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    min-width: 100%;
}

:deep(.ant-table-thead > tr > th) {
    background-color: #f8fafc;
    font-weight: 600;
    color: #334155;
    font-size: 14px;
    white-space: nowrap;
}

:deep(.ant-table-tbody > tr:hover > td) {
    background-color: #f1f5f9 !important;
}

:deep(.ant-table-tbody > tr > td) {
    padding: 12px 8px;
}

/* Responsive table cells */
@media (max-width: 768px) {
    :deep(.ant-table-tbody > tr > td) {
        padding: 8px 4px;
        font-size: 13px;
    }

    :deep(.ant-table-thead > tr > th) {
        padding: 10px 4px;
        font-size: 12px;
    }

    :deep(.ant-table) {
        font-size: 13px;
    }
}

/* Action buttons styling */
:deep(.ant-btn-link) {
    padding: 4px 8px;
    height: auto;
    min-width: 32px;
}

@media (max-width: 768px) {
    :deep(.ant-btn-link) {
        padding: 4px 4px;
        min-width: 28px;
    }
}

:deep(.ant-btn-link:hover) {
    transform: translateY(-1px);
    transition: transform 0.2s;
}

/* Empty state styling */
:deep(.ant-table-empty) {
    padding: 40px;
}

@media (max-width: 768px) {
    :deep(.ant-table-empty) {
        padding: 20px;
    }
}

/* Pagination styling */
:deep(.ant-pagination) {
    margin-top: 16px;
}

@media (max-width: 768px) {
    :deep(.ant-pagination) {
        margin-top: 12px;
    }

    :deep(.ant-pagination-item) {
        min-width: 28px;
        height: 28px;
        line-height: 26px;
    }

    :deep(.ant-pagination-prev),
    :deep(.ant-pagination-next) {
        min-width: 28px;
        height: 28px;
        line-height: 26px;
    }
}

/* Tag styling */
:deep(.ant-tag) {
    margin: 0;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 100%;
}

@media (max-width: 768px) {
    :deep(.ant-tag) {
        font-size: 11px;
        padding: 0 6px;
    }
}

/* Avatar styling */
@media (max-width: 768px) {
    :deep(.ant-avatar) {
        width: 32px !important;
        height: 32px !important;
        line-height: 32px !important;
    }

    :deep(.ant-avatar-icon) {
        font-size: 18px !important;
    }
}

/* Space component for actions */
:deep(.ant-space) {
    gap: 4px !important;
}

@media (max-width: 768px) {
    :deep(.ant-space) {
        gap: 2px !important;
    }
}

/* Scrollbar styling for better UX */
.table-responsive-container::-webkit-scrollbar {
    height: 8px;
}

.table-responsive-container::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

.table-responsive-container::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 10px;
}

.table-responsive-container::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}
</style>