<!-- Resources/js/Pages/Site/ICAOAnnex.vue -->
<script setup>
import PageLayout from '@/Components/PageLayout.vue';

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
});

const sections = [
    {
        title: 'What is ICAO Annex 13?',
        content: 'ICAO Annex 13 to the Convention on International Civil Aviation provides international standards and recommended practices for aircraft accident and incident investigation. It establishes the framework for how investigations should be conducted and reported.'
    },
    {
        title: 'Key Principles',
        content: 'The primary objective is the prevention of accidents and incidents, not to apportion blame or liability. Investigations are conducted independently and impartially, with cooperation between states and organizations.',
        points: [
            'Safety first approach',
            'Independent investigation',
            'International cooperation',
            'Timely reporting',
            'Confidentiality of sensitive information'
        ]
    },
    {
        title: 'Investigation Process',
        content: 'Annex 13 outlines the systematic approach to investigation including notification, on-site investigation, analysis, findings, causes, and safety recommendations.',
        points: [
            'Notification of occurrence',
            'On-site investigation',
            'Evidence collection and preservation',
            'Analysis and findings',
            'Safety recommendations'
        ]
    },
    {
        title: 'Reporting Requirements',
        content: 'States are required to submit preliminary reports within 30 days and final reports within 12 months of an occurrence. Reports must be made publicly available.',
        points: [
            'Preliminary report (30 days)',
            'Final report (12 months)',
            'Public accessibility',
            'Safety recommendations tracking'
        ]
    }
];

const benefits = [
    'Harmonized investigation standards globally',
    'Enhanced aviation safety worldwide',
    'Improved international cooperation',
    'Consistent reporting methodologies',
    'Better accident prevention strategies'
];
</script>

<template>
    <PageLayout 
        title="ICAO Annex 13" 
        :canLogin="canLogin"
        :canRegister="canRegister"
        :showHero="true"
    >
        <!-- Custom Hero Section for ICAO Annex 13 -->
        <template #hero>
            <div class="text-center mt-12">
                <h1 class="text-4xl sm:text-5xl lg:text-6xl xl:text-7xl font-bold leading-tight mb-6">
                    <span class="bg-gradient-to-r from-teal-400 via-emerald-400 to-teal-300 bg-clip-text text-transparent">
                        ICAO Annex 13
                    </span>
                </h1>
                <p class="text-lg sm:text-xl text-white/80 max-w-3xl mx-auto">
                    International Standards for Aircraft Accident Investigation
                </p>
            </div>
        </template>

        <!-- Main Content -->
        <div class="space-y-8">
            <!-- Introduction -->
            <div class="bg-teal-50 rounded-xl p-6 border border-teal-200">
                <p class="text-gray-700 leading-relaxed">
                    <strong class="text-teal-700">ICAO Annex 13</strong> establishes the international framework for 
                    aircraft accident and incident investigation, ensuring consistent, independent, and safety-focused 
                    investigations worldwide.
                </p>
            </div>

            <!-- Sections -->
            <div v-for="section in sections" :key="section.title" class="space-y-4">
                <h2 class="text-2xl font-bold text-gray-900">{{ section.title }}</h2>
                <p class="text-gray-600 leading-relaxed">{{ section.content }}</p>
                <ul v-if="section.points" class="list-disc list-inside space-y-2 ml-4">
                    <li v-for="point in section.points" :key="point" class="text-gray-600">{{ point }}</li>
                </ul>
            </div>

            <!-- Benefits Section -->
            <section class="bg-gray-50 rounded-xl p-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Benefits of ICAO Annex 13 Compliance</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div v-for="benefit in benefits" :key="benefit" class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span class="text-gray-700">{{ benefit }}</span>
                    </div>
                </div>
            </section>

            <!-- Resources -->
            <div class="bg-gradient-to-r from-teal-50 to-emerald-50 rounded-xl p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Additional Resources</h3>
                <p class="text-gray-600 mb-3">For more detailed information about ICAO Annex 13:</p>
                <a href="#" class="inline-flex items-center gap-2 text-teal-600 hover:text-teal-700 font-medium">
                    Download the full document
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </a>
            </div>
        </div>
    </PageLayout>
</template>