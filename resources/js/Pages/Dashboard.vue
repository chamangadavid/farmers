<!-- Resources/js/Pages/Dashboard.vue -->
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import Card from "@/Components/Auth/Card.vue";

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
  UsergroupAddOutlined,
  PieChartFilled,
  PieChartOutlined,
  CarOutlined,
  PlayCircleOutlined,
  QuestionOutlined,
  QuestionCircleFilled,
  QuestionCircleTwoTone,
  PhoneFilled,
  PhoneTwoTone,
  FolderAddFilled,
  FolderOpenFilled,
  InfoCircleFilled,
  InboxOutlined,
  LineChartOutlined,
  RiseOutlined,
  FallOutlined,
  UserAddOutlined
} from '@ant-design/icons-vue'

// Props from Inertia
const props = defineProps({
  auth: Object,
  stats: Object,
  charts: Object
});

const activeKey = ref('1');

// Function to check if user has a permission
const can = (permission) => {
  if (props.auth?.user?.roles?.some(role => role.name === 'Super Admin')) {
    return true;
  }
  return props.auth?.permissions?.includes(permission);
};

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
            <div class="text-sm text-gray-500 dark:text-gray-400">
              {{ new Date().toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' }) }}
            </div>
          </div>
        </div>
      </div>
    </template>

    <div class="py-4">
      <div class="mx-auto max-w-7xl sm:px-6 lg:px-4">

        <!-- Main Card with Tabs -->
        <div class="overflow-hidden bg-white dark:bg-gray-800 shadow-lg rounded-xl border border-gray-100 dark:border-gray-700">
          <div class="p-6">

            <!-- Professional Quick Links Tabs -->
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
                      <div class="max-h-[500px] overflow-y-auto pr-2">
                    <div class="flex flex-wrap gap-4">

                      <Card 
                        title="Access Control Management" 
                        v-if="can('manage access control')"
                        subTitle="Create, edit and delete of Admin Access Control" 
                        routeName="admin.rolesAndPermission">
                        <template #icon>
                          <SettingOutlined style="font-size: 24px; color: #14b8a6;" />
                        </template>
                      </Card>

                       <Card 
                        title="Staff Role Assignment" 
                        v-if="can('manage staff access control')"
                        subTitle="Create, edit and delete of Staff Access Control" 
                        routeName="staff.index">
                        <template #icon>
                          <TeamOutlined style="font-size: 24px; color: #14b8a6;" />
                        </template>
                      </Card>

                        <Card 
                        title="Chicken Management" 
                        v-if="can('staff can manage chicken records')"
                        subTitle="Create, edit & delete chicken records" 
                        routeName="chicken.index">
                        <template #icon>
                          <FileTextOutlined style="font-size: 24px; color: #14b8a6;" />
                        </template>
                      </Card>

                       <Card 
                        title="Chicken Reports Centre" 
                        v-if="can('staff can manage sales records')"
                        subTitle="Create, edit & delete sales records" 
                        routeName="sales-report.index">
                        <template #icon>
                          <FileDoneOutlined style="font-size: 24px; color: #14b8a6;" />
                        </template>
                      </Card>

                       <Card 
                        title="Vagetable Management" 
                        v-if="can('staff can manage vegetable records')"
                        subTitle="Create, edit & delete vegetable records" 
                        routeName="vegetables.index">
                        <template #icon>
                          <FileTextOutlined style="font-size: 24px; color: #14b8a6;" />
                        </template>
                      </Card>

                       <Card 
                        title="Vagetable Production Management" 
                        v-if="can('staff can manage vegetable production records')"
                        subTitle="Create, edit & delete vegetable production records" 
                        routeName="vegetables-production.index">
                        <template #icon>
                          <PieChartOutlined style="font-size: 24px; color: #14b8a6;" />
                        </template>
                      </Card>

                      <Card 
                        title="Harvest & Sales Management" 
                        v-if="can('staff can manage vegetable harvest records')"
                        subTitle="Create, edit & delete vegetable production records" 
                        routeName="vegetables-harvest.index">
                        <template #icon>
                          <RiseOutlined style="font-size: 24px; color: #14b8a6;" />
                        </template>
                      </Card>

                      <!-- <Card 
                        title="Vagetable Sales Management" 
                        v-if="can('staff can manage vegetable sales records')"
                        subTitle="Create, edit & delete vegetable sale records" 
                        routeName="vegetables-sale.index">
                        <template #icon>
                          <SwapOutlined style="font-size: 24px; color: #14b8a6;" />
                        </template>
                      </Card> -->

                      <Card 
                        title="Vagetable Expenses Management" 
                        v-if="can('staff can manage vegetable expenses records')"
                        subTitle="Create, edit & delete vegetable expenses records" 
                        routeName="vegetables-expenses.index">
                        <template #icon>
                          <SwapOutlined style="font-size: 24px; color: #14b8a6;" />
                        </template>
                      </Card>


                      <Card 
                        title="Gallery Management" 
                        v-if="can('manage product gallery')"
                        subTitle="Create, edit & delete product images" 
                        routeName="galleries.index">
                        <template #icon>
                          <PictureOutlined style="font-size: 24px; color: #14b8a6;" />
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
                        title="Testimonials Management" 
                        v-if="can('manage testimoials')"
                        subTitle="Create, edit & delete testimoials" 
                        routeName="testimonials.index">
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
                          <QuestionCircleOutlined style="font-size: 24px; color: #14b8a6;" />
                        </template>
                      </Card>

                      <Card 
                        title="Contacts Management" 
                        v-if="can('manage contact us')"
                        subTitle="Create, edit & delete Contact messages" 
                        routeName="contact.index">
                        <template #icon>
                          <PhoneFilled style="font-size: 24px; color: #14b8a6;" />
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
                        title="Document Repository" 
                        v-if="can('manage document repository')"
                        subTitle="Create, edit & delete Document Repository" 
                        routeName="document.index">
                        <template #icon>
                          <FolderOpenFilled style="font-size: 24px; color: #14b8a6;" />
                        </template>
                      </Card>

                       <Card 
                        title="Trail Audit Management" 
                        v-if="can('manage trail audit')"
                        subTitle="Create, edit & delete Trail Audit" 
                        routeName="trail-audit.index">
                        <template #icon>
                          <HistoryOutlined style="font-size: 24px; color: #14b8a6;" />
                        </template>
                      </Card>

                      <Card 
                        title="My Account Management" 
                        subTitle="Manage your profile and account settings" 
                        routeName="profile.edit">
                        <template #icon>
                          <UserAddOutlined style="font-size: 24px; color: #14b8a6;" />
                        </template>
                      </Card>
                    </div>
                    </div>
                  </div>
                </a-tab-pane>

              </a-tabs>
            </div>
          </div>
        </div>
        <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
          <div class="bg-gradient-to-r from-teal-500 to-emerald-500 rounded-xl p-6 text-white shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
            <div class="flex items-center justify-between mb-3">
              <h3 class="text-lg font-semibold">System Status</h3>
              <div class="flex items-center gap-2">
                <div class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></div>
                <span class="text-xs text-teal-100">Operational</span>
              </div>
            </div>
            <p class="text-teal-100 text-sm mb-4">All systems are running smoothly with no reported issues.</p>
            <div class="flex items-center gap-2">
              <div class="flex-1 h-1.5 bg-teal-600 rounded-full overflow-hidden">
                <div class="h-full w-[95%] bg-green-400 rounded-full"></div>
              </div>
              <span class="text-xs font-medium">95% uptime</span>
            </div>
          </div>
          
          <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-100 dark:border-gray-700 shadow-sm hover:shadow-md transition-all duration-300">
            <div class="flex items-center gap-3 mb-3">
              <div class="w-10 h-10 bg-teal-100 dark:bg-teal-900/50 rounded-full flex items-center justify-center">
                <QuestionCircleOutlined class="text-teal-600 dark:text-teal-400 text-lg" />
              </div>
              <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Quick Tip</h3>
            </div>
            <p class="text-gray-600 dark:text-gray-400 text-sm mb-4">
              Need help with the system? Check out our comprehensive documentation or contact our support team.
            </p>
            <div class="flex gap-3">
              <a href="#" class="text-teal-600 dark:text-teal-400 text-sm font-medium hover:text-teal-700 dark:hover:text-teal-300 transition-colors flex items-center gap-1">
                View Documentation →
              </a>
              <a href="#" class="text-gray-500 dark:text-gray-500 text-sm font-medium hover:text-gray-700 dark:hover:text-gray-400 transition-colors">
                Contact Support
              </a>
            </div>
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


/* Custom scrollbar */
.scrollbar {
  scrollbar-width: thin;
  scrollbar-color: #14b8a6 #f3f4f6;
}

.scrollbar::-webkit-scrollbar {
  width: 8px;
}

.scrollbar::-webkit-scrollbar-track {
  background: #f3f4f6;
  border-radius: 8px;
}

.scrollbar::-webkit-scrollbar-thumb {
  background: #14b8a6;
  border-radius: 8px;
}

.scrollbar::-webkit-scrollbar-thumb:hover {
  background: #0f766e;
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

/* Chart container enhancements */
:deep(.pie-chart-container),
:deep(.bar-chart-container) {
  min-height: 300px;
  display: flex;
  align-items: center;
  justify-content: center;
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
  
  .grid {
    gap: 1rem;
  }
}

/* Hover effects for cards */
.bg-white, .bg-gradient-to-r {
  transition: all 0.3s ease;
}

/* Chart card hover effect */
.chart-card:hover {
  transform: translateY(-2px);
}

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

/* Chart.js canvas styling */
canvas {
  max-height: 300px;
  width: 100% !important;
}

</style>
