<!-- resources/js/Components/Contact/ViewContactModal.vue -->
<script setup>
import { defineProps, defineEmits, ref, watch } from 'vue';
import { Modal, Descriptions, Tag, Divider } from 'ant-design-vue';
import { 
  UserOutlined, 
  MailOutlined, 
  MessageOutlined, 
  CalendarOutlined,
  TagOutlined,
  PhoneOutlined
} from '@ant-design/icons-vue';

const props = defineProps({
  visible: Boolean,
  contact: Object
});

const emit = defineEmits(['update:visible']); 

const localVisible = ref(false);

// Sync prop with local state
watch(() => props.visible, (val) => {
  localVisible.value = val;
});

const closeModal = () => {
  localVisible.value = false;
  emit('update:visible', false);
};

// Format date if exists
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

// Get subject color based on type
const getSubjectColor = (subject) => {
  const subjectLower = subject?.toLowerCase() || '';
  if (subjectLower.includes('urgent') || subjectLower.includes('emergency')) return 'red';
  if (subjectLower.includes('complaint')) return 'orange';
  if (subjectLower.includes('inquiry') || subjectLower.includes('question')) return 'blue';
  if (subjectLower.includes('feedback')) return 'green';
  return 'teal';
};
</script>

<template>
  <a-modal
    v-model:open="localVisible"
    title="Contact Details"
    @cancel="closeModal"
    :footer="null"
    width="700px"
    :maskClosable="true"
    class="contact-modal"
  >
    <div v-if="contact" class="contact-details">
      <!-- Header with Teal/Emerald Gradient Background -->
      <div class="header-gradient rounded-xl p-6 mb-6">
        <div class="flex items-center gap-4">
          <div class="w-14 h-14 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center shadow-lg">
            <UserOutlined class="text-3xl text-white" />
          </div>
          <div class="flex-1">
            <h3 class="text-xl font-bold text-white mb-1">{{ contact.name }}</h3>
            <p class="text-teal-50 text-sm flex items-center gap-2">
              <MailOutlined class="text-sm" />
              Contact Information
            </p>
          </div>
        </div>
      </div>

      <!-- Contact Information Grid -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
        <!-- Full Name -->
        <div class="flex items-start gap-3 p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-all duration-200">
          <UserOutlined class="text-teal-500 mt-0.5 text-lg" />
          <div class="flex-1">
            <div class="text-xs text-gray-500 font-medium uppercase tracking-wide">Full Name</div>
            <div class="text-gray-900 font-medium">{{ contact.name }}</div>
          </div>
        </div>

        <!-- Email -->
        <div class="flex items-start gap-3 p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-all duration-200">
          <MailOutlined class="text-teal-500 mt-0.5 text-lg" />
          <div class="flex-1">
            <div class="text-xs text-gray-500 font-medium uppercase tracking-wide">Email Address</div>
            <a :href="`mailto:${contact.email}`" class="text-teal-600 hover:text-teal-700 font-medium">
              {{ contact.email }}
            </a>
          </div>
        </div>

        <!-- Subject -->
        <div class="flex items-start gap-3 p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-all duration-200 md:col-span-2">
          <TagOutlined class="text-teal-500 mt-0.5 text-lg" />
          <div class="flex-1">
            <div class="text-xs text-gray-500 font-medium uppercase tracking-wide">Subject</div>
            <div class="mt-1">
              <Tag :color="getSubjectColor(contact.subject)" class="px-3 py-1">
                {{ contact.subject }}
              </Tag>
            </div>
          </div>
        </div>

        <!-- Created Date (if exists) -->
        <div v-if="contact.created_at" class="flex items-start gap-3 p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-all duration-200 md:col-span-2">
          <CalendarOutlined class="text-teal-500 mt-0.5 text-lg" />
          <div class="flex-1">
            <div class="text-xs text-gray-500 font-medium uppercase tracking-wide">Submitted On</div>
            <div class="text-gray-700">{{ formatDate(contact.created_at) }}</div>
          </div>
        </div>
      </div>

      <!-- Divider -->
      <Divider class="my-4">
        <MessageOutlined class="text-teal-500" />
        <span class="ml-2 text-gray-500">Message Content</span>
      </Divider>

      <!-- Message Content -->
      <div class="bg-gradient-to-br from-gray-50 to-white rounded-xl p-6 border border-gray-100">
        <div class="prose prose-sm max-w-none">
          <div class="message-content whitespace-pre-wrap text-gray-700 leading-relaxed">
            {{ contact.message }}
          </div>
        </div>
      </div>

      <!-- Footer Actions -->
      <div class="mt-6 pt-4 border-t border-gray-100 flex justify-end gap-3">
        <button 
          @click="closeModal"
          class="px-4 py-2 text-gray-600 hover:text-gray-800 transition-colors duration-200"
        >
          Close
        </button>
        <a 
          :href="`mailto:${contact.email}?subject=Re: ${contact.subject}`"
          class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-teal-600 to-emerald-600 text-white rounded-lg hover:shadow-lg transition-all duration-200 transform hover:-translate-y-0.5"
        >
          <MailOutlined />
          Reply to Message
        </a>
      </div>
    </div>

    <!-- Empty State (if no contact) -->
    <div v-else class="text-center py-8">
      <MessageOutlined class="text-4xl text-gray-300 mb-3" />
      <p class="text-gray-500">No contact information available</p>
    </div>
  </a-modal>
</template>

<style scoped>
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

/* Modal Styling */
:deep(.ant-modal-header) {
  border-bottom: 2px solid #f0fdf4;
  padding: 20px 24px;
  background: linear-gradient(135deg, #14b8a6 0%, #10b981 50%, #059669 100%);
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

/* Contact Details Container */
.contact-details {
  max-height: 70vh;
  overflow-y: auto;
  padding-right: 4px;
}

/* Custom Scrollbar */
.contact-details::-webkit-scrollbar {
  width: 6px;
}

.contact-details::-webkit-scrollbar-track {
  background: #f1f1f1;
  border-radius: 10px;
}

.contact-details::-webkit-scrollbar-thumb {
  background: linear-gradient(135deg, #14b8a6, #10b981);
  border-radius: 10px;
}

.contact-details::-webkit-scrollbar-thumb:hover {
  background: linear-gradient(135deg, #0d9488, #059669);
}

/* Card Hover Effects */
.bg-gray-50 {
  transition: all 0.2s ease;
}

.bg-gray-50:hover {
  background: #f9fafb;
  transform: translateX(4px);
  box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

/* Message Content Styling */
.message-content {
  font-size: 0.95rem;
  line-height: 1.6;
}

/* Animation for modal content */
.contact-details {
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

/* Responsive adjustments */
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
  
  .message-content {
    font-size: 0.9rem;
  }
}

/* Print styles */
@media print {
  :deep(.ant-modal-close),
  .reply-button,
  .header-gradient::before {
    display: none;
  }
  
  .contact-details {
    max-height: none;
    overflow: visible;
  }
  
  .header-gradient {
    background: #f3f4f6;
    color: #1f2937;
  }
  
  .header-gradient .text-white {
    color: #1f2937;
  }
  
  .header-gradient .w-14.h-14 {
    background: #e5e7eb;
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

/* Button animations */
button, a {
  transition: all 0.2s ease;
}

button:active, a:active {
  transform: scale(0.98);
}

/* Additional hover effect for reply button */
.reply-button:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(20, 184, 166, 0.3);
}
</style>