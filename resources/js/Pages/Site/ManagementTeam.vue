<!-- Resources/js/Pages/Site/ManagementTeam.vue -->
<script setup>
import PageLayout from '@/Components/PageLayout.vue';

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
});

const team = [
    { name: 'Dr. John Smith', title: 'Chairperson', bio: 'Aviation safety expert with 25+ years experience' },
    { name: 'Sarah Johnson', title: 'Chief Investigator', bio: 'Leading accident investigation specialist' },
    { name: 'Michael Brown', title: 'Technical Director', bio: 'Expert in aircraft systems and engineering' }
];
</script>

<template>
    <PageLayout 
        title="Management Team" 
        :canLogin="canLogin"
        :canRegister="canRegister"
        :showHero="true"
    >
        <!-- Custom Hero Section for Management Team -->
        <template #hero>
            <div class="text-center mt-12">
                <h1 class="text-4xl sm:text-5xl lg:text-6xl xl:text-7xl font-bold leading-tight mb-6">
                    <span class="bg-gradient-to-r from-teal-400 via-emerald-400 to-teal-300 bg-clip-text text-transparent">
                        Our Leadership Team
                    </span>
                </h1>
                <p class="text-lg sm:text-xl text-white/80 max-w-3xl mx-auto">
                    Meet the experienced professionals dedicated to aviation safety excellence
                </p>
            </div>
        </template>

        <!-- Main Content -->
        <div class="space-y-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div v-for="member in team" :key="member.name" class="bg-gray-50 rounded-xl p-6 border border-gray-200 text-center">
                    <div class="w-32 h-32 bg-gradient-to-r from-teal-500 to-emerald-500 rounded-full mx-auto mb-4 flex items-center justify-center">
                        <span class="text-4xl text-white font-bold">{{ member.name.charAt(0) }}</span>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">{{ member.name }}</h3>
                    <p class="text-teal-600 font-semibold mb-2">{{ member.title }}</p>
                    <p class="text-gray-600 text-sm">{{ member.bio }}</p>
                </div>
            </div>
        </div>
    </PageLayout>
</template>