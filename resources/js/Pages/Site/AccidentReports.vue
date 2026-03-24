<!-- Resources/js/Pages/Site/AccidentReports.vue -->
<script setup>
import PageLayout from '@/Components/PageLayout.vue';
import { ref } from 'vue';

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
});

const reports = ref([
    { 
        year: '2024', 
        title: 'Interim Report - Runway Excursion Incident', 
        reference: 'AAIB-2024-001',
        status: 'Published', 
        date: 'March 15, 2024',
        type: 'Interim'
    },
    { 
        year: '2023', 
        title: 'Final Report - Engine Failure Investigation', 
        reference: 'AAIB-2023-008',
        status: 'Published', 
        date: 'December 10, 2023',
        type: 'Final'
    },
    { 
        year: '2023', 
        title: 'Safety Investigation - Landing Gear Malfunction', 
        reference: 'AAIB-2023-005',
        status: 'Published', 
        date: 'August 22, 2023',
        type: 'Final'
    },
    { 
        year: '2022', 
        title: 'Accident Report - Aircraft Collision Investigation', 
        reference: 'AAIB-2022-012',
        status: 'Published', 
        date: 'November 15, 2022',
        type: 'Final'
    },
    { 
        year: '2022', 
        title: 'Special Investigation - Weather Related Incident', 
        reference: 'AAIB-2022-003',
        status: 'Published', 
        date: 'June 30, 2022',
        type: 'Special'
    },
]);

const selectedYear = ref('all');
const years = ['all', '2024', '2023', '2022'];

const filteredReports = ref(reports.value);

const filterReports = () => {
    if (selectedYear.value === 'all') {
        filteredReports.value = reports.value;
    } else {
        filteredReports.value = reports.value.filter(report => report.year === selectedYear.value);
    }
};
</script>

<template>
    <PageLayout 
        title="Accident Reports" 
        :canLogin="canLogin"
        :canRegister="canRegister"
        :showHero="true"
    >
        <!-- Custom Hero Section for Accident Reports -->
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
                    <a href="#reports" class="px-8 py-4 bg-gradient-to-r from-teal-600 to-emerald-600 text-white rounded-xl font-semibold hover:shadow-2xl hover:shadow-teal-500/30 transition-all duration-300 transform hover:-translate-y-1">
                        Browse Reports
                    </a>
                    <a href="#statistics" class="px-8 py-4 bg-white/10 backdrop-blur-sm border border-teal-400/30 text-white rounded-xl font-semibold hover:bg-white/20 transition-all duration-300">
                        View Statistics
                    </a>
                </div>
            </div>
        </template>

        <!-- Main Content -->
        <div class="space-y-8">
            <!-- Filter Section -->
            <section class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 pb-4 border-b border-gray-200">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Investigation Reports</h2>
                    <p class="text-gray-600 text-sm mt-1">Published in accordance with ICAO Annex 13 guidelines</p>
                </div>
                <div class="flex items-center gap-3">
                    <label class="text-gray-700 text-sm font-medium">Filter by year:</label>
                    <select 
                        v-model="selectedYear" 
                        @change="filterReports"
                        class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 text-gray-700"
                    >
                        <option value="all">All Years</option>
                        <option v-for="year in years" :key="year" :value="year" v-if="year !== 'all'">{{ year }}</option>
                    </select>
                </div>
            </section>

            <!-- Reports List -->
            <section id="reports" class="space-y-4">
                <div v-for="report in filteredReports" :key="report.reference" class="border border-gray-200 rounded-lg p-6 hover:shadow-lg transition-all duration-300">
                    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
                        <div class="flex-1">
                            <div class="flex flex-wrap items-center gap-3 mb-2">
                                <span class="px-3 py-1 bg-teal-100 text-teal-700 rounded-full text-xs font-semibold">
                                    {{ report.type }} Report
                                </span>
                                <span class="px-3 py-1 bg-emerald-100 text-emerald-700 rounded-full text-xs font-semibold">
                                    {{ report.status }}
                                </span>
                                <span class="text-gray-500 text-sm">{{ report.reference }}</span>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ report.title }}</h3>
                            <div class="flex items-center gap-4 text-sm text-gray-600">
                                <span>📅 {{ report.date }}</span>
                                <span>📁 Year: {{ report.year }}</span>
                            </div>
                        </div>
                        <div class="flex gap-3">
                            <button class="px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition-colors text-sm flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Download PDF
                            </button>
                            <button class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors text-sm">
                                View Summary
                            </button>
                        </div>
                    </div>
                </div>

                <div v-if="filteredReports.length === 0" class="text-center py-12">
                    <p class="text-gray-500">No reports found for the selected year.</p>
                </div>
            </section>

            <!-- Statistics Section -->
            <section id="statistics" class="mt-8 pt-8 border-t border-gray-200">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Statistics & Insights</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-gradient-to-br from-teal-50 to-emerald-50 rounded-xl p-6 text-center">
                        <div class="text-3xl font-bold text-teal-600 mb-2">{{ reports.length }}</div>
                        <div class="text-gray-700 font-medium">Total Reports</div>
                        <div class="text-gray-500 text-sm mt-1">Published to date</div>
                    </div>
                    <div class="bg-gradient-to-br from-teal-50 to-emerald-50 rounded-xl p-6 text-center">
                        <div class="text-3xl font-bold text-teal-600 mb-2">{{ reports.filter(r => r.type === 'Final').length }}</div>
                        <div class="text-gray-700 font-medium">Final Reports</div>
                        <div class="text-gray-500 text-sm mt-1">Completed investigations</div>
                    </div>
                    <div class="bg-gradient-to-br from-teal-50 to-emerald-50 rounded-xl p-6 text-center">
                        <div class="text-3xl font-bold text-teal-600 mb-2">{{ new Date().getFullYear() - 2022 }}</div>
                        <div class="text-gray-700 font-medium">Years of Service</div>
                        <div class="text-gray-500 text-sm mt-1">Continuous improvement</div>
                    </div>
                </div>
            </section>

            <!-- Note Section -->
            <div class="mt-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
                <p class="text-gray-600 text-sm">
                    <strong class="text-teal-600">Note:</strong> All reports are published in accordance with ICAO Annex 13 guidelines. 
                    For older reports or specific inquiries, please contact our archives department at 
                    <a href="mailto:archives@aaib.gov.zm" class="text-teal-600 hover:text-teal-700">archives@aaib.gov.zm</a>
                </p>
            </div>
        </div>
    </PageLayout>
</template>