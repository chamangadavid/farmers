<!-- Resources/js/Pages/Site/Announcements.vue -->

<!-- Resources/js/Pages/Site/Announcements.vue -->
<script setup>
import PageLayout from '@/Components/PageLayout.vue';
import { ref, onMounted, computed } from 'vue';
import axios from 'axios';
import dayjs from 'dayjs';

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
});

const announcements = ref([]);
const selectedCategory = ref('all');
const loading = ref(false);
const searchTerm = ref('');

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

// Load on page mount
onMounted(() => {
    fetchAnnouncements();
});

// Dynamic categories
const categories = computed(() => {
    return ['all', ...new Set(announcements.value.map(a => a.category))];
});

// Filtered announcements with search
const filteredAnnouncements = computed(() => {
    let filtered = announcements.value;
    
    if (selectedCategory.value !== 'all') {
        filtered = filtered.filter(a => a.category === selectedCategory.value);
    }
    
    if (searchTerm.value.trim()) {
        const query = searchTerm.value.toLowerCase();
        filtered = filtered.filter(a => 
            a.title.toLowerCase().includes(query) ||
            a.summary.toLowerCase().includes(query) ||
            a.details.toLowerCase().includes(query)
        );
    }
    
    return filtered;
});

// Priority color helper
const getPriorityColor = (priority) => {
    switch(priority?.toLowerCase()) {
        case 'high': return 'bg-red-100 text-red-700';
        case 'medium': return 'bg-yellow-100 text-yellow-700';
        case 'low': return 'bg-green-100 text-green-700';
        default: return 'bg-teal-100 text-teal-700';
    }
};

// Priority icon helper
const getPriorityIcon = (priority) => {
    switch(priority?.toLowerCase()) {
        case 'high': return '🔴';
        case 'medium': return '🟠';
        case 'low': return '🟢';
        default: return '📌';
    }
};

// Format date
const formatDate = (date) => {
    return dayjs(date).format('MMM D, YYYY');
};

// Clear search
const clearSearch = () => {
    searchTerm.value = '';
};
</script>

