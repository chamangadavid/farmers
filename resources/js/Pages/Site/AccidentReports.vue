<!-- Resources/js/Pages/Site/AccidentReports.vue -->
<script setup>
import PageLayout from '@/Components/PageLayout.vue';
import { ref, onMounted, watch, computed } from 'vue';
import axios from 'axios';
import { debounce } from 'lodash';

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
});

const reports = ref([]);
const filteredReports = ref([]);
const loading = ref(false);
const selectedYear = ref('all');
const years = ref(['all']);
const searchQuery = ref('');

// Computed property for search and filter
const performSearchAndFilter = () => {
    let results = reports.value;
    
    // Apply year filter
    if (selectedYear.value !== 'all') {
        results = results.filter(report => report.year === selectedYear.value);
    }
    
    // Apply search filter
    if (searchQuery.value.trim() !== '') {
        const query = searchQuery.value.toLowerCase().trim();
        results = results.filter(report => {
            return (
                (report.title && report.title.toLowerCase().includes(query)) ||
                (report.summary && report.summary.toLowerCase().includes(query)) ||
                (report.reference && report.reference.toLowerCase().includes(query)) ||
                (report.type && report.type.toLowerCase().includes(query)) ||
                (report.status && report.status.toLowerCase().includes(query))
            );
        });
    }
    
    filteredReports.value = results;
};

// Debounced search for better performance
const debouncedSearch = debounce(() => {
    performSearchAndFilter();
}, 300);

// Watch for changes in search query
watch(searchQuery, () => {
    debouncedSearch();
});

// Watch for year changes
watch(selectedYear, () => {
    performSearchAndFilter();
});

const fetchReports = async () => {
    loading.value = true;
    try {
        const res = await axios.get('/public-accident-reports');
        reports.value = res.data.reports;
        generateYears();
        performSearchAndFilter();
    } catch (error) {
        console.error('Failed to fetch reports:', error);
    } finally {
        loading.value = false;
    }
};

const generateYears = () => {
    const uniqueYears = [...new Set(reports.value.map(r => r.year))].sort((a, b) => b - a);
    years.value = ['all', ...uniqueYears];
};

const clearSearch = () => {
    searchQuery.value = '';
    performSearchAndFilter();
};

const trimText = (text, length = 120) => {
    if (!text) return '';
    return text.length > length 
        ? text.substring(0, length).trim() + '...' 
        : text.trim();
};

const formatDate = (dateString) => {
    if (!dateString) return '';
    try {
        return new Date(dateString).toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
    } catch {
        return dateString;
    }
};

// Highlight search term in text
const highlightText = (text, query) => {
    if (!query || !text) return text;
    const regex = new RegExp(`(${query.replace(/[.*+?^${}()|[\]\\]/g, '\\$&')})`, 'gi');
    return text.replace(regex, '<mark class="bg-yellow-200 text-gray-900">$1</mark>');
};

onMounted(() => {
    fetchReports();
});
</script>

