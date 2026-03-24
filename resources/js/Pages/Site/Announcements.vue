<!-- Resources/js/Pages/Site/Announcements.vue -->
<script setup>
import PageLayout from '@/Components/PageLayout.vue';
import { ref } from 'vue';

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
});

const announcements = ref([
    {
        id: 1,
        title: 'AAIB Announces New Investigation Training Program',
        date: 'March 20, 2024',
        category: 'Training',
        priority: 'High',
        summary: 'The Board is launching a comprehensive training program for aviation safety investigators. Applications are now open for qualified candidates.',
        details: 'This 6-month program covers modern investigation techniques, data analysis, and international standards.'
    },
    {
        id: 2,
        title: 'Public Consultation on Aviation Safety Regulations',
        date: 'March 15, 2024',
        category: 'Consultation',
        priority: 'Medium',
        summary: 'We invite stakeholders and the public to provide feedback on proposed amendments to aviation safety regulations.',
        details: 'Consultation period runs from March 20 to April 30, 2024. Submit comments via email to regulations@aaib.gov.zm'
    },
    {
        id: 3,
        title: 'Annual Safety Report 2023 Now Available',
        date: 'February 28, 2024',
        category: 'Publication',
        priority: 'High',
        summary: 'The 2023 Annual Safety Report highlights key findings, recommendations, and safety improvements achieved over the past year.',
        details: 'The report is available for download on our website. Hard copies can be requested from our communications office.'
    },
    {
        id: 4,
        title: 'AAIB Participates in International Aviation Safety Conference',
        date: 'February 10, 2024',
        category: 'Event',
        priority: 'Medium',
        summary: 'Board members will present findings at the International Aviation Safety Conference in Montreal next month.',
        details: 'The conference focuses on emerging technologies and their impact on aviation safety.'
    },
    {
        id: 5,
        title: 'Call for Experts: Technical Advisory Panel',
        date: 'January 25, 2024',
        category: 'Recruitment',
        priority: 'High',
        summary: 'We are seeking aviation experts to join our Technical Advisory Panel for a 2-year term.',
        details: 'Areas of expertise needed: aircraft systems, human factors, air traffic control, and maintenance engineering.'
    }
]);

const selectedCategory = ref('all');
const categories = ['all', ...new Set(announcements.value.map(a => a.category))];

const filteredAnnouncements = ref(announcements.value);

const filterAnnouncements = () => {
    if (selectedCategory.value === 'all') {
        filteredAnnouncements.value = announcements.value;
    } else {
        filteredAnnouncements.value = announcements.value.filter(a => a.category === selectedCategory.value);
    }
};

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
        <!-- Custom Hero Section for Announcements -->
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

        <!-- Main Content -->
        <div class="space-y-8">
            <!-- Filter Section -->
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

            <!-- Announcements List -->
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
                                {{ announcement.date }}
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

            <!-- Subscribe Section -->
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

            <!-- Archive Section -->
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
</template>