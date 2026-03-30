 <!-- resources\js\Components\StartsCards.vue -->
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { computed } from 'vue';

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
  InboxOutlined
} from '@ant-design/icons-vue'
import { Head } from '@inertiajs/vue3';
import Card from "@/Components/Auth/Card.vue";

// Props from Inertia
const props = defineProps({
    stats: Object
});

// Quick Stats
// const quickStats = [
//   { label: 'Total Users', value: '1,234', change: '+12%', icon: UserOutlined },
//   { label: 'Reported Accidents', value: '456', change: '+5%', icon: DashboardOutlined },
//   { label: 'Total Vacancies', value: '789', change: '+23%', icon: FileTextOutlined },
//   { label: 'Total Contacts', value: '567', change: '+8%', icon: AppstoreOutlined }
// ];

const quickStats = computed(() => [
  { label: 'Total Users', value: props.stats?.users || 0, icon: UserOutlined },
  { label: 'Reported Accidents', value: props.stats?.accidents || 0, icon: DashboardOutlined },
  { label: 'Total Vacancies', value: props.stats?.jobs || 0, icon: FileTextOutlined },
  { label: 'Total Contacts', value: props.stats?.contacts || 0, icon: AppstoreOutlined }
]);
</script>
<template>
      <!-- Quick Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
          <div v-for="stat in quickStats" :key="stat.label" class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 hover:shadow-md transition-all duration-300 border border-gray-100 dark:border-gray-700">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-sm text-gray-600 dark:text-gray-400">{{ stat.label }}</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white mt-2">{{ stat.value }}</p>
              </div>
              <div class="w-12 h-12 rounded-xl bg-gradient-to-r from-teal-500 to-emerald-500 flex items-center justify-center shadow-lg">
                <component :is="stat.icon" class="text-white text-xl" />
              </div>
            </div>
          </div>
        </div>
</template>