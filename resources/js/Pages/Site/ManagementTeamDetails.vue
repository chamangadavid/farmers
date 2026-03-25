<!-- resources/js/Pages/Site/ManagementTeamDetails.vue -->
<script setup>
import PageLayout from '@/Components/PageLayout.vue';
import { onMounted } from 'vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({
    member: Object
});

onMounted(() => {
    // Smooth scroll to top when page loads
    window.scrollTo({ top: 0, behavior: 'smooth' });
});



const goBack = () => {
    router.visit('/management-team');
};

</script>

<template>
    <PageLayout :title="member.fullname" :showHero="false">
        <div class="bg-gradient-to-b from-gray-50 to-white min-h-screen">
            <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-16">
                <!-- Back Button -->
                <div class="mb-8">
                    <button @click="goBack"
                        class="inline-flex items-center gap-2 text-gray-600 hover:text-teal-600 transition-colors duration-300 group">
                        <svg class="w-5 h-5 transform group-hover:-translate-x-1 transition-transform" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        <span>Back to Team</span>
                    </button>
                </div>

                <!-- Profile Card -->
                <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
                    <!-- Header Section with Gradient Background -->
                    <div class="relative bg-gradient-to-r from-teal-600 to-emerald-600 px-6 py-12 md:px-12">
                        <div class="absolute inset-0 bg-black opacity-10"></div>
                        <div class="relative z-10">
                            <!-- Profile Image -->
                            <div class="flex justify-center">
                                <div class="relative">
                                    <div class="absolute inset-0 bg-white rounded-full opacity-20 scale-110"></div>
                                    <img v-if="member.image" :src="`/storage/${member.image}`" :alt="member.fullname"
                                        class="w-32 h-32 md:w-40 md:h-40 rounded-full object-cover border-4 border-white shadow-xl" />
                                    <div v-else
                                        class="w-32 h-32 md:w-40 md:h-40 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center border-4 border-white shadow-xl">
                                        <svg class="w-16 h-16 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Content Section -->
                    <div class="px-6 py-8 md:px-12 md:py-10">
                        <!-- Name and Title -->
                        <div class="text-center mb-8">
                            <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-3">
                                {{ member.fullname }}
                            </h1>
                            <div class="inline-flex items-center gap-2 bg-teal-50 text-teal-700 px-4 py-2 rounded-full">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                    </path>
                                </svg>
                                <span class="font-semibold">{{ member.position }}</span>
                            </div>
                        </div>

                        <!-- Divider with Icon -->
                        <div class="relative my-8">
                            <div class="absolute inset-0 flex items-center">
                                <div class="w-full border-t border-gray-200"></div>
                            </div>
                            <div class="relative flex justify-center">
                                <span class="bg-white px-4 text-gray-400">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                        </path>
                                    </svg>
                                </span>
                            </div>
                        </div>

                        <!-- Biography Content -->
                        <div class="prose prose-lg max-w-none">
                            <div class="bio-content text-gray-700 leading-relaxed" v-html="member.content"></div>
                        </div>

                        <!-- Additional Info Section (Optional) -->
                        <div class="mt-12 pt-8 border-t border-gray-100">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div class="text-center">
                                    <div class="text-teal-600 font-semibold mb-1">Joined</div>
                                    <div class="text-gray-600 text-sm">
                                        {{ new Date(member.created_at).toLocaleDateString('en-US', {
                                            year: 'numeric',
                                        month: 'long' }) }}
                                    </div>
                                </div>
                                <div class="text-center">
                                    <div class="text-teal-600 font-semibold mb-1">Role</div>
                                    <div class="text-gray-600 text-sm">{{ member.position }}</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-teal-600 font-semibold mb-1">Status</div>
                                    <div class="text-green-600 text-sm flex items-center justify-center gap-1">
                                        <span class="inline-block w-2 h-2 bg-green-500 rounded-full"></span>
                                        Active
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Share Section (Optional) -->
                <div class="mt-8 text-center">
                    <p class="text-sm text-gray-500 mb-3">Share this profile</p>
                    <div class="flex justify-center gap-3">
                        <button
                            class="p-2 bg-gray-100 hover:bg-teal-100 rounded-full transition-colors duration-300 group">
                            <svg class="w-5 h-5 text-gray-600 group-hover:text-teal-600" fill="currentColor"
                                viewBox="0 0 24 24">
                                <path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"></path>
                            </svg>
                        </button>
                        <button
                            class="p-2 bg-gray-100 hover:bg-teal-100 rounded-full transition-colors duration-300 group">
                            <svg class="w-5 h-5 text-gray-600 group-hover:text-teal-600" fill="currentColor"
                                viewBox="0 0 24 24">
                                <path
                                    d="M23 3a10.9 10.9 0 01-3.14 1.53 4.48 4.48 0 00-7.86 3v1A10.66 10.66 0 013 4s-4 9 5 13a11.64 11.64 0 01-7 2c9 5 20 0 20-11.5a4.5 4.5 0 00-.08-.83A7.72 7.72 0 0023 3z">
                                </path>
                            </svg>
                        </button>
                        <button
                            class="p-2 bg-gray-100 hover:bg-teal-100 rounded-full transition-colors duration-300 group">
                            <svg class="w-5 h-5 text-gray-600 group-hover:text-teal-600" fill="currentColor"
                                viewBox="0 0 24 24">
                                <path
                                    d="M16 8a6 6 0 016 6v7h-4v-7a2 2 0 00-2-2 2 2 0 00-2 2v7h-4v-7a6 6 0 016-6zM2 9h4v12H2z">
                                </path>
                                <circle cx="4" cy="4" r="2"></circle>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </PageLayout>
