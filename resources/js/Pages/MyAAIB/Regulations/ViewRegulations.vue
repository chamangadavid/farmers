<script setup>
import { ref, watch } from 'vue';
import { Modal, Tag, Divider, Descriptions } from 'ant-design-vue';
import { 
  FilePdfOutlined, 
  CalendarOutlined, 
  FolderOutlined,
  FileTextOutlined,
  CloseOutlined,
  EyeOutlined,
  DownloadOutlined
} from '@ant-design/icons-vue';

const props = defineProps({
    open: Boolean,
    regulation: Object
});

const emit = defineEmits(['update:open']);

const localOpen = ref(false);

watch(() => props.open, (val) => {
    localOpen.value = val;
});

const formatDate = (date) => {
    if (!date) return 'N/A';
    return new Date(date).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
};

const getDocumentTypeColor = (type) => {
    const typeLower = type?.toLowerCase() || '';
    if (typeLower.includes('regulation')) return 'blue';
    if (typeLower.includes('annex')) return 'purple';
    if (typeLower.includes('standard')) return 'green';
    if (typeLower.includes('guidance')) return 'orange';
    return 'teal';
};

const getDocumentTypeIcon = (type) => {
    const typeLower = type?.toLowerCase() || '';
    if (typeLower.includes('regulation')) return '📋';
    if (typeLower.includes('annex')) return '📎';
    if (typeLower.includes('standard')) return '⭐';
    if (typeLower.includes('guidance')) return '📘';
    return '📄';
};
</script>

