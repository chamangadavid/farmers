<!-- resources\js\Pages\Site\InvestigationDetails.vue -->
<!-- resources/js/Pages/Site/InvestigationDetails.vue -->
<script setup>
import PageLayout from '@/Components/PageLayout.vue';
import { router } from '@inertiajs/vue3';
import { onMounted, computed } from 'vue';
import { Tag, Divider, Button, message } from 'ant-design-vue';
import { 
    FilePdfOutlined, 
    DownloadOutlined,
    CalendarOutlined,
    ClockCircleOutlined,
    EnvironmentOutlined,
    UserOutlined,
    MailOutlined,
    PhoneOutlined,
    CarOutlined,
    AlertOutlined,
    FileTextOutlined,
    SafetyOutlined,
    MedicineBoxOutlined,
    ToolOutlined
} from '@ant-design/icons-vue';

const props = defineProps({
    result: Object
});

// Format date
const formatDate = (date) => {
    if (!date) return 'N/A';
    return new Date(date).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
};

// Format time
const formatTime = (time) => {
    if (!time) return 'N/A';
    return time;
};

// Get status color
const getStatusColor = (status) => {
    const colors = {
        'Open': 'orange',
        'In Progress': 'blue',
        'Under Investigation': 'blue',
        'Closed': 'green',
        'Completed': 'green',
        'Pending': 'orange',
        'Draft': 'default'
    };
    return colors[status] || 'default';
};

// Get report type color
const getReportTypeColor = (type) => {
    const colors = {
        'Preliminary': 'orange',
        'Interim': 'blue',
        'Final': 'green',
        'Safety Recommendation': 'purple'
    };
    return colors[type] || 'default';
};

