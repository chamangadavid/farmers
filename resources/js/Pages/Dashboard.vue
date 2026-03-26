<!-- Resources/js/Pages/Dashboard.vue -->
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { 
  FileTextOutlined, 
  SwapOutlined, 
  FileDoneOutlined, 
  IdcardOutlined, 
  GiftOutlined, 
  PhoneOutlined, 
  UserOutlined, 
  ReadOutlined, 
  AppstoreOutlined, 
  PictureOutlined, 
  CalendarOutlined, 
  SettingOutlined,
  DashboardOutlined,
  SafetyOutlined,
  TeamOutlined,
  MailOutlined,
  QuestionCircleOutlined,
  HistoryOutlined,
  BookOutlined,
  GlobalOutlined,
  BarChartOutlined,
  PlusCircleOutlined,
  NotificationOutlined,
  AuditOutlined,
  BuildOutlined,
  HomeOutlined,
  StarOutlined,
  RocketOutlined,
  TrophyOutlined,
  UsergroupAddOutlined
} from '@ant-design/icons-vue'
import { Head } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import Card from "@/Components/Auth/Card.vue";

// Props from Inertia
const props = defineProps({
  auth: Object
});

const activeKey = ref('1');

// Function to check if user has a permission
const can = (permission) => {
  if (props.auth?.user?.roles?.some(role => role.name === 'Super Admin')) {
    return true;
  }
  return props.auth?.permissions?.includes(permission);
};

// Recent Activity Data
const recentActivities = [
  { id: 1, title: 'New user registered', time: '5 minutes ago', icon: UserOutlined, color: 'teal' },
  { id: 2, title: 'Role permissions updated', time: '1 hour ago', icon: SettingOutlined, color: 'emerald' },
  { id: 3, title: 'New report generated', time: '3 hours ago', icon: FileTextOutlined, color: 'teal' },
  { id: 4, title: 'QR code created', time: 'Yesterday', icon: AppstoreOutlined, color: 'emerald' }
];

// Quick Stats
const quickStats = [
  { label: 'Total Users', value: '1,234', change: '+12%', icon: UserOutlined },
  { label: 'Active Sessions', value: '456', change: '+5%', icon: DashboardOutlined },
  { label: 'Reports Generated', value: '789', change: '+23%', icon: FileTextOutlined },
  { label: 'QR Codes', value: '567', change: '+8%', icon: AppstoreOutlined }
];
</script>