<template>
    <PageLayout 
        title="Announcements" 
        :canLogin="canLogin"
        :canRegister="canRegister"
        :showHero="true"
    >
        <!-- Custom Hero Section for Announcements -->
        <template #hero>
            <div class="text-center mt-12">
                <div class="mb-6">
                    <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-white/10 backdrop-blur-sm mb-4">
                        <svg class="w-10 h-10 text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-1.417 1.726l-1.09.218a2 2 0 01-2.02-.672L4.5 17.5M13 5.882V19.24a1.76 1.76 0 001.417 1.726l1.09.218a2 2 0 002.02-.672L19.5 17.5M5 9h4m-2 4h2m8-4h4m-2 4h2"></path>
                        </svg>
                    </div>
                </div>
                <h1 class="text-4xl sm:text-5xl lg:text-6xl xl:text-7xl font-bold leading-tight mb-6">
                    <span class="bg-gradient-to-r from-teal-400 via-emerald-400 to-teal-300 bg-clip-text text-transparent">
                        Latest Announcements
                    </span>
                </h1>
                <p class="text-lg sm:text-xl text-white/80 max-w-3xl mx-auto mb-8">
                    Stay informed about our latest news, events, and important updates
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="#announcements-list" class="px-8 py-4 bg-gradient-to-r from-teal-600 to-emerald-600 text-white rounded-xl font-semibold hover:shadow-2xl hover:shadow-teal-500/30 transition-all duration-300 transform hover:-translate-y-1">
                        View All
                    </a>
                    <a href="#subscribe" class="px-8 py-4 bg-white/10 backdrop-blur-sm border border-teal-400/30 text-white rounded-xl font-semibold hover:bg-white/20 transition-all duration-300">
                        Subscribe to Updates
                    </a>
                </div>
            </div>
        </template>

        <!-- Main Content -->
        <div class="max-w-5xl mx-auto">
            <div class="space-y-8">
                <!-- Filter Section -->
                <section class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 pb-4 border-b border-gray-200">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">Announcements & Updates</h2>
                        <p class="text-gray-600 text-sm mt-1">Stay up to date with the latest from AAIB</p>
                    </div>
                    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3">
                        <!-- Search Input -->
                        <div class="relative">
                            <input 
                                v-model="searchTerm"
                                type="text" 
                                placeholder="Search announcements..."
                                class="w-full sm:w-64 px-4 py-2 pl-10 border border-gray-300 rounded-lg focus:outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 text-gray-700"
                            />
                            <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            <button 
                                v-if="searchTerm"
                                @click="clearSearch"
                                class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600"
                            >
                                ✕
                            </button>
                        </div>
                        
                        <!-- Category Filter -->
                        <select 
                            v-model="selectedCategory"
                            class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 text-gray-700"
                        >
                            <option value="all">All Categories</option>
                            <option v-for="cat in categories" :key="cat" :value="cat" v-if="cat !== 'all'">{{ cat }}</option>
                        </select>
                    </div>
                </section>

                <!-- Loading State -->
                <div v-if="loading" class="text-center py-12">
                    <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-teal-600"></div>
                    <p class="mt-4 text-gray-500">Loading announcements...</p>
                </div>

                <!-- Announcements List with Scrollable Container -->
                <div v-else id="announcements-list" class="announcements-container">
                    <div v-if="filteredAnnouncements.length > 0" class="space-y-4">
                        <div 
                            v-for="announcement in filteredAnnouncements" 
                            :key="announcement.id" 
                            class="announcement-card bg-white border border-gray-200 rounded-xl hover:shadow-xl transition-all duration-300 overflow-hidden"
                        >
                            <div class="p-6">
                                <div class="flex flex-wrap items-center justify-between gap-3 mb-3">
                                    <div class="flex flex-wrap items-center gap-3">
                                        <span :class="['px-3 py-1 rounded-full text-xs font-semibold flex items-center gap-1', getPriorityColor(announcement.priority)]">
                                            <span>{{ getPriorityIcon(announcement.priority) }}</span>
                                            {{ announcement.priority }} Priority
                                        </span>
                                        <span class="px-3 py-1 bg-teal-100 text-teal-700 rounded-full text-xs font-semibold flex items-center gap-1">
                                            📢 {{ announcement.category }}
                                        </span>
                                    </div>
                                    <span class="text-gray-500 text-sm flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        {{ formatDate(announcement.date) }}
                                    </span>
                                </div>
                                <h3 class="text-xl font-bold text-gray-900 mb-2">{{ announcement.title }}</h3>
                                <p class="text-gray-600 mb-3 leading-relaxed">{{ announcement.summary }}</p>
                                <details class="mt-2 group">
                                    <summary class="text-teal-600 hover:text-teal-700 cursor-pointer font-medium text-sm flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                        Read more
                                    </summary>
                                    <p class="text-gray-600 mt-3 pt-3 border-t border-gray-100 text-sm leading-relaxed">{{ announcement.details }}</p>
                                </details>
                            </div>
                        </div>
                    </div>

                    <!-- Empty State -->
                    <div v-else class="text-center py-12 bg-gray-50 rounded-2xl">
                        <div class="text-6xl mb-4">📢</div>
                        <p class="text-gray-500 text-lg mb-2">No announcements found</p>
                        <p class="text-gray-400 text-sm">
                            {{ searchTerm ? 'Try adjusting your search criteria' : 'Check back later for updates' }}
                        </p>
                        <button 
                            v-if="searchTerm" 
                            @click="clearSearch" 
                            class="mt-4 px-6 py-2 bg-gradient-to-r from-teal-600 to-emerald-600 text-white rounded-lg hover:shadow-lg transition-all"
                        >
                            Clear Search
                        </button>
                    </div>
                </div>

                <!-- Stats -->
                <div v-if="filteredAnnouncements.length > 0" class="text-center text-sm text-gray-500">
                    Showing {{ filteredAnnouncements.length }} announcement{{ filteredAnnouncements.length !== 1 ? 's' : '' }}
                </div>

                <!-- Subscribe Section -->
                <section id="subscribe" class="mt-8 pt-8 border-t border-gray-200">
                    <div class="bg-gradient-to-r from-teal-50 to-emerald-50 rounded-2xl p-8">
                        <div class="text-center max-w-2xl mx-auto">
                            <h2 class="text-2xl font-bold text-gray-900 mb-3">Subscribe to Updates</h2>
                            <p class="text-gray-600 mb-6">Get the latest announcements delivered directly to your inbox</p>
                            <form class="flex flex-col sm:flex-row gap-3" @submit.prevent>
                                <input 
                                    type="email" 
                                    placeholder="Enter your email address"
                                    class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 text-gray-900"
                                />
                                <button 
                                    type="submit"
                                    class="px-6 py-3 bg-gradient-to-r from-teal-600 to-emerald-600 text-white rounded-lg font-semibold hover:shadow-lg transition-all duration-300"
                                >
                                    Subscribe Now
                                </button>
                            </form>
                            <p class="text-gray-500 text-xs mt-3">We respect your privacy. Unsubscribe at any time.</p>
                        </div>
                    </div>
                </section>

                <!-- Archive Section -->
                <div class="text-center pt-4">
                    <a href="#" class="inline-flex items-center gap-2 text-teal-600 hover:text-teal-700 font-medium group">
                        View Archived Announcements
                        <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </PageLayout>
