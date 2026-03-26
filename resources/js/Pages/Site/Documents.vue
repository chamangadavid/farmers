<!-- Resources/js/Pages/Site/Documents.vue -->
<script setup>
import { ref, onMounted, computed } from 'vue';
import axios from 'axios';
import PageLayout from '@/Components/PageLayout.vue';
import { Empty, Spin, Tag, message, Input } from 'ant-design-vue';
import {
    FolderOutlined,
    FileOutlined,
    DownloadOutlined,
    ArrowLeftOutlined,
    FilePdfOutlined,
    FileImageOutlined,
    FileTextOutlined,
    FileExcelOutlined,
    FileWordOutlined,
    SearchOutlined,
    CloseOutlined
} from '@ant-design/icons-vue';

const folders = ref([]);
const selectedFolder = ref(null);
const loading = ref(false);
const searchTerm = ref('');
const globalSearchTerm = ref('');
const searchResults = ref([]);
const isSearching = ref(false);

// Fetch folders
const fetchFolders = async () => {
    loading.value = true;
    try {
        const res = await axios.get('/documents/folders');
        folders.value = res.data;
    } catch (error) {
        console.error(error);
        message.error('Failed to fetch folders');
    } finally {
        loading.value = false;
    }
};

// Double click handler
const openFolder = (folder) => {
    selectedFolder.value = folder;
    globalSearchTerm.value = '';
    isSearching.value = false;
    searchResults.value = [];
};

// Back to folders
const goBack = () => {
    selectedFolder.value = null;
    searchTerm.value = '';
    globalSearchTerm.value = '';
    isSearching.value = false;
    searchResults.value = [];
};

// Global search across all folders and documents
const performGlobalSearch = () => {
    if (!globalSearchTerm.value.trim()) {
        isSearching.value = false;
        searchResults.value = [];
        return;
    }

    isSearching.value = true;
    const query = globalSearchTerm.value.toLowerCase();
    const results = [];

    folders.value.forEach(folder => {
        const matchingDocuments = folder.documents?.filter(doc =>
            doc.name.toLowerCase().includes(query)
        ) || [];

        if (matchingDocuments.length > 0) {
            results.push({
                folder: folder,
                documents: matchingDocuments
            });
        }
    });

    searchResults.value = results;
};

// Clear global search
const clearGlobalSearch = () => {
    globalSearchTerm.value = '';
    isSearching.value = false;
    searchResults.value = [];
};

// Filter documents within selected folder based on search
const filteredDocuments = computed(() => {
    if (!selectedFolder.value) return [];
    if (!searchTerm.value) return selectedFolder.value.documents;
    const query = searchTerm.value.toLowerCase();
    return selectedFolder.value.documents.filter(doc =>
        doc.name.toLowerCase().includes(query)
    );
});

// Get file icon based on extension
const getFileIcon = (fileName) => {
    const ext = fileName?.split('.').pop()?.toLowerCase();
    if (ext === 'pdf') return FilePdfOutlined;
    if (['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(ext)) return FileImageOutlined;
    if (['xls', 'xlsx', 'csv'].includes(ext)) return FileExcelOutlined;
    if (['doc', 'docx'].includes(ext)) return FileWordOutlined;
    if (['txt', 'md', 'rtf'].includes(ext)) return FileTextOutlined;
    return FileOutlined;
};

// Format file size
const formatFileSize = (bytes) => {
    if (!bytes) return 'N/A';
    const sizes = ['B', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(1024));
    return `${(bytes / Math.pow(1024, i)).toFixed(2)} ${sizes[i]}`;
};

// Format date
const formatDate = (date) => {
    if (!date) return 'N/A';
    return new Date(date).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    });
};

// Get total document count
const totalDocuments = computed(() => {
    return folders.value.reduce((total, folder) => total + (folder.documents?.length || 0), 0);
});

onMounted(() => {
    fetchFolders();
});
</script>