<template>
    <Modal
        :open="localOpen"
        title="Details Information"
        :footer="null"
        @cancel="localOpen = false"
        width="900px"
        :maskClosable="true"
        class="view-regulation-modal"
    >
        <div v-if="regulation" class="regulation-details">
            <!-- Header with Icon -->
            <div class="header-gradient rounded-xl p-6 mb-6">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center shadow-lg">
                        <FilePdfOutlined class="text-3xl text-white" />
                    </div>
                    <div class="flex-1">
                        <h3 class="text-xl font-bold text-white mb-1">Detail Information</h3>
                        <p class="text-teal-50 text-sm flex items-center gap-2">
                            <FileTextOutlined class="text-sm" />
                            Aviation Regulation Document Details
                        </p>
                    </div>
                </div>
            </div>

            <!-- Content Sections -->
            <div class="space-y-6">
                <!-- Title Section -->
                <div class="flex items-start gap-3 p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-all duration-200">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-r from-teal-100 to-emerald-100 flex items-center justify-center flex-shrink-0">
                        <FileTextOutlined class="text-teal-600 text-lg" />
                    </div>
                    <div class="flex-1">
                        <div class="text-xs text-gray-500 font-medium uppercase tracking-wide mb-1">Document Title</div>
                        <div class="text-gray-900 font-bold text-lg leading-relaxed">
                            {{ regulation.title }}
                        </div>
                    </div>
                </div>

                <!-- Document Type Section -->
                <div class="flex items-start gap-3 p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-all duration-200">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-r from-teal-100 to-emerald-100 flex items-center justify-center flex-shrink-0">
                        <FolderOutlined class="text-teal-600 text-lg" />
                    </div>
                    <div class="flex-1">
                        <div class="text-xs text-gray-500 font-medium uppercase tracking-wide mb-1">Document Type</div>
                        <Tag :color="getDocumentTypeColor(regulation.document_type)" class="px-3 py-1 text-sm">
                            <span class="mr-1">{{ getDocumentTypeIcon(regulation.document_type) }}</span>
                            {{ regulation.document_type }}
                        </Tag>
                    </div>
                </div>

                <!-- PDF Preview Section -->
                <div class="space-y-3">
                    <Divider class="my-2">
                        <FilePdfOutlined class="text-teal-500" />
                        <span class="ml-2 text-gray-500">Document Preview</span>
                    </Divider>
                    
                    <div v-if="regulation.regulation_file" class="pdf-preview-container">
                        <div class="bg-gray-100 px-4 py-3 rounded-t-xl border-b border-gray-200 flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <FilePdfOutlined class="text-red-500 text-lg" />
                                <span class="text-sm font-medium text-gray-700">{{ regulation.title }}.pdf</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <a
                                    :href="`/storage/${regulation.regulation_file}`"
                                    target="_blank"
                                    class="inline-flex items-center gap-1 text-teal-600 hover:text-teal-700 text-sm"
                                >
                                    <EyeOutlined />
                                    Open in new tab
                                </a>
                                <a
                                    :href="`/storage/${regulation.regulation_file}`"
                                    download
                                    class="inline-flex items-center gap-1 text-teal-600 hover:text-teal-700 text-sm"
                                >
                                    <DownloadOutlined />
                                    Download
                                </a>
                            </div>
                        </div>
                        <div class="pdf-viewer bg-gray-50 rounded-b-xl overflow-hidden">
                            <iframe
                                :src="`/storage/${regulation.regulation_file}`"
                                class="w-full h-[500px] border-0"
                                frameborder="0"
                            ></iframe>
                        </div>
                    </div>

                    <div v-else class="text-center py-8 bg-gray-50 rounded-xl">
                        <FilePdfOutlined class="text-6xl text-gray-300 mb-3" />
                        <p class="text-gray-500">No document uploaded</p>
                    </div>
                </div>

                <!-- Metadata Section -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4 pt-4 border-t border-gray-100">
                    <div class="flex items-center gap-2 text-sm text-gray-500">
                        <CalendarOutlined class="text-teal-500" />
                        <span>Created: {{ formatDate(regulation.created_at) }}</span>
                    </div>
                    <div v-if="regulation.updated_at && regulation.updated_at !== regulation.created_at" class="flex items-center gap-2 text-sm text-gray-500">
                        <CalendarOutlined class="text-teal-500" />
                        <span>Last Updated: {{ formatDate(regulation.updated_at) }}</span>
                    </div>
                </div>
            </div>

            <!-- Footer Actions -->
            <div class="mt-6 pt-4 border-t border-gray-100 flex justify-end gap-3">
                <button 
                    @click="localOpen = false"
                    class="px-5 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-all duration-200"
                >
                    <CloseOutlined class="mr-1" />
                    Close
                </button>
                <a 
                    v-if="regulation.regulation_file"
                    :href="`/storage/${regulation.regulation_file}`"
                    download
                    class="inline-flex items-center gap-2 px-5 py-2 bg-gradient-to-r from-teal-600 to-emerald-600 text-white rounded-lg hover:shadow-lg transition-all duration-200 transform hover:-translate-y-0.5"
                >
                    <DownloadOutlined />
                    Download PDF
                </a>
            </div>
        </div>

        <!-- Empty State -->
        <div v-else class="text-center py-12">
            <FilePdfOutlined class="text-6xl text-gray-300 mb-4" />
            <p class="text-gray-500 text-lg">No regulation data available</p>
        </div>
    </Modal>
</template>

<style scoped>
/* Modal Header Styling */
:deep(.ant-modal-header) {
    border-bottom: 2px solid #f0fdf4;
    padding: 20px 24px;
    background: linear-gradient(135deg, #14b8a6 0%, #10b981 100%);
    border-radius: 8px 8px 0 0;
}

:deep(.ant-modal-title) {
    font-size: 1.25rem;
    font-weight: 600;
    color: white;
    display: flex;
    align-items: center;
    gap: 8px;
}

:deep(.ant-modal-title::before) {
    content: '';
    display: inline-block;
    width: 4px;
    height: 20px;
    background: white;
    border-radius: 2px;
}

:deep(.ant-modal-close) {
    top: 20px;
    right: 20px;
    color: white;
    transition: all 0.3s ease;
}

:deep(.ant-modal-close:hover) {
    color: white;
    transform: rotate(90deg);
    background: rgba(255,255,255,0.2);
    border-radius: 50%;
}

:deep(.ant-modal-body) {
    padding: 24px;
}

/* Header Gradient Background */
.header-gradient {
    background: linear-gradient(135deg, #14b8a6 0%, #10b981 50%, #059669 100%);
    position: relative;
    overflow: hidden;
}

.header-gradient::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0) 70%);
    animation: pulse 3s ease-in-out infinite;
}

@keyframes pulse {
    0%, 100% {
        transform: scale(1);
        opacity: 0.5;
    }
    50% {
        transform: scale(1.1);
        opacity: 0.8;
    }
}

