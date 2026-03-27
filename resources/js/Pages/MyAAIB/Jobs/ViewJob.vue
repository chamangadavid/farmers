<script setup>
import { ref, watch, computed } from 'vue';
import { Modal, Tag, Divider, Descriptions, Button, message } from 'ant-design-vue';
import { 
  FilePdfOutlined, 
  DownloadOutlined, 
  CalendarOutlined, 
  EnvironmentOutlined,
  TeamOutlined,
  CheckCircleOutlined,
  ClockCircleOutlined,
  CloseCircleOutlined,
  FileTextOutlined,
  UserOutlined,
  MailOutlined
} from '@ant-design/icons-vue';
import dayjs from 'dayjs';

const props = defineProps({
  open: Boolean, // modal open state
  job: Object,   // job data to view
});

const emit = defineEmits(['update:open']);

// Local reactive state for modal
const localOpen = ref(props.open);

// Sync prop → local state
watch(() => props.open, val => localOpen.value = val);

// Sync local state → emit to parent
watch(localOpen, val => emit('update:open', val));

// Status color and icon mapping
const getStatusConfig = (status) => {
  const configs = {
    'Open': { color: 'green', icon: CheckCircleOutlined, text: 'Open for Applications' },
    'Closed': { color: 'red', icon: CloseCircleOutlined, text: 'Closed' },
    'Pending': { color: 'orange', icon: ClockCircleOutlined, text: 'Pending Review' },
    'Draft': { color: 'default', icon: FileTextOutlined, text: 'Draft' },
    'Published': { color: 'blue', icon: CheckCircleOutlined, text: 'Published' },
    'Filled': { color: 'purple', icon: TeamOutlined, text: 'Position Filled' }
  };
  return configs[status] || { color: 'default', icon: FileTextOutlined, text: status || 'Unknown' };
};

// Format deadline with status
const formattedDeadline = computed(() => {
  if (!props.job?.deadline) return 'Not specified';
  const deadline = dayjs(props.job.deadline);
  const today = dayjs();
  const daysUntil = deadline.diff(today, 'day');
  
  if (daysUntil < 0) return { text: `Expired (${deadline.format('MMMM D, YYYY')})`, isExpired: true };
  if (daysUntil === 0) return { text: `Today - ${deadline.format('MMMM D, YYYY')}`, isUrgent: true };
  if (daysUntil <= 7) return { text: `${daysUntil} day(s) left - ${deadline.format('MMMM D, YYYY')}`, isUrgent: true };
  return { text: deadline.format('MMMM D, YYYY'), isExpired: false };
});

// Handle PDF download
const downloadPDF = () => {
  if (props.job?.pdf_file) {
    const link = document.createElement('a');
    link.href = `/storage/${props.job.pdf_file}`;
    link.download = `${props.job.title}_job_description.pdf`;
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    message.success('Download started');
  }
};

// Format requirements as list
const formattedRequirements = computed(() => {
  if (!props.job?.requirements) return [];
  if (Array.isArray(props.job.requirements)) return props.job.requirements;
  if (typeof props.job.requirements === 'string') {
    return props.job.requirements.split(',').map(r => r.trim());
  }
  return [];
});
</script>

