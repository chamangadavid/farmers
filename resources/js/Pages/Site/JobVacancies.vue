<!-- Resources/js/Pages/Site/JobVacancies.vue -->
<script setup>
import PageLayout from '@/Components/PageLayout.vue';
import { onMounted, ref, computed } from 'vue';
import axios from 'axios';

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
});

const jobs = ref([]);
const loading = ref(false);
const selectedJobType = ref('all');
const selectedLocation = ref('all');

// Filter options
const jobTypes = computed(() => {
    const types = ['all', ...new Set(jobs.value.map(job => job.type).filter(Boolean))];
    return types;
});

const locations = computed(() => {
    const locs = ['all', ...new Set(jobs.value.map(job => job.location).filter(Boolean))];
    return locs;
});

// Filtered jobs
const filteredJobs = computed(() => {
    let result = jobs.value;
    
    if (selectedJobType.value !== 'all') {
        result = result.filter(job => job.type === selectedJobType.value);
    }
    
    if (selectedLocation.value !== 'all') {
        result = result.filter(job => job.location === selectedLocation.value);
    }
    
    return result;
});

// Helper function to get correct PDF URL
const getPdfUrl = (pdfFile) => {
    if (!pdfFile) return null;
    
    // If it's already a full URL
    if (pdfFile.startsWith('http')) {
        return pdfFile;
    }
    
    // Remove any 'storage/' prefix to avoid duplication
    let cleanPath = pdfFile.replace(/^storage\//, '');
    
    // Remove any leading slashes
    cleanPath = cleanPath.replace(/^\/+/, '');
    
    // Return the correct storage URL
    return `/storage/${cleanPath}`;
};

const fetchJobs = async () => {
    loading.value = true;
    try {
        const res = await axios.get('/jobs/active');
        jobs.value = res.data.jobs.map(job => ({
            ...job,
            requirements: Array.isArray(job.requirements) ? job.requirements : 
                         (job.requirements ? (typeof job.requirements === 'string' ? JSON.parse(job.requirements) : job.requirements) : []),
            deadline: job.deadline ? new Date(job.deadline).toISOString().split('T')[0] : null,
            created_at: job.created_at ? new Date(job.created_at).toLocaleDateString() : null,
            pdf_url: getPdfUrl(job.pdf_file) // Pre-compute PDF URL
        }));
    } catch (error) {
        console.error('Failed to fetch jobs:', error);
    } finally {
        loading.value = false;
    }
};

const formatDeadline = (deadline) => {
    if (!deadline) return 'Not specified';
    const today = new Date();
    const deadlineDate = new Date(deadline);
    const daysUntil = Math.ceil((deadlineDate - today) / (1000 * 60 * 60 * 24));
    
    if (daysUntil < 0) return `Expired (${deadline})`;
    if (daysUntil === 0) return `Today (${deadline})`;
    if (daysUntil <= 7) return `${daysUntil} day(s) left (${deadline})`;
    return deadline;
};

const getDeadlineClass = (deadline) => {
    if (!deadline) return '';
    const today = new Date();
    const deadlineDate = new Date(deadline);
    const daysUntil = Math.ceil((deadlineDate - today) / (1000 * 60 * 60 * 24));
    
    if (daysUntil < 0) return 'deadline-expired';
    if (daysUntil <= 7) return 'deadline-urgent';
    return '';
};

onMounted(() => {
    fetchJobs();

    // Smooth scroll logic
    if (window.location.hash) {
        setTimeout(() => {
            const element = document.querySelector(window.location.hash);
            if (element) {
                element.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        }, 100);
    }
    
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
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
    <PageLayout title="Job Vacancies" :canLogin="canLogin" :canRegister="canRegister" :showHero="true">
        <!-- Custom Hero Section for Job Vacancies -->
        <template #hero>
            <div class="text-center mt-12">
                <h1 class="text-4xl sm:text-5xl lg:text-6xl xl:text-7xl font-bold leading-tight mb-6">
                    <span class="bg-gradient-to-r from-teal-400 via-emerald-400 to-teal-300 bg-clip-text text-transparent">
                        Career Opportunities
                    </span>
                </h1>
                <p class="text-lg sm:text-xl text-white/80 max-w-3xl mx-auto mb-6">
                    Discover exciting job vacancies and take the next step in your career with a leading aviation safety investigation authority.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="#openings"
                        class="px-8 py-4 bg-gradient-to-r from-teal-600 to-emerald-600 text-white rounded-xl font-semibold hover:shadow-2xl hover:shadow-teal-500/30 transition-all duration-300 transform hover:-translate-y-1">
                        View Openings
                    </a>
                    <a href="#benefits"
                        class="px-8 py-4 bg-white/10 backdrop-blur-sm border border-teal-400/30 text-white rounded-xl font-semibold hover:bg-white/20 transition-all duration-300">
                        Benefits
                    </a>
                </div>
            </div>
        </template>

        <!-- Main Content -->
        <div class="space-y-8">
            <!-- Current Openings Section -->
            <section id="openings" class="scroll-mt-24">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">Current Openings</h2>
                        <p class="text-gray-600 text-sm mt-1">
                            {{ filteredJobs.length }} position{{ filteredJobs.length !== 1 ? 's' : '' }} available
                        </p>
                    </div>
                    
                    <!-- Filters -->
                    <div class="flex flex-col sm:flex-row gap-3">
                        <select 
                            v-model="selectedJobType"
                            class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 text-gray-700 bg-white text-sm"
                        >
                            <option value="all">All Job Types</option>
                            <option v-for="type in jobTypes" :key="type" :value="type" v-if="type !== 'all'">
                                {{ type }}
                            </option>
                        </select>
                        
                        <select 
                            v-model="selectedLocation"
                            class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 text-gray-700 bg-white text-sm"
                        >
                            <option value="all">All Locations</option>
                            <option v-for="loc in locations" :key="loc" :value="loc" v-if="loc !== 'all'">
                                {{ loc }}
                            </option>
                        </select>
                    </div>
                </div>

                <!-- Loading State -->
                <div v-if="loading" class="flex justify-center items-center py-12">
                    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-teal-600"></div>
                </div>

                <!-- Jobs List with Vertical Scrollbar -->
                <div v-else class="jobs-list-container">
                    <div class="space-y-4">
                        <div v-for="job in filteredJobs" :key="job.id"
                            class="job-card border border-gray-200 rounded-lg p-6 hover:shadow-xl transition-all duration-300 bg-white">
                            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
                                <div class="flex-1">
                                    <!-- Job Header -->
                                    <div class="flex flex-wrap items-center gap-2 mb-3">
                                        <span class="px-3 py-1 bg-teal-100 text-teal-700 rounded-full text-xs font-semibold">
                                            {{ job.type }}
                                        </span>
                                        <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-xs">
                                            {{ job.location }}
                                        </span>
                                        <span 
                                            :class="['px-3 py-1 rounded-full text-xs font-semibold', getDeadlineClass(job.deadline)]"
                                        >
                                            ⏰ {{ formatDeadline(job.deadline) }}
                                        </span>
                                    </div>
                                    
                                    <h3 class="text-xl font-semibold text-gray-900 mb-2 hover:text-teal-600 transition-colors">
                                        {{ job.title }}
                                    </h3>
                                    
                                    <p class="text-gray-600 text-sm leading-relaxed mb-3 line-clamp-2">
                                        {{ job.description }}
                                    </p>
                                    
                                    <!-- Requirements -->
                                    <div v-if="job.requirements && job.requirements.length" class="mt-3">
                                        <p class="text-xs font-semibold text-gray-500 mb-2 uppercase tracking-wide">
                                            Key Requirements:
                                        </p>
                                        <div class="flex flex-wrap gap-2">
                                            <span v-for="req in job.requirements.slice(0, 4)" :key="req"
                                                class="text-xs px-2 py-1 bg-gray-50 text-gray-600 rounded border border-gray-200">
                                                {{ req }}
                                            </span>
                                            <span v-if="job.requirements.length > 4" 
                                                class="text-xs px-2 py-1 text-teal-600">
                                                +{{ job.requirements.length - 4 }} more
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <!-- Posted Date -->
                                    <div v-if="job.created_at" class="mt-3 text-xs text-gray-400">
                                        Posted: {{ job.created_at }}
                                    </div>
                                </div>
                                
                                <!-- Action Buttons -->
                                <div class="flex flex-col gap-2 min-w-[140px]">
                                    <a :href="`/jobs/${job.id}/apply`"
                                        class="px-6 py-2.5 bg-gradient-to-r from-teal-600 to-emerald-600 text-white rounded-lg hover:from-teal-700 hover:to-emerald-700 transition-all duration-300 text-sm font-semibold text-center shadow-sm hover:shadow-md">
                                        Apply Now
                                    </a>
                                    
                                    <!-- PDF Download - FIXED URL -->
                                    <a v-if="job.pdf_url" 
                                        :href="job.pdf_url" 
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        class="px-6 py-2 border border-teal-600 text-teal-600 rounded-lg hover:bg-teal-50 transition-all duration-300 text-sm font-medium text-center flex items-center justify-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        Job Description
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Empty State -->
                        <div v-if="filteredJobs.length === 0" class="text-center py-12 bg-gray-50 rounded-lg">
                            <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                    d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            <p class="text-gray-500 text-lg">No job openings available</p>
                            <p class="text-gray-400 text-sm mt-2">Please check back later for new opportunities</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Benefits Section -->
            <section id="benefits" class="scroll-mt-24 pt-8 border-t border-gray-200">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Why Join Us?</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="benefit-card text-center p-6 rounded-xl bg-gradient-to-br from-gray-50 to-white border border-gray-200 hover:shadow-lg transition-all duration-300">
                        <div class="w-14 h-14 bg-teal-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-7 h-7 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                </path>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-2">Competitive Salary</h3>
                        <p class="text-gray-600 text-sm">Market-related compensation packages with annual reviews</p>
                    </div>
                    
                    <div class="benefit-card text-center p-6 rounded-xl bg-gradient-to-br from-gray-50 to-white border border-gray-200 hover:shadow-lg transition-all duration-300">
                        <div class="w-14 h-14 bg-teal-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-7 h-7 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                </path>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-2">Professional Development</h3>
                        <p class="text-gray-600 text-sm">Continuous learning, training programs, and career advancement</p>
                    </div>
                    
                    <div class="benefit-card text-center p-6 rounded-xl bg-gradient-to-br from-gray-50 to-white border border-gray-200 hover:shadow-lg transition-all duration-300">
                        <div class="w-14 h-14 bg-teal-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-7 h-7 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                </path>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-2">Comprehensive Benefits</h3>
                        <p class="text-gray-600 text-sm">Health, dental, and retirement plans for you and your family</p>
                    </div>
                </div>
            </section>
        </div>
    </PageLayout>
</template>

<style scoped>
/* Scroll margin for anchor links */
.scroll-mt-24 {
    scroll-margin-top: 6rem;
}

/* Jobs List Container with Vertical Scrollbar */
.jobs-list-container {
    max-height: 700px;
    overflow-y: auto;
    overflow-x: hidden;
    scroll-behavior: smooth;
    padding-right: 4px;
}

/* Custom Scrollbar Styling */
.jobs-list-container::-webkit-scrollbar {
    width: 8px;
}

.jobs-list-container::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 4px;
}

.jobs-list-container::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 4px;
    transition: background 0.2s ease;
}

.jobs-list-container::-webkit-scrollbar-thumb:hover {
    background: #14b8a6;
}

/* Firefox Scrollbar Styling */
.jobs-list-container {
    scrollbar-width: thin;
    scrollbar-color: #cbd5e1 #f1f5f9;
}

/* Job Card Styling */
.job-card {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.job-card:hover {
    transform: translateY(-2px);
}

/* Deadline Styling */
.deadline-expired {
    background: #fee2e2;
    color: #991b1b;
}

.deadline-urgent {
    background: #ffedd5;
    color: #9a3412;
}

/* Benefit Card Styling */
.benefit-card {
    transition: all 0.3s ease;
}

.benefit-card:hover {
    transform: translateY(-4px);
}

/* Line clamp for description */
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Responsive Adjustments */
@media (max-width: 768px) {
    .jobs-list-container {
        max-height: 550px;
    }
    
    .job-card {
        padding: 1rem;
    }
}

@media (max-width: 640px) {
    .jobs-list-container {
        max-height: 500px;
    }
}

/* Smooth scrolling for the whole page */
:global(html) {
    scroll-behavior: smooth;
}

/* Filter dropdown styling */
select:focus {
    outline: none;
    border-color: #14b8a6;
    box-shadow: 0 0 0 2px rgba(20, 184, 166, 0.1);
}

/* Animation for cards */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.job-card {
    animation: fadeInUp 0.3s ease-out;
}
</style>