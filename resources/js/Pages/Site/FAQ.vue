<!-- Resources/js/Pages/Site/FAQ.vue -->
<script setup>
import PageLayout from '@/Components/PageLayout.vue';
import { ref, onMounted, watch, computed } from 'vue';
import axios from 'axios';

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
});

const faqs = ref([]);
const filteredFaqs = ref([]);
const categories = ref(['all']);
const selectedCategory = ref('all');
const searchQuery = ref('');
const openQuestion = ref(null);
const loading = ref(false);
const activeTab = ref('all');

// Category icons mapping
const categoryIcons = {
    'General Information': '📚',
    'Report Accidents': '🚨',
    'Incident': '⚠️',
    'Investigations': '🔍',
    'Reports & Publications': '📊',
    'default': '📄'
};

// Fetch from backend
const fetchFaqs = async () => {
    loading.value = true;
    try {
        const res = await axios.get('/faqs/all', {
            params: {
                category: selectedCategory.value,
                search: searchQuery.value
            }
        });
        faqs.value = res.data;

        // Extract unique categories
        const uniqueCategories = [...new Set(faqs.value.map(f => f.category))];
        categories.value = ['all', ...uniqueCategories];
        
        filteredFaqs.value = faqs.value;
    } catch (err) {
        console.error('Failed to fetch FAQs', err);
    } finally {
        loading.value = false;
    }
};

// Filter FAQs based on search and category
const filterFaqs = () => {
    let filtered = faqs.value;
    
    if (selectedCategory.value !== 'all') {
        filtered = filtered.filter(f => f.category === selectedCategory.value);
    }
    
    if (searchQuery.value.trim()) {
        const query = searchQuery.value.toLowerCase();
        filtered = filtered.map(category => ({
            ...category,
            questions: category.questions.filter(q => 
                q.q.toLowerCase().includes(query) ||
                q.a.toLowerCase().includes(query)
            )
        })).filter(category => category.questions.length > 0);
    }
    
    filteredFaqs.value = filtered;
};

// Reset filters
const resetFilters = () => {
    selectedCategory.value = 'all';
    searchQuery.value = '';
    activeTab.value = 'all';
    openQuestion.value = null;
    fetchFaqs();
};

// Get category icon
const getCategoryIcon = (category) => {
    return categoryIcons[category] || categoryIcons.default;
};

// Get category color
const getCategoryColor = (category) => {
    const categoryLower = category?.toLowerCase() || '';
    if (categoryLower.includes('general')) return 'from-blue-500 to-blue-600';
    if (categoryLower.includes('accident')) return 'from-red-500 to-red-600';
    if (categoryLower.includes('incident')) return 'from-orange-500 to-orange-600';
    if (categoryLower.includes('investigation')) return 'from-purple-500 to-purple-600';
    if (categoryLower.includes('report')) return 'from-green-500 to-green-600';
    return 'from-teal-500 to-emerald-500';
};

// Calculate statistics
const stats = computed(() => {
    const totalFaqs = faqs.value.reduce((acc, cat) => acc + cat.questions.length, 0);
    const categoriesCount = categories.value.length - 1;
    return { totalFaqs, categoriesCount };
});

watch([selectedCategory, searchQuery], filterFaqs);

const toggleQuestion = (index) => {
    openQuestion.value = openQuestion.value === index ? null : index;
};

onMounted(() => {
    fetchFaqs();
});
</script>

