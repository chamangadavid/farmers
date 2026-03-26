<!-- Resources/js/Pages/Site/NationalRegulation.vue -->
<script setup>
import { ref, onMounted, computed } from 'vue';
import axios from 'axios';
import PageLayout from '@/Components/PageLayout.vue';
import { Tag, Empty, Spin, Card } from 'ant-design-vue';
import {
    FilePdfOutlined,
    DownloadOutlined,
    CalendarOutlined,
    BookOutlined,
    FileTextOutlined
} from '@ant-design/icons-vue';

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
});

const regulations = ref([]);
const loading = ref(false);
const searchTerm = ref('');

const annexes = computed(() =>
    regulations.value.filter(r => r.document_type === 'Annex')
);

const regulationsOnly = computed(() =>
    regulations.value.filter(r => r.document_type === 'Regulation')
);

// Search filtered annexes
const filteredAnnexes = computed(() => {
    if (!searchTerm.value) return annexes.value;
    const query = searchTerm.value.toLowerCase();
    return annexes.value.filter(r =>
        r.title.toLowerCase().includes(query)
    );
});

// Search filtered regulations
const filteredRegulations = computed(() => {
    if (!searchTerm.value) return regulationsOnly.value;
    const query = searchTerm.value.toLowerCase();
    return regulationsOnly.value.filter(r =>
        r.title.toLowerCase().includes(query)
    );
});

// FETCH DATA
const fetchRegulations = async () => {
    loading.value = true;

    try {
        const res = await axios.get('/public/regulations');
        regulations.value = res.data.regulations;
    } catch (error) {
        console.error(error);
    } finally {
        loading.value = false;
    }
};

// Clear search
const clearSearch = () => {
    searchTerm.value = '';
};

// Format date
const formatYear = (date) => {
    if (!date) return 'N/A';
    return new Date(date).getFullYear();
};

onMounted(fetchRegulations);
</script>

