<!-- Resources/js/Pages/MyAAIB/Jobs/Index.vue -->
<script setup>
import { ref, h, onMounted, computed } from 'vue';
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Button, Input, Popconfirm, message, Table, Tooltip, Tag, Space, Card, Tabs } from 'ant-design-vue';
import { 
  EyeOutlined, 
  EditOutlined, 
  DeleteOutlined, 
  PlusOutlined, 
  SearchOutlined, 
  ClearOutlined,
  FileTextOutlined,
  UserOutlined,
  DownloadOutlined
} from '@ant-design/icons-vue';
import dayjs from 'dayjs';
import axios from 'axios';
import { debounce } from 'lodash';

import CreateJob from './CreateJob.vue';
import EditJob from './EditJob.vue';
import ViewJob from './ViewJob.vue';
import ViewApplicationDetails from '@/Pages/Site/ViewApplicationDetails.vue';

const jobs = ref([]);
const applications = ref([]);
const selectedJob = ref(null);
const selectedApplication = ref(null);

const showCreateModal = ref(false);
const showEditModal = ref(false);
const showViewModal = ref(false);
const showApplicationModal = ref(false);

const searchTerm = ref('');
const applicationSearchTerm = ref('');
const loading = ref(false);
const applicationsLoading = ref(false);
const activeTab = ref('jobs');

// Status color mapping for jobs
const getStatusColor = (status) => {
    const colors = {
        'Open': 'green',
        'Closed': 'red',
        'Pending': 'orange',
        'Draft': 'default',
        'Published': 'blue',
        'Filled': 'purple'
    };
    return colors[status] || 'default';
};

// Fetch Jobs
const fetchJobs = async () => {
    loading.value = true;
    try {
        const res = await axios.get('/jobs', { params: { search: searchTerm.value } });
        jobs.value = res.data.jobs;
    } catch (err) {
        message.error('Failed to fetch jobs');
        console.error(err);
    } finally {
        loading.value = false;
    }
};

// Fetch Applications
const fetchApplications = async () => {
    applicationsLoading.value = true;
    try {
        const res = await axios.get('/job-applications', { 
            params: { search: applicationSearchTerm.value } 
        });
        applications.value = res.data.applications;
    } catch (err) {
        message.error('Failed to fetch applications');
        console.error(err);
    } finally {
        applicationsLoading.value = false;
    }
};

// Search handlers
const handleSearch = debounce(() => {
    if (activeTab.value === 'jobs') {
        fetchJobs();
    } else {
        fetchApplications();
    }
}, 500);

const clearSearch = () => {
    if (activeTab.value === 'jobs') {
        searchTerm.value = '';
        fetchJobs();
    } else {
        applicationSearchTerm.value = '';
        fetchApplications();
    }
};

// Delete job
const deleteJob = async (id) => {
    try {
        await axios.delete(`/jobs/${id}`);
        message.success('Job deleted successfully');
        fetchJobs();
    } catch (err) {
        message.error('Failed to delete job');
    }
};

// Delete application
const deleteApplication = async (id) => {
    try {
        await axios.delete(`/job-applications/${id}`);
        message.success('Application deleted successfully');
        fetchApplications();
    } catch (err) {
        message.error('Failed to delete application');
    }
};

// View application
const viewApplication = (application) => {
    selectedApplication.value = application;
    showApplicationModal.value = true;
};

// Helper to truncate text
const truncate = (text, length = 30) => {
    if (!text) return '';
    return text.trim().length > length 
        ? text.trim().substring(0, length) + '...' 
        : text.trim();
};

// Format deadline
const formatDeadline = (deadline) => {
    if (!deadline) return 'Not specified';
    const date = dayjs(deadline);
    const today = dayjs();
    const daysUntil = date.diff(today, 'day');
    
    if (daysUntil < 0) return `Expired (${date.format('YYYY-MM-DD')})`;
    if (daysUntil === 0) return `Today (${date.format('YYYY-MM-DD')})`;
    if (daysUntil <= 7) return `In ${daysUntil} day(s) - ${date.format('YYYY-MM-DD')}`;
    return date.format('YYYY-MM-DD');
};

