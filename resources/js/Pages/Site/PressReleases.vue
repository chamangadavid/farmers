<!-- Resources/js/Pages/Site/PressReleases.vue -->
<script setup>
import PageLayout from '@/Components/PageLayout.vue';
import { ref } from 'vue';

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
});

const releases = ref([
    {
        id: 1,
        title: 'AAIB Appoints New Board Members',
        date: 'March 15, 2024',
        summary: 'Three new members appointed to strengthen the Board\'s investigation capabilities.',
        pdfLink: '#'
    },
    {
        id: 2,
        title: 'Completion of Major Investigation',
        date: 'February 20, 2024',
        summary: 'Final report published on the 2023 runway excursion investigation.',
        pdfLink: '#'
    },
    {
        id: 3,
        title: 'Safety Awareness Campaign Launch',
        date: 'January 25, 2024',
        summary: 'New campaign to promote safety reporting culture in aviation industry.',
        pdfLink: '#'
    }
]);
</script>

<template>
    <PageLayout 
        title="Press Releases" 
        :canLogin="canLogin"
        :canRegister="canRegister"
        :showHero="true"
    >
        <!-- Custom Hero Section for Press Releases -->
        <template #hero>
            <div class="text-center mt-12">
                <h1 class="text-4xl sm:text-5xl lg:text-6xl xl:text-7xl font-bold leading-tight mb-6">
                    <span class="bg-gradient-to-r from-teal-400 via-emerald-400 to-teal-300 bg-clip-text text-transparent">
                        Press Releases
                    </span>
                </h1>
                <p class="text-lg sm:text-xl text-white/80 max-w-3xl mx-auto">
                    Official statements and announcements from AAIB
                </p>
            </div>
        </template>

        <!-- Main Content -->
        <div class="space-y-6">
            <div v-for="release in releases" :key="release.id" class="border border-gray-200 rounded-xl p-6 hover:shadow-lg transition-all">
                <div class="flex flex-wrap justify-between items-start gap-4 mb-3">
                    <h3 class="text-xl font-bold text-gray-900">{{ release.title }}</h3>
                    <span class="text-gray-500 text-sm">{{ release.date }}</span>
                </div>
                <p class="text-gray-600 mb-4">{{ release.summary }}</p>
                <a :href="release.pdfLink" class="inline-flex items-center gap-2 text-teal-600 hover:text-teal-700 font-medium">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Download Press Release (PDF)
                </a>
            </div>
        </div>
    </PageLayout>
</template>