<template>
  <Head title="Dashboard" />
  <AuthenticatedLayout>
    <template #header>
      <div class="flex justify-between items-center">
        <div>
          <h2 class="text-2xl font-bold text-gray-800 dark:text-white">
            Dashboard
          </h2>
          <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
            Welcome back, {{ auth?.user?.name }}! Here's what's happening today.
          </p>
        </div>
        <div class="flex gap-3">
          <div class="text-right">
            <div class="text-sm text-gray-500 dark:text-gray-400">{{ new Date().toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' }) }}</div>
          </div>
        </div>
      </div>
    </template>

    <div class="py-4">
      <div class="mx-auto max-w-7xl sm:px-6 lg:px-4">
        <!-- Quick Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
          <div v-for="stat in quickStats" :key="stat.label" class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 hover:shadow-md transition-all duration-300 border border-gray-100 dark:border-gray-700">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-sm text-gray-600 dark:text-gray-400">{{ stat.label }}</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white mt-2">{{ stat.value }}</p>
                <p class="text-xs text-green-600 mt-2">{{ stat.change }} from last month</p>
              </div>
              <div class="w-12 h-12 rounded-xl bg-gradient-to-r from-teal-500 to-emerald-500 flex items-center justify-center shadow-lg">
                <component :is="stat.icon" class="text-white text-xl" />
              </div>
            </div>
          </div>
        </div>

        <!-- Main Card with Tabs -->
        <div class="overflow-hidden bg-white dark:bg-gray-800 shadow-lg rounded-xl border border-gray-100 dark:border-gray-700">
          <div class="p-6">

            <!-- Professional Tabs -->
            <div class="dashboard-tabs">
              <a-tabs v-model:activeKey="activeKey" class="custom-tabs">
                
                <a-tab-pane key="1">
                  <template #tab>
                    <div class="flex items-center gap-2">
                      <StarOutlined class="text-teal-500" />
                      <span>Quick Links</span>
                    </div>
                  </template>
                  
                  <div class="mt-6">
                    <div class="flex flex-wrap gap-4">
                      <Card 
                        title="Access Control Management" 
                        v-if="can('manage access control')"
                        subTitle="Create, edit and delete of Access Control" 
                        routeName="admin.rolesAndPermission">
                        <template #icon>
                          <SettingOutlined style="font-size: 24px; color: #14b8a6;" />
                        </template>
                      </Card>

                        <Card 
                        title="Team Member Management" 
                        v-if="can('manage management team')"
                        subTitle="Create, edit & delete management team" 
                        routeName="management.index">
                        <template #icon>
                          <UsergroupAddOutlined style="font-size: 24px; color: #14b8a6;" />
                        </template>
                      </Card>

                       <Card 
                        title="Job Vacancies Management" 
                        v-if="can('manage job vacancies')"
                        subTitle="Create, edit & delete job vacancies" 
                        routeName="job.index">
                        <template #icon>
                          <UsergroupAddOutlined style="font-size: 24px; color: #14b8a6;" />
                        </template>
                      </Card>

                      <Card 
                        title="FAQs Management" 
                        v-if="can('manage faqs')"
                        subTitle="Create, edit & delete FAQs" 
                        routeName="faq.index">
                        <template #icon>
                          <UsergroupAddOutlined style="font-size: 24px; color: #14b8a6;" />
                        </template>
                      </Card>

                       <Card 
                        title="Contacts Management" 
                        v-if="can('manage contact us')"
                        subTitle="Create, edit & delete Contact messages" 
                        routeName="contact.index">
                        <template #icon>
                          <UsergroupAddOutlined style="font-size: 24px; color: #14b8a6;" />
                        </template>
                      </Card>

                      <Card 
                        title="Accidents Management" 
                        v-if="can('manage all accidents')"
                        subTitle="Create, edit & delete all accidents" 
                        routeName="accidents.index">
                        <template #icon>
                          <UsergroupAddOutlined style="font-size: 24px; color: #14b8a6;" />
                        </template>
                      </Card>

                      <Card 
                        title="Incidents Management" 
                        v-if="can('manage all incidents')"
                        subTitle="Create, edit & delete all incidents" 
                        routeName="incidents.index">
                        <template #icon>
                          <UsergroupAddOutlined style="font-size: 24px; color: #14b8a6;" />
                        </template>
                      </Card>

                       <Card 
                        title="Investigation Management" 
                        v-if="can('manage all investigagtions')"
                        subTitle="Create, edit & delete all investigations" 
                        routeName="investigations.index">
                        <template #icon>
                          <UsergroupAddOutlined style="font-size: 24px; color: #14b8a6;" />
                        </template>
                      </Card>

                       <Card 
                        title="News Management" 
                        v-if="can('manage all latest news')"
                        subTitle="Create, edit & delete latest news" 
                        routeName="news.index">
                        <template #icon>
                          <UsergroupAddOutlined style="font-size: 24px; color: #14b8a6;" />
                        </template>
                      </Card>

                       <Card 
                        title="Press Releases Management" 
                        v-if="can('manage all press releases')"
                        subTitle="Create, edit & delete press releases" 
                        routeName="press.index">
                        <template #icon>
                          <UsergroupAddOutlined style="font-size: 24px; color: #14b8a6;" />
                        </template>
                      </Card>

                       <Card 
                        title="Announcements Management" 
                        v-if="can('manage all announcements')"
                        subTitle="Create, edit & delete announcements" 
                        routeName="announcement.index">
                        <template #icon>
                          <UsergroupAddOutlined style="font-size: 24px; color: #14b8a6;" />
                        </template>
                      </Card>

                       <Card 
                        title="Regulations Management" 
                        v-if="can('manage all national regulations')"
                        subTitle="Create, edit & delete national regulations" 
                        routeName="regulations.index">
                        <template #icon>
                          <UsergroupAddOutlined style="font-size: 24px; color: #14b8a6;" />
                        </template>
                      </Card>

                       <Card 
                        title="Document Repository" 
                        v-if="can('manage document repository')"
                        subTitle="Create, edit & delete icao annex" 
                        routeName="document.index">
                        <template #icon>
                          <UsergroupAddOutlined style="font-size: 24px; color: #14b8a6;" />
                        </template>
                      </Card>

                       <Card 
                        title="Reports Management" 
                        v-if="can('manage all reports')"
                        subTitle="View and generate system reports" 
                        routeName="reports.index">
                        <template #icon>
                          <FileTextOutlined style="font-size: 24px; color: #10b981;" />
                        </template>
                      </Card>

                      <Card 
                        title="My Account" 
                        subTitle="Manage your profile and account settings" 
                        routeName="profile.edit">
                        <template #icon>
                          <IdcardOutlined style="font-size: 24px; color: #10b981;" />
                        </template>
                      </Card>

                     


                    </div>
                  </div>
                </a-tab-pane>

                <a-tab-pane key="2">
                  <template #tab>
                    <div class="flex items-center gap-2">
                      <RocketOutlined class="text-teal-500" />
                      <span>Recent Activity</span>
                    </div>
                  </template>
                  
                  <div class="mt-6">
                    <div class="space-y-4">
                      <div v-for="activity in recentActivities" :key="activity.id" class="flex items-center gap-4 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                        <div :class="['w-10 h-10 rounded-full flex items-center justify-center', activity.color === 'teal' ? 'bg-teal-100 dark:bg-teal-900/30' : 'bg-emerald-100 dark:bg-emerald-900/30']">
                          <component :is="activity.icon" :class="['text-lg', activity.color === 'teal' ? 'text-teal-600 dark:text-teal-400' : 'text-emerald-600 dark:text-emerald-400']" />
                        </div>
                        <div class="flex-1">
                          <p class="font-medium text-gray-900 dark:text-white">{{ activity.title }}</p>
                          <p class="text-sm text-gray-500 dark:text-gray-400">{{ activity.time }}</p>
                        </div>
                        <button class="text-teal-600 hover:text-teal-700 dark:text-teal-400 dark:hover:text-teal-300 text-sm font-medium">View</button>
                      </div>
                    </div>
                  </div>
                </a-tab-pane>

                <a-tab-pane key="3">
                  <template #tab>
                    <div class="flex items-center gap-2">
                      <TrophyOutlined class="text-teal-500" />
                      <span>Achievements</span>
                    </div>
                  </template>
                  
                  <div class="mt-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                      <div class="p-4 bg-gradient-to-r from-teal-50 to-emerald-50 dark:from-teal-900/20 dark:to-emerald-900/20 rounded-lg border border-teal-200 dark:border-teal-800">
                        <div class="flex items-center gap-3">
                          <div class="w-12 h-12 rounded-full bg-gradient-to-r from-teal-500 to-emerald-500 flex items-center justify-center">
                            <TrophyOutlined class="text-white text-xl" />
                          </div>
                          <div>
                            <p class="font-semibold text-gray-900 dark:text-white">First Login</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Completed 30 days ago</p>
                          </div>
                        </div>
                      </div>
                      <div class="p-4 bg-gradient-to-r from-teal-50 to-emerald-50 dark:from-teal-900/20 dark:to-emerald-900/20 rounded-lg border border-teal-200 dark:border-teal-800">
                        <div class="flex items-center gap-3">
                          <div class="w-12 h-12 rounded-full bg-gradient-to-r from-teal-500 to-emerald-500 flex items-center justify-center">
                            <StarOutlined class="text-white text-xl" />
                          </div>
                          <div>
                            <p class="font-semibold text-gray-900 dark:text-white">Role Assigned</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Completed 15 days ago</p>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </a-tab-pane>

              </a-tabs>
            </div>
          </div>
        </div>

        <!-- Additional Info Section -->
        <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
          <div class="bg-gradient-to-r from-teal-500 to-emerald-500 rounded-xl p-6 text-white">
            <h3 class="text-lg font-semibold mb-2">System Status</h3>
            <p class="text-teal-100 text-sm mb-4">All systems operational</p>
            <div class="flex items-center gap-2">
              <div class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></div>
              <span class="text-sm">System running smoothly</span>
            </div>
          </div>
          <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-100 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Quick Tip</h3>
            <p class="text-gray-600 dark:text-gray-400 text-sm mb-3">Need help? Check out our documentation or contact support.</p>
            <a href="#" class="text-teal-600 dark:text-teal-400 text-sm font-medium hover:text-teal-700 dark:hover:text-teal-300">Learn more →</a>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<style scoped>
/* Professional Tabs Styling */
.dashboard-tabs :deep(.ant-tabs-nav) {
  margin-bottom: 0;
  border-bottom: 2px solid #e5e7eb;
}

.dark .dashboard-tabs :deep(.ant-tabs-nav) {
  border-bottom-color: #374151;
}

.dashboard-tabs :deep(.ant-tabs-tab) {
  padding: 12px 24px;
  font-size: 0.95rem;
  font-weight: 500;
  transition: all 0.3s;
  margin: 0 4px;
  border-radius: 8px 8px 0 0;
  color: #6b7280;
}

.dark .dashboard-tabs :deep(.ant-tabs-tab) {
  color: #9ca3af;
}

.dashboard-tabs :deep(.ant-tabs-tab:hover) {
  color: #14b8a6;
}

.dashboard-tabs :deep(.ant-tabs-tab-active) {
  color: #14b8a6;
}

.dashboard-tabs :deep(.ant-tabs-tab-active .ant-tabs-tab-btn) {
  color: #14b8a6;
  font-weight: 600;
}

.dashboard-tabs :deep(.ant-tabs-ink-bar) {
  background: linear-gradient(90deg, #14b8a6, #10b981);
  height: 3px;
  border-radius: 3px;
}

.dashboard-tabs :deep(.ant-tabs-tab-btn) {
  display: flex;
  align-items: center;
  gap: 8px;
}

/* Card hover effects */
.card-hover {
  transition: all 0.3s ease;
}

.card-hover:hover {
  transform: translateY(-4px);
  box-shadow: 0 12px 20px -12px rgba(0, 0, 0, 0.2);
}

/* Custom animations */
@keyframes pulse {
  0%, 100% {
    opacity: 1;
  }
  50% {
    opacity: 0.5;
  }
}

.animate-pulse {
  animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

/* Responsive adjustments */
@media (max-width: 768px) {
  .dashboard-tabs :deep(.ant-tabs-tab) {
    padding: 8px 12px;
    font-size: 0.85rem;
  }
  
  .dashboard-tabs :deep(.ant-tabs-tab-btn) {
    gap: 4px;
  }
}
</style>

<style>
/* Global styles for Ant Design tabs to match theme */
.ant-tabs-tab-btn {
  transition: color 0.3s;
}

.ant-tabs-tab .anticon {
  font-size: 1rem;
}

/* Dark mode support for tabs */
.dark .ant-tabs-nav::before {
  border-bottom-color: #374151;
}

.dark .ant-tabs-tab {
  color: #9ca3af;
}

.dark .ant-tabs-tab:hover {
  color: #14b8a6;
}

.dark .ant-tabs-tab-active .ant-tabs-tab-btn {
  color: #14b8a6 !important;
}
</style>