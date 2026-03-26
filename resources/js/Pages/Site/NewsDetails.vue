<script setup>
import PageLayout from '@/Components/PageLayout.vue';
import { router } from '@inertiajs/vue3';
import { onMounted } from 'vue';

const props = defineProps({
    news: Object
});

const imageUrl = (img) => {
    if (!img) return '/assets/placeholder-news.jpg';
    if (img.startsWith('http')) return img;
    return `/storage/${img}`;
};

// Smooth scroll to top on mount
onMounted(() => {
    window.scrollTo({ top: 0, behavior: 'smooth' });
});

// Format date
const formatDate = (date) => {
    if (!date) return 'N/A';
    return new Date(date).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
};

// Share functionality
const shareArticle = () => {
    if (navigator.share) {
        navigator.share({
            title: props.news.title,
            text: props.news.summary,
            url: window.location.href
        });
    } else {
        navigator.clipboard.writeText(window.location.href);
        alert('Link copied to clipboard!');
    }
};
</script>

<template>
    <PageLayout title="News Details" :showHero="false">
        <div class="bg-gradient-to-b from-gray-50 to-white min-h-screen">
            <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-16">
                <!-- Back Button -->
                <div class="mb-8">
                    <button 
                        @click="router.visit('/all-news')" 
                        class="inline-flex items-center gap-2 text-gray-600 hover:text-teal-600 transition-colors duration-300 group"
                    >
                        <svg class="w-5 h-5 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        <span>Back to News</span>
                    </button>
                </div>

                <!-- Hero Section with Image -->
                <div class="relative rounded-2xl overflow-hidden shadow-xl mb-8">
                    <div class="relative h-96 md:h-[500px]">
                        <img 
                            :src="imageUrl(news.image)" 
                            :alt="news.title"
                            class="w-full h-full object-cover"
                        />
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
                    </div>
                    
                    <!-- Category Badge Overlay -->
                    <div class="absolute top-6 left-6">
                        <span class="px-4 py-2 bg-gradient-to-r from-teal-600 to-emerald-600 text-white rounded-full text-sm font-semibold shadow-lg">
                            {{ news.category }}
                        </span>
                    </div>
                </div>

                <!-- Content Container -->
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                    <!-- Article Header -->
                    <div class="p-8 md:p-10 border-b border-gray-100">
                        <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold text-gray-900 mb-4 leading-tight">
                            {{ news.title }}
                        </h1>
                        
                        <!-- Meta Information -->
                        <div class="flex flex-wrap items-center justify-between gap-4">
                            <div class="flex flex-wrap items-center gap-4 text-sm text-gray-500">
                                <div class="flex items-center gap-2">
                                    <div class="w-8 h-8 rounded-full bg-gradient-to-r from-teal-100 to-emerald-100 flex items-center justify-center">
                                        <svg class="w-4 h-4 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                    </div>
                                    <span>By {{ news.author }}</span>
                                </div>
                                
                                <div class="w-1 h-1 bg-gray-300 rounded-full"></div>
                                
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <span>{{ formatDate(news.date) }}</span>
                                </div>
                                
                                <div class="w-1 h-1 bg-gray-300 rounded-full"></div>
                                
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span>📖 {{ news.read_time }} read</span>
                                </div>
                            </div>
                            
                            <!-- Share Button -->
                            <button 
                                @click="shareArticle"
                                class="flex items-center gap-2 px-4 py-2 bg-gray-100 hover:bg-teal-50 rounded-lg transition-colors duration-200 group"
                            >
                                <svg class="w-4 h-4 text-gray-600 group-hover:text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"></path>
                                </svg>
                                <span class="text-sm text-gray-600 group-hover:text-teal-600">Share</span>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Article Content -->
                    <div class="p-8 md:p-10">
                        <!-- Summary Section -->
                        <div v-if="news.summary" class="mb-8 p-6 bg-gradient-to-r from-teal-50 to-emerald-50 rounded-xl border-l-4 border-teal-500">
                            <p class="text-gray-700 italic leading-relaxed">
                                {{ news.summary }}
                            </p>
                        </div>
                        
                        <!-- Full Content -->
                        <div class="article-content prose prose-lg max-w-none">
                            <div v-html="news.content" class="text-gray-700 leading-relaxed"></div>
                        </div>
                        
                        <!-- Tags Section (Optional) -->
                        <div v-if="news.tags" class="mt-8 pt-6 border-t border-gray-100">
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="text-sm font-medium text-gray-500">Tags:</span>
                                <span v-for="tag in news.tags.split(',')" :key="tag" 
                                    class="px-3 py-1 bg-gray-100 text-gray-600 rounded-full text-xs">
                                    {{ tag.trim() }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </PageLayout>
</template>

<style scoped>
/* Article Content Styling */
.article-content {
    font-size: 1.05rem;
    line-height: 1.8;
}

.article-content :deep(p) {
    margin-bottom: 1.25rem;
    color: #4b5563;
}

.article-content :deep(h1),
.article-content :deep(h2),
.article-content :deep(h3) {
    margin-top: 1.5rem;
    margin-bottom: 1rem;
    font-weight: 700;
    color: #1f2937;
}

.article-content :deep(h1) {
    font-size: 1.875rem;
}

.article-content :deep(h2) {
    font-size: 1.5rem;
}

.article-content :deep(h3) {
    font-size: 1.25rem;
}

.article-content :deep(ul),
.article-content :deep(ol) {
    margin-bottom: 1.25rem;
    padding-left: 1.5rem;
}

.article-content :deep(li) {
    margin-bottom: 0.5rem;
    color: #4b5563;
}

.article-content :deep(strong) {
    font-weight: 700;
    color: #1f2937;
}

.article-content :deep(em) {
    font-style: italic;
    color: #6b7280;
}

.article-content :deep(a) {
    color: #14b8a6;
    text-decoration: none;
    border-bottom: 1px solid transparent;
    transition: all 0.3s ease;
}

.article-content :deep(a:hover) {
    color: #0d9488;
    border-bottom-color: #14b8a6;
}

.article-content :deep(blockquote) {
    border-left: 4px solid #14b8a6;
    padding: 1rem 0 1rem 1.5rem;
    margin: 1.5rem 0;
    background: #f0fdfa;
    border-radius: 0.5rem;
    font-style: italic;
    color: #374151;
}

.article-content :deep(img) {
    max-width: 100%;
    height: auto;
    border-radius: 0.75rem;
    margin: 1.5rem 0;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
}

.article-content :deep(code) {
    background-color: #f1f5f9;
    padding: 0.2rem 0.4rem;
    border-radius: 0.375rem;
    font-family: monospace;
    font-size: 0.875rem;
    color: #d946ef;
}

.article-content :deep(pre) {
    background-color: #1e293b;
    color: #e2e8f0;
    padding: 1rem;
    border-radius: 0.75rem;
    overflow-x: auto;
    margin: 1.5rem 0;
    font-size: 0.875rem;
}

.article-content :deep(pre code) {
    background-color: transparent;
    color: inherit;
    padding: 0;
}

.article-content :deep(table) {
    width: 100%;
    border-collapse: collapse;
    margin: 1.5rem 0;
}

.article-content :deep(th),
.article-content :deep(td) {
    border: 1px solid #e2e8f0;
    padding: 0.75rem;
    text-align: left;
}

.article-content :deep(th) {
    background-color: #f8fafc;
    font-weight: 600;
}

/* Animation for content */
.article-content {
    animation: fadeInUp 0.6s ease-out;
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

/* Responsive Adjustments */
@media (max-width: 768px) {
    .article-content {
        font-size: 0.95rem;
        line-height: 1.7;
    }
    
    .article-content :deep(h1) {
        font-size: 1.5rem;
    }
    
    .article-content :deep(h2) {
        font-size: 1.25rem;
    }
    
    .article-content :deep(h3) {
        font-size: 1.125rem;
    }
    
    .article-content :deep(blockquote) {
        padding: 0.75rem 0 0.75rem 1rem;
        margin: 1rem 0;
    }
}

/* Print Styles */
@media print {
    .back-button,
    .share-button,
    .related-articles {
        display: none;
    }
    
    .article-content {
        font-size: 12pt;
        line-height: 1.5;
    }
    
    .hero-image {
        break-inside: avoid;
    }
}
</style>


<!-- <script setup>
import PageLayout from '@/Components/PageLayout.vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({
    news: Object
});

const imageUrl = (img) => {
    if (!img) return '/assets/placeholder-news.jpg';

    if (img.startsWith('http')) return img;

    return `/storage/${img}`;
};

</script>

<template>
    <PageLayout title="News Details">
        <div class="max-w-4xl mx-auto py-10">

            <button @click="router.visit('/all-news')" class="text-teal-600 hover:underline">
                ← Back to News
            </button>
            <br/>
            <img :src="imageUrl(news.image)" class="w-full h-96 object-cover rounded-xl" />

            <div class="flex items-center gap-3 text-sm text-gray-500 mb-4">
                <span class="px-3 py-1 bg-teal-100 text-teal-700 rounded-full">
                    {{ news.category }}
                </span>
                <span>{{ news.date }}</span>
                <span>📖 {{ news.read_time }}</span>
            </div>

            <h1 class="text-3xl font-bold mb-4">
                {{ news.title }}
            </h1>

            <p class="text-gray-500 mb-6">
                By {{ news.author }}
            </p>

            <div class="text-gray-700 leading-relaxed whitespace-pre-line">
                {{ news.content }}
            </div>

        </div>
    </PageLayout>
</template> -->