// Jobs Table Columns
const jobColumns = [
    { 
        title: 'Job Title', 
        dataIndex: 'title',
        width: '25%',
        customRender: ({ record }) => h('div', { class: 'font-medium text-gray-900' }, truncate(record.title, 35))
    },
    { 
        title: 'Type', 
        dataIndex: 'type',
        width: '12%',
        customRender: ({ record }) => record.type 
            ? h(Tag, { color: 'blue', class: 'px-2 py-1' }, record.type)
            : h('span', { class: 'text-gray-400' }, '—')
    },
    { 
        title: 'Location', 
        dataIndex: 'location',
        width: '15%',
        customRender: ({ record }) => record.location 
            ? h('div', { class: 'flex items-center gap-1' }, [
                h('span', '📍'),
                h('span', truncate(record.location, 20))
              ])
            : h('span', { class: 'text-gray-400' }, '—')
    },
    {
        title: 'Deadline',
        dataIndex: 'deadline',
        width: '18%',
        customRender: ({ record }) => {
            if (!record.deadline) return h('span', { class: 'text-gray-400' }, 'Not specified');
            const isExpired = dayjs(record.deadline).isBefore(dayjs());
            return h('div', { class: 'flex items-center gap-1' }, [
                h('span', '⏰'),
                h('span', { 
                    class: isExpired ? 'text-red-600 font-medium' : 'text-gray-700' 
                }, formatDeadline(record.deadline))
            ]);
        }
    },
    { 
        title: 'Status', 
        dataIndex: 'status',
        width: '12%',
        customRender: ({ record }) => record.status 
            ? h(Tag, { 
                color: getStatusColor(record.status),
                class: 'px-2 py-1 font-medium'
              }, record.status.toUpperCase())
            : h(Tag, { color: 'default' }, 'UNKNOWN')
    },
    {
        title: 'Actions',
        width: '18%',
        align: 'center',
        customRender: ({ record }) => h(Space, { size: 'small' }, [
            h(Tooltip, { title: 'View Job Details' }, {
                default: () => h(Button, { 
                    size: 'small', 
                    type: 'link',
                    icon: h(EyeOutlined),
                    onClick: () => { 
                        selectedJob.value = record; 
                        showViewModal.value = true; 
                    },
                    class: 'text-blue-600 hover:text-blue-800'
                })
            }),
            h(Tooltip, { title: 'Edit Job' }, {
                default: () => h(Button, { 
                    size: 'small', 
                    type: 'link',
                    icon: h(EditOutlined),
                    onClick: () => { 
                        selectedJob.value = record; 
                        showEditModal.value = true; 
                    },
                    class: 'text-amber-600 hover:text-amber-800'
                })
            }),
            h(Tooltip, { title: 'Delete Job' }, {
                default: () => h(Popconfirm, { 
                    title: 'Delete Job',
                    description: 'Are you sure you want to delete this job? This action cannot be undone.',
                    okText: 'Yes, Delete',
                    cancelText: 'Cancel',
                    okType: 'danger',
                    onConfirm: () => deleteJob(record.id) 
                }, {
                    default: () => h(Button, { 
                        danger: true, 
                        size: 'small', 
                        type: 'link',
                        icon: h(DeleteOutlined),
                        class: 'text-red-600 hover:text-red-800'
                    })
                })
            })
        ])
    }
];

