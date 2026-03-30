<script setup>
import { onMounted, ref, computed } from 'vue'
import { usePage, Head } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { 
  UserOutlined, 
  DatabaseOutlined, 
  ClockCircleOutlined,
  GlobalOutlined,
  FileTextOutlined,
  ArrowLeftOutlined,
  CheckCircleOutlined,
  CloseCircleOutlined,
  EditOutlined,
  DeleteOutlined,
  PlusOutlined
} from '@ant-design/icons-vue'

const audit = ref(null)
const page = usePage()

onMounted(() => {
  audit.value = page.props.audit
})

// Format date
const formatDate = (date) => {
  if (!date) return 'N/A'
  return new Date(date).toLocaleString('en-US', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
    second: '2-digit'
  })
}

// Get action color
const getActionColor = (action) => {
  const colors = {
    'created': '#10b981',
    'updated': '#3b82f6',
    'deleted': '#ef4444',
    'restored': '#8b5cf6'
  }
  return colors[action?.toLowerCase()] || '#6b7280'
}

// Get action icon
const getActionIcon = (action) => {
  const icons = {
    'created': PlusOutlined,
    'updated': EditOutlined,
    'deleted': DeleteOutlined
  }
  return icons[action?.toLowerCase()] || EditOutlined
}

// Get model name
const getModelName = (modelType) => {
  if (!modelType) return 'N/A'
  const parts = modelType.split('\\')
  return parts[parts.length - 1]
}
</script>

<template>
  <Head title="Audit Trail Details" />
  <AuthenticatedLayout>
    <template #header>
      <div class="flex justify-between items-center">
        <div>
          <h2 class="text-2xl font-bold text-gray-800 dark:text-white">
            Audit Trail Details
          </h2>
          <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
            Detailed view of system activity and changes
          </p>
        </div>
        <div class="flex gap-3">
          <button 
            @click="$inertia.visit('/audit-logs')"
            class="px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-all duration-200 text-sm font-medium flex items-center gap-2"
          >
            <ArrowLeftOutlined />
            Back to Audit Logs
          </button>
        </div>
      </div>
    </template>

    <div class="py-12">
      <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        
        <!-- Loading State -->
        <div v-if="!audit" class="flex justify-center items-center py-20">
          <a-spin size="large" />
        </div>

        <!-- Audit Details -->
        <div v-else class="space-y-6">
          
          <!-- Header Card -->
          <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-xl border border-gray-100 dark:border-gray-700">
            <div class="bg-gradient-to-r from-teal-600 to-emerald-600 px-6 py-5">
              <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                  <h3 class="text-xl font-bold text-white">Audit Log Entry</h3>
                  <p class="text-white/80 text-sm mt-1">ID: #{{ audit.id }}</p>
                </div>
                <div class="flex gap-2">
                  <span 
                    class="px-3 py-1 bg-white/20 backdrop-blur-sm rounded-full text-white text-sm font-semibold flex items-center gap-2"
                    :style="{ borderLeft: `3px solid ${getActionColor(audit.action)}` }"
                  >
                    <component :is="getActionIcon(audit.action)" />
                    {{ audit.action?.toUpperCase() || 'ACTION' }}
                  </span>
                </div>
              </div>
            </div>
            
            <div class="p-6">
              <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <!-- Model Information -->
                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4 border border-gray-200 dark:border-gray-600">
                  <div class="flex items-center gap-2 text-gray-600 dark:text-gray-400 mb-2">
                    <DatabaseOutlined />
                    <span class="text-xs font-medium uppercase tracking-wide">Model</span>
                  </div>
                  <div class="text-gray-900 dark:text-white font-medium font-mono text-sm">
                    {{ getModelName(audit.auditable_type) }}
                  </div>
                  <div class="text-xs text-gray-500 dark:text-gray-400 mt-1 break-all">
                    {{ audit.auditable_type }}
                  </div>
                </div>
                
                <!-- Record ID -->
                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4 border border-gray-200 dark:border-gray-600">
                  <div class="flex items-center gap-2 text-gray-600 dark:text-gray-400 mb-2">
                    <FileTextOutlined />
                    <span class="text-xs font-medium uppercase tracking-wide">Record ID</span>
                  </div>
                  <div class="text-gray-900 dark:text-white font-medium font-mono">
                    #{{ audit.auditable_id }}
                  </div>
                </div>
                
                <!-- User Information -->
                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4 border border-gray-200 dark:border-gray-600">
                  <div class="flex items-center gap-2 text-gray-600 dark:text-gray-400 mb-2">
                    <UserOutlined />
                    <span class="text-xs font-medium uppercase tracking-wide">User</span>
                  </div>
                  <div class="text-gray-900 dark:text-white font-medium">
                    {{ audit.user?.name || 'System' }}
                  </div>
                  <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                    {{ audit.user?.email || 'No email available' }}
                  </div>
                </div>
                
                <!-- IP Address -->
                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4 border border-gray-200 dark:border-gray-600">
                  <div class="flex items-center gap-2 text-gray-600 dark:text-gray-400 mb-2">
                    <GlobalOutlined />
                    <span class="text-xs font-medium uppercase tracking-wide">IP Address</span>
                  </div>
                  <div class="text-gray-900 dark:text-white font-mono text-sm">
                    {{ audit.ip_address || 'N/A' }}
                  </div>
                </div>
                
                <!-- Date & Time -->
                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4 border border-gray-200 dark:border-gray-600">
                  <div class="flex items-center gap-2 text-gray-600 dark:text-gray-400 mb-2">
                    <ClockCircleOutlined />
                    <span class="text-xs font-medium uppercase tracking-wide">Date & Time</span>
                  </div>
                  <div class="text-gray-900 dark:text-white font-medium">
                    {{ formatDate(audit.created_at) }}
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Values Comparison -->
          <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            
            <!-- Old Values Card -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-100 dark:border-gray-700 overflow-hidden">
              <div class="bg-red-50 dark:bg-red-950/30 px-6 py-4 border-b border-red-200 dark:border-red-800">
                <div class="flex items-center gap-2">
                  <CloseCircleOutlined class="text-red-600 dark:text-red-400 text-lg" />
                  <h3 class="text-lg font-semibold text-red-900 dark:text-red-300">Old Values</h3>
                  <span class="ml-auto text-xs text-red-600 dark:text-red-400">Before Changes</span>
                </div>
              </div>
              <div class="p-6">
                <pre class="bg-gray-900 text-gray-100 p-4 rounded-lg overflow-x-auto text-sm font-mono">{{ JSON.stringify(audit.old_values, null, 2) }}</pre>
              </div>
            </div>

            <!-- New Values Card -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-100 dark:border-gray-700 overflow-hidden">
              <div class="bg-green-50 dark:bg-green-950/30 px-6 py-4 border-b border-green-200 dark:border-green-800">
                <div class="flex items-center gap-2">
                  <CheckCircleOutlined class="text-green-600 dark:text-green-400 text-lg" />
                  <h3 class="text-lg font-semibold text-green-900 dark:text-green-300">New Values</h3>
                  <span class="ml-auto text-xs text-green-600 dark:text-green-400">After Changes</span>
                </div>
              </div>
              <div class="p-6">
                <pre class="bg-gray-900 text-gray-100 p-4 rounded-lg overflow-x-auto text-sm font-mono">{{ JSON.stringify(audit.new_values, null, 2) }}</pre>
              </div>
            </div>

          </div>

          <!-- Summary Section (if values changed) -->
          <div v-if="audit.old_values && audit.new_values" class="bg-blue-50 dark:bg-blue-950/30 rounded-xl p-4 border border-blue-200 dark:border-blue-800">
            <div class="flex items-start gap-3">
              <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900/50 rounded-full flex items-center justify-center flex-shrink-0">
                <FileTextOutlined class="text-blue-600 dark:text-blue-400" />
              </div>
              <div>
                <p class="text-sm font-medium text-blue-900 dark:text-blue-300">Change Summary</p>
                <p class="text-xs text-blue-800 dark:text-blue-400 mt-1">
                  {{ audit.action === 'created' ? 'New record was created in the system.' : 
                     audit.action === 'deleted' ? 'Record was permanently removed from the system.' : 
                     'Record was updated with new values. Compare the old and new values above to see specific changes.' }}
                </p>
              </div>
            </div>
          </div>

        </div>
        
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<style scoped>
/* Custom scrollbar for pre blocks */
pre {
  scrollbar-width: thin;
  scrollbar-color: #cbd5e1 #1f2937;
}

