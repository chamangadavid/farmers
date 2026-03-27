<script setup>
import { ref, watch } from 'vue';
import { Modal, Descriptions, Divider, Tag, Card, Collapse, CollapsePanel } from 'ant-design-vue';
import {
    FileTextOutlined,
    UserOutlined,
    MailOutlined,
    PhoneOutlined,
    CalendarOutlined,
    ClockCircleOutlined,
    EnvironmentOutlined,
    CarOutlined,
    WarningOutlined,
    HeartOutlined,
    ToolOutlined,
    CloseOutlined,
} from '@ant-design/icons-vue';

const props = defineProps({
    open: Boolean,
    report: Object
});

const emit = defineEmits(['update:open']);

const localOpen = ref(false);

watch(() => props.open, (val) => {
    localOpen.value = val;
});

const handleClose = () => {
    localOpen.value = false;
    emit('update:open', false);
};

const formatDate = (date) => {
    if (!date) return 'N/A';
    const d = new Date(date);
    const options = { day: '2-digit', month: 'long', year: 'numeric' };
    return d.toLocaleDateString('en-GB', options);
};

const formatTime = (time) => {
    if (!time) return 'N/A';
    return time;
};

// Get report type color and icon
const getReportTypeInfo = (type) => {
    const typeLower = type?.toLowerCase() || '';
    if (typeLower.includes('accident')) {
        return { color: 'red', icon: '🚨', bgColor: 'bg-red-50', textColor: 'text-red-700' };
    }
    if (typeLower.includes('incident')) {
        return { color: 'orange', icon: '⚠️', bgColor: 'bg-orange-50', textColor: 'text-orange-700' };
    }
    if (typeLower.includes('serious')) {
        return { color: 'purple', icon: '🔴', bgColor: 'bg-purple-50', textColor: 'text-purple-700' };
    }
    return { color: 'teal', icon: '📋', bgColor: 'bg-teal-50', textColor: 'text-teal-700' };
};

const reportTypeInfo = (type) => getReportTypeInfo(type);
</script>