// Get file URL
const getFileUrl = (filePath) => {
    if (!filePath) return null;
    if (filePath.startsWith('http')) return filePath;
    let cleanPath = filePath.replace(/^storage\//, '');
    cleanPath = cleanPath.replace(/^\/+/, '');
    return `/storage/${cleanPath}`;
};

// Download file
const downloadFile = () => {
    if (!props.result?.follow_up_file) {
        message.warning('No file available for download');
        return;
    }
    
    const fileUrl = getFileUrl(props.result.follow_up_file);
    const link = document.createElement('a');
    link.href = fileUrl;
    link.download = `investigation_${props.result.id}_report.pdf`;
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    message.success('Download started');
};

// Get file name
const getFileName = (filePath) => {
    if (!filePath) return '';
    return filePath.split('/').pop();
};

// Check if file exists
const hasFile = computed(() => {
    return !!props.result?.follow_up_file;
});
</script>

<template>
    <PageLayout title="Investigation Details" :showHero="true">
        <!-- Custom Hero Section -->
        <template #hero>
            <div class="text-center mt-12">
                <h1 class="text-4xl sm:text-5xl lg:text-6xl xl:text-7xl font-bold leading-tight mb-6">
                    <span class="bg-gradient-to-r from-teal-400 via-emerald-400 to-teal-300 bg-clip-text text-transparent">
                        Investigation Details
                    </span>
                </h1>
                <p class="text-lg sm:text-xl text-white/80 max-w-3xl mx-auto">
                    Comprehensive investigation report and findings
                </p>
            </div>
        </template>

        <!-- Main Content -->
        <div class="max-w-5xl mx-auto space-y-6">
            <!-- Header Card -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100">
                <div class="bg-gradient-to-r from-teal-600 to-emerald-600 px-6 py-5">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                        <div>
                            <h2 class="text-2xl font-bold text-white">Investigation Report</h2>
                            <p class="text-white/80 text-sm mt-1">Case #{{ result.id }}</p>
                        </div>
                        <div class="flex gap-2">
                            <Tag :color="getStatusColor(result.status)" class="px-3 py-1 text-sm font-semibold">
                                <SafetyOutlined class="mr-1" />
                                {{ result.status || 'Unknown' }}
                            </Tag>
                            <Tag :color="getReportTypeColor(result.report_type)" class="px-3 py-1 text-sm font-semibold">
                                <FileTextOutlined class="mr-1" />
                                {{ result.report_type || 'Standard' }} Report
                            </Tag>
                        </div>
                    </div>
                </div>

                <!-- Incident Overview -->
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <AlertOutlined class="text-teal-600" />
                        Incident Overview
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div class="bg-gray-50 rounded-lg p-3 border border-gray-200">
                            <div class="flex items-center gap-2 text-gray-600 mb-1">
                                <CalendarOutlined />
                                <span class="text-xs font-medium uppercase tracking-wide">Accident Date</span>
                            </div>
                            <div class="text-gray-900 font-medium">{{ formatDate(result.accident_date) }}</div>
                        </div>
                        
                        <div class="bg-gray-50 rounded-lg p-3 border border-gray-200">
                            <div class="flex items-center gap-2 text-gray-600 mb-1">
                                <ClockCircleOutlined />
                                <span class="text-xs font-medium uppercase tracking-wide">Accident Time</span>
                            </div>
                            <div class="text-gray-900 font-medium">{{ formatTime(result.accident_time) }}</div>
                        </div>
                        
                        <div class="bg-gray-50 rounded-lg p-3 border border-gray-200">
                            <div class="flex items-center gap-2 text-gray-600 mb-1">
                                <EnvironmentOutlined />
                                <span class="text-xs font-medium uppercase tracking-wide">Location</span>
                            </div>
                            <div class="text-gray-900 font-medium">{{ result.location || 'Not specified' }}</div>
                        </div>
                        
                        <div class="bg-gray-50 rounded-lg p-3 border border-gray-200">
                            <div class="flex items-center gap-2 text-gray-600 mb-1">
                                <CarOutlined />
                                <span class="text-xs font-medium uppercase tracking-wide">Aircraft Registration</span>
                            </div>
                            <div class="text-gray-900 font-medium font-mono">{{ result.aircraft_registration || 'N/A' }}</div>
                        </div>
                    </div>

                    <!-- Description -->
                    <div v-if="result.description" class="mb-6">
                        <h4 class="text-sm font-semibold text-gray-700 mb-2">Incident Description</h4>
                        <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                            <p class="text-gray-700 leading-relaxed whitespace-pre-wrap">{{ result.description }}</p>
                        </div>
                    </div>

                    <!-- Injuries & Damages -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div v-if="result.injuries" class="bg-red-50 rounded-lg p-4 border border-red-200">
                            <div class="flex items-center gap-2 mb-2">
                                <MedicineBoxOutlined class="text-red-600" />
                                <h4 class="font-semibold text-red-900">Injuries</h4>
                            </div>
                            <p class="text-red-800 text-sm">{{ result.injuries }}</p>
                        </div>
                        
                        <div v-if="result.damage" class="bg-amber-50 rounded-lg p-4 border border-amber-200">
                            <div class="flex items-center gap-2 mb-2">
                                <ToolOutlined class="text-amber-600" />
                                <h4 class="font-semibold text-amber-900">Damages</h4>
                            </div>
                            <p class="text-amber-800 text-sm">{{ result.damage }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Reporter Information -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100">
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <UserOutlined class="text-teal-600" />
                        Reporter Information
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-gray-50 rounded-lg p-3 border border-gray-200">
                            <div class="flex items-center gap-2 text-gray-600 mb-1">
                                <UserOutlined />
                                <span class="text-xs font-medium uppercase tracking-wide">Name</span>
                            </div>
                            <div class="text-gray-900 font-medium">{{ result.reporter_name || 'Anonymous' }}</div>
                        </div>
                        
                        <div class="bg-gray-50 rounded-lg p-3 border border-gray-200">
                            <div class="flex items-center gap-2 text-gray-600 mb-1">
                                <MailOutlined />
                                <span class="text-xs font-medium uppercase tracking-wide">Email</span>
                            </div>
                            <div class="text-gray-900">
                                <a :href="`mailto:${result.reporter_email}`" class="text-teal-600 hover:text-teal-700">
                                    {{ result.reporter_email || 'Not provided' }}
                                </a>
                            </div>
                        </div>
                        
                        <div class="bg-gray-50 rounded-lg p-3 border border-gray-200">
                            <div class="flex items-center gap-2 text-gray-600 mb-1">
                                <PhoneOutlined />
                                <span class="text-xs font-medium uppercase tracking-wide">Phone</span>
                            </div>
                            <div class="text-gray-900">{{ result.reporter_phone || 'Not provided' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Attached Files -->
            <div v-if="hasFile" class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100">
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <FilePdfOutlined class="text-red-500" />
                        Attached Documents
                    </h3>
                </div>
                <div class="p-6">
                    <div class="flex justify-between items-center p-4 bg-gray-50 rounded-lg border border-gray-200 hover:shadow-md transition-all duration-200">
                        <div class="flex items-center gap-3">
                            <FilePdfOutlined class="text-red-500 text-2xl" />
                            <div>
                                <p class="text-sm font-medium text-gray-900">Investigation Report</p>
                                <p class="text-xs text-gray-500 font-mono">{{ getFileName(result.follow_up_file) }}</p>
                            </div>
                        </div>
                        <Button 
                            @click="downloadFile"
                            class="border-teal-600 text-teal-600 hover:bg-teal-50"
                        >
                            <DownloadOutlined /> Download Report
                        </Button>
                    </div>
                </div>
            </div>

            <!-- Additional Information -->
            <div class="bg-blue-50 rounded-xl p-4 border border-blue-200">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-blue-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div>
                        <p class="text-gray-700 text-sm">
                            <strong class="text-blue-600">Note:</strong> This investigation is conducted in accordance with ICAO Annex 13 guidelines.
                            For more information about this investigation, please contact our investigation department.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Back Button -->
            <div class="flex justify-center pt-4">
                <button 
                    @click="router.visit('/')" 
                    class="px-6 py-2.5 border border-teal-600 text-teal-600 rounded-lg hover:bg-teal-50 transition-all duration-300 text-sm font-medium flex items-center gap-2"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to Reports
                </button>
            </div>
        </div>
    </PageLayout>
</template>

<style scoped>
/* Smooth scrolling */
:global(html) {
    scroll-behavior: smooth;
}

/* Animation for cards */
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

.bg-white {
    animation: fadeInUp 0.4s ease-out;
}

/* Hover effects */
.bg-gray-50 {
    transition: all 0.2s ease;
}

.bg-gray-50:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
}

/* Tag styling */
:deep(.ant-tag) {
    border-radius: 20px;
    padding: 4px 12px;
    font-weight: 500;
    border: none;
}

:deep(.ant-tag-green) {
    background: #dcfce7;
    color: #166534;
}

:deep(.ant-tag-blue) {
    background: #dbeafe;
    color: #1e40af;
}

:deep(.ant-tag-orange) {
    background: #ffedd5;
    color: #9a3412;
}

:deep(.ant-tag-purple) {
    background: #f3e8ff;
    color: #6b21a5;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .max-w-5xl {
        padding-left: 1rem;
        padding-right: 1rem;
    }
    
    .grid {
        gap: 12px;
    }
}

/* Custom scrollbar */
::-webkit-scrollbar {
    width: 8px;
}

::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 4px;
}

::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
    background: #14b8a6;
}

