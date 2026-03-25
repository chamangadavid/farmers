<!-- Resources/js/Pages/Welcome.vue -->
<script setup>
import { Head } from "@inertiajs/vue3";
import AppFooter from '@/Components/AppFooter.vue';
import Header from '@/Components/Header.vue';
import SlidingBackground from '@/Components/SlidingBackground.vue';
import AnimatedHero from '@/Components/AnimatedHero.vue';
import NewsSection from '@/Components/NewsSection.vue';
import AnnouncementsSection from '@/Components/AnnouncementsSection.vue';
import SearchSection from '@/Components/SearchSection.vue';
import PartnersSlider from '@/Components/PartnersSlider.vue'; 

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
});
</script>

<template>
    <Head title="Aircraft Accident Investigation Board" />

    <div class="min-h-screen relative overflow-x-hidden">
        <!-- Sliding Background with Particles -->
        <SlidingBackground />

        <!-- Content Container -->
        <div class="relative z-10 flex flex-col min-h-screen">
            <!-- Header Component -->
            <Header :canLogin="canLogin" :canRegister="canRegister" />

            <!-- Hero Content with Animations -->
            <AnimatedHero />

            <!-- Main Content Sections -->
            <div class="bg-gradient-to-b from-transparent to-teal-900/20 pb-16">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
                    <!-- Search Section -->
                    <div class="mb-12">
                        <SearchSection />
                    </div>

                    <!-- News and Announcements Grid -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12">
                        <NewsSection />
                        <AnnouncementsSection />
                    </div>

                    <!-- Partners Slider Section -->
                    <div class="mb-12">
                        <PartnersSlider />
                    </div>

                    <!-- Additional Quick Links Section -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8">
                        <div class="bg-white/10 backdrop-blur-sm rounded-xl p-6 text-center hover:bg-white/20 transition-all">
                            <div class="text-4xl mb-3">📊</div>
                            <h3 class="text-lg font-semibold text-white mb-2">Safety Statistics</h3>
                            <p class="text-white/70 text-sm">View annual safety reports and statistical data</p>
                            <a href="/statistics" class="inline-block mt-3 text-teal-400 text-sm hover:text-teal-300">Learn more →</a>
                        </div>
                        <div class="bg-white/10 backdrop-blur-sm rounded-xl p-6 text-center hover:bg-white/20 transition-all">
                            <div class="text-4xl mb-3">📚</div>
                            <h3 class="text-lg font-semibold text-white mb-2">Resources</h3>
                            <p class="text-white/70 text-sm">Access investigation manuals and guidance materials</p>
                            <a href="/resources" class="inline-block mt-3 text-teal-400 text-sm hover:text-teal-300">Browse resources →</a>
                        </div>
                        <div class="bg-white/10 backdrop-blur-sm rounded-xl p-6 text-center hover:bg-white/20 transition-all">
                            <div class="text-4xl mb-3">🚨</div>
                            <h3 class="text-lg font-semibold text-white mb-2">Report an Accident</h3>
                            <p class="text-white/70 text-sm">Immediate reporting hotline and online form</p>
                            <a href="/report-accident" class="inline-block mt-3 text-teal-400 text-sm hover:text-teal-300">Report now →</a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="relative z-10">
        <AppFooter />
    </div>
</template>

<style>
/* Global styles for smooth scrolling */
html {
  scroll-behavior: smooth;
}

/* Ensure footer doesn't overlap content */
@media (min-height: 100vh) {
  .relative.z-10.flex.flex-col {
    min-height: 100vh;
  }
}
</style>