<template>
    <Modal :open="localOpen" title="Accident / Incident Report" width="800px" :footer="null" @cancel="handleClose"
        :maskClosable="true" class="view-report-modal">
        <div v-if="report" class="report-details">
            <!-- Header with Icon -->
            <div class="header-gradient rounded-xl p-6 mb-6">
                <div class="flex items-center gap-4">
                    <div
                        class="w-14 h-14 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center shadow-lg">
                        <FileTextOutlined class="text-3xl text-white" />
                    </div>
                    <div class="flex-1">
                        <h3 class="text-xl font-bold text-white mb-1">Incident Report Details</h3>
                        <p class="text-teal-50 text-sm flex items-center gap-2">
                            <WarningOutlined class="text-sm" />
                            Complete incident information
                        </p>
                    </div>
                </div>
            </div>

            <!-- Content Sections -->
            <div class="space-y-6">
                <!-- Report Type Badge -->
                <div class="flex justify-center">
                    <div
                        :class="['px-6 py-3 rounded-full flex items-center gap-2 shadow-sm', reportTypeInfo(report?.report_type).bgColor]">
                        <span class="text-2xl">{{ reportTypeInfo(report?.report_type).icon }}</span>
                        <span :class="['text-lg font-bold', reportTypeInfo(report?.report_type).textColor]">
                            {{ report?.report_type }} Report
                        </span>
                    </div>
                </div>

                <!-- Reporter Information Section -->
                <div>
                    <div class="flex items-center gap-2 mb-4">
                        <div
                            class="w-8 h-8 rounded-full bg-gradient-to-r from-teal-100 to-emerald-100 flex items-center justify-center">
                            <UserOutlined class="text-teal-600" />
                        </div>
                        <h4 class="text-lg font-semibold text-gray-900">Reporter Information</h4>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="flex items-start gap-3 p-3 bg-gray-50 rounded-lg">
                            <UserOutlined class="text-teal-500 mt-1" />
                            <div>
                                <div class="text-xs text-gray-500 font-medium">Full Name</div>
                                <div class="text-gray-900 font-medium">{{ report?.reporter_name }}</div>
                            </div>
                        </div>
                        <div class="flex items-start gap-3 p-3 bg-gray-50 rounded-lg">
                            <MailOutlined class="text-teal-500 mt-1" />
                            <div>
                                <div class="text-xs text-gray-500 font-medium">Email Address</div>
                                <a :href="`mailto:${report?.reporter_email}`" class="text-teal-600 hover:text-teal-700">
                                    {{ report?.reporter_email }}
                                </a>
                            </div>
                        </div>
                        <div class="flex items-start gap-3 p-3 bg-gray-50 rounded-lg">
                            <PhoneOutlined class="text-teal-500 mt-1" />
                            <div>
                                <div class="text-xs text-gray-500 font-medium">Phone Number</div>
                                <div class="text-gray-900">{{ report?.reporter_phone || 'N/A' }}</div>
                            </div>
                        </div>
                        <div class="flex items-start gap-3 p-3 bg-gray-50 rounded-lg">
                            <ClockCircleOutlined class="text-teal-500 mt-1" />
                            <div>
                                <div class="text-xs text-gray-500 font-medium">Status</div>
                                <div class="text-gray-900">{{ report?.status || 'N/A' }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Occurrence Details Section -->
                <div>
                    <div class="flex items-center gap-2 mb-4">
                        <div
                            class="w-8 h-8 rounded-full bg-gradient-to-r from-teal-100 to-emerald-100 flex items-center justify-center">
                            <CalendarOutlined class="text-teal-600" />
                        </div>
                        <h4 class="text-lg font-semibold text-gray-900">Occurrence Details</h4>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="flex items-start gap-3 p-3 bg-gray-50 rounded-lg">
                            <CalendarOutlined class="text-teal-500 mt-1" />
                            <div>
                                <div class="text-xs text-gray-500 font-medium">Date of Occurrence</div>
                                <div class="text-gray-900">{{ formatDate(report?.accident_date) }}</div>
                            </div>
                        </div>
                        <div class="flex items-start gap-3 p-3 bg-gray-50 rounded-lg">
                            <ClockCircleOutlined class="text-teal-500 mt-1" />
                            <div>
                                <div class="text-xs text-gray-500 font-medium">Time of Occurrence</div>
                                <div class="text-gray-900">{{ formatTime(report?.accident_time) }}</div>
                            </div>
                        </div>
                        <div class="flex items-start gap-3 p-3 bg-gray-50 rounded-lg md:col-span-2">
                            <EnvironmentOutlined class="text-teal-500 mt-1" />
                            <div>
                                <div class="text-xs text-gray-500 font-medium">Location</div>
                                <div class="text-gray-900">{{ report?.location }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Aircraft Details Section -->
                <div v-if="report?.aircraft_registration || report?.aircraft_type">
                    <div class="flex items-center gap-2 mb-4">
                        <div
                            class="w-8 h-8 rounded-full bg-gradient-to-r from-teal-100 to-emerald-100 flex items-center justify-center">
                            <CarOutlined class="text-teal-600" />
                        </div>
                        <h4 class="text-lg font-semibold text-gray-900">Aircraft Details</h4>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="flex items-start gap-3 p-3 bg-gray-50 rounded-lg">
                            <CarOutlined class="text-teal-500 mt-1" />
                            <div>
                                <div class="text-xs text-gray-500 font-medium">Registration</div>
                                <div class="text-gray-900">{{ report?.aircraft_registration || 'N/A' }}</div>
                            </div>
                        </div>
                        <div class="flex items-start gap-3 p-3 bg-gray-50 rounded-lg">
                            <CarOutlined class="text-teal-500 mt-1" />
                            <div>
                                <div class="text-xs text-gray-500 font-medium">Type</div>
                                <div class="text-gray-900">{{ report?.aircraft_type || 'N/A' }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Description Section -->
                <div>
                    <div class="flex items-center gap-2 mb-4">
                        <div
                            class="w-8 h-8 rounded-full bg-gradient-to-r from-teal-100 to-emerald-100 flex items-center justify-center">
                            <FileTextOutlined class="text-teal-600" />
                        </div>
                        <h4 class="text-lg font-semibold text-gray-900">Description</h4>
                    </div>
                    <div class="bg-gradient-to-br from-gray-50 to-white rounded-xl p-5 border border-gray-100">
                        <p class="text-gray-700 leading-relaxed whitespace-pre-wrap">{{ report?.description }}</p>
                    </div>
                </div>

                <!-- Injuries & Damage Section -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <div class="flex items-center gap-2 mb-3">
                            <div
                                class="w-8 h-8 rounded-full bg-gradient-to-r from-red-100 to-red-200 flex items-center justify-center">
                                <HeartOutlined class="text-red-600" />
                            </div>
                            <h4 class="text-md font-semibold text-gray-900">Injuries</h4>
                        </div>
                        <div class="bg-red-50 rounded-xl p-4 border border-red-100">
                            <p class="text-gray-700 leading-relaxed">{{ report?.injuries || 'None reported' }}</p>
                        </div>
                    </div>
                    <div>
                        <div class="flex items-center gap-2 mb-3">
                            <div
                                class="w-8 h-8 rounded-full bg-gradient-to-r from-orange-100 to-orange-200 flex items-center justify-center">
                                <ToolOutlined class="text-orange-600" />
                            </div>
                            <h4 class="text-md font-semibold text-gray-900">Damage</h4>
                        </div>
                        <div class="bg-orange-50 rounded-xl p-4 border border-orange-100">
                            <p class="text-gray-700 leading-relaxed">{{ report?.damage || 'None reported' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Summary Section -->
                <div v-if="report?.summary">
                    <div class="flex items-center gap-2 mb-4">
                        <div
                            class="w-8 h-8 rounded-full bg-gradient-to-r from-teal-100 to-emerald-100 flex items-center justify-center">
                            <FileTextOutlined class="text-teal-600" />
                        </div>
                        <h4 class="text-lg font-semibold text-gray-900">Investigation Summary</h4>
                    </div>

                    <div class="bg-blue-50 rounded-xl p-4 border border-blue-100">
                        <p class="text-gray-700 whitespace-pre-wrap">
                            {{ report.summary }}
                        </p>
                    </div>
                </div>



                <!-- Follow-up Document Section -->
                <div v-if="report?.follow_up_file">

                    <!-- PDF -->
                    <iframe v-if="report.follow_up_file.endsWith('.pdf')" :src="`/storage/${report.follow_up_file}`"
                        class="w-full h-64"></iframe>

                    <!-- Image -->
                    <img v-else-if="report.follow_up_file.match(/\.(jpg|jpeg|png)$/)"
                        :src="`/storage/${report.follow_up_file}`" class="w-full rounded-lg" />

                    <!-- Fallback -->
                    <a v-else :href="`/storage/${report.follow_up_file}`" target="_blank">
                        Download File
                    </a>

                </div>

                <!-- <div v-if="report?.follow_up_file" class="mt-6">
  <div class="flex items-center gap-2 mb-3">
    <div class="w-8 h-8 rounded-full bg-gradient-to-r from-blue-100 to-blue-200 flex items-center justify-center">
      <FileTextOutlined class="text-blue-600" />
    </div>
    <h4 class="text-md font-semibold text-gray-900">Investigation Follow-up</h4>
  </div>

  <div class="bg-blue-50 border border-blue-100 rounded-xl p-4">
 
    <iframe
      :src="`/storage/${report.follow_up_file}`"
      class="w-full h-64 rounded-lg border"
    ></iframe>

   
    <div class="flex justify-between items-center mt-3">
      <span class="text-sm text-gray-600 truncate">
        {{ report.follow_up_file.split('/').pop() }}
      </span>

      <a
        :href="`/storage/${report.follow_up_file}`"
        target="_blank"
        class="text-blue-600 hover:text-blue-700 font-medium text-sm"
      >
        Open Document
      </a>
    </div>

  </div>
</div> -->

                <!-- Metadata Section -->
                <div v-if="report?.created_at"
                    class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4 pt-4 border-t border-gray-100">
                    <div class="flex items-center gap-2 text-sm text-gray-500">
                        <CalendarOutlined class="text-teal-500" />
                        <span>Reported: {{ formatDate(report?.created_at) }}</span>
                    </div>
                    <div v-if="report?.updated_at && report?.updated_at !== report?.created_at"
                        class="flex items-center gap-2 text-sm text-gray-500">
                        <CalendarOutlined class="text-teal-500" />
                        <span>Last Updated: {{ formatDate(report?.updated_at) }}</span>
                    </div>
                </div>
            </div>

            <!-- Footer Actions -->
            <div class="mt-6 pt-4 border-t border-gray-100 flex justify-end">
                <button @click="handleClose"
                    class="px-6 py-2 bg-gradient-to-r from-teal-600 to-emerald-600 text-white rounded-lg hover:shadow-lg transition-all duration-200 transform hover:-translate-y-0.5">
                    <CloseOutlined class="mr-1" />
                    Close
                </button>
            </div>
        </div>

        <!-- Empty State -->
        <div v-else class="text-center py-12">
            <FileTextOutlined class="text-6xl text-gray-300 mb-4" />
            <p class="text-gray-500 text-lg">No report data available</p>
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
    background: rgba(255, 255, 255, 0.2);
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
    background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, rgba(255, 255, 255, 0) 70%);
    animation: pulse 3s ease-in-out infinite;
}

@keyframes pulse {

    0%,
    100% {
        transform: scale(1);
        opacity: 0.5;
    }

    50% {
        transform: scale(1.1);
        opacity: 0.8;
    }
}

/* Report Details Container */
.report-details {
    max-height: 70vh;
    overflow-y: auto;
    padding-right: 4px;
}

/* Custom Scrollbar */
.report-details::-webkit-scrollbar {
    width: 6px;
}

.report-details::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

.report-details::-webkit-scrollbar-thumb {
    background: linear-gradient(135deg, #14b8a6, #10b981);
    border-radius: 10px;
}

.report-details::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(135deg, #0d9488, #059669);
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
.report-details {
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
}

/* Link styling */
a {
    text-decoration: none;
    transition: all 0.2s ease;
}

a:hover {
    text-decoration: none;
    transform: translateX(2px);
}
</style>



<!-- <script setup>
import { ref } from 'vue';
import { Modal, Descriptions, Divider } from 'ant-design-vue';

defineProps({
  open: Boolean,       
  report: Object       
});

const emit = defineEmits(['update:open']);

const handleClose = () => {
  emit('update:open', false);
};


const formatDate = (date) => {
  if (!date) return '';
  const d = new Date(date);
  const options = { day: '2-digit', month: 'short', year: 'numeric' };
  return d.toLocaleDateString('en-GB', options); 
};

</script>

<template>
  <Modal
    :open="open"           
    title="Accident / Incident Report"
    width="600px"
    :footer="null"
    @cancel="handleClose"  
  >
    <div class="space-y-4">
      <Descriptions column="1" bordered size="small">
        <Descriptions.Item label="Report Type">{{ report?.report_type }}</Descriptions.Item>
        <Descriptions.Item label="Reporter Name">{{ report?.reporter_name }}</Descriptions.Item>
        <Descriptions.Item label="Reporter Email">{{ report?.reporter_email }}</Descriptions.Item>
        <Descriptions.Item label="Reporter Phone">{{ report?.reporter_phone || 'N/A' }}</Descriptions.Item>
        <Descriptions.Item label="Date of Occurrence">{{ formatDate(report?.accident_date) }}</Descriptions.Item>
        <Descriptions.Item label="Time of Occurrence">{{ report?.accident_time || 'N/A' }}</Descriptions.Item>
        <Descriptions.Item label="Location">{{ report?.location }}</Descriptions.Item>
        <Descriptions.Item label="Aircraft Registration">{{ report?.aircraft_registration || 'N/A' }}</Descriptions.Item>
        <Descriptions.Item label="Aircraft Type">{{ report?.aircraft_type || 'N/A' }}</Descriptions.Item>
      </Descriptions>

      <Divider />

      <h4 class="font-semibold">Description</h4>
      <p>{{ report?.description }}</p>

      <h4 class="font-semibold">Injuries</h4>
      <p>{{ report?.injuries || 'None reported' }}</p>

      <h4 class="font-semibold">Damage</h4>
      <p>{{ report?.damage || 'None reported' }}</p>
    </div>
  </Modal>
</template>

<style scoped>
p {
  margin: 0;
  line-height: 1.5;
}
</style> -->