</template>

<style scoped>
/* Professional styling for the content */
.bio-content {
    line-height: 1.8;
    font-size: 1.05rem;
}

/* Rich text styling */
.bio-content :deep(p) {
    margin-bottom: 1.25rem;
    color: #4b5563;
}

.bio-content :deep(h1),
.bio-content :deep(h2),
.bio-content :deep(h3) {
    margin-top: 1.5rem;
    margin-bottom: 1rem;
    font-weight: 700;
    color: #1f2937;
}

.bio-content :deep(h1) {
    font-size: 1.875rem;
}

.bio-content :deep(h2) {
    font-size: 1.5rem;
}

.bio-content :deep(h3) {
    font-size: 1.25rem;
}

.bio-content :deep(ul),
.bio-content :deep(ol) {
    margin-bottom: 1.25rem;
    padding-left: 1.5rem;
}

.bio-content :deep(li) {
    margin-bottom: 0.5rem;
    color: #4b5563;
}

.bio-content :deep(strong) {
    font-weight: 700;
    color: #1f2937;
}

.bio-content :deep(em) {
    font-style: italic;
    color: #6b7280;
}

.bio-content :deep(a) {
    color: #14b8a6;
    text-decoration: none;
    border-bottom: 1px solid transparent;
    transition: all 0.3s ease;
}

.bio-content :deep(a:hover) {
    color: #0d9488;
    border-bottom-color: #14b8a6;
}

.bio-content :deep(blockquote) {
    border-left: 4px solid #14b8a6;
    padding: 1rem 0 1rem 1.5rem;
    margin: 1.5rem 0;
    background: #f0fdfa;
    border-radius: 0.5rem;
    font-style: italic;
    color: #374151;
}

.bio-content :deep(img) {
    max-width: 100%;
    height: auto;
    border-radius: 0.75rem;
    margin: 1.5rem 0;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
}

.bio-content :deep(code) {
    background-color: #f1f5f9;
    padding: 0.2rem 0.4rem;
    border-radius: 0.375rem;
    font-family: monospace;
    font-size: 0.875rem;
    color: #d946ef;
}

.bio-content :deep(pre) {
    background-color: #1e293b;
    color: #e2e8f0;
    padding: 1rem;
    border-radius: 0.75rem;
    overflow-x: auto;
    margin: 1.5rem 0;
    font-size: 0.875rem;
}

.bio-content :deep(pre code) {
    background-color: transparent;
    color: inherit;
    padding: 0;
}

.bio-content :deep(table) {
    width: 100%;
    border-collapse: collapse;
    margin: 1.5rem 0;
}

.bio-content :deep(th),
.bio-content :deep(td) {
    border: 1px solid #e2e8f0;
    padding: 0.75rem;
    text-align: left;
}

.bio-content :deep(th) {
    background-color: #f8fafc;
    font-weight: 600;
}

/* Animation for content */
.bio-content {
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

/* Responsive adjustments */
@media (max-width: 768px) {
    .bio-content {
        font-size: 0.95rem;
        line-height: 1.7;
    }

    .bio-content :deep(h1) {
        font-size: 1.5rem;
    }

    .bio-content :deep(h2) {
        font-size: 1.25rem;
    }

    .bio-content :deep(h3) {
        font-size: 1.125rem;
    }

    .bio-content :deep(blockquote) {
        padding: 0.75rem 0 0.75rem 1rem;
        margin: 1rem 0;
    }
}

/* Print styles */
@media print {

    .back-button,
    .share-section {
        display: none;
    }

    .profile-card {
        box-shadow: none;
        border: 1px solid #e2e8f0;
    }
}
</style>