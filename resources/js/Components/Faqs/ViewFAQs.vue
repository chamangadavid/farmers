<script setup>
import { ref, watch } from 'vue';
import { Modal, Tag, Divider, Descriptions } from 'ant-design-vue';
import {
  QuestionCircleOutlined,
  FolderOutlined,
  FileTextOutlined,
  CalendarOutlined,
  MessageOutlined
} from '@ant-design/icons-vue';

const props = defineProps({
  faq: Object,
  open: Boolean
});
const emit = defineEmits(['update:open']);
const localVisible = ref(false);

watch(() => props.open, (val) => {
  localVisible.value = val;
});

watch(() => localVisible.value, (val) => {
  if (!val) {
    emit('update:open', false);
  }
});

// Format date if exists
const formatDate = (date) => {
  if (!date) return 'N/A';
  return new Date(date).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  });
};

// Get category color
const getCategoryColor = (category) => {
  const categoryLower = category?.toLowerCase() || '';
  if (categoryLower.includes('general')) return 'blue';
  if (categoryLower.includes('accident')) return 'red';
  if (categoryLower.includes('incident')) return 'orange';
  if (categoryLower.includes('investigation')) return 'purple';
  if (categoryLower.includes('report')) return 'green';
  return 'teal';
};

// Get category icon
const getCategoryIcon = (category) => {
  const categoryLower = category?.toLowerCase() || '';
  if (categoryLower.includes('general')) return '📚';
  if (categoryLower.includes('accident')) return '🚨';
  if (categoryLower.includes('incident')) return '⚠️';
  if (categoryLower.includes('investigation')) return '🔍';
  if (categoryLower.includes('report')) return '📊';
  return '📄';
};
</script>

<template>
  <a-modal v-model:open="localVisible" title="FAQ Details" @cancel="localVisible = false" :footer="null" width="800px"
    :maskClosable="true" class="view-faq-modal">
    <div v-if="faq" class="faq-details">
      <!-- Header with Icon -->
      <div class="header-gradient rounded-xl p-6 mb-6">
        <div class="flex items-center gap-4">
          <div class="w-14 h-14 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center shadow-lg">
            <QuestionCircleOutlined class="text-3xl text-white" />
          </div>
          <div class="flex-1">
            <h3 class="text-xl font-bold text-white mb-1">FAQ Information</h3>
            <p class="text-teal-50 text-sm flex items-center gap-2">
              <FileTextOutlined class="text-sm" />
              Frequently Asked Question Details
            </p>
          </div>
        </div>
      </div>

      <!-- Content Sections -->
      <div class="space-y-6">
        <!-- Category Section -->
        <div class="flex items-start gap-3 p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-all duration-200">
          <div
            class="w-10 h-10 rounded-full bg-gradient-to-r from-teal-100 to-emerald-100 flex items-center justify-center flex-shrink-0">
            <FolderOutlined class="text-teal-600 text-lg" />
          </div>
          <div class="flex-1">
            <div class="text-xs text-gray-500 font-medium uppercase tracking-wide mb-1">Category</div>
            <Tag :color="getCategoryColor(faq.category)" class="px-3 py-1 text-sm">
              <span class="mr-1">{{ getCategoryIcon(faq.category) }}</span>
              {{ faq.category }}
            </Tag>
          </div>
        </div>

        <!-- Question Section -->
        <div class="flex items-start gap-3 p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-all duration-200">
          <div
            class="w-10 h-10 rounded-full bg-gradient-to-r from-teal-100 to-emerald-100 flex items-center justify-center flex-shrink-0">
            <QuestionCircleOutlined class="text-teal-600 text-lg" />
          </div>
          <div class="flex-1">
            <div class="text-xs text-gray-500 font-medium uppercase tracking-wide mb-1">Question</div>
            <div class="text-gray-900 font-medium text-lg leading-relaxed">
              {{ faq.question }}
            </div>
          </div>
        </div>

        <!-- Answer Section -->
        <div
          class="flex items-start gap-3 p-4 bg-gradient-to-br from-gray-50 to-white rounded-xl border border-gray-100">
          <div
            class="w-10 h-10 rounded-full bg-gradient-to-r from-teal-100 to-emerald-100 flex items-center justify-center flex-shrink-0">
            <MessageOutlined class="text-teal-600 text-lg" />
          </div>
          <div class="flex-1">
            <div class="text-xs text-gray-500 font-medium uppercase tracking-wide mb-2">Answer</div>
            <div class="answer-content prose max-w-none text-gray-700 leading-relaxed">
              <!-- Render HTML content safely -->
              <div v-html="faq.answer"></div>
            </div>
          </div>
        </div>

        <!-- Metadata Section (if dates exist) -->
        <div v-if="faq.created_at" class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4 pt-4 border-t border-gray-100">
          <div class="flex items-center gap-2 text-sm text-gray-500">
            <CalendarOutlined class="text-teal-500" />
            <span>Created: {{ formatDate(faq.created_at) }}</span>
          </div>
          <div v-if="faq.updated_at && faq.updated_at !== faq.created_at"
            class="flex items-center gap-2 text-sm text-gray-500">
            <CalendarOutlined class="text-teal-500" />
            <span>Last Updated: {{ formatDate(faq.updated_at) }}</span>
          </div>
        </div>
      </div>

      <!-- Footer Actions -->
      <div class="mt-6 pt-4 border-t border-gray-100 flex justify-end">
        <button @click="localVisible = false"
          class="px-6 py-2 bg-gradient-to-r from-teal-600 to-emerald-600 text-white rounded-lg hover:shadow-lg transition-all duration-200 transform hover:-translate-y-0.5">
          Close
        </button>
      </div>
    </div>

    <!-- Empty State -->
    <div v-else class="text-center py-12">
      <QuestionCircleOutlined class="text-6xl text-gray-300 mb-4" />
      <p class="text-gray-500 text-lg">No FAQ data available</p>
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