<template>
  <Modal 
    v-model:open="localOpen" 
    title="Job Details" 
    :footer="null" 
    width="900px"
    :bodyStyle="{ padding: '24px' }"
    class="job-details-modal"
  >
    <div v-if="job" class="space-y-6">
      <!-- Header Section with Status -->
      <div class="flex justify-between items-start border-b border-gray-200 pb-4">
        <div>
          <h2 class="text-2xl font-bold text-gray-900 mb-2">{{ job.title }}</h2>
          <div class="flex items-center gap-3 text-sm text-gray-500">
            <span class="flex items-center gap-1">
              <FileTextOutlined />
              {{ job.reference || 'No reference' }}
            </span>
            <span class="w-1 h-1 bg-gray-300 rounded-full"></span>
            <span class="flex items-center gap-1">
              <UserOutlined />
              Posted {{ job.created_at ? dayjs(job.created_at).format('MMM D, YYYY') : 'Recently' }}
            </span>
          </div>
        </div>
        <Tag :color="getStatusConfig(job.status).color" class="status-tag">
          <component :is="getStatusConfig(job.status).icon" class="mr-1" />
          {{ getStatusConfig(job.status).text }}
        </Tag>
      </div>

      <!-- Key Information Cards -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
          <div class="flex items-center gap-2 text-gray-600 mb-2">
            <TeamOutlined class="text-teal-600" />
            <span class="text-sm font-medium">Job Type</span>
          </div>
          <div class="text-gray-900 font-semibold">{{ job.type || 'Not specified' }}</div>
        </div>

        <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
          <div class="flex items-center gap-2 text-gray-600 mb-2">
            <EnvironmentOutlined class="text-teal-600" />
            <span class="text-sm font-medium">Location</span>
          </div>
          <div class="text-gray-900 font-semibold">{{ job.location || 'Not specified' }}</div>
        </div>

        <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
          <div class="flex items-center gap-2 text-gray-600 mb-2">
            <CalendarOutlined class="text-teal-600" />
            <span class="text-sm font-medium">Application Deadline</span>
          </div>
          <div class="flex items-center gap-2">
            <span 
              :class="{
                'text-red-600 font-semibold': formattedDeadline?.isExpired,
                'text-orange-600 font-semibold': formattedDeadline?.isUrgent && !formattedDeadline?.isExpired,
                'text-gray-900': !formattedDeadline?.isExpired && !formattedDeadline?.isUrgent
              }"
            >
              {{ formattedDeadline?.text || 'Not specified' }}
            </span>
            <Tag v-if="formattedDeadline?.isExpired" color="red" size="small">Expired</Tag>
            <Tag v-else-if="formattedDeadline?.isUrgent" color="orange" size="small">Urgent</Tag>
          </div>
        </div>

        <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
          <div class="flex items-center gap-2 text-gray-600 mb-2">
            <MailOutlined class="text-teal-600" />
            <span class="text-sm font-medium">Contact Email</span>
          </div>
          <div class="text-gray-900 font-semibold">{{ job.contact_email || 'Not specified' }}</div>
        </div>
      </div>

      <Divider class="my-4" />

      <!-- Job Description -->
      <div>
        <h3 class="text-lg font-semibold text-gray-900 mb-3 flex items-center gap-2">
          <FileTextOutlined class="text-teal-600" />
          Job Description
        </h3>
        <div class="prose prose-sm max-w-none text-gray-700 bg-gray-50 rounded-lg p-4 border border-gray-200">
          <p class="whitespace-pre-wrap leading-relaxed">
            {{ job.description || 'No description provided.' }}
          </p>
        </div>
      </div>

      <!-- Requirements Section -->
      <div v-if="formattedRequirements.length > 0">
        <h3 class="text-lg font-semibold text-gray-900 mb-3 flex items-center gap-2">
          <CheckCircleOutlined class="text-teal-600" />
          Requirements
        </h3>
        <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
          <ul class="space-y-2">
            <li 
              v-for="(req, index) in formattedRequirements" 
              :key="index"
              class="flex items-start gap-2 text-gray-700"
            >
              <span class="text-teal-600 mt-1">•</span>
              <span class="flex-1">{{ req }}</span>
            </li>
          </ul>
        </div>
      </div>

      <!-- PDF Preview Section -->
      <div v-if="job?.pdf_file" class="space-y-3">
        <Divider class="my-4" />
        <div class="flex justify-between items-center">
          <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
            <FilePdfOutlined class="text-red-500" />
            Job Description PDF
          </h3>
          <Button 
            type="primary" 
            size="small"
            @click="downloadPDF"
            class="bg-teal-600 hover:bg-teal-700"
          >
            <DownloadOutlined class="mr-1" />
            Download PDF
          </Button>
        </div>
        
        <div class="pdf-preview-container border border-gray-200 rounded-lg overflow-hidden bg-gray-100">
          <iframe
            :src="`/storage/${job.pdf_file}`"
            width="100%"
            height="500px"
            class="pdf-iframe"
            frameborder="0"
          ></iframe>
        </div>
        
        <div class="text-center text-sm text-gray-500">
          <p>Having trouble viewing the PDF? <a :href="`/storage/${job.pdf_file}`" target="_blank" class="text-teal-600 hover:text-teal-700 underline">Open in new window</a></p>
        </div>
      </div>

      <!-- Additional Information -->
      <div v-if="job.additional_info" class="mt-4">
        <Divider class="my-4" />
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
          <div class="flex items-start gap-2">
            <span class="text-blue-600 text-lg">ℹ️</span>
            <div>
              <h4 class="text-sm font-semibold text-blue-900 mb-1">Additional Information</h4>
              <p class="text-sm text-blue-800">{{ job.additional_info }}</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Footer Actions -->
      <Divider class="my-4" />
      <div class="flex justify-end gap-3">
        <Button @click="localOpen = false" class="border-gray-300 hover:border-teal-600">
          Close
        </Button>
        <Button 
          v-if="job?.pdf_file"
          type="primary" 
          @click="downloadPDF"
          class="bg-teal-600 hover:bg-teal-700"
        >
          <DownloadOutlined class="mr-1" />
          Download PDF
        </Button>
      </div>
    </div>

    <!-- Empty State -->
    <div v-else class="text-center py-12">
      <div class="text-6xl mb-4">📄</div>
      <p class="text-gray-500 text-lg">No job details available</p>
    </div>
  </Modal>
