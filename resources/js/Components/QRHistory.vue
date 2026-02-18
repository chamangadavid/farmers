<!-- resources/js/Components/QRHistory.vue -->
<script setup>
import { ref, onMounted, computed, onUnmounted  } from 'vue';
import axios from 'axios';
import { Modal, message } from 'ant-design-vue';

const qrCodes = ref([]);
const loading = ref(false);
const pagination = ref({
    current: 1,
    pageSize: 10,
    total: 0
});

// Screen size detection
const isMobile = ref(window.innerWidth <= 768);

// Update screen size on resize
const handleResize = () => {
    isMobile.value = window.innerWidth <= 768;
};

window.addEventListener('resize', handleResize);

// Modal state
const modalVisible = ref(false);
const selectedQrCode = ref(null);
const modalLoading = ref(false);

// Fetch history data
const fetchHistory = async (page = 1) => {
    loading.value = true;
    try {
        const response = await axios.get('/qr/history', {
            params: {
                page: page,
                per_page: pagination.value.pageSize
            }
        });

        if (response.data.success) {
            qrCodes.value = response.data.data.data;
            pagination.value.total = response.data.data.total;
            pagination.value.current = response.data.data.current_page;
        }
    } catch (err) {
        message.error('Failed to fetch history');
        console.error('Fetch history failed:', err);
    } finally {
        loading.value = false;
    }
};

// Handle table pagination change
const handleTableChange = (pag) => {
    pagination.value.current = pag.current;
    fetchHistory(pag.current);
};

// View QR code details
const viewQRCode = (record) => {
    selectedQrCode.value = record;
    modalVisible.value = true;
};

const downloadQR = async (id) => {
    try {
        window.open(`/qr/${id}/download`, '_blank');
    } catch (err) {
        console.error('Download failed:', err);
    }
};

const deleteQRCode = async (record) => {
    Modal.confirm({
        title: 'Delete QR Code',
        content: `Are you sure you want to delete "${record.name}"?`,
        okText: 'Yes, Delete',
        okType: 'danger',
        cancelText: 'Cancel',
        onOk: async () => {
            try {
                const response = await axios.delete(`/qr/${record.id}`);

                if (response.data.success) {
                    message.success('QR Code deleted successfully');
                    // Refresh the list
                    fetchHistory(pagination.value.current);
                }
            } catch (err) {
                message.error('Failed to delete QR code');
                console.error('Delete failed:', err);
            }
        }
    });
};

// Format data for display in modal
const formatData = (data) => {
    if (!data) return 'No data';

    try {
        if (typeof data === 'string') {
            data = JSON.parse(data);
        }

        // Format based on type
        if (selectedQrCode.value?.type === 'vcard') {
            return {
                'Name': `${data.firstName || data.firstname || ''} ${data.lastName || data.lastname || ''}`.trim(),
                'Email': data.email || 'N/A',
                'Phone (Work)': data.phoneWork || data.phone || 'N/A',
                'Phone (Mobile)': data.phoneMobile || 'N/A',
                'Company': data.organization || data.company || 'N/A',
                'Title': data.title || 'N/A',
                'Address': [
                    data.addressStreet,
                    data.addressCity,
                    data.addressState,
                    data.addressZip,
                    data.addressCountry
                ].filter(Boolean).join(', ') || 'N/A',
                'Website': data.website || 'N/A'
            };
        }

        return data;
    } catch (e) {
        return data;
    }
};

const formattedEntries = (data) => {
    const formatted = formatData(data);

    if (!formatted || typeof formatted !== 'object') {
        return [];
    }

    return Object.entries(formatted).map(([label, value]) => ({
        label,
        value: value || 'N/A'
    }));
};