<template>
    <PageLayout title="National Regulation" :canLogin="canLogin" :canRegister="canRegister" :showHero="true">
        <!-- Custom Hero Section -->
        <template #hero>
            <div class="text-center mt-12">
                <div class="mb-6">
                    <div
                        class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-white/10 backdrop-blur-sm mb-4">
                        <BookOutlined class="text-4xl text-teal-400" />
                    </div>
                </div>
                <h1 class="text-4xl sm:text-5xl lg:text-6xl xl:text-7xl font-bold leading-tight mb-6">
                    <span
                        class="bg-gradient-to-r from-teal-400 via-emerald-400 to-teal-300 bg-clip-text text-transparent">
                        Regulations & Annexes
                    </span>
                </h1>
                <p class="text-lg sm:text-xl text-white/80 max-w-3xl mx-auto">
                    Access official aviation regulations, standards, and annex documents
                </p>
            </div>
        </template>

        <!-- Main Content -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Search Bar -->
            <div class="mb-8">
                <div class="relative max-w-md mx-auto">
                    <input v-model="searchTerm" type="text" placeholder="Search by title..."
                        class="w-full px-6 py-3 pl-12 border border-gray-300 rounded-xl focus:outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all" />
                    <svg class="absolute left-4 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <button v-if="searchTerm" @click="clearSearch"
                        class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                        ✕
                    </button>
                </div>
                <div v-if="searchTerm" class="text-center text-sm text-gray-500 mt-2">
                    Found {{ filteredAnnexes.length + filteredRegulations.length }} result(s)
                </div>
            </div>

            <!-- Loading State -->
            <div v-if="loading" class="text-center py-20">
                <Spin size="large" />
                <p class="mt-4 text-gray-500">Loading regulations...</p>
            </div>

            <!-- Split Tables -->
            <div v-else class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- LEFT: ANNEX -->
                <div class="regulation-section">
                    <div class="flex items-center gap-3 mb-6">
                        <div
                            class="w-10 h-10 rounded-full bg-gradient-to-r from-purple-100 to-purple-200 flex items-center justify-center">
                            <FileTextOutlined class="text-purple-600 text-xl" />
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">Annex Documents</h2>
                            <p class="text-sm text-gray-500 mt-1">ICAO Annexes and related documents</p>
                        </div>
                        <div class="ml-auto">
                            <Tag color="purple" class="px-3 py-1">
                                {{ annexes.length }} Documents
                            </Tag>
                        </div>
                    </div>

                    <div v-if="filteredAnnexes.length > 0" class="space-y-3">
                        <div v-for="annex in filteredAnnexes" :key="annex.id"
                            class="document-card bg-white border border-gray-200 rounded-xl p-5 hover:shadow-lg transition-all duration-300 group">
                            <div class="flex items-start justify-between gap-4">
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 mb-2">
                                        <FilePdfOutlined class="text-red-500 text-lg" />
                                        <span class="text-xs font-medium text-gray-500">
                                            Annex Document
                                        </span>
                                    </div>
                                    <h3
                                        class="text-lg font-semibold text-gray-900 mb-2 group-hover:text-teal-600 transition-colors">
                                        {{ annex.title }}
                                    </h3>
                                    <div class="flex items-center gap-4 text-sm text-gray-500">
                                        <span class="flex items-center gap-1">
                                            <CalendarOutlined class="text-teal-500" />
                                            {{ formatYear(annex.created_at) }}
                                        </span>
                                    </div>
                                </div>
                                <a :href="`/storage/${annex.regulation_file}`" target="_blank"
                                    class="flex items-center gap-2 px-4 py-2 bg-teal-50 text-teal-600 rounded-lg hover:bg-teal-100 transition-all duration-200 group-hover:shadow-md">
                                    <DownloadOutlined />
                                    <span class="text-sm font-medium">Download</span>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div v-else class="text-center py-12 bg-gray-50 rounded-xl">
                        <Empty description="No annex documents found" />
                    </div>
                </div>

                <!-- RIGHT: REGULATION -->
                <div class="regulation-section">
                    <div class="flex items-center gap-3 mb-6">
                        <div
                            class="w-10 h-10 rounded-full bg-gradient-to-r from-teal-100 to-emerald-100 flex items-center justify-center">
                            <BookOutlined class="text-teal-600 text-xl" />
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">Regulations</h2>
                            <p class="text-sm text-gray-500 mt-1">National aviation regulations and standards</p>
                        </div>
                        <div class="ml-auto">
                            <Tag color="teal" class="px-3 py-1">
                                {{ regulationsOnly.length }} Documents
                            </Tag>
                        </div>
                    </div>

                    <div v-if="filteredRegulations.length > 0" class="space-y-3">
                        <div v-for="regulation in filteredRegulations" :key="regulation.id"
                            class="document-card bg-white border border-gray-200 rounded-xl p-5 hover:shadow-lg transition-all duration-300 group">
                            <div class="flex items-start justify-between gap-4">
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 mb-2">
                                        <FilePdfOutlined class="text-red-500 text-lg" />
                                        <span class="text-xs font-medium text-gray-500">
                                            Regulation Document
                                        </span>
                                    </div>
                                    <h3
                                        class="text-lg font-semibold text-gray-900 mb-2 group-hover:text-teal-600 transition-colors">
                                        {{ regulation.title }}
                                    </h3>
                                    <div class="flex items-center gap-4 text-sm text-gray-500">
                                        <span class="flex items-center gap-1">
                                            <CalendarOutlined class="text-teal-500" />
                                            {{ formatYear(regulation.created_at) }}
                                        </span>
                                    </div>
                                </div>
                                <a :href="`/storage/${regulation.regulation_file}`" target="_blank"
                                    class="flex items-center gap-2 px-4 py-2 bg-teal-50 text-teal-600 rounded-lg hover:bg-teal-100 transition-all duration-200 group-hover:shadow-md">
                                    <DownloadOutlined />
                                    <span class="text-sm font-medium">Download</span>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div v-else class="text-center py-12 bg-gray-50 rounded-xl">
                        <Empty description="No regulations found" />
                    </div>
                </div>
            </div>

            <!-- Stats Section -->
            <div class="mt-12 pt-8 border-t border-gray-200">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-center">
                    <div class="bg-gradient-to-r from-gray-50 to-white rounded-xl p-4">
                        <div class="text-2xl font-bold text-teal-600">{{ annexes.length }}</div>
                        <div class="text-sm text-gray-500">Annex Documents</div>
                    </div>
                    <div class="bg-gradient-to-r from-gray-50 to-white rounded-xl p-4">
                        <div class="text-2xl font-bold text-teal-600">{{ regulationsOnly.length }}</div>
                        <div class="text-sm text-gray-500">Regulation Documents</div>
                    </div>
                    <div class="bg-gradient-to-r from-gray-50 to-white rounded-xl p-4">
                        <div class="text-2xl font-bold text-teal-600">{{ annexes.length + regulationsOnly.length }}
                        </div>
                        <div class="text-sm text-gray-500">Total Documents</div>
                    </div>
                </div>
            </div>
        </div>
    </PageLayout>
</template>