// Applications Table Columns
const applicationColumns = [
    {
        title: 'ID',
        dataIndex: 'id',
        width: 70,
        customRender: ({ record }) => h('span', { class: 'font-mono text-gray-600' }, `#${record.id}`)
    },
    {
        title: 'Applicant Name',
        dataIndex: 'name',
        sorter: (a, b) => a.name.localeCompare(b.name),
        customRender: ({ record }) => h('div', { class: 'font-medium text-gray-900' }, record.name)
    },
    {
        title: 'Email',
        dataIndex: 'email',
        customRender: ({ record }) => h('a', { 
            href: `mailto:${record.email}`,
            class: 'text-teal-600 hover:text-teal-700'
        }, record.email)
    },
    {
        title: 'Phone',
        dataIndex: 'phone',
    },
    {
        title: 'Position',
        dataIndex: ['job', 'title'],
        customRender: ({ record }) => record.job?.title || 'N/A',
    },
    {
        title: 'Applied Date',
        dataIndex: 'created_at',
        customRender: ({ record }) => dayjs(record.created_at).format('MMM D, YYYY'),
        sorter: (a, b) => new Date(a.created_at) - new Date(b.created_at),
    },
    {
        title: 'Documents',
        key: 'documents',
        width: 100,
        customRender: ({ record }) => {
            const docCount = [record.cv, record.nrc, record.grade12].filter(Boolean).length;
            return h(Tag, { color: 'blue' }, `${docCount} files`);
        }
    },
    {
        title: 'Actions',
        key: 'actions',
        width: 120,
        align: 'center',
        customRender: ({ record }) => h(Space, { size: 'small' }, [
            h(Tooltip, { title: 'View Details' }, {
                default: () => h(Button, { 
                    size: 'small', 
                    type: 'link',
                    icon: h(EyeOutlined),
                    onClick: () => viewApplication(record),
                    class: 'text-blue-600 hover:text-blue-800'
                })
            }),
            h(Tooltip, { title: 'Delete Application' }, {
                default: () => h(Popconfirm, {
                    title: 'Delete Application',
                    description: 'Are you sure you want to delete this application?',
                    okText: 'Yes',
                    cancelText: 'No',
                    okType: 'danger',
                    onConfirm: () => deleteApplication(record.id)
                }, {
                    default: () => h(Button, { 
                        size: 'small', 
                        danger: true,
                        type: 'link',
                        icon: h(DeleteOutlined),
                        class: 'text-red-600 hover:text-red-800'
                    })
                })
            })
        ])
    }
];

onMounted(() => {
    fetchJobs();
    fetchApplications();
});
</script>

