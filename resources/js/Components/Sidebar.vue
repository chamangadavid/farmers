<!-- resources/js/Components/Sidebar.vue -->
<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import {
  DashboardOutlined,
  SettingOutlined,
  UserOutlined,
  IdcardOutlined,
  FileTextOutlined,
  DownOutlined,
  MenuFoldOutlined,
  MenuUnfoldOutlined
} from '@ant-design/icons-vue';

const page = usePage();
const auth = page.props.auth;
const isCollapsed = ref(false);
const openDropdowns = ref({});

// Check permissions function
const can = (permission) => {
  if (!permission) return true; // If no permission required, show the item
  if (auth?.user?.roles?.some(role => role.name === 'Super Admin')) {
    return true;
  }
  return auth?.permissions?.includes(permission);
};

// Toggle sidebar collapse
const toggleSidebar = () => {
  isCollapsed.value = !isCollapsed.value;
  // Apply margin change for main content
  const mainContent = document.querySelector('.main-content');
  if (mainContent) {
    if (isCollapsed.value) {
      mainContent.style.marginLeft = '5rem';
    } else {
      mainContent.style.marginLeft = '16rem';
    }
  }
};

// Toggle dropdown in sidebar
const toggleDropdown = (key) => {
  if (isCollapsed.value) {
    // If collapsed, expand sidebar first
    isCollapsed.value = false;
    const mainContent = document.querySelector('.main-content');
    if (mainContent) {
      mainContent.style.marginLeft = '16rem';
    }
    setTimeout(() => {
      openDropdowns.value[key] = !openDropdowns.value[key];
    }, 300);
  } else {
    openDropdowns.value[key] = !openDropdowns.value[key];
  }
};

// Check if dropdown is open
const isDropdownOpen = (key) => {
  return openDropdowns.value[key] || false;
};

// Define menu items as a constant array (not computed)
const menuItems = [
  {
    key: 'dashboard',
    label: 'Dashboard',
    icon: DashboardOutlined,
    route: 'dashboard',
    permission: null // visible to all authenticated users
  },
  {
    key: 'access-control',
    label: 'Access Control',
    icon: SettingOutlined,
    permission: 'manage access control',
    children: [
      {
        key: 'roles-permissions',
        label: 'Roles & Permissions',
        route: 'admin.rolesAndPermission',
        permission: 'manage access control'
      }
    ]
  },
  {
    key: 'my-account',
    label: 'My Account',
    icon: IdcardOutlined,
    route: 'profile.edit',
    permission: null
  },
  {
    key: 'reports',
    label: 'Reports',
    icon: FileTextOutlined,
    permission: null,
    children: [
      {
        key: 'accident-reports',
        label: 'Accident Reports',
        route: 'accidentPage',
        permission: null
      },
      {
        key: 'investigations',
        label: 'Investigations',
        route: 'investigationPage',
        permission: null
      }
    ]
  }
];

// Filter menu items based on permissions
const filteredMenuItems = computed(() => {
  return menuItems.filter(item => {
    // Check parent permission
    if (item.permission && !can(item.permission)) {
      return false;
    }
    // If item has children, check if any child is accessible
    if (item.children) {
      const hasAccessibleChildren = item.children.some(child => 
        !child.permission || can(child.permission)
      );
      if (!hasAccessibleChildren) {
        return false;
      }
    }
    return true;
  });
});
</script>

