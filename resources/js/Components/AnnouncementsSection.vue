<!-- Resources/js/Components/AnnouncementsSection.vue -->
<script setup>
import { ref, computed, onMounted, onBeforeUnmount } from 'vue';
import axios from 'axios';
import { router } from '@inertiajs/vue3';

const announcements = ref([]);
const loading = ref(false);

const fetchAnnouncements = async () => {
    loading.value = true;
    try {
        const res = await axios.get('/public/announcements');
        announcements.value = res.data.announcements;
    } catch (error) {
        console.error(error);
    } finally {
        loading.value = false;
    }
};

const getPriorityColor = (priority) => {
    switch (priority) {
        case 'High': return 'bg-red-100 text-red-700';
        case 'Medium': return 'bg-yellow-100 text-yellow-700';
        default: return 'bg-green-100 text-green-700';
    }
};


const goToDetails = (item) => {
    router.visit(`/announcement`);
};

const limitedAnnouncements = computed(() => {
    return announcements.value.slice(0, 2);
});

const trimText = (text, length) => {
    if (!text) return '';
    return text.length > length
        ? text.substring(0, length) + '...'
        : text;
};

onMounted(() => {
    fetchAnnouncements();
});




</script>

<template>
    <div class="bg-white rounded-2xl shadow-xl p-6 md:p-8">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-2xl md:text-3xl font-bold text-gray-900">Announcements</h2>
            </div>
            <button @click="goToDetails(item)"
                class="inline-flex items-center gap-1 text-teal-600 text-sm font-medium mt-3 group-hover:gap-2 transition-all">
                All
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                    </path>
                </svg>
            </button>
        </div>

        <div class="space-y-4">
            <div v-for="item in limitedAnnouncements" :key="item.id"
                class="border-b border-gray-100 last:border-0 pb-4 last:pb-0">
                <div class="flex items-start gap-3">
                    <div class="flex-shrink-0 mt-1">
                        <span
                            :class="['inline-block w-2 h-2 rounded-full', item.priority === 'High' ? 'bg-red-500' : item.priority === 'Medium' ? 'bg-yellow-500' : 'bg-green-500']"></span>
                    </div>
                    <div class="flex-1">
                        <div class="flex flex-wrap items-center gap-2 mb-1">
                            <span
                                :class="['px-2 py-1 rounded-full text-xs font-semibold', getPriorityColor(item.priority)]">
                                {{ item.priority }} Priority
                            </span>
                            <span class="text-xs text-gray-500">{{ item.date }}</span>
                            <span class="text-xs px-2 py-1 bg-gray-100 text-gray-600 rounded-full">{{ item.category
                                }}</span>
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-1">{{ trimText(item.title, 40) }}</h3>
                        <p class="text-gray-600 text-sm">{{ trimText(item.summary, 70) }}</p>
                        <button @click="goToDetails(item)"
                            class="inline-flex items-center gap-1 text-teal-600 text-sm font-medium mt-3 group-hover:gap-2 transition-all">
                            Read more
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                                </path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>