<template>
    <PageLayout 
        title="Frequently Asked Questions" 
        :canLogin="canLogin"
        :canRegister="canRegister"
        :showHero="true"
    >
        <!-- Custom Hero Section for FAQ -->
        <template #hero>
            <div class="text-center mt-12">
                <div class="mb-6">
                    <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-white/10 backdrop-blur-sm mb-4">
                        <svg class="w-10 h-10 text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <h1 class="text-4xl sm:text-5xl lg:text-6xl xl:text-7xl font-bold leading-tight mb-6">
                    <span class="bg-gradient-to-r from-teal-400 via-emerald-400 to-teal-300 bg-clip-text text-transparent">
                        Frequently Asked Questions
                    </span>
                </h1>
                <p class="text-lg sm:text-xl text-white/80 max-w-3xl mx-auto mb-8">
                    Find answers to common questions about our farm and other services.
                </p>
                
                <!-- Search Bar -->
                <div class="max-w-2xl mx-auto mb-6">
                    <div class="relative">
                        <input 
                            v-model="searchQuery"
                            type="text" 
                            placeholder="Search for answers..."
                            class="w-full px-6 py-4 bg-white/10 backdrop-blur-sm border border-teal-400/30 rounded-xl text-white placeholder-white/50 focus:outline-none focus:border-teal-400 focus:ring-2 focus:ring-teal-400/20 transition-all"
                        />
                        <svg class="absolute right-4 top-1/2 transform -translate-y-1/2 w-5 h-5 text-white/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </div>

                <!-- Stats Badges -->
                <div class="flex flex-wrap justify-center gap-4 mt-8">
                    <div class="bg-white/10 backdrop-blur-sm rounded-full px-4 py-2">
                        <span class="text-teal-300 font-bold">{{ stats.totalFaqs }}</span>
                        <span class="text-white/70 ml-2">Answers Available</span>
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm rounded-full px-4 py-2">
                        <span class="text-teal-300 font-bold">{{ stats.categoriesCount }}</span>
                        <span class="text-white/70 ml-2">Categories</span>
                    </div>
                </div>
            </div>
        </template>

        <!-- Main Content -->
        <div class="max-w-5xl mx-auto">
            <!-- Loading State -->
            <div v-if="loading" class="text-center py-12">
                <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-teal-600"></div>
                <p class="mt-4 text-gray-500">Loading FAQs...</p>
            </div>

            <div v-else class="space-y-8">
                <!-- Category Filter - Modern Tabs -->
                <div class="relative">
                    <div class="flex flex-wrap gap-2 pb-4 border-b border-gray-200">
                        <button 
                            v-for="cat in categories" 
                            :key="cat"
                            @click="selectedCategory = cat"
                            class="px-5 py-2.5 rounded-full transition-all duration-300 font-medium text-sm"
                            :class="[
                                selectedCategory === cat 
                                    ? 'bg-gradient-to-r from-teal-600 to-emerald-600 text-white shadow-lg transform scale-105' 
                                    : 'bg-gray-100 text-gray-700 hover:bg-gray-200 hover:scale-105'
                            ]"
                        >
                            <span class="mr-2">{{ cat !== 'all' ? getCategoryIcon(cat) : '📋' }}</span>
                            {{ cat === 'all' ? 'All Categories' : cat }}
                            <span v-if="cat !== 'all'" class="ml-1 text-xs opacity-75">
                                ({{ faqs.find(f => f.category === cat)?.questions.length || 0 }})
                            </span>
                        </button>
                    </div>
                    
                    <!-- Reset Filters Button -->
                    <button 
                        v-if="selectedCategory !== 'all' || searchQuery"
                        @click="resetFilters"
                        class="absolute right-0 top-0 text-sm text-teal-600 hover:text-teal-700 mt-2"
                    >
                        Clear Filters
                    </button>
                </div>

                <!-- FAQ Accordion -->
                <div v-if="filteredFaqs.length > 0" class="space-y-8">
                    <div v-for="(category, catIndex) in filteredFaqs" :key="catIndex" class="faq-category">
                        <!-- Category Header with Icon -->
                        <div class="flex items-center gap-3 mb-5">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-r from-teal-100 to-emerald-100 flex items-center justify-center">
                                <span class="text-2xl">{{ getCategoryIcon(category.category) }}</span>
                            </div>
                            <h2 class="text-2xl font-bold text-gray-900">{{ category.category }}</h2>
                            <span class="text-sm text-gray-500 bg-gray-100 px-2 py-1 rounded-full">
                                {{ category.questions.length }} questions
                            </span>
                        </div>
                        
                        <!-- Questions List -->
                        <div class="space-y-3">
                            <div 
                                v-for="(faq, qIndex) in category.questions" 
                                :key="qIndex" 
                                class="faq-item bg-white border border-gray-200 rounded-xl overflow-hidden hover:shadow-md transition-all duration-300"
                                :class="{ 'shadow-md': openQuestion === `${catIndex}-${qIndex}` }"
                            >
                                <button 
                                    @click="toggleQuestion(`${catIndex}-${qIndex}`)"
                                    class="w-full px-6 py-4 text-left flex justify-between items-center hover:bg-gray-50 transition-colors group"
                                >
                                    <div class="flex items-start gap-3">
                                        <div class="flex-shrink-0 mt-1">
                                            <div class="w-6 h-6 rounded-full bg-teal-100 flex items-center justify-center group-hover:bg-teal-200 transition-colors">
                                                <svg class="w-3 h-3 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                                </svg>
                                            </div>
                                        </div>
                                        <span class="font-semibold text-gray-900 text-left leading-relaxed">
                                            {{ faq.q }}
                                        </span>
                                    </div>
                                    <svg 
                                        class="w-5 h-5 text-teal-600 transition-transform duration-300 flex-shrink-0 ml-4" 
                                        :class="{ 'rotate-180': openQuestion === `${catIndex}-${qIndex}` }"
                                        fill="none" 
                                        stroke="currentColor" 
                                        viewBox="0 0 24 24"
                                    >
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                                
                                <transition
                                    enter-active-class="transition-all duration-300 ease-out"
                                    enter-from-class="opacity-0 max-h-0"
                                    enter-to-class="opacity-100 max-h-96"
                                    leave-active-class="transition-all duration-200 ease-in"
                                    leave-from-class="opacity-100 max-h-96"
                                    leave-to-class="opacity-0 max-h-0"
                                >
                                    <div 
                                        v-show="openQuestion === `${catIndex}-${qIndex}`"
                                        class="px-6 pb-5 pt-2 text-gray-600 leading-relaxed border-t border-gray-100 bg-gradient-to-br from-gray-50 to-white"
                                    >
                                        <div class="prose prose-sm max-w-none">
                                            <div v-html="faq.a" class="answer-content"></div>
                                        </div>
                                    </div>
                                </transition>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Empty State -->
                <div v-else class="text-center py-16 bg-gray-50 rounded-2xl">
                    <div class="text-6xl mb-4">🔍</div>
                    <p class="text-gray-500 text-lg mb-2">No results found</p>
                    <p class="text-gray-400 text-sm">Try adjusting your search or category filter</p>
                    <button 
                        @click="resetFilters"
                        class="mt-4 px-6 py-2 bg-gradient-to-r from-teal-600 to-emerald-600 text-white rounded-lg hover:shadow-lg transition-all"
                    >
                        Clear Filters
                    </button>
                </div>
            </div>

            <!-- Contact Support Section -->
            <div class="mt-12 pt-8 border-t border-gray-200 text-center bg-gradient-to-r from-gray-50 to-white rounded-2xl p-8">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-teal-100 mb-4">
                    <svg class="w-8 h-8 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Still have questions?</h3>
                <p class="text-gray-600 mb-4">Our team is here to help you with any additional inquiries</p>
                <a 
                    href="/contact-us" 
                    class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-teal-600 to-emerald-600 text-white rounded-lg font-semibold hover:shadow-lg transition-all duration-300 transform hover:-translate-y-0.5"
                >
                    Contact Our Support Team
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                    </svg>
                </a>
            </div>
        </div>
    </PageLayout>
