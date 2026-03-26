<!-- Resources/js/Pages/Site/PressReleases.vue -->
<script setup>
import PageLayout from '@/Components/PageLayout.vue';
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { debounce } from 'lodash';

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
});

const searchTerm = ref('');
const releases = ref([]);
const loading = ref(false);

const fetchPresses = async () => {
    loading.value = true;
    try {
        const res = await axios.get('/public/presses', {
            params: {
                search: searchTerm.value
            }
        });
        releases.value = res.data.presses;
    } catch (error) {
        console.error(error);
    } finally {
        loading.value = false;
    }
};

const handleSearch = debounce(() => {
    fetchPresses();
}, 500);

const trimText = (text, length = 200) => {
    return text.length > length ? text.substring(0, length) + '...' : text;
};

const formatDate = (date) => {
    if (!date) return 'N/A';
    return new Date(date).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
};

onMounted(() => {
    fetchPresses();
});
</script>

<template>
    <PageLayout title="Press Releases" :canLogin="canLogin" :canRegister="canRegister" :showHero="true">
        <!-- Custom Hero Section for Press Releases -->
        <template #hero>
            <div class="text-center mt-12">
                <div class="mb-6">
                    <div
                        class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-white/10 backdrop-blur-sm mb-4">
                        <svg class="w-10 h-10 text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z">
                            </path>
                        </svg>
                    </div>
                </div>
                <h1 class="text-4xl sm:text-5xl lg:text-6xl xl:text-7xl font-bold leading-tight mb-6">
                    <span
                        class="bg-gradient-to-r from-teal-400 via-emerald-400 to-teal-300 bg-clip-text text-transparent">
                        Press Releases
                    </span>
                </h1>
                <p class="text-lg sm:text-xl text-white/80 max-w-3xl mx-auto">
                    Official statements and announcements from AAIB
                </p>
            </div>
        </template>

        <!-- Main Content -->
        <div class="max-w-4xl mx-auto">
            <!-- Search Bar and Stats -->
            <div class="mb-8">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-4">
                    <div class="text-sm text-gray-500">
                        Showing {{ releases.length }} press {{ releases.length === 1 ? 'release' : 'releases' }}
                    </div>
                    <div class="relative w-full sm:w-80">
                        <input v-model="searchTerm" @input="handleSearch" type="text"
                            placeholder="Search press releases..."
                            class="w-full px-6 py-3 border border-gray-300 rounded-xl focus:outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all" />
                        <svg class="absolute right-4 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Scrollable Container -->
            <div class="press-releases-container">
                <!-- Loading State -->
                <div v-if="loading" class="text-center py-12">
                    <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-teal-600"></div>
                    <p class="mt-4 text-gray-500">Loading press releases...</p>
                </div>

                <!-- Releases List -->
                <div v-else-if="releases.length > 0" class="space-y-4">
                    <div v-for="release in releases" :key="release.id"
                        class="release-card border border-gray-200 rounded-xl p-6 hover:shadow-lg transition-all duration-300">
                        <div class="flex flex-wrap justify-between items-start gap-4 mb-3">
                            <h3 class="text-xl font-bold text-gray-900 hover:text-teal-600 transition-colors">
                                {{ trimText(release.title, 20) }}
                            </h3>
                            <span class="text-gray-500 text-sm flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                                {{ formatDate(release.date) }}
                            </span>
                        </div>
                        <p class="text-gray-600 mb-4 leading-relaxed">
                            {{ trimText(release.summary, 130) }}
                        </p>
                        <a :href="release.pdfLink" target="_blank"
                            class="inline-flex items-center gap-2 text-teal-600 hover:text-teal-700 font-medium group">
                            <svg class="w-4 h-4 group-hover:translate-y-0.5 transition-transform" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                            Download Press Release (PDF)
                        </a>
                    </div>
                </div>

                <!-- Empty State -->
                <div v-else class="text-center py-16 bg-gray-50 rounded-2xl">
                    <div class="text-6xl mb-4">📄</div>
                    <p class="text-gray-500 text-lg mb-2">No press releases found</p>
                    <p class="text-gray-400 text-sm">
                        {{ searchTerm ? 'Try adjusting your search criteria' : 'Check back later for updates' }}
                    </p>
                    <button v-if="searchTerm" @click="searchTerm = ''; fetchPresses()"
                        class="mt-4 px-6 py-2 bg-gradient-to-r from-teal-600 to-emerald-600 text-white rounded-lg hover:shadow-lg transition-all">
                        Clear Search
                    </button>
                </div>
            </div>
        </div>
    </PageLayout>