/* Button styling */
button {
    transition: all 0.2s ease;
}

button:active {
    transform: scale(0.98);
}
</style>

<!-- <script setup>
import PageLayout from '@/Components/PageLayout.vue';
import { router } from '@inertiajs/vue3';
import { onMounted } from 'vue';

const props = defineProps({
    result: Object
});

</script>

<template>
    <PageLayout>
 <div>
        <h1>Investigation Details</h1>
    </div>

    <div>
        <p>Investigation ID: {{ result.id }}</p>
        <p>Status: {{ result.status }}</p>
        <p>Report Type: {{ result.report_type }}</p>
        <p>Reporter's Name: {{ result.reporter_name }}</p>
        <p>Reporter's Email: {{ result.reporter_email }}</p>
        <p>Reporter Phone #: {{ result.reporter_phone }}</p>
        <p>Accident Date: {{ result.accident_date }}</p>
        <p>Accident Time: {{ result.accident_time }}</p>
        <p>Accident Location: {{ result.location }}</p>
        <p>Aircraft Registration: {{ result.aircraft_registration }}</p>
        <p>Description: {{ result.description }}</p>
        <p>Injuries: {{ result.injuries }}</p>
        <p>Damages: {{ result.damage }}</p>
        <p>Files {{ result.follow_up_file }}</p>

    </div>
    </PageLayout>
   
</template> -->