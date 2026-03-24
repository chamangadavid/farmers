<!-- Resources/js/Components/RecentInvestigations.vue -->
<script setup>
import { ref, computed } from 'vue';
import { Link } from '@inertiajs/vue3';

const investigations = ref([
    {
        id: 1,
        title: 'Runway Excursion Investigation',
        reference: 'AAIB-2024-001',
        date: 'March 15, 2024',
        type: 'Accident',
        status: 'Completed',
        location: 'Lusaka International Airport',
        aircraft: 'Boeing 737-800',
        summary: 'Investigation into aircraft veering off runway during landing approach.',
        severity: 'Serious',
        reportUrl: '/reports/runway-excursion-2024',
        image: '/assets/reports/runway-incident.jpg'
    },
    {
        id: 2,
        title: 'Engine Failure Investigation',
        reference: 'AAIB-2024-002',
        date: 'February 28, 2024',
        type: 'Incident',
        status: 'Ongoing',
        location: 'Kenneth Kaunda International',
        aircraft: 'Airbus A320',
        summary: 'Technical investigation into engine malfunction during takeoff.',
        severity: 'Significant',
        reportUrl: '/reports/engine-failure-2024',
        image: '/assets/reports/engine-failure.jpg'
    },
    {
        id: 3,
        title: 'Bird Strike Analysis',
        reference: 'AAIB-2023-045',
        date: 'December 10, 2023',
        type: 'Incident',
        status: 'Completed',
        location: 'Ndola Airport',
        aircraft: 'Bombardier CRJ900',
        summary: 'Analysis of bird strike incident and preventive measures implemented.',
        severity: 'Minor',
        reportUrl: '/reports/bird-strike-2023',
        image: '/assets/reports/bird-strike.jpg'
    },
    {
        id: 4,
        title: 'Landing Gear Malfunction',
        reference: 'AAIB-2023-038',
        date: 'November 22, 2023',
        type: 'Accident',
        status: 'Completed',
        location: 'Livingstone Airport',
        aircraft: 'Embraer E195',
        summary: 'Investigation into landing gear deployment failure during approach.',
        severity: 'Serious',
        reportUrl: '/reports/landing-gear-2023',
        image: '/assets/reports/landing-gear.jpg'
    },
    {
        id: 5,
        title: 'Weather-Related Incident',
        reference: 'AAIB-2024-003',
        date: 'January 18, 2024',
        type: 'Incident',
        status: 'Under Review',
        location: 'Mfuwe Airport',
        aircraft: 'Cessna 208',
        summary: 'Investigation into weather-related operational incident.',
        severity: 'Minor',
        reportUrl: '/reports/weather-incident-2024',
        image: '/assets/reports/weather-incident.jpg'
    }
]);

const selectedType = ref('all');
const selectedStatus = ref('all');
const searchQuery = ref('');
const currentPage = ref(1);
const itemsPerPage = 4;

const types = ['all', 'Accident', 'Incident'];
const statuses = ['all', 'Completed', 'Ongoing', 'Under Review'];

const filteredInvestigations = computed(() => {
    let filtered = investigations.value;
    
    if (selectedType.value !== 'all') {
        filtered = filtered.filter(i => i.type === selectedType.value);
    }
    
    if (selectedStatus.value !== 'all') {
        filtered = filtered.filter(i => i.status === selectedStatus.value);
    }
    
    if (searchQuery.value.trim()) {
        filtered = filtered.filter(i => 
            i.title.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
            i.location.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
            i.aircraft.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
            i.reference.toLowerCase().includes(searchQuery.value.toLowerCase())
        );
    }
    
    return filtered;
});

const paginatedInvestigations = computed(() => {
    const start = (currentPage.value - 1) * itemsPerPage;
    const end = start + itemsPerPage;
    return filteredInvestigations.value.slice(start, end);
});

const totalPages = computed(() => {
    return Math.ceil(filteredInvestigations.value.length / itemsPerPage);
});

const getStatusColor = (status) => {
    const colors = {
        'Completed': 'bg-green-100 text-green-700',
        'Ongoing': 'bg-yellow-100 text-yellow-700',
        'Under Review': 'bg-blue-100 text-blue-700'
    };
    return colors[status] || 'bg-gray-100 text-gray-700';
};

const getTypeColor = (type) => {
    return type === 'Accident' ? 'bg-red-100 text-red-700' : 'bg-orange-100 text-orange-700';
};

const getSeverityIcon = (severity) => {
    const icons = {
        'Serious': '🔴',
        'Significant': '🟠',
        'Minor': '🟡'
    };
    return icons[severity] || '⚪';
};
</script>