// Responsive columns configuration
const columns = computed(() => {
    const baseColumns = [
        {
            title: '#',
            key: 'sn',
            width: isMobile.value ? 50 : 80,
            fixed: isMobile.value ? false : 'left',
            customRender: ({ index }) => {
                return (pagination.value.current - 1) * pagination.value.pageSize + index + 1;
            }
        },
        {
            title: 'Name',
            dataIndex: 'name',
            key: 'name',
            ellipsis: true,
            width: isMobile.value ? 120 : 200,
        },
        {
            title: 'Type',
            dataIndex: 'type',
            key: 'type',
            width: isMobile.value ? 100 : 120,
            customRender: ({ text }) => {
                const typeLabels = {
                    vcard: 'vCard',
                    email: 'Email',
                    sms: 'SMS',
                    url: 'URL',
                    text: 'Text',
                    wifi: 'WiFi',
                    event: 'Event',
                    facebook: 'Facebook',
                    twitter: 'Twitter',
                    linkedin: 'LinkedIn',
                    instagram: 'Instagram',
                    youtube: 'YouTube',
                    tiktok: 'TikTok',
                    pinterest: 'Pinterest',
                    snapchat: 'Snapchat'
                };
                return typeLabels[text] || text;
            }
        },
        {
            title: 'Created',
            dataIndex: 'created_at',
            key: 'created_at',
            width: isMobile.value ? 150 : 180,
            customRender: ({ text }) => {
                if (isMobile.value) {
                    // Shorter date format for mobile
                    const date = new Date(text);
                    return `${date.toLocaleDateString()} ${date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })}`;
                }
                return new Date(text).toLocaleString();
            }
        },
        {
            title: 'Actions',
            key: 'actions',
            width: isMobile.value ? 100 : 150,
            fixed: isMobile.value ? false : 'right',
        }
    ];

    return baseColumns;
});

// Table scroll configuration
const scrollConfig = computed(() => {
    if (isMobile.value) {
        return { x: 'max-content' };
    }
    return { x: 'max-content' };
});

onMounted(() => {
    fetchHistory();
});

// Clean up event listener
onUnmounted(() => {
    window.removeEventListener('resize', handleResize);
});
</script>

<template>
    <div class="qr-history">
        <!-- Mobile View: Card Layout for small screens -->
        <div v-if="isMobile && qrCodes.length > 0" class="mobile-cards">
            <a-card 
                v-for="qr in qrCodes" 
                :key="qr.id" 
                class="mb-4 mobile-card"
                :bordered="true"
            >
                <div class="card-header">
                    <div class="card-title">{{ qr.name }}</div>
                    <a-tag :color="'blue'">{{ qr.type }}</a-tag>
                </div>
                
                <div class="card-details">
                    <div class="detail-row">
                        <span class="label">Created:</span>
                        <span class="value">{{ new Date(qr.created_at).toLocaleDateString() }}</span>
                    </div>
                    
                    <div class="card-actions">
                        <a-button type="link" size="small" @click="viewQRCode(qr)">
                            <template #icon><EyeOutlined /></template>
                            View
                        </a-button>
                        <a-button type="link" danger size="small" @click="deleteQRCode(qr)">
                            <template #icon><DeleteOutlined /></template>
                            Delete
                        </a-button>
                    </div>
                </div>
            </a-card>
            
            <!-- Mobile Pagination -->
            <div class="mobile-pagination">
                <a-pagination 
                    v-model:current="pagination.current"
                    :total="pagination.total"
                    :page-size="pagination.pageSize"
                    size="small"
                    @change="fetchHistory"
                    show-less-items
                />
            </div>
        </div>

        <!-- Desktop/Tablet View: Table Layout -->
        <div v-else>
            <a-table 
                :columns="columns" 
                :data-source="qrCodes" 
                :loading="loading" 
                :pagination="pagination"
                :scroll="scrollConfig"
                @change="handleTableChange" 
                row-key="id"
                :bordered="!isMobile"
                size="middle"
            >
                <!-- Actions Column -->
                <template #bodyCell="{ column, record }">
                    <template v-if="column.key === 'actions'">
                        <a-space>
                            <a-button 
                                type="link" 
                                size="small" 
                                @click="viewQRCode(record)"
                            >
                                <template #icon>
                                    <EyeOutlined />
                                </template>
                                <span v-if="!isMobile">View</span>
                            </a-button>
                            <a-button 
                                type="link" 
                                danger 
                                size="small" 
                                @click="deleteQRCode(record)"
                            >
                                <template #icon>
                                    <DeleteOutlined />
                                </template>
                                <span v-if="!isMobile">Delete</span>
                            </a-button>
                        </a-space>
                    </template>
                </template>
            </a-table>
        </div>

        <!-- View Modal (same for all views) -->
        <a-modal 
            v-model:open="modalVisible" 
            :title="selectedQrCode?.name || 'QR Code Details'" 
            :width="isMobile ? '95%' : '700px'"
            :footer="null" 
            :mask-closable="false"
            :wrap-class-name="isMobile ? 'mobile-modal' : ''"
        >
            <a-spin :spinning="modalLoading">
                <div v-if="selectedQrCode" class="modal-content">
                    <!-- QR Code Image -->
                    <div class="qr-image-section text-center mb-6">
                        <h4 class="font-semibold mb-2">QR Code</h4>
                        <img 
                            :src="`/${selectedQrCode.file_path}`" 
                            :alt="selectedQrCode.name"
                            class="mx-auto border rounded-lg p-2"
                            :style="{ maxWidth: isMobile ? '150px' : '200px' }"
                        />

                        <!-- Download Button -->
                        <div class="mt-3">
                            <a-button 
                                type="primary"
                                :href="`/qr/${selectedQrCode.id}/download`"
                                target="_blank"
                                :block="isMobile"
                            >
                                <template #icon>
                                    <DownloadOutlined />
                                </template>
                                Download SVG
                            </a-button>
                        </div>
                    </div>

                    <!-- QR Code Details -->
                    <a-descriptions 
                        bordered 
                        :column="1" 
                        size="small" 
                        class="mt-4"
                        :label-style="{ width: isMobile ? '100px' : '150px' }"
                    >
                        <a-descriptions-item label="Name">
                            {{ selectedQrCode.name }}
                        </a-descriptions-item>
                        <a-descriptions-item label="Type">
                            {{ selectedQrCode.type }}
                        </a-descriptions-item>
                        <a-descriptions-item label="Created">
                            {{ new Date(selectedQrCode.created_at).toLocaleString() }}
                        </a-descriptions-item>
                        <a-descriptions-item label="Data">
                            <a-descriptions 
                                bordered 
                                :column="1" 
                                size="small"
                                :label-style="{ width: isMobile ? '100px' : '150px' }"
                            >
                                <a-descriptions-item 
                                    v-for="item in formattedEntries(selectedQrCode.data)"
                                    :key="item.label" 
                                    :label="item.label"
                                >
                                    <template v-if="item.label === 'Website' && item.value !== 'N/A'">
                                        <a :href="`https://${item.value}`" target="_blank" class="text-blue-600 break-all">
                                            {{ item.value }}
                                        </a>
                                    </template>
                                    <template v-else>
                                        <span class="break-all">{{ item.value }}</span>
                                    </template>
                                </a-descriptions-item>
                            </a-descriptions>
                        </a-descriptions-item>
                    </a-descriptions>
                </div>
            </a-spin>
        </a-modal>
    </div>
