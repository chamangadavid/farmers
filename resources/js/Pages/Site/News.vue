<!-- Resources/js/Pages/Site/News.vue -->
<script setup>
import PageLayout from '@/Components/PageLayout.vue';
import { ref, computed, onMounted } from 'vue';
import axios from 'axios';
import { router } from '@inertiajs/vue3';

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
});

const news = ref([]);
const loading = ref(false);
const selectedCategory = ref('All');
const searchQuery = ref('');

const fetchNews = async () => {
    try {
        loading.value = true;

        const res = await axios.get('/public/news');
        news.value = res.data.data.map(item => ({
            ...item,
            image: item.image
                ? item.image
                : '/assets/placeholder-news.jpg'
        }));

    } catch (error) {
        console.error('Error fetching news:', error);
    } finally {
        loading.value = false;
    }
};

onMounted(() => {
    fetchNews();
});

const categories = [
    'All',
    'General',
    'Fresh Fruits',
    'Poultry',
    'Farm Services',
    'Orders & Delivery',
    'Payments'
];

// =======================
// FEATURED NEWS
// =======================
const featuredNews = computed(() =>
    news.value.find(n => n.featured) || news.value[0]
);

// =======================
// FILTERED NEWS
// =======================
const filteredNews = computed(() => {
    let filtered = news.value;

    if (selectedCategory.value !== 'All') {
        filtered = filtered.filter(item =>
            item.category === selectedCategory.value
        );
    }

    if (searchQuery.value.trim()) {
        const q = searchQuery.value.toLowerCase();

        filtered = filtered.filter(item =>
            item.title?.toLowerCase().includes(q) ||
            item.summary?.toLowerCase().includes(q) ||
            item.author?.toLowerCase().includes(q)
        );
    }

    return filtered;
});

// =======================
// CATEGORY COLORS
// =======================
const getCategoryColor = (category) => {
    const colors = {
        General: 'bg-blue-100 text-blue-700',
        Vegetables: 'bg-purple-100 text-purple-700',
        Poultry: 'bg-green-100 text-green-700',
        Payments: 'bg-orange-100 text-orange-700',
    };

    return colors[category] || 'bg-gray-100 text-gray-700';
};

const trimText = (text, length) => {
    if (!text) return '';
    return text.length > length
        ? text.substring(0, length) + '...'
        : text;
};

const goToDetails = (item) => {
    router.visit(`/all-news/${item.id}`);
};

</script>

