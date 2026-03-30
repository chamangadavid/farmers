
<script setup>
import { ref, defineProps, onMounted, computed } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { usePage } from '@inertiajs/vue3';
import { 
  UserOutlined, 
  MailOutlined, 
  IdcardOutlined, 
  TeamOutlined,
  ArrowLeftOutlined,
  CalendarOutlined,
  SafetyOutlined,
  CheckCircleOutlined
} from '@ant-design/icons-vue';

const props = defineProps({
  user: {
    type: Object,
    required: true,
    default: () => ({
      id: null,
      name: '',
      email: '',
      roles: []
    })
  }
});

const activeTab = ref('Personal Details');

// Get user initials
const userInitials = computed(() => {
  return props.user.name ? props.user.name.charAt(0).toUpperCase() : 'U';
});

// Get role color
const getRoleColor = (role) => {
  const colors = {
    'Super Admin': 'teal',
    'Admin': 'blue',
    'Editor': 'green',
    'Viewer': 'gray',
    'User': 'default'
  };
  return colors[role] || 'teal';
};

// Format date if available
const formatDate = (date) => {
  if (!date) return 'N/A';
  return new Date(date).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  });
};

onMounted(() => {
  console.log('User Roles:', props.user.roles);
});
</script>

<template>
  <Head title="User Details" />
  <AuthenticatedLayout>
    <template #header>
      <div class="flex justify-between items-center">
        <div>
          <h2 class="text-2xl font-bold text-gray-800 dark:text-white">
            User Details
          </h2>
          <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
            View and manage user information
          </p>
        </div>
        <Link 
          :href="route('dashboard')" 
          class="inline-flex items-center px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-all duration-200 text-sm font-medium"
        >
          <ArrowLeftOutlined class="mr-2" />
          Back to Dashboard
        </Link>
      </div>
    </template>

    <div class="py-12">
      <div class="mx-auto max-w-4xl sm:px-6 lg:px-8">
        <!-- Main Card -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-xl border border-gray-100 dark:border-gray-700">
          
          <!-- Profile Header with Gradient -->
          <div class="bg-gradient-to-r from-teal-600 to-emerald-600 px-8 py-8">
            <div class="flex items-center space-x-5">
              <!-- Avatar -->
              <div class="flex-shrink-0">
                <div class="h-20 w-20 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center text-white text-3xl font-bold border-2 border-white/30">
                  {{ userInitials }}
                </div>
              </div>
              
              <!-- User Info -->
              <div class="flex-1">
                <h1 class="text-2xl font-bold text-white">{{ user.name }}</h1>
                <p class="text-teal-100 mt-1 flex items-center gap-2">
                  <MailOutlined class="text-sm" />
                  {{ user.email }}
                </p>
                <div class="mt-2 flex flex-wrap gap-2">
                  <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-white/20 text-white backdrop-blur-sm">
                    <IdcardOutlined class="mr-1 text-sm" />
                    User ID: #{{ user.id }}
                  </span>
                  <span v-for="role in user.roles" :key="role" 
                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-white/20 text-white backdrop-blur-sm">
                    <TeamOutlined class="mr-1 text-sm" />
                    {{ role }}
                  </span>
                </div>
              </div>
            </div>
          </div>

          <!-- Content Area -->
          <div class="p-8">
            <!-- Tab Navigation -->
            <div class="mb-6 border-b border-gray-200 dark:border-gray-700">
              <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                <button
                  v-for="tab in ['Personal Details']"
                  :key="tab"
                  @click="activeTab = tab"
                  :class="[
                    'whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-all duration-200',
                    activeTab === tab
                      ? 'border-teal-500 text-teal-600 dark:text-teal-400'
                      : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300'
                  ]"
                >
                  <UserOutlined class="mr-2" />
                  {{ tab }}
                </button>
              </nav>
            </div>

            <!-- Tab Content -->
            <div>
              <!-- Personal Details Tab -->
              <div v-show="activeTab === 'Personal Details'">
                <!-- Stats Overview -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                  <div class="bg-gradient-to-br from-teal-50 to-emerald-50 dark:from-teal-950/30 dark:to-emerald-950/30 rounded-xl p-4 border border-teal-100 dark:border-teal-800">
                    <div class="flex items-center justify-between">
                      <div>
                        <p class="text-sm text-teal-600 dark:text-teal-400 font-medium">User Role</p>
                        <p class="text-lg font-bold text-gray-900 dark:text-white mt-1">
                          {{ user.roles[0] || 'No Role Assigned' }}
                        </p>
                      </div>
                      <div class="w-10 h-10 bg-teal-100 dark:bg-teal-900/50 rounded-full flex items-center justify-center">
                        <SafetyOutlined class="text-teal-600 dark:text-teal-400 text-xl" />
                      </div>
                    </div>
                  </div>
                  
                  <div class="bg-gradient-to-br from-teal-50 to-emerald-50 dark:from-teal-950/30 dark:to-emerald-950/30 rounded-xl p-4 border border-teal-100 dark:border-teal-800">
                    <div class="flex items-center justify-between">
                      <div>
                        <p class="text-sm text-teal-600 dark:text-teal-400 font-medium">Total Roles</p>
                        <p class="text-lg font-bold text-gray-900 dark:text-white mt-1">{{ user.roles.length }}</p>
                      </div>
                      <div class="w-10 h-10 bg-teal-100 dark:bg-teal-900/50 rounded-full flex items-center justify-center">
                        <TeamOutlined class="text-teal-600 dark:text-teal-400 text-xl" />
                      </div>
                    </div>
                  </div>
                  
                  <div class="bg-gradient-to-br from-teal-50 to-emerald-50 dark:from-teal-950/30 dark:to-emerald-950/30 rounded-xl p-4 border border-teal-100 dark:border-teal-800">
                    <div class="flex items-center justify-between">
                      <div>
                        <p class="text-sm text-teal-600 dark:text-teal-400 font-medium">Status</p>
                        <p class="text-lg font-bold text-gray-900 dark:text-white mt-1 flex items-center gap-2">
                          <CheckCircleOutlined class="text-green-500 text-sm" />
                          Active
                        </p>
                      </div>
                      <div class="w-10 h-10 bg-teal-100 dark:bg-teal-900/50 rounded-full flex items-center justify-center">
                        <CalendarOutlined class="text-teal-600 dark:text-teal-400 text-xl" />
                      </div>
                    </div>
                  </div>
                </div>

                <!-- User Information Details -->
                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl border border-gray-200 dark:border-gray-600 overflow-hidden">
                  <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-800">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                      <UserOutlined class="text-teal-600" />
                      User Information
                    </h3>
                  </div>
                  
                  <dl class="divide-y divide-gray-200 dark:divide-gray-600">
                    <div class="px-6 py-4 sm:grid sm:grid-cols-3 sm:gap-4 hover:bg-gray-100 dark:hover:bg-gray-600/50 transition-colors">
                      <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 flex items-center gap-2">
                        <UserOutlined class="text-teal-600" />
                        Full Name
                      </dt>
                      <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:mt-0 sm:col-span-2 font-medium">
                        {{ user.name }}
                      </dd>
                    </div>
                    
                    <div class="px-6 py-4 sm:grid sm:grid-cols-3 sm:gap-4 hover:bg-gray-100 dark:hover:bg-gray-600/50 transition-colors">
                      <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 flex items-center gap-2">
                        <MailOutlined class="text-teal-600" />
                        Email Address
                      </dt>
                      <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:mt-0 sm:col-span-2">
                        <a :href="`mailto:${user.email}`" class="text-teal-600 hover:text-teal-700 dark:text-teal-400 dark:hover:text-teal-300">
                          {{ user.email }}
                        </a>
                      </dd>
                    </div>
                    
                    <div class="px-6 py-4 sm:grid sm:grid-cols-3 sm:gap-4 hover:bg-gray-100 dark:hover:bg-gray-600/50 transition-colors">
                      <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 flex items-center gap-2">
                        <IdcardOutlined class="text-teal-600" />
                        User ID
                      </dt>
                      <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:mt-0 sm:col-span-2 font-mono">
                        #{{ user.id }}
                      </dd>
                    </div>
                    
                    <div class="px-6 py-4 sm:grid sm:grid-cols-3 sm:gap-4 hover:bg-gray-100 dark:hover:bg-gray-600/50 transition-colors">
                      <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 flex items-center gap-2">
                        <TeamOutlined class="text-teal-600" />
                        Assigned Roles
                      </dt>
                      <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:mt-0 sm:col-span-2">
                        <div class="flex flex-wrap gap-2">
                          <span v-for="role in user.roles" :key="role" 
                                :class="`inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-${getRoleColor(role)}-100 text-${getRoleColor(role)}-800`">
                            {{ role }}
                          </span>
                          <span v-if="user.roles.length === 0" class="text-gray-500 italic">
                            No roles assigned
                          </span>
                        </div>
                      </dd>
                    </div>
                  </dl>
                </div>

                <!-- Additional Info Note -->
                <div class="mt-6 p-4 bg-blue-50 dark:bg-blue-950/30 rounded-xl border border-blue-200 dark:border-blue-800">
                  <div class="flex items-start gap-3">
                    <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900/50 rounded-full flex items-center justify-center flex-shrink-0">
                      <SafetyOutlined class="text-blue-600 dark:text-blue-400" />
                    </div>
                    <div>
                      <p class="text-sm font-medium text-blue-900 dark:text-blue-300">Role Information</p>
                      <p class="text-xs text-blue-800 dark:text-blue-400 mt-1">
                        User roles determine the permissions and access levels within the system. 
                        Contact your administrator to modify role assignments.
                      </p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<style scoped>
