<script setup>
import { ref, onMounted } from 'vue'
import { router, Head } from '@inertiajs/vue3'
import { message, Tag, Tooltip } from 'ant-design-vue'
import axios from 'axios'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { 
  EyeOutlined, 
  UserOutlined, 
  DatabaseOutlined, 
  ClockCircleOutlined,
  DeleteOutlined,
  EditOutlined,
  PlusOutlined,
  LoginOutlined,
  LogoutOutlined,
  SettingOutlined
} from '@ant-design/icons-vue'

const loading = ref(false)
const audits = ref([])

// Action color mapping
const getActionColor = (action) => {
  const colors = {
    'created': 'green',
    'updated': 'blue',
    'deleted': 'red',
    'restored': 'purple',
    'login': 'teal',
    'logout': 'orange',
    'updated_profile': 'cyan',
    'changed_password': 'magenta'
  }
  return colors[action?.toLowerCase()] || 'default'
}

// Get action icon
const getActionIcon = (action) => {
  const icons = {
    'created': PlusOutlined,
    'updated': EditOutlined,
    'deleted': DeleteOutlined,
    'login': LoginOutlined,
    'logout': LogoutOutlined,
    'updated_profile': SettingOutlined
  }
  return icons[action?.toLowerCase()] || DatabaseOutlined
}

// Format date
const formatDate = (date) => {
  if (!date) return 'N/A'
  return new Date(date).toLocaleString('en-US', {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  })
}

// Get model name from class
const getModelName = (modelType) => {
  if (!modelType) return 'N/A'
  const parts = modelType.split('\\')
  return parts[parts.length - 1]
}

const columns = [
  {
    title: 'ID',
    dataIndex: 'id',
    key: 'id',
    width: 80,
    align: 'center',
  },
  {
    title: 'Action',
    dataIndex: 'action',
    key: 'action',
    width: 120,
  },
  {
    title: 'Model',
    dataIndex: 'auditable_type',
    key: 'auditable_type',
    width: 150,
  },
  {
    title: 'Record ID',
    dataIndex: 'auditable_id',
    key: 'auditable_id',
    width: 100,
    align: 'center'
  },
  {
    title: 'User',
    key: 'user',
    width: 200,
  },
  {
    title: 'Date',
    dataIndex: 'created_at',
    key: 'created_at',
    width: 180,
  },
  {
    title: 'Actions',
    key: 'action_btn',
    width: 100,
    align: 'center',

  },
]

const fetchAudits = async () => {
  loading.value = true
  try {
    const res = await axios.get('/audit-logs')
    audits.value = res.data
  } catch (e) {
    message.error('Failed to load audit logs')
  } finally {
    loading.value = false
  }
}

const viewAudit = (id) => {
  router.visit(`/audits/${id}`)
}

onMounted(fetchAudits)
</script>