<style scoped>
/* Document Card Styling */
.document-card {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.document-card:hover {
    transform: translateY(-2px);
    border-color: #14b8a6;
}

/* Section Headers */
.regulation-section {
    animation: fadeInUp 0.5s ease-out;
}

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

/* Search Input Styling */
input:focus {
    outline: none;
    border-color: #14b8a6;
    box-shadow: 0 0 0 3px rgba(20, 184, 166, 0.1);
}

/* Custom Scrollbar for Tables */
:deep(.ant-table-body) {
    scrollbar-width: thin;
    scrollbar-color: #14b8a6 #f1f1f1;
}

:deep(.ant-table-body::-webkit-scrollbar) {
    width: 6px;
    height: 6px;
}

:deep(.ant-table-body::-webkit-scrollbar-track) {
    background: #f1f1f1;
    border-radius: 10px;
}

:deep(.ant-table-body::-webkit-scrollbar-thumb) {
    background: linear-gradient(135deg, #14b8a6, #10b981);
    border-radius: 10px;
}

:deep(.ant-table-body::-webkit-scrollbar-thumb:hover) {
    background: linear-gradient(135deg, #0d9488, #059669);
}

/* Responsive Adjustments */
@media (max-width: 768px) {
    .document-card {
        padding: 1rem;
    }

    .document-card h3 {
        font-size: 1rem;
    }

    .document-card .flex {
        flex-direction: column;
        gap: 1rem;
    }

    .document-card a {
        align-self: flex-start;
    }
}

/* Empty State Styling */
:deep(.ant-empty-description) {
    color: #9ca3af;
}
</style>


<!-- Resources/js/Pages/Site/NationalRegulation.vue -->
<!-- <script setup>
    import { ref, onMounted, computed } from 'vue';
    import axios from 'axios';
    import PageLayout from '@/Components/PageLayout.vue';

    defineProps({
        canLogin: Boolean,
        canRegister: Boolean,
    });

    const regulations = ref([]);
    const loading = ref(false);

    const annexes = computed(() =>
        regulations.value.filter(r => r.document_type === 'Annex')
    );

    const regulationsOnly = computed(() =>
        regulations.value.filter(r => r.document_type === 'Regulation')
    );

    const fetchRegulations = async () => {
        loading.value = true;

        try {
            const res = await axios.get('/public/regulations');
            regulations.value = res.data.regulations;
        } catch (error) {
            console.error(error);
        } finally {
            loading.value = false;
        }
    };

    onMounted(fetchRegulations);


</script>

<template>
    <PageLayout title="National Regulation" :canLogin="canLogin" :canRegister="canRegister" :showHero="true">
        <template #hero>
            <div class="text-center mt-12">
                <h1 class="text-4xl sm:text-5xl lg:text-6xl xl:text-7xl font-bold leading-tight mb-6">
                    <span
                        class="bg-gradient-to-r from-teal-400 via-emerald-400 to-teal-300 bg-clip-text text-transparent">
                        Regulations & Annexes
                    </span>
                </h1>
                <p class="text-lg sm:text-xl text-white/80 max-w-3xl mx-auto">
                    Explore regulations and annexes for compliance and safety.
                </p>

            </div>
        </template>

        <div v-if="loading" class="text-center py-10">
            Loading...
        </div>

        <div v-else class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <div>
                <h2 class="text-xl font-bold mb-4">Annex</h2>

                <a-table :dataSource="annexes" rowKey="id" :pagination="{ pageSize: 5 }">
                    <a-table-column title="Title" dataIndex="title" />
                    <a-table-column title="Year" dataIndex="created_at">
                        <template #default="{ record }">
                            {{ new Date(record.created_at).getFullYear() }}
                        </template>
                    </a-table-column>

                    <a-table-column title="Action">
                        <template #default="{ record }">
                            <a :href="`/storage/${record.regulation_file}`" target="_blank" class="text-teal-600">
                                Download
                            </a>
                        </template>
                    </a-table-column>
                </a-table>
            </div>

            <div>
                <h2 class="text-xl font-bold mb-4">Regulation</h2>

                <a-table :dataSource="regulationsOnly" rowKey="id" :pagination="{ pageSize: 5 }">
                    <a-table-column title="Title" dataIndex="title" />
                    <a-table-column title="Year" dataIndex="created_at">
                        <template #default="{ record }">
                            {{ new Date(record.created_at).getFullYear() }}
                        </template>
                    </a-table-column>

                    <a-table-column title="Action">
                        <template #default="{ record }">
                            <a :href="`/storage/${record.regulation_file}`" target="_blank" class="text-teal-600">
                                Download
                            </a>
                        </template>
                    </a-table-column>
                </a-table>
            </div>
        </div>
    </PageLayout>
</template> -->