/* Custom animations */
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

/* Avatar hover effect */
.rounded-full {
  transition: all 0.3s ease;
}

.rounded-full:hover {
  transform: scale(1.05);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

/* Tab transitions */
button {
  transition: all 0.2s ease;
}

button:hover {
  transform: translateY(-1px);
}

/* Stats cards hover */
.bg-gradient-to-br {
  transition: all 0.3s ease;
}

.bg-gradient-to-br:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

/* Dark mode support */
.dark .bg-gray-50 {
  background: #1f2937;
}

.dark .border-gray-200 {
  border-color: #374151;
}

/* Role tag colors */
.bg-teal-100 {
  background: #ccfbf1;
  color: #0f766e;
}

.bg-blue-100 {
  background: #dbeafe;
  color: #1e40af;
}

.bg-green-100 {
  background: #dcfce7;
  color: #166534;
}

.bg-gray-100 {
  background: #f3f4f6;
  color: #374151;
}

.bg-default-100 {
  background: #f3f4f6;
  color: #374151;
}

.dark .bg-teal-100 {
  background: #134e4a;
  color: #ccfbf1;
}

.dark .bg-blue-100 {
  background: #1e3a8a;
  color: #dbeafe;
}

.dark .bg-green-100 {
  background: #14532d;
  color: #dcfce7;
}

.dark .bg-gray-100 {
  background: #374151;
  color: #f3f4f6;
}

/* Responsive adjustments */
@media (max-width: 768px) {
  .px-8 {
    padding-left: 1.5rem;
    padding-right: 1.5rem;
  }
  
  .py-8 {
    padding-top: 1.5rem;
    padding-bottom: 1.5rem;
  }
  
  .h-20, .w-20 {
    height: 3rem;
    width: 3rem;
    font-size: 1.25rem;
  }
  
  .text-2xl {
    font-size: 1.25rem;
  }
}

/* Custom scrollbar for any overflow */
::-webkit-scrollbar {
  width: 8px;
  height: 8px;
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
</style>
<!-- <script setup>
import { ref, defineProps,onMounted } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { usePage } from '@inertiajs/vue3'


const props = defineProps({
  user: {
    type: Object,
    required: true,
    default: () => ({
      id: null,
      name: '',
      email: '',
      roles: []
    })
  }
});

onMounted(() => {
  console.log('User Roles:', props.user.roles);
});
const activeTab = ref('Personal Details');
</script>

<template>
 <Head title="Search" />
  <AuthenticatedLayout>
    <template #header>
      <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          User Details: {{ user.name }}
        </h2>
        <Link 
          :href="route('dashboard')" 
          class="text-sm text-purple-600 hover:text-purple-800"
        >
          &larr; Back to Dashboard
        </Link>
      </div>
    </template>

    <div class="py-12">
      <div class="sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 bg-white border-b border-gray-200">
            <div class="flex items-center space-x-4 mb-6">
              <div class="flex-shrink-0 h-16 w-16 rounded-full bg-purple-100 flex items-center justify-center text-purple-800 text-2xl font-bold">
                {{ user.name.charAt(0).toUpperCase() }}
              </div>
              <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ user.name }}</h1>
                <h1 class="text-sm font-normal text-gray-500">{{ user.email }}</h1>
                <p class="text-sm text-gray-500">
                  {{ user.role }}
                </p>
              </div>
            </div>
            <div class="mb-6 border-b border-gray-200">
              <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                <button
                  v-for="tab in ['Personal Details']"
                  :key="tab"
                  @click="activeTab = tab"
                  :class="[
                    'whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm',
                    activeTab === tab
                      ? 'border-purple-500 text-purple-600'
                      : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'
                  ]"
                >
                  {{ tab }}
                </button>
              </nav>
            </div>

            <div>
              <div v-show="activeTab === 'User Info'">
                <dl class="divide-y divide-gray-200">
                  <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                    <dt class="text-sm font-medium text-gray-500">Full name</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ user.name }}</dd>
                  </div>
                  <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                    <dt class="text-sm font-medium text-gray-500">Email address</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ user.email }}</dd>
                  </div>
                  <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                    <dt class="text-sm font-medium text-gray-500">User ID</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ user.id }}</dd>
                  </div>
                  <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                    <dt class="text-sm font-medium text-gray-500">Role</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                          {{ user.roles[0] }}
                        </span>
                      </dd>
                  </div>
                </dl>
              </div>

            </div>

          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template> -->