<template>
    <PageLayout 
        title="Accident Reports" 
        :canLogin="canLogin" 
        :canRegister="canRegister" 
        :showHero="true"
    >
        <!-- Hero Section -->
        <template #hero>
            <div class="text-center mt-12">
                <h1 class="text-4xl sm:text-5xl lg:text-6xl xl:text-7xl font-bold leading-tight mb-6">
                    <span class="bg-gradient-to-r from-teal-400 via-emerald-400 to-teal-300 bg-clip-text text-transparent">
                        Accident Reports
                    </span>
                </h1>
                <p class="text-lg sm:text-xl text-white/80 max-w-3xl mx-auto mb-6">
                    Access comprehensive investigation reports, findings, and safety recommendations
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a 
                        href="#reports"
                        class="px-8 py-4 bg-gradient-to-r from-teal-600 to-emerald-600 text-white rounded-xl font-semibold hover:shadow-2xl hover:shadow-teal-500/30 transition-all duration-300 transform hover:-translate-y-1"
                    >
                        Browse Reports
                    </a>
                    <a 
                        href="#statistics"
                        class="px-8 py-4 bg-white/10 backdrop-blur-sm border border-teal-400/30 text-white rounded-xl font-semibold hover:bg-white/20 transition-all duration-300"
                    >
                        View Statistics
                    </a>
                </div>
            </div>
        </template>

        <!-- Main Content -->
        <div class="space-y-8">
            <!-- Filter and Search Section -->
            <section class="space-y-4 pb-4 border-b border-gray-200">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">Investigation Reports</h2>
                        <p class="text-gray-600 text-sm mt-1">Published in accordance with ICAO Annex 13 guidelines</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <label for="year-filter" class="text-gray-700 text-sm font-medium">Filter by year:</label>
                        <select 
                            id="year-filter"
                            v-model="selectedYear" 
                            class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 text-gray-700 bg-white"
                        >
                            <option value="all">All Years</option>
                            <option v-for="year in years" :key="year" :value="year" v-if="year !== 'all'">
                                {{ year }}
                            </option>
                        </select>
                    </div>
                </div>
                
                <!-- Search Bar -->
                <div class="relative max-w-2xl">
                    <div class="relative">
                        <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <input 
                            type="text"
                            v-model="searchQuery"
                            placeholder="Search by title, reference, type, status, or summary..."
                            class="w-full pl-10 pr-10 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 text-gray-700"
                        />
                        <button 
                            v-if="searchQuery"
                            @click="clearSearch"
                            class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <div v-if="searchQuery" class="mt-2 text-sm text-gray-500">
                        Found {{ filteredReports.length }} result(s) for "{{ searchQuery }}"
                    </div>
                </div>
            </section>

            <!-- Loading State -->
            <div v-if="loading" class="flex justify-center items-center py-12">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-teal-600"></div>
            </div>

            <!-- Reports List with Vertical Scrollbar -->
            <section 
                id="reports" 
                class="reports-list-container"
                :class="{ 'opacity-50': loading }"
            >
                <div class="space-y-4">
                    <div 
                        v-for="report in filteredReports" 
                        :key="report.reference"
                        class="report-card border border-gray-200 rounded-lg p-6 hover:shadow-lg transition-all duration-300 bg-white"
                    >
                        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
                            <div class="flex-1">
                                <div class="flex flex-wrap items-center gap-3 mb-3">
                                    <span class="px-3 py-1 bg-teal-100 text-teal-700 rounded-full text-xs font-semibold">
                                        {{ report.type || 'Investigation' }} Report
                                    </span>
                                    <span class="px-3 py-1 bg-emerald-100 text-emerald-700 rounded-full text-xs font-semibold">
                                        {{ report.status || 'Published' }}
                                    </span>
                                    <span class="text-gray-500 text-sm font-mono">{{ report.reference }}</span>
                                </div>
                                
                                <h3 class="text-xl font-semibold text-gray-900 mb-2 hover:text-teal-600 transition-colors">
                                    <span v-html="searchQuery ? highlightText(trimText(report.title, 68), searchQuery) : trimText(report.title, 68)"></span>
                                </h3>
                                
                                <p v-if="report.summary" class="text-gray-600 text-sm mt-2 leading-relaxed">
                                    <span v-html="searchQuery ? highlightText(trimText(report.summary, 80), searchQuery) : trimText(report.summary, 80)"></span>
                                </p>
                                
                                <div class="flex flex-wrap items-center gap-4 text-sm text-gray-500 mt-3">
                                    <span class="flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        {{ formatDate(report.date) }}
                                    </span>
                                    <span class="flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        Year: {{ report.year }}
                                    </span>
                                </div>
                            </div>
                            
                            <div class="flex gap-3 flex-shrink-0">
                                <a 
                                    v-if="report.file_url" 
                                    :href="report.file_url" 
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition-colors text-sm flex items-center gap-2 shadow-sm hover:shadow-md"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    Download PDF
                                </a>
                                
                                <button 
                                    v-if="report.summary"
                                    class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors text-sm flex items-center gap-2"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    View Summary
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Empty State -->
                    <div v-if="!loading && filteredReports.length === 0" class="text-center py-12 bg-gray-50 rounded-lg">
                        <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <p class="text-gray-500 text-lg">
                            {{ searchQuery ? 'No reports match your search criteria.' : 'No reports found for the selected year.' }}
                        </p>
                        <p class="text-gray-400 text-sm mt-2">
                            {{ searchQuery ? 'Try using different keywords or clear the search.' : 'Please try selecting a different year.' }}
                        </p>
                        <button 
                            v-if="searchQuery"
                            @click="clearSearch"
                            class="mt-4 px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition-colors text-sm"
                        >
                            Clear Search
                        </button>
                    </div>
                </div>
            </section>

            <!-- Statistics Section -->
            <section id="statistics" class="mt-8 pt-8 border-t border-gray-200">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Statistics & Insights</h2>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div class="bg-gradient-to-br from-teal-50 to-emerald-50 rounded-xl p-6 text-center hover:shadow-lg transition-shadow">
                        <div class="text-3xl font-bold text-teal-600 mb-2">{{ reports.length }}</div>
                        <div class="text-gray-700 font-medium">Total Reports</div>
                        <div class="text-gray-500 text-sm mt-1">Published to date</div>
                    </div>
                    <div class="bg-gradient-to-br from-teal-50 to-emerald-50 rounded-xl p-6 text-center hover:shadow-lg transition-shadow">
                        <div class="text-3xl font-bold text-teal-600 mb-2">
                            {{ reports.filter(r => r.status === 'Closed').length }}
                        </div>
                        <div class="text-gray-700 font-medium">Final Reports</div>
                        <div class="text-gray-500 text-sm mt-1">Completed investigations</div>
                    </div>
                    <div class="bg-gradient-to-br from-teal-50 to-emerald-50 rounded-xl p-6 text-center hover:shadow-lg transition-shadow">
                        <div class="text-3xl font-bold text-teal-600 mb-2">
                            {{ years.filter(y => y !== 'all').length }}
                        </div>
                        <div class="text-gray-700 font-medium">Years Covered</div>
                        <div class="text-gray-500 text-sm mt-1">Historical data</div>
                    </div>
                    <div class="bg-gradient-to-br from-teal-50 to-emerald-50 rounded-xl p-6 text-center hover:shadow-lg transition-shadow">
                        <div class="text-3xl font-bold text-teal-600 mb-2">{{ new Date().getFullYear() - 2021 }}</div>
                        <div class="text-gray-700 font-medium">Years of Service</div>
                        <div class="text-gray-500 text-sm mt-1">Continuous improvement</div>
                    </div>
                </div>
            </section>

            <!-- Note Section -->
            <div class="mt-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-blue-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div>
                        <p class="text-gray-700 text-sm">
                            <strong class="text-blue-600">Note:</strong> All reports are published in accordance with ICAO Annex 13 guidelines.
                            For older reports or specific inquiries, please contact our archives department at 
                            <a href="mailto:archives@aaib.gov.zm" class="text-blue-600 hover:text-blue-700 underline">
                                archives@aaib.gov.zm
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </PageLayout>
</template>

<style scoped>
.reports-list-container {
    max-height: 600px;
    overflow-y: auto;
    overflow-x: hidden;
    scroll-behavior: smooth;
}

/* Custom Scrollbar Styling */
.reports-list-container::-webkit-scrollbar {
    width: 8px;
}

.reports-list-container::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 4px;
}

.reports-list-container::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 4px;
    transition: background 0.2s ease;
}

.reports-list-container::-webkit-scrollbar-thumb:hover {
    background: #14b8a6;
}

/* Firefox Scrollbar Styling */
.reports-list-container {
    scrollbar-width: thin;
    scrollbar-color: #cbd5e1 #f1f1f1;
}

.report-card {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.report-card:hover {
    transform: translateY(-2px);
}

/* Highlight animation for search matches */
mark {
    background-color: #fde047;
    color: #1f2937;
    padding: 0 2px;
    border-radius: 2px;
    transition: background-color 0.2s ease;
}

mark:hover {
    background-color: #facc15;
}

/* Responsive Adjustments */
@media (max-width: 768px) {
    .reports-list-container {
        max-height: 500px;
    }
}

@media (max-width: 640px) {
    .reports-list-container {
        max-height: 450px;
    }
}
</style>