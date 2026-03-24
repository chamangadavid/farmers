<!-- Resources/js/Pages/Site/News.vue -->
<script setup>
import PageLayout from '@/Components/PageLayout.vue';
import { ref, computed } from 'vue';

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
});

const news = ref([
    {
        id: 1,
        title: 'AAIB Launches Advanced Investigation Training Program',
        date: 'March 18, 2024',
        category: 'Training',
        summary: 'New program to enhance investigation capabilities using modern technologies and methodologies including drone technology, 3D reconstruction, and advanced data analysis.',
        author: 'Communications Team',
        image: '/assets/news/training-program.jpg',
        featured: true,
        readTime: '5 min read'
    },
    {
        id: 2,
        title: 'International Safety Conference Highlights AAIB Contributions',
        date: 'March 10, 2024',
        category: 'Event',
        summary: 'AAIB presented key findings on emerging aviation safety challenges at the global conference, receiving recognition for innovative investigation techniques.',
        author: 'Research Division',
        image: '/assets/news/conference.jpg',
        featured: false,
        readTime: '4 min read'
    },
    {
        id: 3,
        title: 'Annual Safety Report Shows Positive Trends',
        date: 'February 28, 2024',
        category: 'Report',
        summary: 'The 2023 annual report reveals significant improvements in safety indicators and recommendation implementation rates.',
        author: 'Analysis Department',
        image: '/assets/news/safety-report.jpg',
        featured: false,
        readTime: '6 min read'
    },
    {
        id: 4,
        title: 'New Safety Management System Guidelines Released',
        date: 'February 15, 2024',
        category: 'Guidelines',
        summary: 'Comprehensive guidelines for implementing effective safety management systems in aviation organizations.',
        author: 'Safety Division',
        image: '/assets/news/sms-guidelines.jpg',
        featured: false,
        readTime: '3 min read'
    },
    {
        id: 5,
        title: 'AAIB Partners with International Aviation Organizations',
        date: 'February 5, 2024',
        category: 'Partnership',
        summary: 'New collaboration agreements signed to enhance regional aviation safety capabilities.',
        author: 'External Affairs',
        image: '/assets/news/partnership.jpg',
        featured: false,
        readTime: '4 min read'
    }
]);

const categories = ['All', 'Training', 'Event', 'Report', 'Guidelines', 'Partnership', 'Announcement'];
const selectedCategory = ref('All');
const searchQuery = ref('');

const featuredNews = computed(() => news.value.find(n => n.featured) || news.value[0]);

const filteredNews = computed(() => {
    let filtered = news.value;
    
    if (selectedCategory.value !== 'All') {
        filtered = filtered.filter(item => item.category === selectedCategory.value);
    }
    
    if (searchQuery.value.trim()) {
        filtered = filtered.filter(item => 
            item.title.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
            item.summary.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
            item.author.toLowerCase().includes(searchQuery.value.toLowerCase())
        );
    }
    
    return filtered;
});

const getCategoryColor = (category) => {
    const colors = {
        Training: 'bg-blue-100 text-blue-700',
        Event: 'bg-purple-100 text-purple-700',
        Report: 'bg-green-100 text-green-700',
        Guidelines: 'bg-orange-100 text-orange-700',
        Partnership: 'bg-indigo-100 text-indigo-700',
        Announcement: 'bg-red-100 text-red-700'
    };
    return colors[category] || 'bg-gray-100 text-gray-700';
};
</script>