<template>
    <Head title="Audit Trail Logs" />
  <AuthenticatedLayout>
    <template #header>
      <div class="flex justify-between items-center">
        <div>
          <h2 class="text-2xl font-bold text-gray-800 dark:text-white">
            Audit Trail Logs
          </h2>
          <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
            Track and monitor all system activities and user actions
          </p>
        </div>
        <div class="flex gap-3">
          <div class="bg-teal-100 dark:bg-teal-900/30 rounded-lg px-3 py-1.5 text-sm text-teal-700 dark:text-teal-300">
            <ClockCircleOutlined class="mr-1" />
            Real-time monitoring
          </div>
        </div>
      </div>
    </template>

    <div class="py-12">
      <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-xl border border-gray-100 dark:border-gray-700">
          <div class="p-4 sm:p-6">
            <!-- Stats Summary - Responsive Grid -->
            <div class="mb-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
              <div class="bg-gradient-to-br from-teal-50 to-emerald-50 dark:from-teal-950/30 dark:to-emerald-950/30 rounded-xl p-4 border border-teal-100 dark:border-teal-800">
                <div class="flex items-center justify-between">
                  <div>
                    <p class="text-sm text-teal-600 dark:text-teal-400 font-medium">Total Activities</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ audits.length }}</p>
                  </div>
                  <div class="w-10 h-10 bg-teal-100 dark:bg-teal-900/50 rounded-full flex items-center justify-center">
                    <DatabaseOutlined class="text-teal-600 dark:text-teal-400 text-xl" />
                  </div>
                </div>
              </div>
              
              <div class="bg-gradient-to-br from-teal-50 to-emerald-50 dark:from-teal-950/30 dark:to-emerald-950/30 rounded-xl p-4 border border-teal-100 dark:border-teal-800">
                <div class="flex items-center justify-between">
                  <div>
                    <p class="text-sm text-teal-600 dark:text-teal-400 font-medium">Unique Users</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">
                      {{ new Set(audits.map(a => a.user?.id)).size }}
                    </p>
                  </div>
                  <div class="w-10 h-10 bg-teal-100 dark:bg-teal-900/50 rounded-full flex items-center justify-center">
                    <UserOutlined class="text-teal-600 dark:text-teal-400 text-xl" />
                  </div>
                </div>
              </div>
              
              <div class="bg-gradient-to-br from-teal-50 to-emerald-50 dark:from-teal-950/30 dark:to-emerald-950/30 rounded-xl p-4 border border-teal-100 dark:border-teal-800">
                <div class="flex items-center justify-between">
                  <div>
                    <p class="text-sm text-teal-600 dark:text-teal-400 font-medium">Models Tracked</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">
                      {{ new Set(audits.map(a => getModelName(a.auditable_type))).size }}
                    </p>
                  </div>
                  <div class="w-10 h-10 bg-teal-100 dark:bg-teal-900/50 rounded-full flex items-center justify-center">
                    <DatabaseOutlined class="text-teal-600 dark:text-teal-400 text-xl" />
                  </div>
                </div>
              </div>
              
              <div class="bg-gradient-to-br from-teal-50 to-emerald-50 dark:from-teal-950/30 dark:to-emerald-950/30 rounded-xl p-4 border border-teal-100 dark:border-teal-800">
                <div class="flex items-center justify-between">
                  <div>
                    <p class="text-sm text-teal-600 dark:text-teal-400 font-medium">Latest Activity</p>
                    <p class="text-sm font-medium text-gray-900 dark:text-white mt-1">
                      {{ audits[0] ? formatDate(audits[0].created_at).split(',')[0] : 'N/A' }}
                    </p>
                  </div>
                  <div class="w-10 h-10 bg-teal-100 dark:bg-teal-900/50 rounded-full flex items-center justify-center">
                    <ClockCircleOutlined class="text-teal-600 dark:text-teal-400 text-xl" />
                  </div>
                </div>
              </div>
            </div>

            <!-- Audit Table with Horizontal Scroll on Mobile -->
            <div class="table-container">
              <a-table
                :columns="columns"
                :data-source="audits"
                :loading="loading"
                row-key="id"
                :pagination="{ 
                  pageSize: 10, 
                  showSizeChanger: true, 
                  showTotal: (total) => `Total ${total} logs`,
                  pageSizeOptions: ['10', '20', '50', '100'],
                  responsive: true
                }"
                :scroll="{ x: 1000 }"
                class="audit-table"
                bordered
              >
                <!-- Custom Cell Templates -->
                <template #bodyCell="{ column, record }">
                  
                  <!-- ID Column -->
                  <template v-if="column.key === 'id'">
                    <span class="font-mono text-xs text-gray-500 dark:text-gray-400">#{{ record.id }}</span>
                  </template>

                  <!-- Action Column -->
                  <template v-else-if="column.key === 'action'">
                    <Tag :color="getActionColor(record.action)" class="action-tag">
                      <component :is="getActionIcon(record.action)" class="mr-1" />
                      {{ record.action || 'N/A' }}
                    </Tag>
                  </template>

                  <!-- Model Column -->
                  <template v-else-if="column.key === 'auditable_type'">
                    <Tooltip :title="record.auditable_type">
                      <span class="text-sm text-gray-700 dark:text-gray-300 font-mono">
                        {{ getModelName(record.auditable_type) }}
                      </span>
                    </Tooltip>
                  </template>

                  <!-- Record ID Column -->
                  <template v-else-if="column.key === 'auditable_id'">
                    <span class="font-mono text-xs text-gray-500 dark:text-gray-400">{{ record.auditable_id }}</span>
                  </template>

                  <!-- User Column -->
                  <template v-else-if="column.key === 'user'">
                    <div class="flex items-center gap-2">
                      <div class="w-6 h-6 bg-teal-100 dark:bg-teal-900/50 rounded-full flex items-center justify-center flex-shrink-0">
                        <UserOutlined class="text-teal-600 dark:text-teal-400 text-xs" />
                      </div>
                      <span class="text-sm font-medium text-gray-900 dark:text-white truncate max-w-[150px]">
                        {{ record.user?.name || 'System' }}
                      </span>
                    </div>
                  </template>

                  <!-- Date Column -->
                  <template v-else-if="column.key === 'created_at'">
                    <div class="text-sm text-gray-600 dark:text-gray-400">
                      <div>{{ formatDate(record.created_at).split(',')[0] }}</div>
                      <div class="text-xs text-gray-400 dark:text-gray-500">{{ formatDate(record.created_at).split(',')[1] }}</div>
                    </div>
                  </template>

                  <!-- Action Button Column -->
                  <template v-else-if="column.key === 'action_btn'">
                    <Tooltip title="View Details">
                      <button 
                        @click="viewAudit(record.id)"
                        class="view-btn px-3 py-1.5 bg-teal-50 dark:bg-teal-900/30 text-teal-600 dark:text-teal-400 rounded-lg hover:bg-teal-100 dark:hover:bg-teal-900/50 transition-all duration-200 text-sm font-medium flex items-center gap-1 whitespace-nowrap"
                      >
                        <EyeOutlined />
                        View
                      </button>
                    </Tooltip>
                  </template>

                </template>

                <!-- Empty State -->
                <template #emptyText>
                  <div class="py-16 text-center">
                    <div class="text-6xl mb-4">📋</div>
                    <p class="text-gray-500 dark:text-gray-400 text-lg font-medium">No audit logs found</p>
                    <p class="text-gray-400 dark:text-gray-500 text-sm mt-2">Activities will appear here as users interact with the system</p>
                  </div>
                </template>
              </a-table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<style scoped>
