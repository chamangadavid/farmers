<script setup>
import { ref, watch } from 'vue';
import { Modal, Tag, Divider, Descriptions } from 'ant-design-vue';
import { 
  FileTextOutlined, 
  CalendarOutlined, 
  FlagOutlined,
  FolderOutlined,
  CloseOutlined,
  EyeOutlined
} from '@ant-design/icons-vue';

const props = defineProps({
    open: Boolean,
    announcement: Object
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
        day: 'numeric'
    });
};

// Get priority color
const getPriorityColor = (priority) => {
    const priorityLower = priority?.toLowerCase() || '';
    if (priorityLower.includes('high') || priorityLower.includes('urgent')) return 'red';
    if (priorityLower.includes('medium')) return 'orange';
    if (priorityLower.includes('low')) return 'green';
    return 'teal';
};

// Get priority icon
const getPriorityIcon = (priority) => {
    const priorityLower = priority?.toLowerCase() || '';
    if (priorityLower.includes('high') || priorityLower.includes('urgent')) return '🔴';
    if (priorityLower.includes('medium')) return '🟠';
    if (priorityLower.includes('low')) return '🟢';
    return '📌';
};

// Get category icon
const getCategoryIcon = (category) => {
    const categoryLower = category?.toLowerCase() || '';
    if (categoryLower.includes('general')) return '📚';
    if (categoryLower.includes('update')) return '🔄';
    if (categoryLower.includes('alert')) return '⚠️';
    if (categoryLower.includes('event')) return '🎉';
    return '📢';
};
</script>

<template>
    <a-modal 
        :open="localOpen" 
        width="800px" 
        title="Announcement Details" 
        @cancel="emit('update:open', false)"
        :footer="null"
        :maskClosable="true"
        class="view-announcement-modal"
    >
        <div v-if="announcement" class="announcement-details">
            <!-- Header with Icon -->
            <div class="header-gradient rounded-xl p-6 mb-6">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center shadow-lg">
                        <FileTextOutlined class="text-3xl text-white" />
                    </div>
                    <div class="flex-1">
                        <h3 class="text-xl font-bold text-white mb-1">Announcement Information</h3>
                        <p class="text-teal-50 text-sm flex items-center gap-2">
                            <EyeOutlined class="text-sm" />
                            Official Announcement Details
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
                        <div class="text-xs text-gray-500 font-medium uppercase tracking-wide mb-1">Title</div>
                        <div class="text-gray-900 font-bold text-xl leading-relaxed">
                            {{ announcement.title }}
                        </div>
                    </div>
                </div>

                <!-- Date and Priority Row -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Date Section -->
                    <div class="flex items-start gap-3 p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-all duration-200">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-r from-teal-100 to-emerald-100 flex items-center justify-center flex-shrink-0">
                            <CalendarOutlined class="text-teal-600 text-lg" />
                        </div>
                        <div class="flex-1">
                            <div class="text-xs text-gray-500 font-medium uppercase tracking-wide mb-1">Release Date</div>
                            <Tag color="teal" class="px-3 py-1 text-sm">
                                <CalendarOutlined class="mr-1" />
                                {{ formatDate(announcement.date) }}
                            </Tag>
                        </div>
                    </div>

                    <!-- Priority Section -->
                    <div class="flex items-start gap-3 p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-all duration-200">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-r from-teal-100 to-emerald-100 flex items-center justify-center flex-shrink-0">
                            <FlagOutlined class="text-teal-600 text-lg" />
                        </div>
                        <div class="flex-1">
                            <div class="text-xs text-gray-500 font-medium uppercase tracking-wide mb-1">Priority Level</div>
                            <Tag :color="getPriorityColor(announcement.priority)" class="px-3 py-1 text-sm">
                                <span class="mr-1">{{ getPriorityIcon(announcement.priority) }}</span>
                                {{ announcement.priority }}
                            </Tag>
                        </div>
                    </div>
                </div>

                <!-- Category Section -->
                <div class="flex items-start gap-3 p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-all duration-200">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-r from-teal-100 to-emerald-100 flex items-center justify-center flex-shrink-0">
                        <FolderOutlined class="text-teal-600 text-lg" />
                    </div>
                    <div class="flex-1">
                        <div class="text-xs text-gray-500 font-medium uppercase tracking-wide mb-1">Category</div>
                        <Tag color="teal" class="px-3 py-1 text-sm">
                            <span class="mr-1">{{ getCategoryIcon(announcement.category) }}</span>
                            {{ announcement.category }}
                        </Tag>
                    </div>
                </div>

                <!-- Summary Section -->
                <div class="flex items-start gap-3 p-4 bg-gradient-to-br from-gray-50 to-white rounded-xl border border-gray-100">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-r from-teal-100 to-emerald-100 flex items-center justify-center flex-shrink-0">
                        <EyeOutlined class="text-teal-600 text-lg" />
                    </div>
                    <div class="flex-1">
                        <div class="text-xs text-gray-500 font-medium uppercase tracking-wide mb-2">Summary</div>
                        <div class="summary-content text-gray-700 leading-relaxed">
                            {{ announcement.summary }}
                        </div>
                    </div>
                </div>

                <!-- Details Section -->
                <div class="space-y-3">
                    <Divider class="my-2">
                        <FileTextOutlined class="text-teal-500" />
                        <span class="ml-2 text-gray-500">Full Details</span>
                    </Divider>
                    
                    <div class="details-container bg-gradient-to-br from-gray-50 to-white rounded-xl p-6 border border-gray-100">
                        <div class="details-content text-gray-700 leading-relaxed">
                            {{ announcement.details }}
                        </div>
                    </div>
                </div>

                <!-- Metadata Section -->
                <div v-if="announcement.created_at" class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4 pt-4 border-t border-gray-100">
                    <div class="flex items-center gap-2 text-sm text-gray-500">
                        <CalendarOutlined class="text-teal-500" />
                        <span>Created: {{ formatDate(announcement.created_at) }}</span>
                    </div>
                    <div v-if="announcement.updated_at && announcement.updated_at !== announcement.created_at" class="flex items-center gap-2 text-sm text-gray-500">
                        <CalendarOutlined class="text-teal-500" />
                        <span>Last Updated: {{ formatDate(announcement.updated_at) }}</span>
                    </div>
                </div>
            </div>

            <!-- Footer Actions -->
            <div class="mt-6 pt-4 border-t border-gray-100 flex justify-end">
                <button 
                    @click="emit('update:open', false)"
                    class="px-6 py-2 bg-gradient-to-r from-teal-600 to-emerald-600 text-white rounded-lg hover:shadow-lg transition-all duration-200 transform hover:-translate-y-0.5"
                >
                    <CloseOutlined class="mr-1" />
                    Close
                </button>
            </div>
        </div>

        <!-- Empty State -->
        <div v-else class="text-center py-12">
            <FileTextOutlined class="text-6xl text-gray-300 mb-4" />
            <p class="text-gray-500 text-lg">No announcement data available</p>
        </div>
    </a-modal>
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

