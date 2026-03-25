<!-- Resources/js/Pages/Site/InvestigationProcess.vue -->
<script setup>
import PageLayout from '@/Components/PageLayout.vue';
import RecentInvestigations from '@/Components/RecentInvestigations.vue';


defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
});

const steps = [
    {
        number: '1',
        title: 'Notification & Response',
        description: 'Immediate notification of occurrence, mobilization of investigation team, and initial response to secure the site.',
        duration: 'Within 24 hours',
        icon: '🚨'
    },
    {
        number: '2',
        title: 'On-Site Investigation',
        description: 'Evidence collection, witness interviews, aircraft examination, and data recovery from flight recorders.',
        duration: '1-4 weeks',
        icon: '🔍'
    },
    {
        number: '3',
        title: 'Analysis & Testing',
        description: 'Laboratory analysis of evidence, flight data analysis, simulation, and technical evaluation.',
        duration: '2-6 months',
        icon: '🔬'
    },
    {
        number: '4',
        title: 'Draft Report',
        description: 'Preparation of preliminary findings, cause analysis, and draft recommendations.',
        duration: '2-3 months',
        icon: '📝'
    },
    {
        number: '5',
        title: 'Review & Consultation',
        description: 'Review by involved parties, consultation with stakeholders, and legal review.',
        duration: '1-2 months',
        icon: '👥'
    },
    {
        number: '6',
        title: 'Final Report',
        description: 'Publication of final report with findings, causes, and safety recommendations.',
        duration: 'Within 12 months',
        icon: '📄'
    }
];
</script>

<template>
    <PageLayout 
        title="Our Investigations" 
        :canLogin="canLogin"
        :canRegister="canRegister"
        :showHero="true"
    >
        <!-- Custom Hero Section for Investigation Process -->
        <template #hero>
            <div class="text-center mt-12">
                <h1 class="text-4xl sm:text-5xl lg:text-6xl xl:text-7xl font-bold leading-tight mb-6">
                    <span class="bg-gradient-to-r from-teal-400 via-emerald-400 to-teal-300 bg-clip-text text-transparent">
                        Our Investigation
                    </span>
                </h1>
                <p class="text-lg sm:text-xl text-white/80 max-w-3xl mx-auto">
                    Systematic approach to uncovering facts and improving aviation safety
                </p>
            </div>
        </template>

        <!-- Main Content -->
        <div class="space-y-8">

            <!-- Recent Investigations Section -->
                    <div class="mb-12">
                        <RecentInvestigations />
                    </div>

            <!-- Introduction -->
            <div class="text-center mb-12">
                <p class="text-gray-600 leading-relaxed max-w-3xl mx-auto">
                    Our investigation process follows international standards to ensure thorough, independent, 
                    and timely investigations focused on preventing future occurrences.
                </p>
            </div>

            <!-- Process Steps -->
            <div class="relative">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div v-for="step in steps" :key="step.number" class="relative">
                        <div class="bg-white border border-gray-200 rounded-xl p-6 hover:shadow-lg transition-all h-full">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="w-12 h-12 bg-gradient-to-r from-teal-500 to-emerald-500 rounded-full flex items-center justify-center text-white font-bold text-xl">
                                    {{ step.number }}
                                </div>
                                <div class="text-2xl">{{ step.icon }}</div>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-2">{{ step.title }}</h3>
                            <p class="text-gray-600 mb-3">{{ step.description }}</p>
                            <div class="text-sm text-teal-600 font-medium">
                                ⏱️ {{ step.duration }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Key Principles -->
            <section class="bg-gray-50 rounded-xl p-6 mt-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Key Principles</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-teal-600 mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                        <span class="text-gray-700">Safety first - prevention not blame</span>
                    </div>
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-teal-600 mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                        <span class="text-gray-700">Independent investigation</span>
                    </div>
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-teal-600 mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                        <span class="text-gray-700">Transparent process</span>
                    </div>
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-teal-600 mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                        <span class="text-gray-700">International cooperation</span>
                    </div>
                </div>
            </section>

        </div>
    </PageLayout>
</template>