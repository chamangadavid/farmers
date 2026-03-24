<!-- Resources/js/Components/SearchSection.vue -->
<script setup>
import { ref, computed } from 'vue';

const searchQuery = ref('');
const searchResults = ref([]);
const isSearching = ref(false);
const searchType = ref('accidents'); // 'accidents' or 'incidents'

const searchInvestigations = () => {
    if (!searchQuery.value.trim()) {
        searchResults.value = [];
        return;
    }
    
    isSearching.value = true;
    
    // Simulate API search
    setTimeout(() => {
        const mockResults = [
            {
                id: 1,
                title: 'Runway Excursion Investigation - 2023',
                type: 'Accident',
                date: 'December 2023',
                location: 'Lusaka International Airport',
                summary: 'Investigation into aircraft veering off runway during landing.',
                status: 'Completed'
            },
            {
                id: 2,
                title: 'Engine Failure Incident - 2024',
                type: 'Incident',
                date: 'January 2024',
                location: 'Kenneth Kaunda International',
                summary: 'Technical investigation into engine malfunction during takeoff.',
                status: 'Ongoing'
            },
            {
                id: 3,
                title: 'Bird Strike Investigation - 2023',
                type: 'Incident',
                date: 'November 2023',
                location: 'Ndola Airport',
                summary: 'Analysis of bird strike incident and preventive measures.',
                status: 'Completed'
            }
        ];
        
        searchResults.value = mockResults.filter(result => 
            result.title.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
            result.location.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
            result.summary.toLowerCase().includes(searchQuery.value.toLowerCase())
        );
        
        isSearching.value = false;
    }, 500);
};

const getStatusColor = (status) => {
    return status === 'Completed' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700';
};
</script>

<template>
    <div class="bg-white rounded-2xl shadow-xl p-6 md:p-8">
        <div class="text-center mb-6">
            <h2 class="text-2xl md:text-3xl font-bold text-gray-900">Search Investigations</h2>
            <p class="text-gray-600 mt-1">Search through our database of accident and incident investigations</p>
        </div>

        <!-- Search Input -->
        <div class="max-w-2xl mx-auto">
            <div class="flex flex-col sm:flex-row gap-3">
                <div class="flex-1 relative">
                    <input 
                        v-model="searchQuery"
                        @input="searchInvestigations"
                        type="text" 
                        placeholder="Search by location, aircraft type, or keywords..."
                        class="w-full px-4 py-3 pl-10 border border-gray-300 rounded-lg focus:outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20"
                    />
                    <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <div class="flex gap-2">
                    <button 
                        @click="searchType = 'accidents'"
                        :class="[
                            'px-4 py-2 rounded-lg font-medium transition-all',
                            searchType === 'accidents' 
                                ? 'bg-teal-600 text-white' 
                                : 'bg-gray-100 text-gray-700 hover:bg-gray-200'
                        ]"
                    >
                        Accidents
                    </button>
                    <button 
                        @click="searchType = 'incidents'"
                        :class="[
                            'px-4 py-2 rounded-lg font-medium transition-all',
                            searchType === 'incidents' 
                                ? 'bg-teal-600 text-white' 
                                : 'bg-gray-100 text-gray-700 hover:bg-gray-200'
                        ]"
                    >
                        Incidents
                    </button>
                </div>
            </div>

            <!-- Loading State -->
            <div v-if="isSearching" class="text-center py-8">
                <svg class="animate-spin h-8 w-8 text-teal-600 mx-auto" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <p class="text-gray-600 mt-2">Searching investigations...</p>
            </div>

            <!-- Search Results -->
            <div v-else-if="searchQuery && searchResults.length > 0" class="mt-6 space-y-4">
                <div v-for="result in searchResults" :key="result.id" class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                    <div class="flex flex-wrap justify-between items-start gap-2 mb-2">
                        <h3 class="font-semibold text-gray-900">{{ result.title }}</h3>
                        <span :class="['px-2 py-1 rounded-full text-xs font-semibold', getStatusColor(result.status)]">
                            {{ result.status }}
                        </span>
                    </div>
                    <div class="flex flex-wrap gap-3 text-sm text-gray-600 mb-2">
                        <span>📅 {{ result.date }}</span>
                        <span>📍 {{ result.location }}</span>
                        <span>🔍 {{ result.type }}</span>
                    </div>
                    <p class="text-gray-600 text-sm mb-3">{{ result.summary }}</p>
                    <a href="#" class="text-teal-600 hover:text-teal-700 text-sm font-medium inline-flex items-center gap-1">
                        View investigation details
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>

            <!-- No Results -->
            <div v-else-if="searchQuery && searchResults.length === 0" class="text-center py-8">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p class="text-gray-500">No investigations found for "{{ searchQuery }}"</p>
                <p class="text-gray-400 text-sm mt-1">Try different keywords or browse our reports section</p>
            </div>

            <!-- Initial Message -->
            <div v-else class="text-center py-8">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                <p class="text-gray-500">Enter search terms to find accident and incident investigations</p>
                <p class="text-gray-400 text-sm mt-1">Try searching by location, date, or aircraft type</p>
            </div>

            <!-- Quick Links -->
            <div class="mt-6 pt-4 border-t border-gray-200">
                <p class="text-sm text-gray-600 mb-2">Quick links:</p>
                <div class="flex flex-wrap gap-2">
                    <a href="/accident-reports" class="text-sm text-teal-600 hover:text-teal-700">All Reports</a>
                    <span class="text-gray-300">|</span>
                    <a href="/investigation-process" class="text-sm text-teal-600 hover:text-teal-700">Investigation Process</a>
                    <span class="text-gray-300">|</span>
                    <a href="/statistics" class="text-sm text-teal-600 hover:text-teal-700">Statistics</a>
                </div>
            </div>
        </div>
    </div>
</template>