/* Announcement Details Container */
.announcement-details {
    max-height: 70vh;
    overflow-y: auto;
    padding-right: 4px;
}

/* Custom Scrollbar */
.announcement-details::-webkit-scrollbar {
    width: 6px;
}

.announcement-details::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

.announcement-details::-webkit-scrollbar-thumb {
    background: linear-gradient(135deg, #14b8a6, #10b981);
    border-radius: 10px;
}

.announcement-details::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(135deg, #0d9488, #059669);
}

/* Content Styling */
.summary-content,
.details-content {
    font-size: 0.95rem;
    line-height: 1.6;
}

.details-container {
    transition: all 0.3s ease;
}

.details-container:hover {
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
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
.announcement-details {
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
    
    .summary-content,
    .details-content {
        font-size: 0.875rem;
    }
}

/* Print Styles */
@media print {
    :deep(.ant-modal-close),
    .close-button {
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
    
    .details-container {
        break-inside: avoid;
        page-break-inside: avoid;
    }
}
</style>


<!-- <script setup>
import { ref, watch } from 'vue';

const props = defineProps({
    open: Boolean,
    announcement: Object
});

const emit = defineEmits(['update:open']);
</script>

<template>
<a-modal :open="open" title="View Announcement" @cancel="emit('update:open', false)" :footer="null">
    <div v-if="announcement" class="space-y-3">
        <h2 class="text-xl font-bold">{{ announcement.title }}</h2>
        <p>{{ announcement.date }}</p>
        <p><b>Category:</b> {{ announcement.category }}</p>
        <p><b>Priority:</b> {{ announcement.priority }}</p>
        <p>{{ announcement.summary }}</p>
        <div class="bg-gray-100 p-3 rounded">
            {{ announcement.details }}
        </div>
    </div>
</a-modal>
</template> -->