</template>

<style scoped>
.qr-history :deep(.ant-table) {
    background: white;
    border-radius: 8px;
    overflow-x: auto;
}

.qr-history :deep(.ant-table-thead > tr > th) {
    background-color: #fafafa;
    font-weight: 600;
}

.qr-image-section {
    border-bottom: 1px solid #f0f0f0;
    padding-bottom: 16px;
}

.modal-content {
    padding: 8px 0;
}

/* Mobile Card Layout */
.mobile-cards {
    padding: 8px;
}

.mobile-card {
    border-radius: 8px;
    transition: all 0.3s;
}

.mobile-card:hover {
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.09);
}

.card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 12px;
    padding-bottom: 8px;
    border-bottom: 1px solid #f0f0f0;
}

.card-title {
    font-weight: 600;
    font-size: 14px;
    flex: 1;
    margin-right: 8px;
    word-break: break-word;
}

.card-details {
    padding: 4px 0;
}

.detail-row {
    display: flex;
    justify-content: space-between;
    margin-bottom: 8px;
    font-size: 13px;
}

.detail-row .label {
    color: #8c8c8c;
}

.detail-row .value {
    color: #262626;
    text-align: right;
}

.card-actions {
    display: flex;
    justify-content: flex-end;
    gap: 8px;
    margin-top: 8px;
    padding-top: 8px;
    border-top: 1px solid #f0f0f0;
}

.mobile-pagination {
    display: flex;
    justify-content: center;
    margin-top: 16px;
    padding: 8px;
}

/* Mobile Modal */
:deep(.mobile-modal .ant-modal) {
    max-width: 95%;
    margin: 10px auto;
}

:deep(.mobile-modal .ant-modal-body) {
    padding: 16px;
}

/* Responsive breakpoints */
@media screen and (max-width: 768px) {
    .qr-history :deep(.ant-table) {
        font-size: 12px;
    }
    
    .qr-history :deep(.ant-table-thead > tr > th) {
        padding: 8px;
        font-size: 12px;
    }
    
    .qr-history :deep(.ant-table-tbody > tr > td) {
        padding: 8px;
    }
    
    /* Hide text labels on mobile for action buttons */
    .qr-history :deep(.ant-btn-link) {
        padding: 4px 8px;
    }
}

/* Tablet adjustments */
@media screen and (min-width: 769px) and (max-width: 1024px) {
    .qr-history :deep(.ant-table) {
        font-size: 13px;
    }
}
</style>