<template>
  <Head title="Job Management" />

  <AuthenticatedLayout>
    <template #header>
      <div class="flex justify-between items-center">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
          Job Management
        </h2>
        <div class="text-sm text-gray-500">
          Manage job postings and view applications
        </div>
      </div>
    </template>

    <div class="py-12">
      <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
        <Card class="shadow-sm hover:shadow-md transition-shadow duration-300">
          <!-- Tabs -->
          <Tabs v-model:activeKey="activeTab" class="custom-tabs">
            <!-- Jobs Tab -->
            <Tabs.TabPane key="jobs" tab="Job Openings">
              <template #tab>
                <div class="flex items-center gap-2">
                  <FileTextOutlined />
                  <span>Job Openings</span>
                  <Tag color="blue" class="ml-2">{{ jobs.length }}</Tag>
                </div>
              </template>
              
              <!-- Header with Add Button -->
              <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6 pb-4 border-b border-gray-200">
                <div>
                  <h3 class="text-lg font-semibold text-gray-900">Job Management</h3>
                  <p class="text-sm text-gray-500 mt-1">Create, edit, and manage job vacancies</p>
                </div>
                <Button 
                  type="primary" 
                  @click="showCreateModal = true"
                  class="bg-teal-600 hover:bg-teal-700 border-teal-600 shadow-sm"
                >
                  <PlusOutlined class="mr-2" />
                  Add New Job
                </Button>
              </div>

              <!-- Search Bar -->
              <div class="flex justify-end items-center mb-6">
                <div class="w-full sm:w-auto">
                  <Input 
                    v-model:value="searchTerm" 
                    placeholder="Search by title, type, or location..." 
                    :prefix="h(SearchOutlined, { class: 'text-gray-400' })"
                    :suffix="searchTerm ? h(ClearOutlined, { class: 'text-gray-400 cursor-pointer hover:text-gray-600', onClick: clearSearch }) : null"
                    style="width: 320px" 
                    allowClear
                    @input="handleSearch"
                    class="search-input"
                  />
                </div>
              </div>

              <!-- Jobs Table -->
              <Table 
                :dataSource="jobs" 
                :loading="loading" 
                :columns="jobColumns" 
                rowKey="id"
                :pagination="{ 
                  pageSize: 10, 
                  showSizeChanger: true, 
                  showTotal: (total) => `Total ${total} job${total !== 1 ? 's' : ''}`,
                  pageSizeOptions: ['10', '20', '50'],
                  showQuickJumper: true
                }"
                :scroll="{ x: 768 }"
                class="jobs-table"
              >
                <template #emptyText>
                  <div class="py-16 text-center">
                    <div class="text-6xl mb-4">📄</div>
                    <p class="text-gray-500 text-lg mb-2 font-medium">
                      {{ searchTerm ? 'No matching jobs found' : 'No jobs available' }}
                    </p>
                    <p class="text-gray-400 text-sm mb-4">
                      {{ searchTerm 
                        ? `No results for "${searchTerm}". Try adjusting your search.` 
                        : 'Click "Add New Job" to create your first job posting.'
                      }}
                    </p>
                    <div v-if="searchTerm" class="space-x-3">
                      <Button type="primary" @click="clearSearch" class="bg-teal-600">
                        Clear Search
                      </Button>
                      <Button @click="showCreateModal = true" class="border-teal-600 text-teal-600">
                        Create New Job
                      </Button>
                    </div>
                    <div v-else>
                      <Button type="primary" @click="showCreateModal = true" class="bg-teal-600">
                        <PlusOutlined class="mr-2" />
                        Create Your First Job
                      </Button>
                    </div>
                  </div>
                </template>
              </Table>

              <!-- Quick Stats Footer -->
              <div v-if="jobs.length > 0" class="mt-6 pt-4 border-t border-gray-200 flex justify-between items-center text-sm text-gray-500">
                <div>
                  <span class="font-medium text-gray-700">{{ jobs.length }}</span> job{{ jobs.length !== 1 ? 's' : '' }} total
                </div>
                <div class="flex gap-4">
                  <div>
                    <span class="inline-block w-2 h-2 rounded-full bg-green-500 mr-1"></span>
                    Open: {{ jobs.filter(j => j.status === 'Open').length }}
                  </div>
                  <div>
                    <span class="inline-block w-2 h-2 rounded-full bg-red-500 mr-1"></span>
                    Closed: {{ jobs.filter(j => j.status === 'Closed').length }}
                  </div>
                  <div>
                    <span class="inline-block w-2 h-2 rounded-full bg-blue-500 mr-1"></span>
                    Published: {{ jobs.filter(j => j.status === 'Published').length }}
                  </div>
                </div>
              </div>
            </Tabs.TabPane>

            <!-- Applications Tab -->
            <Tabs.TabPane key="applications" tab="Submitted Applications">
              <template #tab>
                <div class="flex items-center gap-2">
                  <UserOutlined />
                  <span>Submitted Applications</span>
                  <Tag color="green" class="ml-2">{{ applications.length }}</Tag>
                </div>
              </template>
              
              <!-- Header -->
              <div class="mb-6 pb-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Job Applications</h3>
                <p class="text-sm text-gray-500 mt-1">Review and manage submitted job applications</p>
              </div>

              <!-- Search Bar -->
              <div class="flex justify-end items-center mb-6">
                <div class="w-full sm:w-auto">
                  <Input 
                    v-model:value="applicationSearchTerm" 
                    placeholder="Search by name, email, or position..." 
                    :prefix="h(SearchOutlined, { class: 'text-gray-400' })"
                    :suffix="applicationSearchTerm ? h(ClearOutlined, { class: 'text-gray-400 cursor-pointer hover:text-gray-600', onClick: clearSearch }) : null"
                    style="width: 320px" 
                    allowClear
                    @input="handleSearch"
                    class="search-input"
                  />
                </div>
              </div>

              <!-- Applications Table -->
              <Table 
                :dataSource="applications" 
                :loading="applicationsLoading" 
                :columns="applicationColumns" 
                rowKey="id"
                :pagination="{ 
                  pageSize: 10, 
                  showSizeChanger: true, 
                  showTotal: (total) => `Total ${total} application${total !== 1 ? 's' : ''}`,
                  pageSizeOptions: ['10', '20', '50'],
                  showQuickJumper: true
                }"
                :scroll="{ x: 800 }"
                class="applications-table"
              >
                <template #emptyText>
                  <div class="py-16 text-center">
                    <div class="text-6xl mb-4">📋</div>
                    <p class="text-gray-500 text-lg mb-2 font-medium">
                      {{ applicationSearchTerm ? 'No matching applications found' : 'No applications submitted yet' }}
                    </p>
                    <p class="text-gray-400 text-sm">
                      {{ applicationSearchTerm 
                        ? `No results for "${applicationSearchTerm}". Try adjusting your search.` 
                        : 'Applications will appear here once candidates submit their applications.'
                      }}
                    </p>
                    <Button v-if="applicationSearchTerm" type="primary" @click="clearSearch" class="mt-4 bg-teal-600">
                      Clear Search
                    </Button>
                  </div>
                </template>
              </Table>
            </Tabs.TabPane>
          </Tabs>
        </Card>
      </div>
    </div>

    <!-- Modals -->
    <CreateJob :open="showCreateModal" @update:open="showCreateModal = $event" @created="fetchJobs" />
    <EditJob :open="showEditModal" :job="selectedJob" @update:open="showEditModal = $event" @updated="fetchJobs" />
    <ViewJob :open="showViewModal" :job="selectedJob" @update:open="showViewModal = $event" />
    <ViewApplicationDetails 
      :open="showApplicationModal" 
      :application="selectedApplication"
      @update:open="showApplicationModal = $event"
      @deleted="fetchApplications"
    />
  </AuthenticatedLayout>
