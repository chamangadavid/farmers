<!-- Resources/js/Layouts/AuthenticatedLayout.vue -->
<script setup>
import { ref, watch, onMounted, onUnmounted } from 'vue';
import { debounce } from 'lodash';
import { router, usePage } from '@inertiajs/vue3';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link } from '@inertiajs/vue3';
import axios from 'axios'; // Make sure axios is imported
import { Tabs } from 'ant-design-vue';

const page = usePage();
const auth = page.props.auth;


const can = (permission) => {
  if (auth?.user?.roles?.some(role => role.name === 'Super Admin')) {
    return true;
  }
  return auth?.permissions?.includes(permission);
};

const showingNavigationDropdown = ref(false);
const searchValue = ref('');
const searchResults = ref([]);
const showSuggestions = ref(false);
const isLoading = ref(false);

// Debounced search function
const debouncedSearch = debounce(async (value) => {
  if (value.length < 2) {
    searchResults.value = [];
    showSuggestions.value = false;
    return;
  }

  isLoading.value = true;
  try {
    const response = await axios.get(route('users.search'), {
      params: { query: value }
    });
    searchResults.value = response.data;
    showSuggestions.value = true;
  } catch (error) {
    console.error('Search error:', error);
    searchResults.value = [];
    showSuggestions.value = false;
  } finally {
    isLoading.value = false;
  }
}, 500);

// Watch for changes in searchValue
watch(searchValue, (newVal) => {
  if (newVal) {
    debouncedSearch(newVal);
  } else {
    searchResults.value = [];
    showSuggestions.value = false;
  }
});

// Select user handler
const selectUser = (user) => {
  router.visit(route('users.show', { user: user.id }));
  searchValue.value = '';
  searchResults.value = [];
  showSuggestions.value = false;
};

// Click outside handler to close suggestions
const handleClickOutside = (event) => {
  const searchContainer = document.querySelector('.search-container');
  if (searchContainer && !searchContainer.contains(event.target)) {
    showSuggestions.value = false;
  }
};

onMounted(() => {
  document.addEventListener('click', handleClickOutside);
});

onUnmounted(() => {
  document.removeEventListener('click', handleClickOutside);
});
</script>

