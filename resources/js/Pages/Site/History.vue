<!-- Resources/js/Pages/Site/History.vue -->
<script setup>
import PageLayout from '@/Components/PageLayout.vue';

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
});

const timeline = [
    {
        year: '2000',
        title: 'Establishment of AAIB',
        description: 'The Aircraft Accident Investigation Board was established as an independent body to investigate aviation accidents and incidents.',
        icon: '🏛️'
    },
    {
        year: '2005',
        title: 'First Major Investigation',
        description: 'Conducted the first comprehensive investigation of a commercial aircraft accident, establishing modern investigation protocols.',
        icon: '✈️'
    },
    {
        year: '2010',
        title: 'International Recognition',
        description: 'AAIB gained recognition from ICAO for excellence in accident investigation and safety recommendations.',
        icon: '🌍'
    },
    {
        year: '2015',
        title: 'Technology Modernization',
        description: 'Implemented advanced investigation technologies including flight data analysis and 3D reconstruction capabilities.',
        icon: '💻'
    },
    {
        year: '2020',
        title: 'Regional Leadership',
        description: 'Became a regional leader in aviation safety, providing training and support to neighboring countries.',
        icon: '👥'
    },
    {
        year: '2024',
        title: 'Digital Transformation',
        description: 'Launched new digital platforms for reporting, investigation management, and public access to safety information.',
        icon: '📱'
    }
];

const achievements = [
    { number: '500+', label: 'Investigations Completed', icon: '📊' },
    { number: '98%', label: 'Recommendation Implementation', icon: '✅' },
    { number: '15+', label: 'Countries Assisted', icon: '🤝' },
    { number: '50+', label: 'Safety Workshops', icon: '🎓' }
];
</script>

<template>
    <PageLayout 
        title="Our History" 
        :canLogin="canLogin"
        :canRegister="canRegister"
        :showHero="true"
    >
        <!-- Custom Hero Section for History -->
        <template #hero>
            <div class="text-center mt-12">
                <h1 class="text-4xl sm:text-5xl lg:text-6xl xl:text-7xl font-bold leading-tight mb-6">
                    <span class="bg-gradient-to-r from-teal-400 via-emerald-400 to-teal-300 bg-clip-text text-transparent">
                        Our History
                    </span>
                </h1>
                <p class="text-lg sm:text-xl text-white/80 max-w-3xl mx-auto">
                    A journey of excellence in aviation safety since 2000
                </p>
            </div>
        </template>

        <!-- Main Content -->
        <div class="space-y-12">
            <!-- Introduction -->
            <section class="text-center">
                <p class="text-gray-600 leading-relaxed max-w-3xl mx-auto">
                    Since our establishment, the Aircraft Accident Investigation Board has been at the forefront of 
                    aviation safety, committed to independent, thorough, and timely investigations. Our history reflects 
                    continuous growth, innovation, and dedication to making air travel safer for everyone.
                </p>
            </section>

            <!-- Key Achievements -->
            <section>
                <h2 class="text-2xl font-bold text-gray-900 mb-6 text-center">Key Achievements</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div v-for="achievement in achievements" :key="achievement.label" class="text-center p-6 bg-gray-50 rounded-xl">
                        <div class="text-4xl mb-3">{{ achievement.icon }}</div>
                        <div class="text-3xl font-bold text-teal-600 mb-2">{{ achievement.number }}</div>
                        <div class="text-gray-600 text-sm">{{ achievement.label }}</div>
                    </div>
                </div>
            </section>

            <!-- Timeline -->
            <section>
                <h2 class="text-2xl font-bold text-gray-900 mb-8 text-center">Our Journey Through Time</h2>
                <div class="relative">
                    <!-- Timeline Line -->
                    <div class="absolute left-1/2 transform -translate-x-1/2 w-0.5 h-full bg-gradient-to-b from-teal-500 to-emerald-500 hidden md:block"></div>
                    
                    <!-- Timeline Items -->
                    <div class="space-y-12">
                        <div v-for="(item, index) in timeline" :key="item.year" 
                             :class="['flex flex-col md:flex-row gap-8', index % 2 === 0 ? 'md:flex-row' : 'md:flex-row-reverse']">
                            <div class="flex-1 md:w-1/2"></div>
                            <div class="relative flex-shrink-0 w-16 h-16 bg-gradient-to-r from-teal-500 to-emerald-500 rounded-full flex items-center justify-center text-2xl shadow-lg z-10 mx-auto md:mx-0">
                                {{ item.icon }}
                            </div>
                            <div class="flex-1 md:w-1/2 bg-white border border-gray-200 rounded-xl p-6 shadow-sm hover:shadow-md transition-shadow">
                                <div class="text-teal-600 font-bold text-xl mb-2">{{ item.year }}</div>
                                <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ item.title }}</h3>
                                <p class="text-gray-600">{{ item.description }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Legacy Section -->
            <section class="bg-gradient-to-r from-teal-50 to-emerald-50 rounded-2xl p-8 mt-8">
                <div class="text-center">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Our Legacy</h2>
                    <p class="text-gray-600 max-w-2xl mx-auto">
                        Today, AAIB continues to uphold the highest standards of aviation safety investigation, 
                        building on decades of experience and commitment to protecting lives through safety excellence.
                    </p>
                </div>
            </section>
        </div>
    </PageLayout>
</template>