pre::-webkit-scrollbar {
  height: 8px;
  width: 8px;
}

pre::-webkit-scrollbar-track {
  background: #1f2937;
  border-radius: 4px;
}

pre::-webkit-scrollbar-thumb {
  background: #cbd5e1;
  border-radius: 4px;
}

pre::-webkit-scrollbar-thumb:hover {
  background: #14b8a6;
}

/* Animation for cards */
.bg-white, .bg-gray-50 {
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

/* Hover effects for info cards */
.bg-gray-50 {
  transition: all 0.2s ease;
}

.bg-gray-50:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
}

/* Gradient text for headers */
.bg-gradient-to-r {
  position: relative;
  overflow: hidden;
}

/* Responsive adjustments */
@media (max-width: 768px) {
  .grid {
    gap: 1rem;
  }
  
  pre {
    font-size: 11px;
  }
}

/* Dark mode pre styling */
.dark pre {
  background: #0f172a;
  color: #e2e8f0;
}

/* Smooth transitions */
.transition-all {
  transition-property: all;
  transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
  transition-duration: 300ms;
}

/* Back button hover effect */
.back-btn:hover {
  transform: translateX(-2px);
}
</style>


<!-- <script setup>
import { onMounted, ref } from 'vue'
import { usePage, Head } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

const audit = ref(null)
const page = usePage()

onMounted(() => {
  audit.value = page.props.audit
})
</script>

<template>
    <Head title="Audit Trail Logs" />
  <AuthenticatedLayout>
    <div>
      <h2 class="text-xl font-bold mb-4">Audit Details</h2>

      <div v-if="audit">
        
        <a-card bordered>
          <p><strong>Action:</strong> {{ audit.action }}</p>
          <p><strong>Model:</strong> {{ audit.auditable_type }}</p>
          <p><strong>Record ID:</strong> {{ audit.auditable_id }}</p>
          <p><strong>User:</strong> {{ audit.user?.name || 'System' }}</p>
          <p><strong>IP:</strong> {{ audit.ip_address }}</p>
          <p><strong>Date:</strong> {{ audit.created_at }}</p>
        </a-card>

        <a-row :gutter="16" class="mt-4">

          <a-col :span="12">
            <a-card title="Old Values">
              <pre>{{ JSON.stringify(audit.old_values, null, 2) }}</pre>
            </a-card>
          </a-col>

          <a-col :span="12">
            <a-card title="New Values">
              <pre>{{ JSON.stringify(audit.new_values, null, 2) }}</pre>
            </a-card>
          </a-col>
        </a-row>

      </div>

      <div v-else>
        <a-spin />
      </div>

    </div>
  </AuthenticatedLayout>
</template> -->