<template>
  <div>
    <div class="min-h-screen bg-gradient-to-br from-teal-50 to-emerald-50">
      
      <div class="min-h-screen">
        <div class="max-w-7xl mx-auto px-4">
          <!-- everything inside here -->
          <nav class="bg-gradient-to-r from-teal-700 to-emerald-700 text-white shadow-lg rounded-lg px-6 mx-4 mt-3">

            <!-- Primary Navigation Menu -->
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
              <div class="flex h-16 justify-between">
                <div class="flex">
                  <!-- Logo -->
                  <div class="flex shrink-0 items-center">
                    <Link :href="route('dashboard')">
                      <ApplicationLogo class="block h-9 w-auto fill-current text-white" />
                    </Link>
                  </div>

                  <!-- Navigation Links -->
                  <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <NavLink
                      :href="route('dashboard')"
                      :active="route().current('dashboard')"
                      class="text-white hover:text-teal-200 hover:bg-teal-600/50 transition-colors"
                    >
                      Dashboard
                    </NavLink>
                   
                    <NavLink
                      v-if="can('manage access control')"
                      :href="route('admin.rolesAndPermission')"
                      :active="route().current('admin.rolesAndPermission')"
                      class="text-white hover:text-teal-200 hover:bg-teal-600/50 transition-colors px-2"
                    >
                      Roles & Permissions
                    </NavLink>
                  </div>
                </div>

                <div class="hidden sm:ms-6 sm:flex sm:items-center">
                  <!-- Search Field with Suggestions -->
                  <div class="ms-4 flex items-center search-container relative">
                    <a-input
                      v-model:value="searchValue"
                      placeholder="Search user..."
                      allowClear
                      style="width: 350px; border-radius: 12px"
                      @focus="showSuggestions = searchResults.length > 0"
                      class="search-input"
                    />
                    
                    <!-- Suggestions Dropdown -->
                    <div 
                      v-if="showSuggestions"
                      class="absolute top-full left-0 w-full mt-1 bg-white rounded-md shadow-lg z-50 max-h-60 overflow-auto border border-teal-200"
                    >
                      <div 
                        v-for="user in searchResults" 
                        :key="user.id"
                        class="px-4 py-2 hover:bg-teal-50 cursor-pointer flex items-center search-suggestion-item transition-colors"
                        @click="selectUser(user)"
                      >
                        <div class="flex-shrink-0 h-10 w-10 rounded-full bg-gradient-to-r from-teal-500 to-emerald-500 flex items-center justify-center text-white font-bold">
                          {{ user.name.charAt(0).toUpperCase() }}
                        </div>
                        <div class="ml-3">
                          <div class="font-medium text-gray-900">{{ user.name }}</div>
                          <div class="text-sm text-gray-500">{{ user.email }}</div>
                        </div>
                      </div>
                      <div v-if="isLoading" class="px-4 py-2 text-center text-gray-500">
                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-teal-600 inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Searching...
                      </div>
                      <div 
                        v-if="!isLoading && searchResults.length === 0 && searchValue.length >= 2" 
                        class="px-4 py-2 text-center text-gray-500"
                      >
                        No users found for "{{ searchValue }}"
                      </div>
                    </div>
                  </div>

                  <!-- Administration Role Display -->
                  <div class="relative ms-3">
                    <span v-if="auth.user && auth.user.roles && auth.user.roles.length"
                          class="inline-flex items-center rounded-md bg-white/20 backdrop-blur-sm px-3 py-2 text-sm font-medium text-white border border-teal-300/30">
                      {{ auth.user.roles[0] }}
                    </span>
                  </div>
                  
                  <!-- Settings Dropdown -->
                  <div class="relative ms-3">
                    <Dropdown align="right" width="48">
                      <template #trigger>
                        <span class="inline-flex rounded-md">
                          <button
                            type="button"
                            class="inline-flex items-center rounded-md border border-teal-300/30 bg-white/10 backdrop-blur-sm px-3 py-2 text-sm font-medium leading-4 text-white transition duration-150 ease-in-out hover:bg-white/20 focus:outline-none"
                          >
                            {{ auth.user.name }}

                            <svg
                              class="-me-0.5 ms-2 h-4 w-4"
                              xmlns="http://www.w3.org/2000/svg"
                              viewBox="0 0 20 20"
                              fill="currentColor"
                            >
                              <path
                                fill-rule="evenodd"
                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                clip-rule="evenodd"
                              />
                            </svg>
                          </button>
                        </span>
                      </template>

                      <template #content>
                        <DropdownLink :href="route('profile.edit')">
                          Profile
                        </DropdownLink>
                        <DropdownLink :href="route('logout')" method="post" as="button">
                          Log Out
                        </DropdownLink>
                      </template>
                    </Dropdown>
                  </div>
                </div>

                <!-- Hamburger -->
                <div class="-me-2 flex items-center sm:hidden">
                  <button
                    @click="showingNavigationDropdown = !showingNavigationDropdown"
                    class="inline-flex items-center justify-center rounded-md p-2 text-white transition duration-150 ease-in-out hover:bg-teal-600/50 focus:bg-teal-600/50 focus:outline-none"
                  >
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                      <path
                        :class="{ hidden: showingNavigationDropdown, 'inline-flex': !showingNavigationDropdown }"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M4 6h16M4 12h16M4 18h16"
                      />
                      <path
                        :class="{ hidden: !showingNavigationDropdown, 'inline-flex': showingNavigationDropdown }"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M6 18L18 6M6 6l12 12"
                      />
                    </svg>
                  </button>
                </div>
              </div>
            </div>
          </nav>

          <!-- Responsive Navigation Menu -->
          <div
            :class="{ block: showingNavigationDropdown, hidden: !showingNavigationDropdown }"
            class="sm:hidden"
          >
            <div class="space-y-1 pb-3 pt-2 bg-white rounded-lg shadow-lg mt-2">
              <ResponsiveNavLink :href="route('dashboard')" :active="route().current('dashboard')">
                Dashboard
              </ResponsiveNavLink>
              <ResponsiveNavLink v-if="can('manage access control')"
                :href="route('admin.rolesAndPermission')" 
                :active="route().current('admin.rolesAndPermission')">
                Roles & Permissions
              </ResponsiveNavLink>
            </div>

            <!-- Responsive Settings Options -->
            <div class="border-t border-teal-200 pb-1 pt-4 mt-2 bg-white rounded-lg shadow-lg">
              <div class="px-4">
                <div class="text-base font-medium text-gray-800">{{ auth.user.name }}</div>
                <div class="text-sm font-medium text-gray-500">{{ auth.user.email }}</div>
              </div>

              <div class="mt-3 space-y-1">
                <ResponsiveNavLink :href="route('profile.edit')">Profile</ResponsiveNavLink>
                <ResponsiveNavLink :href="route('logout')" method="post" as="button">Log Out</ResponsiveNavLink>
              </div>
            </div>
          </div>

          <!-- Page Content -->
          <main>
            <slot />
          </main>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
/* Tab text */
.qr-header-tabs :deep(.ant-tabs-tab) {
    color: rgba(255, 255, 255, 0.8) !important;
}

/* Active tab text */
.qr-header-tabs :deep(.ant-tabs-tab-active .ant-tabs-tab-btn) {
    color: #ffffff !important;
}

/* Ink bar (underline) */
.qr-header-tabs :deep(.ant-tabs-ink-bar) {
    background-color: #ffffff !important;
}

/* Remove blue hover */
.qr-header-tabs :deep(.ant-tabs-tab:hover) {
    color: #ffffff !important;
}

/* Search input styling */
.search-input :deep(.ant-input) {
  border-radius: 12px;
  background: rgba(255, 255, 255, 0.2);
  border: 1px solid rgba(255, 255, 255, 0.3);
  color: white;
}

.search-input :deep(.ant-input::placeholder) {
  color: rgba(255, 255, 255, 0.7);
}

.search-input :deep(.ant-input-affix-wrapper) {
  background: rgba(255, 255, 255, 0.2);
  border: 1px solid rgba(255, 255, 255, 0.3);
  border-radius: 12px;
}

.search-input :deep(.ant-input-affix-wrapper input) {
  background: transparent;
  color: white;
}

.search-input :deep(.ant-input-clear-icon) {
  color: white;
}

.search-input :deep(.ant-input-prefix) {
  color: rgba(255, 255, 255, 0.7);
}

/* Hover effects */
.search-suggestion-item:hover {
  background-color: rgba(20, 184, 166, 0.1);
}

/* Dark mode support */
@media (prefers-color-scheme: dark) {
  .bg-gradient-to-br {
    background: linear-gradient(135deg, #0f172a 0%, #115e59 100%);
  }
}
</style>