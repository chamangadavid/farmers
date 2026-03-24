// resources/js/composables/useSidebar.js
import { ref, onMounted, onUnmounted } from 'vue';

export function useSidebar() {
  const isCollapsed = ref(false);
  
  // Load saved state from localStorage
  onMounted(() => {
    const saved = localStorage.getItem('sidebar-collapsed');
    if (saved !== null) {
      isCollapsed.value = saved === 'true';
    }
    applySidebarState();
  });
  
  const toggleSidebar = () => {
    isCollapsed.value = !isCollapsed.value;
    localStorage.setItem('sidebar-collapsed', isCollapsed.value);
    applySidebarState();
  };
  
  const applySidebarState = () => {
    const mainContent = document.querySelector('.main-content');
    if (mainContent) {
      if (isCollapsed.value) {
        mainContent.style.marginLeft = '5rem';
        document.documentElement.style.setProperty('--sidebar-width', '5rem');
      } else {
        mainContent.style.marginLeft = '16rem';
        document.documentElement.style.setProperty('--sidebar-width', '16rem');
      }
    }
  };
  
  return {
    isCollapsed,
    toggleSidebar
  };
}