<template>
  <aside 
    :class="[
      'sidebar fixed left-0 top-0 h-screen z-30 shadow-2xl transition-all duration-300 ease-in-out',
      'bg-gradient-to-b from-blue-900 to-blue-800',
      isCollapsed ? 'w-20' : 'w-64'
    ]"
  >
    <!-- Sidebar Header -->
    <div class="flex items-center justify-between p-4 border-b border-blue-700/50">
      <div v-if="!isCollapsed" class="flex items-center space-x-2">
        <img src="/assets/marz-logo.png" alt="Logo" class="h-8 w-auto">
        <span class="font-bold text-lg text-white">AAIB Portal</span>
      </div>
      <div v-else class="w-full flex justify-center">
        <img src="/assets/marz-logo.png" alt="Logo" class="h-8 w-auto">
      </div>
      <button 
        @click="toggleSidebar"
        class="p-2 rounded-lg hover:bg-blue-700/50 transition-colors text-white"
      >
        <MenuFoldOutlined v-if="!isCollapsed" class="text-lg" />
        <MenuUnfoldOutlined v-else class="text-lg" />
      </button>
    </div>

    <!-- Sidebar Menu -->
    <nav class="flex-1 overflow-y-auto py-4" style="height: calc(100vh - 73px - 73px);">
      <div class="px-3 space-y-1">
        <template v-for="item in filteredMenuItems" :key="item.key">
          <!-- Parent with children -->
          <div v-if="item.children && item.children.length" class="mb-1">
            <button
              @click="toggleDropdown(item.key)"
              :class="[
                'w-full flex items-center justify-between px-3 py-2 rounded-lg transition-all duration-200',
                'hover:bg-blue-700/50 hover:text-white text-blue-100',
                isCollapsed ? 'justify-center' : ''
              ]"
            >
              <div class="flex items-center space-x-3">
                <component :is="item.icon" class="text-lg" />
                <span v-if="!isCollapsed" class="text-sm font-medium">{{ item.label }}</span>
              </div>
              <DownOutlined 
                v-if="!isCollapsed" 
                :class="[
                  'text-xs transition-transform duration-200',
                  isDropdownOpen(item.key) ? 'rotate-180' : ''
                ]" 
              />
            </button>
            
            <!-- Children Dropdown -->
            <transition
              enter-active-class="transition-all duration-200 ease-out"
              enter-from-class="opacity-0 max-h-0"
              enter-to-class="opacity-100 max-h-96"
              leave-active-class="transition-all duration-200 ease-in"
              leave-from-class="opacity-100 max-h-96"
              leave-to-class="opacity-0 max-h-0"
            >
              <div 
                v-if="!isCollapsed && isDropdownOpen(item.key)" 
                class="ml-8 mt-1 space-y-1 overflow-hidden"
              >
                <Link
                  v-for="child in item.children"
                  :key="child.key"
                  v-if="!child.permission || can(child.permission)"
                  :href="route(child.route)"
                  class="flex items-center px-3 py-2 text-sm text-blue-100 rounded-lg hover:bg-blue-700/50 hover:text-white transition-colors"
                  :class="{ 'bg-blue-700/50 text-white': route().current(child.route) }"
                >
                  {{ child.label }}
                </Link>
              </div>
            </transition>
          </div>
          
          <!-- Single item without children -->
          <Link
            v-else
            :href="route(item.route)"
            :class="[
              'flex items-center px-3 py-2 rounded-lg transition-all duration-200',
              route().current(item.route) ? 'bg-blue-700 text-white' : 'text-blue-100 hover:bg-blue-700/50 hover:text-white',
              isCollapsed ? 'justify-center' : ''
            ]"
          >
            <component :is="item.icon" class="text-lg" />
            <span v-if="!isCollapsed" class="ml-3 text-sm font-medium">{{ item.label }}</span>
          </Link>
        </template>
      </div>
    </nav>

    <!-- Sidebar Footer -->
    <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-blue-700/50 bg-blue-900/50">
      <div v-if="!isCollapsed" class="flex items-center space-x-3">
        <div class="w-8 h-8 rounded-full bg-blue-600 flex items-center justify-center text-white">
          <UserOutlined />
        </div>
        <div class="flex-1 min-w-0">
          <p class="text-sm font-medium text-white truncate">{{ auth?.user?.name || 'User' }}</p>
          <p class="text-xs text-blue-300 truncate">{{ auth?.user?.email || '' }}</p>
        </div>
      </div>
      <div v-else class="flex justify-center">
        <div class="w-8 h-8 rounded-full bg-blue-600 flex items-center justify-center text-white">
          <UserOutlined />
        </div>
      </div>
    </div>
  </aside>
</template>

<style scoped>
.sidebar {
  overflow-y: auto;
  scrollbar-width: thin;
  scrollbar-color: rgba(59, 130, 246, 0.5) transparent;
}

.sidebar::-webkit-scrollbar {
  width: 5px;
}

.sidebar::-webkit-scrollbar-track {
  background: transparent;
}

.sidebar::-webkit-scrollbar-thumb {
  background-color: rgba(59, 130, 246, 0.5);
  border-radius: 10px;
}

.sidebar::-webkit-scrollbar-thumb:hover {
  background-color: rgba(59, 130, 246, 0.8);
}
</style>