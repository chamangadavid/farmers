<!-- Resources/js/Pages/Site/JobVacancies.vue -->
<script setup>
import PageLayout from '@/Components/PageLayout.vue';
import { onMounted, ref } from 'vue';

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
});

const jobs = [
    { 
        title: 'Senior Investigator', 
        type: 'Full-time', 
        location: 'Lusaka', 
        deadline: 'Dec 31, 2024',
        description: 'Lead complex accident investigations and manage investigation teams.',
        requirements: ['10+ years experience', 'Aviation background', 'Leadership skills']
    },
    { 
        title: 'Aviation Safety Analyst', 
        type: 'Full-time', 
        location: 'Lusaka', 
        deadline: 'Dec 15, 2024',
        description: 'Analyze safety data and contribute to investigation reports.',
        requirements: ['5+ years experience', 'Data analysis skills', 'Safety management knowledge']
    },
    { 
        title: 'Research Assistant', 
        type: 'Contract', 
        location: 'Lusaka', 
        deadline: 'Nov 30, 2024',
        description: 'Support research activities and documentation.',
        requirements: ['Research experience', 'Excellent writing skills', 'Attention to detail']
    }
];

// Handle smooth scrolling when clicking anchor links
const handleSmoothScroll = (event) => {
    const hash = window.location.hash;
    if (hash) {
        event.preventDefault();
        const element = document.querySelector(hash);
        if (element) {
            element.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    }
};

onMounted(() => {
    // Check if there's a hash in the URL and scroll to it
    if (window.location.hash) {
        setTimeout(() => {
            const element = document.querySelector(window.location.hash);
            if (element) {
                element.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        }, 100);
    }
    
    // Add click handlers for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('href');
            if (targetId && targetId !== '#') {
                const targetElement = document.querySelector(targetId);
                if (targetElement) {
                    targetElement.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            }
        });
    });
});
</script>

<template>
    <PageLayout 
        title="Job Vacancies" 
        :canLogin="canLogin"
        :canRegister="canRegister"
        :showHero="true"
    >
        <!-- Custom Hero Section for Job Vacancies -->
        <template #hero>
            <div class="text-center mt-12">
                <h1 class="text-4xl sm:text-5xl lg:text-6xl xl:text-7xl font-bold leading-tight mb-6">
                    <span class="bg-gradient-to-r from-teal-400 via-emerald-400 to-teal-300 bg-clip-text text-transparent">
                        Join Our Team
                    </span>
                </h1>
                <p class="text-lg sm:text-xl text-white/80 max-w-3xl mx-auto mb-6">
                    Be part of a world-class aviation safety investigation authority
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a 
                        href="#openings" 
                        class="px-8 py-4 bg-gradient-to-r from-teal-600 to-emerald-600 text-white rounded-xl font-semibold hover:shadow-2xl hover:shadow-teal-500/30 transition-all duration-300 transform hover:-translate-y-1"
                    >
                        View Openings
                    </a>
                    <a 
                        href="#benefits" 
                        class="px-8 py-4 bg-white/10 backdrop-blur-sm border border-teal-400/30 text-white rounded-xl font-semibold hover:bg-white/20 transition-all duration-300"
                    >
                        Benefits
                    </a>
                </div>
            </div>
        </template>

        <!-- Main Content -->
        <div class="space-y-8">
            <!-- Current Openings Section -->
            <section id="openings" class="scroll-mt-24">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Current Openings</h2>
                <div class="space-y-4">
                    <div v-for="job in jobs" :key="job.title" class="border border-gray-200 rounded-lg p-6 hover:shadow-lg transition-all">
                        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                            <div class="flex-1">
                                <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ job.title }}</h3>
                                <p class="text-gray-600 text-sm mb-3">{{ job.description }}</p>
                                <div class="flex flex-wrap gap-3 text-sm">
                                    <span class="px-2 py-1 bg-teal-100 text-teal-700 rounded">{{ job.type }}</span>
                                    <span class="px-2 py-1 bg-gray-100 text-gray-700 rounded">{{ job.location }}</span>
                                    <span class="px-2 py-1 bg-gray-100 text-gray-700 rounded">Deadline: {{ job.deadline }}</span>
                                </div>
                                <div class="mt-3">
                                    <p class="text-xs text-gray-500 mb-1">Requirements:</p>
                                    <div class="flex flex-wrap gap-2">
                                        <span v-for="req in job.requirements" :key="req" class="text-xs px-2 py-1 bg-gray-50 text-gray-600 rounded">
                                            {{ req }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <a 
                                href="#" 
                                class="px-6 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition-colors whitespace-nowrap"
                            >
                                Apply Now
                            </a>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Benefits Section -->
            <section id="benefits" class="scroll-mt-24">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Benefits</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="text-center p-4 hover:shadow-md transition-shadow rounded-lg">
                        <div class="w-12 h-12 bg-teal-100 rounded-full flex items-center justify-center mx-auto mb-3">
                            <svg class="w-6 h-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-2">Competitive Salary</h3>
                        <p class="text-gray-600 text-sm">Market-related compensation packages</p>
                    </div>
                    <div class="text-center p-4 hover:shadow-md transition-shadow rounded-lg">
                        <div class="w-12 h-12 bg-teal-100 rounded-full flex items-center justify-center mx-auto mb-3">
                            <svg class="w-6 h-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-2">Training & Development</h3>
                        <p class="text-gray-600 text-sm">Continuous learning opportunities</p>
                    </div>
                    <div class="text-center p-4 hover:shadow-md transition-shadow rounded-lg">
                        <div class="w-12 h-12 bg-teal-100 rounded-full flex items-center justify-center mx-auto mb-3">
                            <svg class="w-6 h-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-2">Health Benefits</h3>
                        <p class="text-gray-600 text-sm">Comprehensive medical coverage</p>
                    </div>
                </div>
            </section>

            <!-- Application Process -->
            <!-- <section class="mt-8 pt-8 border-t border-gray-200">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">How to Apply</h2>
                <div class="bg-gray-50 rounded-xl p-6">
                    <p class="text-gray-600 mb-4">To apply for any of the positions above, please send your CV and cover letter to:</p>
                    <div class="bg-white rounded-lg p-4 border border-gray-200">
                        <p class="font-mono text-gray-700">careers@aaib.gov.zm</p>
                    </div>
                    <p class="text-gray-600 text-sm mt-4">Please include the job title in the subject line of your email.</p>
                    <p class="text-gray-500 text-xs mt-2">Only shortlisted candidates will be contacted.</p>
                </div>
            </section> -->
        </div>
    </PageLayout>
</template>

<style scoped>
/* Add scroll margin for anchor links */
.scroll-mt-24 {
    scroll-margin-top: 6rem;
}

/* Ensure smooth scrolling for the whole page */
:global(html) {
    scroll-behavior: smooth;
}
</style>