/* Regulation Details Container */
.regulation-details {
    max-height: 70vh;
    overflow-y: auto;
    padding-right: 4px;
}

/* Custom Scrollbar */
.regulation-details::-webkit-scrollbar {
    width: 6px;
}

.regulation-details::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

.regulation-details::-webkit-scrollbar-thumb {
    background: linear-gradient(135deg, #14b8a6, #10b981);
    border-radius: 10px;
}

.regulation-details::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(135deg, #0d9488, #059669);
}

/* PDF Preview Container */
.pdf-preview-container {
    transition: all 0.3s ease;
    border-radius: 0.75rem;
    overflow: hidden;
    border: 1px solid #e2e8f0;
}

.pdf-preview-container:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.pdf-viewer {
    background: #f9fafb;
}

/* Tag Styling */
:deep(.ant-tag) {
    border-radius: 1rem;
    padding: 4px 12px;
    font-size: 0.875rem;
    font-weight: 500;
    border: none;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}

/* Card Hover Effects */
.bg-gray-50 {
    transition: all 0.2s ease;
}

.bg-gray-50:hover {
    transform: translateX(4px);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}

/* Animation for modal content */
.regulation-details {
    animation: fadeInUp 0.4s ease-out;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Divider Styling */
:deep(.ant-divider) {
    margin: 16px 0;
    color: #6b7280;
}

:deep(.ant-divider .ant-divider-inner-text) {
    display: flex;
    align-items: center;
    gap: 8px;
}

/* Responsive Adjustments */
@media (max-width: 768px) {
    :deep(.ant-modal-body) {
        padding: 20px;
    }
    
    .header-gradient {
        padding: 20px;
    }
    
    .header-gradient .w-14.h-14 {
        width: 48px;
        height: 48px;
    }
    
    .header-gradient .text-3xl {
        font-size: 1.5rem;
    }
    
    .bg-gray-50 {
        padding: 12px;
    }
    
    .pdf-viewer iframe {
        height: 300px;
    }
    
    .bg-gray-100 .flex {
        flex-wrap: wrap;
        gap: 8px;
    }
}

/* Print Styles */
@media print {
    :deep(.ant-modal-close),
    .close-button,
    .download-button {
        display: none;
    }
    
    .header-gradient {
        background: #f3f4f6;
        color: #1f2937;
    }
    
    .header-gradient .text-white {
        color: #1f2937;
    }
    
    .bg-gray-50 {
        background: #f9fafb;
        break-inside: avoid;
    }
    
    .pdf-preview-container {
        break-inside: avoid;
        page-break-inside: avoid;
    }
}
</style>


<!-- <script setup>
import { Modal, Tag } from 'ant-design-vue';

const props = defineProps({
    open: Boolean,
    regulation: Object
});

const emit = defineEmits(['update:open']);
</script>

<template>
    <Modal
        :open="open"
        title="View Regulation"
        :footer="null"
        @cancel="$emit('update:open', false)"
        width="900px"
    >
        <div v-if="regulation" class="space-y-5">

            <div>
                <h3 class="text-gray-500 text-sm">Title</h3>
                <p class="text-lg font-semibold">
                    {{ regulation.title }}
                </p>
            </div>

            <div>
                <h3 class="text-gray-500 text-sm">Document Type</h3>
                <Tag color="blue">
                    {{ regulation.document_type }}
                </Tag>
            </div>

            <div>
                <h3 class="text-gray-500 text-sm mb-2">Document Preview</h3>

                <div v-if="regulation.regulation_file">
                    
                    <iframe
                        :src="`/storage/${regulation.regulation_file}`"
                        class="w-full h-[500px] border rounded"
                    ></iframe>

                    <div class="mt-2">
                        <a
                            :href="`/storage/${regulation.regulation_file}`"
                            target="_blank"
                            class="text-teal-600 hover:underline"
                        >
                            Open in new tab
                        </a>
                    </div>

                </div>

                <p v-else class="text-gray-400">
                    No file uploaded
                </p>
            </div>

            <div>
                <h3 class="text-gray-500 text-sm">Created At</h3>
                <p>
                    {{ new Date(regulation.created_at).toLocaleString() }}
                </p>
            </div>

        </div>
    </Modal>
</template> -->