<template>
    <PageLayout 
        title="News" 
        :canLogin="canLogin"
        :canRegister="canRegister"
        :showHero="true"
    >
        <!-- Custom Hero Section for News -->
        <template #hero>
            <div class="text-center mt-12">
                <h1 class="text-4xl sm:text-5xl lg:text-6xl xl:text-7xl font-bold leading-tight mb-6">
                    <span class="bg-gradient-to-r from-teal-400 via-emerald-400 to-teal-300 bg-clip-text text-transparent">
                        Latest News
                    </span>
                </h1>
                <p class="text-lg sm:text-xl text-white/80 max-w-3xl mx-auto">
                    Stay informed about our activities, achievements, and safety initiatives
                </p>
            </div>
        </template>

        <!-- Main Content -->
        <div class="space-y-8">
            <!-- Search and Filter Section -->
            <div class="flex flex-col md:flex-row gap-4 justify-between items-center pb-4 border-b border-gray-200">
                <div class="flex flex-wrap gap-3">
                    <button 
                        v-for="cat in categories" 
                        :key="cat"
                        @click="selectedCategory = cat"
                        :class="[
                            'px-4 py-2 rounded-lg font-medium transition-all duration-300',
                            selectedCategory === cat 
                                ? 'bg-teal-600 text-white' 
                                : 'bg-gray-100 text-gray-700 hover:bg-gray-200'
                        ]"
                    >
                        {{ cat }}
                    </button>
                </div>
                
                <div class="relative w-full md:w-64">
                    <input 
                        v-model="searchQuery"
                        type="text" 
                        placeholder="Search news..."
                        class="w-full px-4 py-2 pl-10 border border-gray-300 rounded-lg focus:outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20"
                    />
                    <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
            </div>

            <!-- Featured News (Hero Section) -->
            <div v-if="featuredNews && selectedCategory === 'All' && !searchQuery" class="relative rounded-xl overflow-hidden mb-8">
                <div class="relative h-96">
                    <img 
                        :src="featuredNews.image" 
                        :alt="featuredNews.title"
                        class="w-full h-full object-cover"
                        @error="(e) => e.target.src = '/assets/placeholder-news.jpg'"
                    />
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent"></div>
                    <div class="absolute bottom-0 left-0 right-0 p-8 text-white">
                        <div class="flex items-center gap-3 mb-3">
                            <span :class="['px-3 py-1 rounded-full text-sm font-semibold', getCategoryColor(featuredNews.category)]">
                                {{ featuredNews.category }}
                            </span>
                            <span class="text-sm text-white/80">{{ featuredNews.date }}</span>
                            <span class="text-sm text-white/80">📖 {{ featuredNews.readTime }}</span>
                        </div>
                        <h2 class="text-3xl md:text-4xl font-bold mb-3">{{ featuredNews.title }}</h2>
                        <p class="text-white/90 text-lg mb-4 max-w-2xl">{{ featuredNews.summary }}</p>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-white/80">By {{ featuredNews.author }}</span>
                            <a href="#" class="inline-flex items-center gap-2 text-white bg-teal-600 px-6 py-2 rounded-lg hover:bg-teal-700 transition-colors">
                                Read More
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- News Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div v-for="item in filteredNews" :key="item.id" class="bg-white border border-gray-200 rounded-xl overflow-hidden hover:shadow-xl transition-all duration-300 group">
                    <!-- Image Container -->
                    <div class="relative h-48 overflow-hidden bg-gray-100">
                        <img 
                            :src="item.image" 
                            :alt="item.title"
                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                            @error="(e) => e.target.src = '/assets/placeholder-news.jpg'"
                        />
                        <div class="absolute top-3 left-3">
                            <span :class="['px-3 py-1 rounded-full text-xs font-semibold', getCategoryColor(item.category)]">
                                {{ item.category }}
                            </span>
                        </div>
                    </div>
                    
                    <!-- Content Container -->
                    <div class="p-6">
                        <div class="flex items-center gap-3 text-sm text-gray-500 mb-3">
                            <span>{{ item.date }}</span>
                            <span>•</span>
                            <span>📖 {{ item.readTime }}</span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2 group-hover:text-teal-600 transition-colors">
                            {{ item.title }}
                        </h3>
                        <p class="text-gray-600 mb-4 line-clamp-3">
                            {{ item.summary }}
                        </p>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-500">By {{ item.author }}</span>
                            <a href="#" class="text-teal-600 hover:text-teal-700 font-medium inline-flex items-center gap-1">
                                Read more
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- No Results Message -->
            <div v-if="filteredNews.length === 0" class="text-center py-12">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p class="text-gray-500 text-lg">No news articles found</p>
                <p class="text-gray-400 mt-2">Try adjusting your search or filter criteria</p>
            </div>

            <!-- Load More Button -->
            <div v-if="filteredNews.length > 6" class="text-center pt-4">
                <button class="px-6 py-3 border border-teal-600 text-teal-600 rounded-lg hover:bg-teal-50 transition-colors font-medium">
                    Load More Articles
                </button>
            </div>

            <!-- Newsletter Subscription -->
            <div class="mt-8 pt-8 border-t border-gray-200">
                <div class="bg-gradient-to-r from-teal-50 to-emerald-50 rounded-xl p-8 text-center">
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Subscribe to Our Newsletter</h3>
                    <p class="text-gray-600 mb-6">Get the latest news and updates delivered to your inbox</p>
                    <form class="flex flex-col sm:flex-row gap-3 max-w-md mx-auto">
                        <input 
                            type="email" 
                            placeholder="Enter your email address"
                            class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20"
                        />
                        <button 
                            type="submit"
                            class="px-6 py-3 bg-gradient-to-r from-teal-600 to-emerald-600 text-white rounded-lg font-semibold hover:shadow-lg transition-all"
                        >
                            Subscribe
                        </button>
                    </form>
                    <p class="text-gray-500 text-xs mt-3">We respect your privacy. Unsubscribe at any time.</p>
                </div>
            </div>
        </div>
    </PageLayout>
</template>

<style scoped>
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
</style>