<template>
    <PageLayout title="News" :canLogin="canLogin" :canRegister="canRegister" :showHero="true">
        <!-- Custom Hero Section for News -->
        <template #hero>
            <div class="text-center mt-12">
                <div class="mb-6">
                    <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-white/10 backdrop-blur-sm mb-4">
                        <svg class="w-10 h-10 text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                        </svg>
                    </div>
                </div>
                <h1 class="text-4xl sm:text-5xl lg:text-6xl xl:text-7xl font-bold leading-tight mb-6">
                    <span
                        class="bg-gradient-to-r from-teal-400 via-emerald-400 to-teal-300 bg-clip-text text-transparent">
                        Speciallized News
                    </span>
                </h1>
                <p class="text-lg sm:text-xl text-white/80 max-w-3xl mx-auto">
                    Stay informed about our activities, and achievements
                </p>
            </div>
        </template>

        <!-- Main Content -->
        <div class="max-w-7xl mx-auto">
            <div class="space-y-8">
                <!-- Search and Filter Section -->
                <div class="flex flex-col md:flex-row gap-4 justify-between items-center pb-4 border-b border-gray-200">
                    <div class="flex flex-wrap gap-3">
                        <button v-for="cat in categories" :key="cat" @click="selectedCategory = cat" :class="[
                            'px-4 py-2 rounded-lg font-medium transition-all duration-300',
                            selectedCategory === cat
                                ? 'bg-teal-600 text-white shadow-md'
                                : 'bg-gray-100 text-gray-700 hover:bg-gray-200'
                        ]">
                            {{ cat }}
                        </button>
                    </div>

                    <div class="relative w-full md:w-80">
                        <input v-model="searchQuery" type="text" placeholder="Search news..."
                            class="w-full px-4 py-2 pl-10 border border-gray-300 rounded-lg focus:outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20" />
                        <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        <button v-if="searchQuery" @click="searchQuery = ''"
                            class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            ✕
                        </button>
                    </div>
                </div>

                <!-- Featured News (Hero Section) -->
                <div v-if="featuredNews && selectedCategory === 'All' && !searchQuery"
                    class="relative rounded-xl overflow-hidden mb-8 shadow-xl">
                    <div class="relative h-96">
                        <img :src="featuredNews.image" :alt="featuredNews.title" class="w-full h-full object-cover"
                            @error="(e) => e.target.src = '/assets/placeholder-news.jpg'" />
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent"></div>
                        <div class="absolute bottom-0 left-0 right-0 p-8 text-white">
                            <div class="flex items-center gap-3 mb-3">
                                <span
                                    :class="['px-3 py-1 rounded-full text-sm font-semibold', getCategoryColor(featuredNews.category)]">
                                    {{ featuredNews.category }}
                                </span>
                                <span class="text-sm text-white/80">{{ featuredNews.date }}</span>
                                <span class="text-sm text-white/80">📖 {{ featuredNews.read_time }}</span>
                            </div>
                            <h2 class="text-3xl md:text-4xl font-bold mb-3"> {{ trimText(featuredNews.title, 80) }}</h2>
                            <p class="text-white/90 text-lg mb-4 max-w-2xl">{{ trimText(featuredNews.summary, 100) }}</p>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-white/80">By {{ featuredNews.author }}</span>
                                <a href="#"
                                    class="inline-flex items-center gap-2 text-white bg-teal-600 px-6 py-2 rounded-lg hover:bg-teal-700 transition-colors">
                                    Read More
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7">
                                        </path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Stats -->
                <div v-if="filteredNews.length > 0" class="text-sm text-gray-500">
                    Showing {{ filteredNews.length }} news article{{ filteredNews.length !== 1 ? 's' : '' }}
                </div>

                <!-- Scrollable News Grid Container -->
                <div class="news-grid-container">
                    <!-- Loading State -->
                    <div v-if="loading" class="text-center py-12">
                        <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-teal-600"></div>
                        <p class="mt-4 text-gray-500">Loading news articles...</p>
                    </div>

                    <!-- News Grid -->
                    <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div v-for="item in filteredNews" :key="item.id"
                            class="bg-white border border-gray-200 rounded-xl overflow-hidden hover:shadow-xl transition-all duration-300 group">
                            <!-- Image Container -->
                            <div class="relative h-48 overflow-hidden bg-gray-100">
                                <img :src="item.image" :alt="item.title"
                                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                                    @error="(e) => e.target.src = '/assets/placeholder-news.jpg'" />
                                <div class="absolute top-3 left-3">
                                    <span
                                        :class="['px-3 py-1 rounded-full text-xs font-semibold', getCategoryColor(item.category)]">
                                        {{ item.category }}
                                    </span>
                                </div>
                            </div>

                            <!-- Content Container -->
                            <div class="p-6">
                                <div class="flex items-center gap-3 text-sm text-gray-500 mb-3">
                                    <span>{{ item.date }}</span>
                                    <span>•</span>
                                    <span>📖 {{ item.read_time }}</span>
                                </div>
                                <h3 class="text-xl font-bold text-gray-900 mb-2 group-hover:text-teal-600 transition-colors line-clamp-2">
                                    {{ trimText(item.title, 25) }}
                                </h3>
                                <p class="text-gray-600 text-sm mb-4 line-clamp-3">
                                    {{ trimText(item.summary, 70) }}
                                </p>
                                <div class="flex items-center justify-between">
                                    <span class="text-xs text-gray-500">By {{ item.author }}</span>
                                    <a href="#" @click="goToDetails(item)"
                                        class="text-sm text-teal-600 hover:text-teal-700 font-medium inline-flex items-center gap-1 group">
                                        Read more
                                        <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5l7 7-7 7">
                                            </path>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- No Results Message -->
                <div v-if="!loading && filteredNews.length === 0" class="text-center py-12">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-gray-500 text-lg">No news articles found</p>
                    <p class="text-gray-400 mt-2">Try adjusting your search or filter criteria</p>
                    <button v-if="searchQuery || selectedCategory !== 'All'" @click="selectedCategory = 'All'; searchQuery = ''"
                        class="mt-4 px-6 py-2 bg-gradient-to-r from-teal-600 to-emerald-600 text-white rounded-lg hover:shadow-lg transition-all">
                        Clear Filters
                    </button>
                </div>

                <!-- Load More Button (optional) -->
                <div v-if="filteredNews.length > 6 && !loading" class="text-center pt-4">
                    <button
                        class="px-6 py-3 border border-teal-600 text-teal-600 rounded-lg hover:bg-teal-50 transition-colors font-medium">
                        Load More Articles
                    </button>
                </div>

                <!-- Newsletter Subscription -->
                <div class="mt-8 pt-8 border-t border-gray-200">
                    <div class="bg-gradient-to-r from-teal-50 to-emerald-50 rounded-xl p-8 text-center">
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">Subscribe to Our Newsletter</h3>
                        <p class="text-gray-600 mb-6">Get the latest news and updates delivered to your inbox</p>
                        <form class="flex flex-col sm:flex-row gap-3 max-w-md mx-auto" @submit.prevent>
                            <input type="email" placeholder="Enter your email address"
                                class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20" />
                            <button type="submit"
                                class="px-6 py-3 bg-gradient-to-r from-teal-600 to-emerald-600 text-white rounded-lg font-semibold hover:shadow-lg transition-all">
                                Subscribe
                            </button>
                        </form>
                        <p class="text-gray-500 text-xs mt-3">We respect your privacy. Unsubscribe at any time.</p>
                    </div>
                </div>
            </div>
        </div>
    </PageLayout>
