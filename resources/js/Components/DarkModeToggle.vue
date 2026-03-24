<!-- Resources/js/Components/DarkModeToggle.vue -->
<script setup>
import { ref, onMounted } from 'vue';
import { BulbOutlined, BulbFilled } from '@ant-design/icons-vue';

const isDark = ref(false);

onMounted(() => {
  // Check local storage or system preference
  const savedTheme = localStorage.getItem('theme');
  if (savedTheme === 'dark') {
    isDark.value = true;
    document.documentElement.classList.add('dark');
  } else if (savedTheme === 'light') {
    isDark.value = false;
    document.documentElement.classList.remove('dark');
  } else if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
    isDark.value = true;
    document.documentElement.classList.add('dark');
  }
});

const toggleDarkMode = () => {
  isDark.value = !isDark.value;
  if (isDark.value) {
    document.documentElement.classList.add('dark');
    localStorage.setItem('theme', 'dark');
  } else {
    document.documentElement.classList.remove('dark');
    localStorage.setItem('theme', 'light');
  }
};
</script>

<template>
  <button 
    @click="toggleDarkMode" 
    class="dark-mode-toggle"
    :title="isDark ? 'Switch to Light Mode' : 'Switch to Dark Mode'"
  >
    <BulbFilled v-if="isDark" class="toggle-icon" />
    <BulbOutlined v-else class="toggle-icon" />
  </button>
</template>

<style scoped>
.dark-mode-toggle {
  background: rgba(0, 0, 0, 0.05);
  border: none;
  border-radius: 50%;
  width: 36px;
  height: 36px;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: all 0.3s ease;
  color: #6b7280;
}

.dark .dark-mode-toggle {
  background: rgba(255, 255, 255, 0.1);
  color: white;
}

.dark-mode-toggle:hover {
  background: rgba(20, 184, 166, 0.2);
  transform: scale(1.05);
}

.toggle-icon {
  font-size: 1.2rem;
}
</style>