</template>

<style scoped>
/* Professional Table Styling */
.jobs-table :deep(.ant-table),
.applications-table :deep(.ant-table) {
  font-family: inherit;
}

.jobs-table :deep(.ant-table-thead > tr > th),
.applications-table :deep(.ant-table-thead > tr > th) {
  background: #f8fafc;
  font-weight: 600;
  color: #1e293b;
  border-bottom: 2px solid #e2e8f0;
  padding: 12px 16px;
}

.jobs-table :deep(.ant-table-tbody > tr > td),
.applications-table :deep(.ant-table-tbody > tr > td) {
  padding: 16px;
  border-bottom: 1px solid #f1f5f9;
}

.jobs-table :deep(.ant-table-tbody > tr:hover > td),
.applications-table :deep(.ant-table-tbody > tr:hover > td) {
  background: #fafbff;
}

/* Search Input Styling */
.search-input :deep(.ant-input) {
  border-radius: 8px;
  border-color: #e2e8f0;
  transition: all 0.3s ease;
}

.search-input :deep(.ant-input:focus) {
  border-color: #14b8a6;
  box-shadow: 0 0 0 2px rgba(20, 184, 166, 0.1);
}

.search-input :deep(.ant-input:hover) {
  border-color: #14b8a6;
}

/* Button Styling */
:deep(.ant-btn-primary) {
  background: #14b8a6;
  border-color: #14b8a6;
  border-radius: 8px;
  font-weight: 500;
  transition: all 0.3s ease;
}

:deep(.ant-btn-primary:hover) {
  background: #0d9488;
  border-color: #0d9488;
  transform: translateY(-1px);
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
}

:deep(.ant-btn-link) {
  border-radius: 6px;
}

:deep(.ant-btn-link:hover) {
  transform: scale(1.05);
}

/* Tag Styling */
:deep(.ant-tag) {
  border-radius: 6px;
  padding: 2px 10px;
  font-weight: 500;
  border: none;
}

:deep(.ant-tag-green) {
  background: #dcfce7;
  color: #166534;
}

:deep(.ant-tag-red) {
  background: #fee2e2;
  color: #991b1b;
}

:deep(.ant-tag-orange) {
  background: #ffedd5;
  color: #9a3412;
}

:deep(.ant-tag-blue) {
  background: #dbeafe;
  color: #1e40af;
}

:deep(.ant-tag-purple) {
  background: #f3e8ff;
  color: #6b21a5;
}

/* Tabs Styling */
.custom-tabs :deep(.ant-tabs-nav) {
  margin-bottom: 24px;
}

.custom-tabs :deep(.ant-tabs-tab) {
  padding: 12px 20px;
  font-size: 15px;
  font-weight: 500;
}

.custom-tabs :deep(.ant-tabs-tab-active) {
  color: #14b8a6;
}

.custom-tabs :deep(.ant-tabs-ink-bar) {
  background: #14b8a6;
}

/* Card Hover Effect */
:deep(.ant-card) {
  border-radius: 12px;
  transition: all 0.3s ease;
}

:deep(.ant-card:hover) {
  transform: translateY(-2px);
}

/* Responsive Adjustments */
@media (max-width: 640px) {
  .search-input :deep(.ant-input) {
    width: 100%;
  }
  
  :deep(.ant-table) {
    font-size: 12px;
  }
  
  :deep(.ant-table-thead > tr > th),
  :deep(.ant-table-tbody > tr > td) {
    padding: 8px 12px;
  }
}
</style>