</template>

<style scoped>
/* Scrollable Container */
.press-releases-container {
    max-height: 600px;
    overflow-y: auto;
    overflow-x: hidden;
    padding-right: 8px;
}

/* Custom Scrollbar Styling */
.press-releases-container::-webkit-scrollbar {
    width: 8px;
}

.press-releases-container::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

.press-releases-container::-webkit-scrollbar-thumb {
    background: linear-gradient(135deg, #14b8a6, #10b981);
    border-radius: 10px;
}

.press-releases-container::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(135deg, #0d9488, #059669);
}

/* Release Card Styling */
.release-card {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    background: white;
}

.release-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.02);
    border-color: #14b8a6;
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

/* Search Input Focus Effects */
input:focus {
    outline: none;
    border-color: #14b8a6;
    box-shadow: 0 0 0 3px rgba(20, 184, 166, 0.1);
}

/* Responsive Adjustments */
@media (max-width: 768px) {
    .press-releases-container {
        max-height: 500px;
        padding-right: 4px;
    }

    .release-card {
        padding: 1rem;
    }

    .release-card h3 {
        font-size: 1.125rem;
    }

    .press-releases-container::-webkit-scrollbar {
        width: 6px;
    }
}

/* Firefox Scrollbar */
.press-releases-container {
    scrollbar-width: thin;
    scrollbar-color: #14b8a6 #f1f1f1;
}

/* Smooth Scroll */
.press-releases-container {
    scroll-behavior: smooth;
}

/* Hover Effects for Links */
a {
    transition: all 0.2s ease;
}

a:hover svg {
    transform: translateY(2px);
}

/* Stats Badge Styling */
.stats-badge {
    background: linear-gradient(135deg, #f0fdfa, #ecfdf5);
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

.text-center.py-16 {
    animation: fadeIn 0.3s ease-out;
}
</style>



<!-- Resources/js/Pages/Site/PressReleases.vue -->
<!-- <script setup>
import PageLayout from '@/Components/PageLayout.vue';
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { debounce } from 'lodash';

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
});


const searchTerm = ref('');
const releases = ref([]);

const fetchPresses = async () => {
    try {
        const res = await axios.get('/public/presses', {
            params: {
                search: searchTerm.value
            }
        });
        releases.value = res.data.presses;
    } catch (error) {
        console.error(error);
    }
};

const handleSearch = debounce(() => {
    fetchPresses();
}, 500);

const trimText = (text, length = 200) => {
    return text.length > length ? text.substring(0, length) + '...' : text;
};

onMounted(() => {
    fetchPresses();
});

</script>

<template>
    <PageLayout title="Press Releases" :canLogin="canLogin" :canRegister="canRegister" :showHero="true">
     
         <template #hero>
            <div class="text-center mt-12">
                <h1 class="text-4xl sm:text-5xl lg:text-6xl xl:text-7xl font-bold leading-tight mb-6">
                    <span
                        class="bg-gradient-to-r from-teal-400 via-emerald-400 to-teal-300 bg-clip-text text-transparent">
                        Press Releases
                    </span>
                </h1>
                <p class="text-lg sm:text-xl text-white/80 max-w-3xl mx-auto">
                    Official statements and announcements from AAIB
                </p>
            </div>
        </template>

        <div class="space-y-6">
            <div class="max-w-2xl mb-6">
                <div class="relative">
                    <input v-model="searchTerm" @input="handleSearch" type="text" placeholder="Search press releases..."
                        class="w-full px-6 py-3 border border-gray-300 rounded-xl focus:outline-none focus:border-teal-500" />

                    <svg class="absolute right-4 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
            </div>
            <div v-for="release in releases" :key="release.id"
                class="border border-gray-200 rounded-xl p-6 hover:shadow-lg transition-all">
                <div class="flex flex-wrap justify-between items-start gap-4 mb-3">
                    <h3 class="text-xl font-bold text-gray-900">{{ trimText(release.title, 20) }}</h3>
                    <span class="text-gray-500 text-sm">{{ release.date }}</span>
                </div>
                <p class="text-gray-600 mb-4">
                    {{ trimText(release.summary, 130) }}
                </p>
                <a :href="release.pdfLink"
                    class="inline-flex items-center gap-2 text-teal-600 hover:text-teal-700 font-medium">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                    Download Press Release (PDF)
                </a>
            </div>

            <div v-if="releases.length === 0" class="text-center py-10">
                <p class="text-gray-500">No press releases found.</p>
            </div>
        </div>
    </PageLayout>
</template> -->