/* Table Container for better scrolling */
.table-container {
  width: 100%;
  overflow-x: auto;
  position: relative;
}

/* Professional Table Styling */
.audit-table :deep(.ant-table) {
  font-family: inherit;
  border-radius: 12px;
  overflow: hidden;
  min-width: 800px;
}

.audit-table :deep(.ant-table-thead > tr > th) {
  background: #f8fafc;
  font-weight: 600;
  color: #1e293b;
  border-bottom: 2px solid #e2e8f0;
  padding: 14px 16px;
  font-size: 0.875rem;
  white-space: nowrap;
}

.dark .audit-table :deep(.ant-table-thead > tr > th) {
  background: #1f2937;
  color: #f3f4f6;
  border-bottom-color: #374151;
}

.audit-table :deep(.ant-table-tbody > tr > td) {
  padding: 14px 16px;
  border-bottom: 1px solid #f1f5f9;
}

.dark .audit-table :deep(.ant-table-tbody > tr > td) {
  border-bottom-color: #374151;
}

.audit-table :deep(.ant-table-tbody > tr:hover > td) {
  background: #fafbff;
}

.dark .audit-table :deep(.ant-table-tbody > tr:hover > td) {
  background: #1f2937;
}

/* Fixed columns shadow effect */
.audit-table :deep(.ant-table-cell-fix-left),
.audit-table :deep(.ant-table-cell-fix-right) {
  background: inherit;
}

.audit-table :deep(.ant-table-cell-fix-left-last::after) {
  box-shadow: inset 10px 0 8px -8px rgba(0, 0, 0, 0.15);
}

.audit-table :deep(.ant-table-cell-fix-right-first::after) {
  box-shadow: inset -10px 0 8px -8px rgba(0, 0, 0, 0.15);
}

/* Action Tag Styling */
.action-tag {
  padding: 4px 12px;
  font-weight: 500;
  border-radius: 20px;
  display: inline-flex;
  align-items: center;
  gap: 4px;
  border: none;
  text-transform: capitalize;
  white-space: nowrap;
}

:deep(.ant-tag-green) {
  background: #dcfce7;
  color: #166534;
}

:deep(.ant-tag-blue) {
  background: #dbeafe;
  color: #1e40af;
}

:deep(.ant-tag-red) {
  background: #fee2e2;
  color: #991b1b;
}

:deep(.ant-tag-purple) {
  background: #f3e8ff;
  color: #6b21a5;
}

:deep(.ant-tag-teal) {
  background: #ccfbf1;
  color: #0f766e;
}

:deep(.ant-tag-orange) {
  background: #ffedd5;
  color: #9a3412;
}

:deep(.ant-tag-cyan) {
  background: #cffafe;
  color: #155e75;
}

:deep(.ant-tag-magenta) {
  background: #fce7f3;
  color: #9d174d;
}

/* View Button Styling */
.view-btn {
  transition: all 0.2s ease;
}

.view-btn:hover {
  transform: translateY(-1px);
}

.view-btn:active {
  transform: translateY(0);
}

/* Pagination Styling */
.audit-table :deep(.ant-pagination) {
  margin-top: 24px;
}

.audit-table :deep(.ant-pagination-item-active) {
  border-color: #14b8a6;
  background: #14b8a6;
}

.audit-table :deep(.ant-pagination-item-active a) {
  color: white;
}

.audit-table :deep(.ant-pagination-item:hover) {
  border-color: #14b8a6;
}

.audit-table :deep(.ant-pagination-item:hover a) {
  color: #14b8a6;
}

.audit-table :deep(.ant-pagination-options-quick-jumper input:focus) {
  border-color: #14b8a6;
  box-shadow: 0 0 0 2px rgba(20, 184, 166, 0.1);
}