<template>
    <PageLayout title="Document Repository" :showHero="true">
        <template #hero>
            <div class="text-center mt-12">
                <div class="mb-6">
                    <div
                        class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-white/10 backdrop-blur-sm mb-4">
                        <FolderOutlined class="text-4xl text-teal-400" />
                    </div>
                </div>
                <h1 class="text-4xl sm:text-5xl lg:text-6xl xl:text-7xl font-bold leading-tight mb-6">
                    <span
                        class="bg-gradient-to-r from-teal-400 via-emerald-400 to-teal-300 bg-clip-text text-transparent">
                        Document Repository
                    </span>
                </h1>
                <p class="text-lg sm:text-xl text-white/80 max-w-3xl mx-auto">
                    Access all aviation documents, regulations, and resources in one central repository
                </p>
            </div>
        </template>

        <!-- Main Content -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <!-- Global Search Bar (Always Visible) -->
            <div class="mb-8">
                <div class="relative max-w-2xl mx-auto">
                    <div class="relative">
                        <SearchOutlined
                            class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400 text-lg" />
                        <input v-model="globalSearchTerm" @input="performGlobalSearch" type="text"
                            placeholder="Search across all folders and documents..."
                            class="w-full px-12 py-4 border border-gray-300 rounded-xl focus:outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all text-gray-900" />
                        <button v-if="globalSearchTerm" @click="clearGlobalSearch"
                            class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <CloseOutlined />
                        </button>
                    </div>
                    <div v-if="globalSearchTerm && !isSearching" class="text-center text-sm text-gray-500 mt-2">
                        Found {{searchResults.reduce((total, result) => total + result.documents.length, 0)}}
                        result(s)
                    </div>
                </div>
            </div>

            <!-- Loading State -->
            <div v-if="loading" class="text-center py-20">
                <Spin size="large" />
                <p class="mt-4 text-gray-500">Loading documents...</p>
            </div>

            <!-- Content -->
            <div v-else>
                <!-- 🔹 GLOBAL SEARCH RESULTS -->
                <div v-if="isSearching && globalSearchTerm">
                    <div class="mb-6">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-2xl font-bold text-gray-900">Search Results</h2>
                            <button @click="clearGlobalSearch" class="text-sm text-teal-600 hover:text-teal-700">
                                Clear search
                            </button>
                        </div>
                        <p class="text-gray-600 mb-4">Found {{searchResults.reduce((total, result) => total +
                            result.documents.length, 0) }} documents matching "{{ globalSearchTerm }}"</p>
                    </div>

                    <div v-if="searchResults.length > 0" class="space-y-6">
                        <div v-for="result in searchResults" :key="result.folder.id"
                            class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                            <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
                                <div class="flex items-center gap-2">
                                    <img src="/assets/document.png" alt="Folder" class="w-5 h-5" />
                                    <h3 class="font-semibold text-gray-900">{{ result.folder.name }}</h3>
                                    <span class="text-xs text-gray-500">({{ result.documents.length }} documents)</span>
                                </div>
                            </div>
                            <div class="divide-y divide-gray-100">
                                <div v-for="doc in result.documents" :key="doc.id"
                                    class="p-4 hover:bg-gray-50 transition-colors">
                                    <div
                                        class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                                        <div class="flex items-center gap-3 flex-1 min-w-0">
                                            <div
                                                class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0">
                                                <component :is="getFileIcon(doc.name)" class="text-teal-600 text-xl" />
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <h4 class="text-base font-semibold text-gray-900 truncate">
                                                    {{ doc.name }}
                                                </h4>
                                                <div
                                                    class="flex flex-wrap items-center gap-3 text-xs text-gray-500 mt-1">
                                                    <span class="flex items-center gap-1">📄 {{
                                                        doc.file_type?.toUpperCase() ||
                                                        'FILE' }}</span>
                                                    <span class="flex items-center gap-1">💾 {{ formatFileSize(doc.size)
                                                        }}</span>
                                                    <span class="flex items-center gap-1">📅 {{
                                                        formatDate(doc.created_at)
                                                        }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex gap-2 flex-shrink-0">
                                            <button @click="openFolder(result.folder)"
                                                class="px-3 py-1 text-sm text-teal-600 hover:text-teal-700">
                                                Browse folder
                                            </button>
                                            <a :href="`/storage/${doc.file_path}`" target="_blank"
                                                class="inline-flex items-center gap-1 px-3 py-1 bg-teal-50 text-teal-600 rounded-lg hover:bg-teal-100 transition-colors">
                                                <DownloadOutlined class="text-sm" />
                                                <span class="text-sm">Download</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div v-else class="text-center py-16 bg-gray-50 rounded-2xl">
                        <SearchOutlined class="text-5xl text-gray-300 mb-3" />
                        <p class="text-gray-500">No documents found matching "{{ globalSearchTerm }}"</p>
                        <button @click="clearGlobalSearch" class="mt-3 text-teal-600 hover:text-teal-700">
                            Clear search
                        </button>
                    </div>
                </div>

                <!-- 🔹 IF NO FOLDER SELECTED - FOLDER GRID -->
                <div v-else-if="!selectedFolder">
                    <div class="mb-8">
                        <div class="flex items-center justify-between">
                            <div>
                                <h2 class="text-2xl font-bold text-gray-900 mb-2">Document Repository</h2>
                                <p class="text-gray-600">Browse through our organized document folders</p>
                            </div>
                            <div class="bg-teal-50 text-teal-700 px-3 py-1 rounded-full text-sm font-medium">
                                Total: {{ folders.length }} Folders | {{ totalDocuments }} Documents
                            </div>
                        </div>
                        <div class="mt-2 h-1 w-20 bg-gradient-to-r from-teal-500 to-emerald-500 rounded-full"></div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                        <div v-for="folder in folders" :key="folder.id" @dblclick="openFolder(folder)"
                            class="folder-card group cursor-pointer bg-white rounded-xl shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100 hover:border-teal-200">
                            <div class="p-6">
                                <div class="flex items-center justify-center mb-4">
                                    <div
                                        class="w-16 h-16 rounded-full bg-gradient-to-br from-teal-100 to-emerald-100 flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                        <img src="/assets/document.png" alt="Folder" class="w-8 h-8" />
                                    </div>
                                </div>
                                <h3
                                    class="text-lg font-semibold text-gray-900 text-center mb-2 group-hover:text-teal-600 transition-colors">
                                    {{ folder.name }}
                                </h3>
                                <div class="flex items-center justify-center gap-2 text-sm text-gray-500">
                                    <FileOutlined class="text-teal-500" />
                                    <span>{{ folder.documents?.length || 0 }} documents</span>
                                </div>
                                <div class="mt-3 text-center text-xs text-gray-400">
                                    Double-click to open
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Empty State for Folders -->
                    <div v-if="folders.length === 0" class="text-center py-16 bg-gray-50 rounded-2xl">
                        <img src="/assets/document.png" alt="No folders" class="w-24 h-24 mx-auto mb-4 opacity-50" />
                        <Empty description="No folders available" />
                    </div>
                </div>

                <!-- 🔹 IF FOLDER IS OPEN - DOCUMENTS LIST -->
                <div v-else class="folder-content">
                    <!-- Header with Back Button -->
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
                        <button @click="goBack"
                            class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors duration-200 group">
                            <ArrowLeftOutlined class="text-gray-600 group-hover:-translate-x-1 transition-transform" />
                            <span class="text-gray-700">Back to Folders</span>
                        </button>

                        <div class="flex items-center gap-3">
                            <div class="bg-teal-50 text-teal-700 px-3 py-1 rounded-full text-sm font-medium">
                                {{ selectedFolder.documents?.length || 0 }} documents
                            </div>
                        </div>
                    </div>

                    <!-- Folder Header -->
                    <div class="mb-6 pb-4 border-b border-gray-200">
                        <div class="flex items-center gap-3">
                            <div
                                class="w-12 h-12 rounded-full bg-gradient-to-r from-teal-100 to-emerald-100 flex items-center justify-center">
                                <img src="/assets/document.png" alt="Folder" class="w-6 h-6" />
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-gray-900">{{ selectedFolder.name }}</h2>
                                <p class="text-gray-500 text-sm mt-1">Double-click any document to view or download</p>
                            </div>
                        </div>
                    </div>

                    <!-- Folder Search Bar -->
                    <div class="mb-6">
                        <div class="relative max-w-md">
                            <SearchOutlined class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400" />
                            <input v-model="searchTerm" type="text" placeholder="Search documents in this folder..."
                                class="w-full px-4 py-2 pl-10 border border-gray-300 rounded-lg focus:outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all" />
                            <button v-if="searchTerm" @click="searchTerm = ''"
                                class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                <CloseOutlined />
                            </button>
                        </div>
                        <div v-if="searchTerm && filteredDocuments.length > 0" class="text-sm text-gray-500 mt-2">
                            Found {{ filteredDocuments.length }} document(s)
                        </div>
                    </div>

                    <!-- Documents List with Vertical Scrollbar -->
                    <div class="documents-container">
                        <div v-if="filteredDocuments.length > 0" class="space-y-3">
                            <div v-for="doc in filteredDocuments" :key="doc.id"
                                class="document-card bg-white border border-gray-200 rounded-xl p-4 hover:shadow-md transition-all duration-200 group">
                                <div
                                    class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                                    <div class="flex items-center gap-3 flex-1 min-w-0">
                                        <div
                                            class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0">
                                            <component :is="getFileIcon(doc.name)" class="text-teal-600 text-xl" />
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <h4
                                                class="text-base font-semibold text-gray-900 truncate group-hover:text-teal-600 transition-colors">
                                                {{ doc.name }}
                                            </h4>
                                            <div class="flex flex-wrap items-center gap-3 text-xs text-gray-500 mt-1">
                                                <span class="flex items-center gap-1">📄 {{ doc.file_type?.toUpperCase()
                                                    ||
                                                    'FILE' }}</span>
                                                <span class="flex items-center gap-1">💾 {{ formatFileSize(doc.size)
                                                    }}</span>
                                                <span class="flex items-center gap-1">📅 {{ formatDate(doc.created_at)
                                                    }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <a :href="`/storage/${doc.file_path}`" target="_blank"
                                        class="inline-flex items-center gap-2 px-4 py-2 bg-teal-50 text-teal-600 rounded-lg hover:bg-teal-100 transition-all duration-200 group-hover:shadow-sm flex-shrink-0">
                                        <DownloadOutlined />
                                        <span class="text-sm font-medium">Download</span>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- No Documents Found -->
                        <div v-else-if="selectedFolder.documents.length > 0 && filteredDocuments.length === 0"
                            class="text-center py-12 bg-gray-50 rounded-xl">
                            <FileOutlined class="text-5xl text-gray-300 mb-3" />
                            <p class="text-gray-500">No documents match your search</p>
                            <button @click="searchTerm = ''" class="mt-3 text-teal-600 hover:text-teal-700 text-sm">
                                Clear search
                            </button>
                        </div>

                        <!-- Empty Folder State -->
                        <div v-else class="text-center py-12 bg-gray-50 rounded-xl">
                            <img src="/assets/document.png" alt="Empty folder"
                                class="w-20 h-20 mx-auto mb-4 opacity-50" />
                            <p class="text-gray-500">This folder is empty</p>
                            <p class="text-gray-400 text-sm mt-1">No documents available in this folder</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </PageLayout>
</template>

<style scoped>
/* Folder Card Styling */
.folder-card {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.folder-card:hover {
    transform: translateY(-4px);
}

/* Documents Container with Vertical Scrollbar */
.documents-container {
    max-height: 500px;
    overflow-y: auto;
    overflow-x: hidden;
    padding-right: 8px;
}

/* Custom Scrollbar Styling */
.documents-container::-webkit-scrollbar {
    width: 8px;
}

.documents-container::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

.documents-container::-webkit-scrollbar-thumb {
    background: linear-gradient(135deg, #14b8a6, #10b981);
    border-radius: 10px;
}

.documents-container::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(135deg, #0d9488, #059669);
}

/* Firefox Scrollbar */
.documents-container {
    scrollbar-width: thin;
    scrollbar-color: #14b8a6 #f1f1f1;
}

/* Document Card Styling */
.document-card {
    transition: all 0.3s ease;
}

.document-card:hover {
    border-color: #14b8a6;
}

/* Animation for folder content */
.folder-content {
    animation: fadeInUp 0.4s ease-out;
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

/* Search Input Focus */
input:focus {
    outline: none;
    border-color: #14b8a6;
    box-shadow: 0 0 0 3px rgba(20, 184, 166, 0.1);
}

/* Responsive Adjustments */
@media (max-width: 768px) {
    .documents-container {
        max-height: 400px;
        padding-right: 4px;
    }

    .documents-container::-webkit-scrollbar {
        width: 6px;
    }

    .document-card {
        padding: 1rem;
    }

    .folder-card {
        padding: 1rem;
    }

    .global-search {
        padding: 0.75rem;
    }
}
</style>