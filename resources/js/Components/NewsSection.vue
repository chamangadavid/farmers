<!-- Resources/js/Components/NewsSection.vue -->
<script setup>
import { ref, computed } from 'vue';

const news = ref([
    {
        id: 1,
        title: 'AAIB Launches Advanced Investigation Training Program',
        date: 'March 18, 2024',
        category: 'Training',
        summary: 'New program to enhance investigation capabilities using modern technologies and methodologies.',
        image: '/assets/news/training-program.jpg',
        featured: true,
        readTime: '5 min read'
    },
    {
        id: 2,
        title: 'International Safety Conference Highlights AAIB Contributions',
        date: 'March 10, 2024',
        category: 'Event',
        summary: 'AAIB presented key findings on emerging aviation safety challenges at the global conference.',
        image: '/assets/news/conference.jpg',
        readTime: '4 min read'
    },
    {
        id: 3,
        title: 'Annual Safety Report Shows Positive Trends',
        date: 'February 28, 2024',
        category: 'Report',
        summary: 'The 2023 annual report reveals significant improvements in safety indicators.',
        image: '/assets/news/safety-report.jpg',
        readTime: '6 min read'
    }
]);

const getCategoryColor = (category) => {
    const colors = {
        Training: 'bg-blue-100 text-blue-700',
        Event: 'bg-purple-100 text-purple-700',
        Report: 'bg-green-100 text-green-700'
    };
    return colors[category] || 'bg-gray-100 text-gray-700';
};
</script>

<template>
    <div class="bg-white rounded-2xl shadow-xl p-6 md:p-8">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-2xl md:text-3xl font-bold text-gray-900">Latest News</h2>
                <p class="text-gray-600 mt-1">Stay updated with our latest activities and announcements</p>
            </div>
            <a href="/news" class="text-teal-600 hover:text-teal-700 font-medium inline-flex items-center text-xs gap-1">
                View All
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div v-for="item in news" :key="item.id" class="group cursor-pointer">
                <div class="relative h-48 overflow-hidden rounded-xl bg-gray-100">
                    <img 
                        :src="item.image" 
                        :alt="item.title"
                        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                        @error="(e) => e.target.src = '/assets/placeholder-news.jpg'"
                    />
                    <div class="absolute top-3 left-3">
                        <span :class="['px-2 py-1 rounded-full text-xs font-semibold', getCategoryColor(item.category)]">
                            {{ item.category }}
                        </span>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="flex items-center gap-3 text-sm text-gray-500 mb-2">
                        <span>{{ item.date }}</span>
                        <span>•</span>
                        <span>📖 {{ item.readTime }}</span>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 group-hover:text-teal-600 transition-colors mb-2">
                        {{ item.title }}
                    </h3>
                    <p class="text-gray-600 text-sm line-clamp-2">{{ item.summary }}</p>
                    <a href="#" class="inline-flex items-center gap-1 text-teal-600 text-sm font-medium mt-3 group-hover:gap-2 transition-all">
                        Read more
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
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