</template>

<style scoped>
/* Scrollable Container */
.announcements-container {
    max-height: 600px;
    overflow-y: auto;
    overflow-x: hidden;
    padding-right: 8px;
}

/* Custom Scrollbar Styling */
.announcements-container::-webkit-scrollbar {
    width: 8px;
}

.announcements-container::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

.announcements-container::-webkit-scrollbar-thumb {
    background: linear-gradient(135deg, #14b8a6, #10b981);
    border-radius: 10px;
}

.announcements-container::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(135deg, #0d9488, #059669);
}

/* Firefox Scrollbar */
.announcements-container {
    scrollbar-width: thin;
    scrollbar-color: #14b8a6 #f1f1f1;
}

/* Announcement Card Styling */
.announcement-card {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.announcement-card:hover {
    transform: translateY(-2px);
    border-color: #14b8a6;
}

/* Smooth Scroll */
.announcements-container {
    scroll-behavior: smooth;
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

/* Details Summary Styling */
details summary {
    list-style: none;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}

details summary::-webkit-details-marker {
    display: none;
}

details[open] summary svg {
    transform: rotate(180deg);
}

/* Search Input Focus Effects */
input:focus {
    outline: none;
    border-color: #14b8a6;
    box-shadow: 0 0 0 3px rgba(20, 184, 166, 0.1);
}

/* Responsive Adjustments */
@media (max-width: 768px) {
    .announcements-container {
        max-height: 500px;
        padding-right: 4px;
    }
    
    .announcement-card {
        padding: 0;
    }
    
    .announcement-card h3 {
        font-size: 1.125rem;
    }
    
    .announcements-container::-webkit-scrollbar {
        width: 6px;
    }
}

/* Empty State Animation */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.text-center.py-12 {
    animation: fadeIn 0.3s ease-out;
}

/* Stats Badge Styling */
.text-sm.text-gray-500 {
    background: linear-gradient(135deg, #f8fafc, #ffffff);
    display: inline-block;
    padding: 4px 12px;
    border-radius: 20px;
}
</style>


<!-- <script setup>
import PageLayout from '@/Components/PageLayout.vue';
import { ref, onMounted, computed } from 'vue';
import axios from 'axios';
import dayjs from 'dayjs';

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
});

const announcements = ref([]);
const selectedCategory = ref('all');

const loading = ref(false);

const fetchAnnouncements = async () => {
    loading.value = true;
    try {
        const res = await axios.get('/announcements');
        announcements.value = res.data.announcements;
    } finally {
        loading.value = false;
    }
};

onMounted(() => {
    fetchAnnouncements();
});

const categories = computed(() => {
    return ['all', ...new Set(announcements.value.map(a => a.category))];
});

const filteredAnnouncements = computed(() => {
    if (selectedCategory.value === 'all') {
        return announcements.value;
    }
    return announcements.value.filter(
        a => a.category === selectedCategory.value
    );
});

const getPriorityColor = (priority) => {
    switch(priority) {
        case 'High': return 'bg-red-100 text-red-700';
        case 'Medium': return 'bg-yellow-100 text-yellow-700';
        default: return 'bg-green-100 text-green-700';
    }
};
</script>
<template>
    <PageLayout 
        title="Announcements" 
        :canLogin="canLogin"
        :canRegister="canRegister"
        :showHero="true"
    >

        <template #hero>
            <div class="text-center mt-12">
                <h1 class="text-4xl sm:text-5xl lg:text-6xl xl:text-7xl font-bold leading-tight mb-6">
                    <span class="bg-gradient-to-r from-teal-400 via-emerald-400 to-teal-300 bg-clip-text text-transparent">
                        Latest Announcements
                    </span>
                </h1>
                <p class="text-lg sm:text-xl text-white/80 max-w-3xl mx-auto mb-6">
                    Stay informed about our latest news, events, and important updates
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="#announcements" class="px-8 py-4 bg-gradient-to-r from-teal-600 to-emerald-600 text-white rounded-xl font-semibold hover:shadow-2xl hover:shadow-teal-500/30 transition-all duration-300 transform hover:-translate-y-1">
                        View All
                    </a>
                    <a href="#subscribe" class="px-8 py-4 bg-white/10 backdrop-blur-sm border border-teal-400/30 text-white rounded-xl font-semibold hover:bg-white/20 transition-all duration-300">
                        Subscribe to Updates
                    </a>
                </div>
            </div>
        </template>

        <div class="space-y-8">

            <section class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 pb-4 border-b border-gray-200">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Announcements & Updates</h2>
                    <p class="text-gray-600 text-sm mt-1">Stay up to date with the latest from AAIB</p>
                </div>
                <div class="flex items-center gap-3">
                    <label class="text-gray-700 text-sm font-medium">Filter by category:</label>
                    <select 
                        v-model="selectedCategory"
                        @change="filterAnnouncements"
                        class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 text-gray-700"
                    >
                        <option value="all">All Categories</option>
                        <option v-for="cat in categories" :key="cat" :value="cat" v-if="cat !== 'all'">{{ cat }}</option>
                    </select>
                </div>
            </section>

            <section id="announcements" class="space-y-6">
                <div v-for="announcement in filteredAnnouncements" :key="announcement.id" class="bg-white border border-gray-200 rounded-xl hover:shadow-xl transition-all duration-300 overflow-hidden">
                    <div class="p-6">
                        <div class="flex flex-wrap items-center justify-between gap-3 mb-3">
                            <div class="flex flex-wrap items-center gap-3">
                                <span :class="['px-3 py-1 rounded-full text-xs font-semibold', getPriorityColor(announcement.priority)]">
                                    {{ announcement.priority }} Priority
                                </span>
                                <span class="px-3 py-1 bg-teal-100 text-teal-700 rounded-full text-xs font-semibold">
                                    {{ announcement.category }}
                                </span>
                            </div>
                            <span class="text-gray-500 text-sm flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                {{ dayjs(announcement.date).format('MMM D, YYYY') }}
                            </span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">{{ announcement.title }}</h3>
                        <p class="text-gray-600 mb-3">{{ announcement.summary }}</p>
                        <details class="mt-2">
                            <summary class="text-teal-600 hover:text-teal-700 cursor-pointer font-medium text-sm">Read more</summary>
                            <p class="text-gray-600 mt-2 pt-2 border-t border-gray-100 text-sm">{{ announcement.details }}</p>
                        </details>
                    </div>
                </div>

                <div v-if="filteredAnnouncements.length === 0" class="text-center py-12">
                    <p class="text-gray-500">No announcements found for this category.</p>
                </div>
            </section>

            <section id="subscribe" class="mt-8 pt-8 border-t border-gray-200">
                <div class="bg-gradient-to-r from-teal-50 to-emerald-50 rounded-2xl p-8">
                    <div class="text-center max-w-2xl mx-auto">
                        <h2 class="text-2xl font-bold text-gray-900 mb-3">Subscribe to Updates</h2>
                        <p class="text-gray-600 mb-6">Get the latest announcements delivered directly to your inbox</p>
                        <form class="flex flex-col sm:flex-row gap-3">
                            <input 
                                type="email" 
                                placeholder="Enter your email address"
                                class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 text-gray-900"
                            />
                            <button 
                                type="submit"
                                class="px-6 py-3 bg-gradient-to-r from-teal-600 to-emerald-600 text-white rounded-lg font-semibold hover:shadow-lg transition-all duration-300"
                            >
                                Subscribe Now
                            </button>
                        </form>
                        <p class="text-gray-500 text-xs mt-3">We respect your privacy. Unsubscribe at any time.</p>
                    </div>
                </div>
            </section>

            <div class="text-center pt-4">
                <a href="#" class="inline-flex items-center gap-2 text-teal-600 hover:text-teal-700 font-medium">
                    View Archived Announcements
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>
        </div>
    </PageLayout>
</template> -->