/* FAQ Details Container */
.faq-details {
  max-height: 70vh;
  overflow-y: auto;
  padding-right: 4px;
}

/* Custom Scrollbar */
.faq-details::-webkit-scrollbar {
  width: 6px;
}

.faq-details::-webkit-scrollbar-track {
  background: #f1f1f1;
  border-radius: 10px;
}

.faq-details::-webkit-scrollbar-thumb {
  background: linear-gradient(135deg, #14b8a6, #10b981);
  border-radius: 10px;
}

.faq-details::-webkit-scrollbar-thumb:hover {
  background: linear-gradient(135deg, #0d9488, #059669);
}

/* Answer Content Styling */
.answer-content {
  font-size: 0.95rem;
  line-height: 1.6;
}

.answer-content :deep(p) {
  margin-bottom: 0.75rem;
  color: #4b5563;
}

.answer-content :deep(ul),
.answer-content :deep(ol) {
  padding-left: 1.5rem;
  margin-bottom: 0.75rem;
}

.answer-content :deep(li) {
  margin-bottom: 0.25rem;
}

.answer-content :deep(strong) {
  font-weight: 600;
  color: #1f2937;
}

.answer-content :deep(em) {
  font-style: italic;
  color: #6b7280;
}

.answer-content :deep(a) {
  color: #14b8a6;
  text-decoration: none;
}

.answer-content :deep(a:hover) {
  text-decoration: underline;
  color: #0d9488;
}

.answer-content :deep(blockquote) {
  border-left: 3px solid #14b8a6;
  padding-left: 1rem;
  margin: 0.75rem 0;
  color: #6b7280;
  font-style: italic;
}

.answer-content :deep(code) {
  background-color: #f1f5f9;
  padding: 0.2rem 0.4rem;
  border-radius: 4px;
  font-family: monospace;
  font-size: 0.875em;
  color: #d946ef;
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
.faq-details {
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

  .answer-content {
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
}
</style>





<!-- <script setup>
import { ref, watch } from 'vue';
import { Modal } from 'ant-design-vue';

const props = defineProps({
    faq: Object
});
const emit = defineEmits(['update:open']);
const open = ref(false);

watch(() => props.faq, (val) => {
    open.value = !!val;
});
</script>

<template>
  <a-modal
    v-model:open="open"
    title="View FAQ"
    @cancel="emit('update:open', false)"
    :footer="null"
  >
    <div v-if="faq">
      <p><strong>Category:</strong> {{ faq.category }}</p>
      <p><strong>Question:</strong> {{ faq.question }}</p>
      <p><strong>Answer:</strong></p>
      <p>{{ faq.answer }}</p>
    </div>
  </a-modal>
</template> -->