</template>

<style scoped>
.job-details-modal :deep(.ant-modal-header) {
  border-bottom: 2px solid #f0f0f0;
  padding: 20px 24px;
  background: linear-gradient(to right, #f8fafc, #ffffff);
}

.job-details-modal :deep(.ant-modal-title) {
  font-size: 1.25rem;
  font-weight: 600;
  color: #1f2937;
}

.job-details-modal :deep(.ant-modal-body) {
  padding: 0;
}

.job-details-modal :deep(.ant-modal-content) {
  border-radius: 12px;
  overflow: hidden;
}

.status-tag {
  padding: 4px 12px;
  font-size: 0.875rem;
  font-weight: 500;
  border-radius: 20px;
  display: inline-flex;
  align-items: center;
  gap: 4px;
}

.pdf-preview-container {
  position: relative;
  background: #f5f5f5;
  transition: all 0.3s ease;
}

.pdf-preview-container:hover {
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.pdf-iframe {
  background: white;
}

/* Responsive adjustments */
@media (max-width: 768px) {
  .job-details-modal :deep(.ant-modal) {
    max-width: 95%;
    margin: 16px;
  }
  
  .grid {
    gap: 12px;
  }
  
  .pdf-preview-container iframe {
    height: 300px;
  }
}

/* Prose styling for description */
.prose p {
  margin-bottom: 0.75rem;
  line-height: 1.6;
}

.prose p:last-child {
  margin-bottom: 0;
}

/* Animation for modal */
.job-details-modal :deep(.ant-modal) {
  animation: slideUp 0.3s ease-out;
}

@keyframes slideUp {
  from {
    opacity: 0;
    transform: translateY(30px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* Hover effects for cards */
.bg-gray-50 {
  transition: all 0.2s ease;
}

.bg-gray-50:hover {
  transform: translateY(-2px);
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}

/* Custom scrollbar for iframe container */
.pdf-preview-container::-webkit-scrollbar {
  width: 8px;
  height: 8px;
}

.pdf-preview-container::-webkit-scrollbar-track {
  background: #f1f1f1;
  border-radius: 4px;
}

.pdf-preview-container::-webkit-scrollbar-thumb {
  background: #cbd5e1;
  border-radius: 4px;
}

.pdf-preview-container::-webkit-scrollbar-thumb:hover {
  background: #14b8a6;
}
</style>


<!-- <script setup>
import { ref, watch } from 'vue';
import { Modal } from 'ant-design-vue';
import dayjs from 'dayjs';

const props = defineProps({
  open: Boolean, 
  job: Object,  
});

const emit = defineEmits(['update:open']);
const localOpen = ref(props.open);

watch(() => props.open, val => localOpen.value = val);

watch(localOpen, val => emit('update:open', val));
</script>

<template>
  <Modal v-model:open="localOpen" title="Job Details" footer={null} width="800px">
    <div class="mb-2">
      <strong>Title:</strong> {{ job?.title }}
    </div>

    <div class="mb-2">
      <strong>Type:</strong> {{ job?.type }}
    </div>

    <div class="mb-2">
      <strong>Location:</strong> {{ job?.location }}
    </div>

    <div class="mb-2">
    <strong>Deadline:</strong> {{ job?.deadline ? dayjs(job.deadline).format('YYYY-MM-DD') : 'N/A' }}
    </div>

    <div class="mb-2">
      <strong>Description:</strong> {{ job?.description }}
    </div>

    <div class="mb-2">
      <strong>Requirements:</strong>
      <span v-if="job?.requirements && job.requirements.length">
        <span v-for="(req, index) in job.requirements" :key="index">
          {{ req }}<span v-if="index < job.requirements.length - 1">, </span>
        </span>
      </span>
      <span v-else>N/A</span>
    </div>

    <div class="mb-2" v-if="job?.pdf_file">
      <strong>PDF Preview:</strong>
      <iframe
        :src="`/storage/${job.pdf_file}`"
        width="100%"
        height="400px"
        style="border:1px solid #ccc; margin-top:8px;"
      ></iframe>
    </div>
  </Modal>
</template> -->