/* Responsive Adjustments */
@media (max-width: 768px) {
  .p-6 {
    padding: 1rem;
  }
  
  .grid {
    gap: 0.75rem;
  }
  
  .audit-table :deep(.ant-table) {
    font-size: 12px;
  }
  
  .audit-table :deep(.ant-table-thead > tr > th),
  .audit-table :deep(.ant-table-tbody > tr > td) {
    padding: 8px 12px;
  }
  
  .action-tag {
    padding: 2px 8px;
    font-size: 11px;
  }
  
  .view-btn {
    padding: 4px 10px;
    font-size: 12px;
  }
}

@media (max-width: 640px) {
  .stats-grid {
    grid-template-columns: repeat(2, 1fr);
  }
}

/* Dark mode support for table */
.dark .audit-table :deep(.ant-table) {
  background: #1f2937;
}

.dark .audit-table :deep(.ant-table-tbody > tr) {
  background: #1f2937;
}

/* Animation for stats cards */
.bg-gradient-to-br {
  transition: all 0.3s ease;
}

.bg-gradient-to-br:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

/* Scrollbar styling for the table container */
.table-container::-webkit-scrollbar {
  height: 8px;
}

.table-container::-webkit-scrollbar-track {
  background: #f1f5f9;
  border-radius: 4px;
}

.table-container::-webkit-scrollbar-thumb {
  background: #cbd5e1;
  border-radius: 4px;
}

.table-container::-webkit-scrollbar-thumb:hover {
  background: #14b8a6;
}

.dark .table-container::-webkit-scrollbar-track {
  background: #1f2937;
}

.dark .table-container::-webkit-scrollbar-thumb {
  background: #4b5563;
}

.dark .table-container::-webkit-scrollbar-thumb:hover {
  background: #14b8a6;
}
</style>

<style>
/* Global dark mode support */
.dark .ant-table-tbody > tr > td {
  background: #1f2937;
  color: #f3f4f6;
}

.dark .ant-empty-description {
  color: #9ca3af;
}

.dark .ant-pagination-item {
  background: #374151;
  border-color: #4b5563;
}

.dark .ant-pagination-item a {
  color: #f3f4f6;
}

.dark .ant-pagination-item:hover a {
  color: #14b8a6;
}

.dark .ant-pagination-prev .ant-pagination-item-link,
.dark .ant-pagination-next .ant-pagination-item-link {
  background: #374151;
  border-color: #4b5563;
  color: #f3f4f6;
}

.dark .ant-pagination-options-quick-jumper {
  color: #f3f4f6;
}

.dark .ant-pagination-options-quick-jumper input {
  background: #374151;
  border-color: #4b5563;
  color: #f3f4f6;
}

/* Tooltip dark mode */
.dark .ant-tooltip-inner {
  background-color: #1f2937;
  color: #f3f4f6;
}
</style>





<!-- <script setup>
import { ref, onMounted } from 'vue'
import { router, Head } from '@inertiajs/vue3'
import { message } from 'ant-design-vue'
import axios from 'axios'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

const loading = ref(false)
const audits = ref([])

const columns = [
  {
    title: 'ID',
    dataIndex: 'id',
    key: 'id',
  },
  {
    title: 'Action',
    dataIndex: 'action',
    key: 'action',
  },
  {
    title: 'Model',
    dataIndex: 'auditable_type',
    key: 'auditable_type',
  },
  {
    title: 'Record ID',
    dataIndex: 'auditable_id',
    key: 'auditable_id',
  },
  {
    title: 'User',
    key: 'user',
  },
  {
    title: 'Date',
    dataIndex: 'created_at',
    key: 'created_at',
  },
  {
    title: 'Action',
    key: 'action_btn',
  },
]

const fetchAudits = async () => {
  loading.value = true
  try {
    const res = await axios.get('/audit-logs')
    audits.value = res.data
  } catch (e) {
    message.error('Failed to load audit logs')
  } finally {
    loading.value = false
  }
}

const viewAudit = (id) => {
  router.visit(`/audits/${id}`)
}

onMounted(fetchAudits)
</script>

<template>
    <Head title="Audit Trail Logs" />
  <AuthenticatedLayout>
    <div>
      <h2 class="text-xl font-bold mb-4">Audit Trail Logs</h2>

      <a-table
        :columns="columns"
        :data-source="audits"
        :loading="loading"
        row-key="id"
        bordered
      >

        <template #bodyCell="{ column, record }">
          
          <template v-if="column.key === 'user'">
            {{ record.user?.name || 'System' }}
          </template>

          <template v-else-if="column.key === 'action_btn'">
            <a-button type="link" @click="viewAudit(record.id)">
              View
            </a-button>
          </template>

        </template>
      </a-table>
    </div>
  </AuthenticatedLayout>
</template> -->