</template>

<style scoped>
/* Custom Animations */
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

.faq-category {
    animation: fadeInUp 0.5s ease-out;
}

.faq-item {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Answer Content Styling */
.answer-content {
    font-size: 0.95rem;
    line-height: 1.6;
    color: #4b5563;
}

.answer-content :deep(p) {
    margin-bottom: 0.75rem;
}

.answer-content :deep(ul),
.answer-content :deep(ol) {
    padding-left: 1.5rem;
    margin-bottom: 0.75rem;
}

.answer-content :deep(li) {
    margin-bottom: 0.25rem;
}

.answer-content :deep(strong) {
    font-weight: 600;
    color: #1f2937;
}

.answer-content :deep(a) {
    color: #14b8a6;
    text-decoration: none;
}

.answer-content :deep(a:hover) {
    text-decoration: underline;
}

/* Category Filter Buttons */
button {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Responsive Adjustments */
@media (max-width: 768px) {
    .faq-item button {
        padding: 1rem;
    }
    
    .faq-item .font-semibold {
        font-size: 0.95rem;
    }
    
    .answer-content {
        font-size: 0.875rem;
    }
    
    .category-filter {
        gap: 0.5rem;
    }
    
    .category-filter button {
        padding: 0.5rem 1rem;
        font-size: 0.875rem;
    }
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
</style>




<!-- Resources/js/Pages/Site/FAQ.vue -->
<!-- <script setup>
import PageLayout from '@/Components/PageLayout.vue';
import { ref, onMounted, watch } from 'vue';
import axios from 'axios';

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
});

const faqs = ref([]);
const filteredFaqs = ref([]);
const categories = ref(['all']);
const selectedCategory = ref('all');
const searchQuery = ref('');
const openQuestion = ref(null);


const fetchFaqs = async () => {
    try {
        const res = await axios.get('/faqs/all', {
            params: {
                category: selectedCategory.value,
                search: searchQuery.value
            }
        });
        faqs.value = res.data;


        categories.value = ['all', ...faqs.value.map(f => f.category)];

        filteredFaqs.value = faqs.value;
    } catch (err) {
        console.error('Failed to fetch FAQs', err);
    }
};

const filterFaqs = () => {
    let filtered = faqs.value;

    if (selectedCategory.value !== 'all') {
        filtered = filtered.filter(f => f.category === selectedCategory.value);
    }

    if (searchQuery.value.trim()) {
        filtered = filtered.map(category => ({
            ...category,
            questions: category.questions.filter(q =>
                q.q.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
                q.a.toLowerCase().includes(searchQuery.value.toLowerCase())
            )
        })).filter(category => category.questions.length > 0);
    }

    filteredFaqs.value = filtered;
};

watch([selectedCategory, searchQuery], filterFaqs);

const toggleQuestion = (index) => {
    openQuestion.value = openQuestion.value === index ? null : index;
};

onMounted(fetchFaqs);

</script>

<template>
    <PageLayout title="Frequently Asked Questions" :canLogin="canLogin" :canRegister="canRegister" :showHero="true">
        <template #hero>
            <div class="text-center mt-12">
                <h1 class="text-4xl sm:text-5xl lg:text-6xl xl:text-7xl font-bold leading-tight mb-6">
                    <span
                        class="bg-gradient-to-r from-teal-400 via-emerald-400 to-teal-300 bg-clip-text text-transparent">
                        Frequently Asked Questions
                    </span>
                </h1>
                <p class="text-lg sm:text-xl text-white/80 max-w-3xl mx-auto mb-6">
                    Find answers to common questions about our work, investigations, and services
                </p>
                <div class="max-w-2xl mx-auto">
                    <div class="relative">
                        <input v-model="searchQuery" @input="filterFaqs" type="text" placeholder="Search for answers..."
                            class="w-full px-6 py-4 bg-white/10 backdrop-blur-sm border border-teal-400/30 rounded-xl text-white placeholder-white/50 focus:outline-none focus:border-teal-400" />
                        <svg class="absolute right-4 top-1/2 transform -translate-y-1/2 w-5 h-5 text-white/50"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </template>

        <div class="space-y-8">
            <div class="flex flex-wrap gap-3 pb-4 border-b border-gray-200">
                <button v-for="cat in categories" :key="cat" @click="selectedCategory = cat; filterFaqs()"
                    :class="[selectedCategory === cat ? 'bg-teal-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200']">
                    {{ cat === 'all' ? 'All Categories' : cat }}
                </button>

            </div>

            <div class="space-y-8">
                <div v-for="(category, catIndex) in filteredFaqs" :key="catIndex">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">{{ category.category }}</h2>
                    <div class="space-y-3">
                        <div v-for="(faq, qIndex) in category.questions" :key="qIndex"
                            class="border border-gray-200 rounded-lg overflow-hidden">
                            <button @click="toggleQuestion(`${catIndex}-${qIndex}`)"
                                class="w-full px-6 py-4 text-left flex justify-between items-center hover:bg-gray-50 transition-colors">
                                <span class="font-semibold text-gray-900">{{ faq.q }}</span>
                                <svg class="w-5 h-5 text-teal-600 transition-transform duration-300"
                                    :class="{ 'rotate-180': openQuestion === `${catIndex}-${qIndex}` }" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7">
                                    </path>
                                </svg>
                            </button>
                            <div v-show="openQuestion === `${catIndex}-${qIndex}`"
                                class="px-6 pb-4 text-gray-600 leading-relaxed border-t border-gray-100">
                                {{ faq.a }}
                            </div>
                        </div>
                    </div>
                </div>

                <div v-if="filteredFaqs.length === 0" class="text-center py-12">
                    <p class="text-gray-500">No results found. Please try a different search term.</p>
                </div>
            </div>

            <div class="mt-8 pt-8 border-t border-gray-200 text-center">
                <p class="text-gray-600">Still have questions?</p>
                <a href="/contact-us"
                    class="inline-block mt-2 px-6 py-3 bg-gradient-to-r from-teal-600 to-emerald-600 text-white rounded-lg font-semibold hover:shadow-lg transition-all duration-300">
                    Contact Our Support Team
                </a>
            </div>
        </div>
    </PageLayout>
</template> -->