</template>

<style scoped>
/* Scrollable News Grid Container */
.news-grid-container {
    max-height: 800px;
    overflow-y: auto;
    overflow-x: hidden;
    padding-right: 8px;
}

/* Custom Scrollbar Styling */
.news-grid-container::-webkit-scrollbar {
    width: 8px;
}

.news-grid-container::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

.news-grid-container::-webkit-scrollbar-thumb {
    background: linear-gradient(135deg, #14b8a6, #10b981);
    border-radius: 10px;
}

.news-grid-container::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(135deg, #0d9488, #059669);
}

/* Firefox Scrollbar */
.news-grid-container {
    scrollbar-width: thin;
    scrollbar-color: #14b8a6 #f1f1f1;
}

/* Smooth Scroll */
.news-grid-container {
    scroll-behavior: smooth;
}

/* Line Clamp for Text Truncation */
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Smooth image loading */
img {
    transition: transform 0.5s ease;
}

/* Hover effect for cards */
.group:hover .group-hover\:scale-110 {
    transform: scale(1.1);
}

/* Loading Animation */
@keyframes spin {
    to {
        transform: rotate(360deg);
    }
}

.animate-spin {
    animation: spin 1s linear infinite;
}

/* Responsive Adjustments */
@media (max-width: 768px) {
    .news-grid-container {
        max-height: 600px;
        padding-right: 4px;
    }
    
    .news-grid-container::-webkit-scrollbar {
        width: 6px;
    }
}

/* Stats Badge Styling */
.text-sm.text-gray-500 {
    background: linear-gradient(135deg, #f8fafc, #ffffff);
    display: inline-block;
    padding: 4px 12px;
    border-radius: 20px;
}

/* Smooth button transitions */
button {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}
</style>