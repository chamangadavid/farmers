<!-- Resources/js/Pages/Site/NationalRegulation.vue -->
<script setup>
import PageLayout from '@/Components/PageLayout.vue';

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
});

const regulations = [
    {
        title: 'Civil Aviation Act',
        year: '2000',
        description: 'Establishes the legal framework for aviation safety and accident investigation.',
        sections: ['Accident investigation authority', 'Reporting requirements', 'Investigation powers']
    },
    {
        title: 'Aviation Safety Regulations',
        year: '2005',
        description: 'Detailed regulations governing aviation safety standards and practices.',
        sections: ['Safety management systems', 'Occurrence reporting', 'Investigation procedures']
    },
    {
        title: 'Investigation Procedures Manual',
        year: '2010',
        description: 'Standard operating procedures for accident and incident investigations.',
        sections: ['Evidence handling', 'Witness interviews', 'Report preparation']
    }
];
</script>

<template>
    <PageLayout 
        title="National Regulation" 
        :canLogin="canLogin"
        :canRegister="canRegister"
        :showHero="true"
    >
        <!-- Custom Hero Section for National Regulation -->
        <template #hero>
            <div class="text-center mt-12">
                <h1 class="text-4xl sm:text-5xl lg:text-6xl xl:text-7xl font-bold leading-tight mb-6">
                    <span class="bg-gradient-to-r from-teal-400 via-emerald-400 to-teal-300 bg-clip-text text-transparent">
                        National Regulation
                    </span>
                </h1>
                <p class="text-lg sm:text-xl text-white/80 max-w-3xl mx-auto">
                    Legal framework for aviation safety investigation
                </p>
            </div>
        </template>

        <!-- Main Content -->
        <div class="space-y-8">
            <div class="space-y-6">
                <div v-for="reg in regulations" :key="reg.title" class="border border-gray-200 rounded-xl p-6">
                    <div class="flex flex-wrap justify-between items-start mb-4">
                        <h2 class="text-2xl font-bold text-gray-900">{{ reg.title }}</h2>
                        <span class="px-3 py-1 bg-teal-100 text-teal-700 rounded-full text-sm">{{ reg.year }}</span>
                    </div>
                    <p class="text-gray-600 mb-4">{{ reg.description }}</p>
                    <div class="flex flex-wrap gap-2">
                        <span v-for="section in reg.sections" :key="section" class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-sm">
                            {{ section }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Download Section -->
            <div class="bg-gray-50 rounded-xl p-6 text-center">
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Regulatory Documents</h3>
                <p class="text-gray-600 mb-4">Download the complete regulatory framework</p>
                <button class="px-6 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition-colors">
                    Download Regulations (PDF)
                </button>
            </div>
        </div>
    </PageLayout>
</template>