<template>
    <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-teal-600 to-emerald-600 px-6 py-4">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-2xl font-bold text-white">Recent Investigations & Reports</h2>
                    <p class="text-teal-100 text-sm mt-1">Latest accident and incident investigation findings</p>
                </div>
                <Link href="/accident-reports" class="text-white hover:text-teal-200 font-medium inline-flex items-center gap-1 text-sm">
                    View All Reports
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </Link>
            </div>
        </div>

        <!-- Filters -->
        <div class="p-6 border-b border-gray-200 bg-gray-50">
            <div class="flex flex-col md:flex-row gap-4">
                <div class="flex-1 relative">
                    <input 
                        v-model="searchQuery"
                        type="text" 
                        placeholder="Search by title, location, aircraft, or reference..."
                        class="w-full px-4 py-2 pl-10 border border-gray-300 rounded-lg focus:outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20"
                    />
                    <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <div class="flex gap-3">
                    <select 
                        v-model="selectedType"
                        class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-teal-500"
                    >
                        <option value="all">All Types</option>
                        <option v-for="type in types" :key="type" :value="type" v-if="type !== 'all'">{{ type }}</option>
                    </select>
                    <select 
                        v-model="selectedStatus"
                        class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-teal-500"
                    >
                        <option value="all">All Status</option>
                        <option v-for="status in statuses" :key="status" :value="status" v-if="status !== 'all'">{{ status }}</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Investigations Grid -->
        <div class="p-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div v-for="item in paginatedInvestigations" :key="item.id" class="group">
                    <div class="border border-gray-200 rounded-xl overflow-hidden hover:shadow-xl transition-all duration-300 bg-white">
                        <!-- Image Placeholder (optional) -->
                        <div class="relative h-48 bg-gradient-to-r from-teal-100 to-emerald-100 overflow-hidden">
                            <div class="absolute inset-0 flex items-center justify-center">
                                <div class="text-6xl">{{ getSeverityIcon(item.severity) }}</div>
                            </div>
                            <div class="absolute top-3 left-3 flex gap-2">
                                <span :class="['px-2 py-1 rounded-full text-xs font-semibold', getTypeColor(item.type)]">
                                    {{ item.type }}
                                </span>
                                <span :class="['px-2 py-1 rounded-full text-xs font-semibold', getStatusColor(item.status)]">
                                    {{ item.status }}
                                </span>
                            </div>
                            <div class="absolute bottom-3 right-3 bg-black/50 backdrop-blur-sm px-2 py-1 rounded text-xs text-white">
                                {{ item.reference }}
                            </div>
                        </div>
                        
                        <!-- Content -->
                        <div class="p-5">
                            <div class="flex items-start justify-between mb-2">
                                <h3 class="text-lg font-bold text-gray-900 group-hover:text-teal-600 transition-colors">
                                    {{ item.title }}
                                </h3>
                            </div>
                            
                            <div class="flex flex-wrap gap-3 text-sm text-gray-600 mb-3">
                                <span class="flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    {{ item.date }}
                                </span>
                                <span class="flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    {{ item.location }}
                                </span>
                            </div>
                            
                            <div class="flex flex-wrap gap-3 text-sm text-gray-600 mb-3">
                                <span class="flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    {{ item.aircraft }}
                                </span>
                            </div>
                            
                            <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ item.summary }}</p>
                            
                            <div class="flex justify-between items-center pt-3 border-t border-gray-100">
                                <div class="flex gap-2">
                                    <Link 
                                        :href="item.reportUrl" 
                                        class="text-teal-600 hover:text-teal-700 text-sm font-medium inline-flex items-center gap-1"
                                    >
                                        View Full Report
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                        </svg>
                                    </Link>
                                    <a 
                                        href="#" 
                                        class="text-gray-500 hover:text-gray-700 text-sm inline-flex items-center gap-1"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        Download PDF
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- No Results -->
            <div v-if="filteredInvestigations.length === 0" class="text-center py-12">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p class="text-gray-500">No investigations found matching your criteria</p>
                <p class="text-gray-400 text-sm mt-1">Try adjusting your filters or search term</p>
            </div>

            <!-- Pagination -->
            <div v-if="totalPages > 1" class="flex justify-center items-center gap-2 mt-8 pt-4 border-t border-gray-200">
                <button 
                    @click="currentPage--"
                    :disabled="currentPage === 1"
                    class="px-3 py-1 rounded-lg border border-gray-300 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed transition"
                >
                    Previous
                </button>
                <div class="flex gap-1">
                    <button 
                        v-for="page in totalPages" 
                        :key="page"
                        @click="currentPage = page"
                        :class="[
                            'px-3 py-1 rounded-lg transition',
                            currentPage === page 
                                ? 'bg-teal-600 text-white' 
                                : 'border border-gray-300 hover:bg-gray-50'
                        ]"
                    >
                        {{ page }}
                    </button>
                </div>
                <button 
                    @click="currentPage++"
                    :disabled="currentPage === totalPages"
                    class="px-3 py-1 rounded-lg border border-gray-300 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed transition"
                >
                    Next
                </button>
            </div>

            <!-- Statistics Summary -->
            <div class="mt-6 pt-4 border-t border-gray-200">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-center">
                    <div class="bg-gray-50 rounded-lg p-3">
                        <div class="text-2xl font-bold text-teal-600">{{ investigations.length }}</div>
                        <div class="text-xs text-gray-600">Total Investigations</div>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3">
                        <div class="text-2xl font-bold text-red-600">{{ investigations.filter(i => i.type === 'Accident').length }}</div>
                        <div class="text-xs text-gray-600">Accidents</div>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3">
                        <div class="text-2xl font-bold text-orange-600">{{ investigations.filter(i => i.type === 'Incident').length }}</div>
                        <div class="text-xs text-gray-600">Incidents</div>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3">
                        <div class="text-2xl font-bold text-green-600">{{ investigations.filter(i => i.status === 'Completed').length }}</div>
                        <div class="text-xs text-gray-600">Completed</div>
                    </div>
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