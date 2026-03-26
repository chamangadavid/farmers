<!-- Resources/js/Components/NewsSection.vue -->
<script setup>
import { ref, computed, onMounted, onBeforeUnmount } from 'vue';
import axios from 'axios';
import { router } from '@inertiajs/vue3';

const news = ref([]);
const loading = ref(false);

const fetchNews = async () => {
    try {
        loading.value = true;

        const res = await axios.get('/public/news');

        news.value = res.data.data.map(item => ({
            ...item,
            image: item.image
                ? item.image.startsWith('http')
                    ? item.image
                    : `/storage/${item.image}`
                : '/assets/placeholder-news.jpg'
        }));

    } catch (err) {
        console.error(err);
    } finally {
        loading.value = false;
    }
};

// =======================
// CATEGORY COLORS
// =======================
const getCategoryColor = (category) => {
    const colors = {
        Training: 'bg-blue-100 text-blue-700',
        Event: 'bg-purple-100 text-purple-700',
        Report: 'bg-green-100 text-green-700',
        Guidelines: 'bg-orange-100 text-orange-700',
        Partnership: 'bg-indigo-100 text-indigo-700',
        Announcement: 'bg-red-100 text-red-700',
        Accident: 'bg-yellow-100 text-yellow-700',
        Incidents: 'bg-gray-200 text-gray-800'
    };

    return colors[category] || 'bg-gray-100 text-gray-700';
};

onMounted(fetchNews);

const goToDetails = (id) => {
    router.visit(`/all-news/${id}`);
};


const viewAllDetails = () => {
    router.visit(`/all-news`);
};

const limitedNews = computed(() => {
    return news.value.slice(0, 3);
});


const trimText = (text, length) => {
    if (!text) return '';
    return text.length > length
        ? text.substring(0, length) + '...'
        : text;
};


</script>

<template>
    <div class="bg-white rounded-2xl shadow-xl p-6 md:p-8">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-2xl md:text-3xl font-bold text-gray-900">Latest News</h2>
            </div>
            <button @click="viewAllDetails()"
                class="inline-flex items-center gap-1 text-teal-600 text-sm font-medium mt-3 group-hover:gap-2 transition-all">
                All
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div v-for="item in limitedNews" :key="item.id" class="group cursor-pointer">
                <div class="relative h-48 overflow-hidden rounded-xl bg-gray-100">
                    <img :src="item.image" :alt="item.title"
                        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                        @error="(e) => e.target.src = '/assets/placeholder-news.jpg'" />
                    <div class="absolute top-3 left-3">
                        <span
                            :class="['px-2 py-1 rounded-full text-xs font-semibold', getCategoryColor(item.category)]">
                            {{ item.category }}
                        </span>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="flex items-center gap-3 text-sm text-gray-500 mb-2">
                        <span>{{ item.date }}</span>
                        <span>•</span>
                        <span>{{ item.read_time }}</span>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 group-hover:text-teal-600 transition-colors mb-2">
                        {{ trimText(item.title, 14) }}
                    </h3>
                    <p class="text-gray-600 text-sm line-clamp-2">
                        {{ trimText(item.summary, 15) }}
                    </p>
                    <button @click="goToDetails(item.id)"
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
</template>

<style scoped>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>