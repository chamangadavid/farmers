<!-- Resources/js/Pages/Welcome.vue -->
<script setup>
import { Head } from "@inertiajs/vue3";
import AppFooter from '@/Components/AppFooter.vue';
import Header from '@/Components/Header.vue';
import SlidingBackground from '@/Components/SlidingBackground.vue';
import AnimatedHero from '@/Components/AnimatedHero.vue';
import NewsSection from '@/Components/NewsSection.vue';
import AnnouncementsSection from '@/Components/AnnouncementsSection.vue';
import ProductGallery from '@/Components/ProductGallery.vue';
import ProfessionalBanner from '@/Components/ProfessionalBanner.vue'
import FeaturesSection from '@/Components/FeaturesSection.vue';
import AboutOrganic from '@/Components/AboutOrganic.vue'
import OurFarmingTeam from '@/Components/OurFarmingTeam.vue'

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
});
</script>

<template>
    <Head title="Alinaswe farm" />

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

                    <!-- Features Section -->
                    <div class="mb-16">
                        <FeaturesSection />
                    </div>

                    <!-- About Alinawe Farm Section -->
                    <div class="mb-16">
                        <AboutOrganic />
                    </div>

                    <!-- News and Announcements Grid -->
                    <!-- <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12">
                        <NewsSection />
                        <AnnouncementsSection />
                    </div> -->

                    <div class="mb-16">
                         <NewsSection />
                    </div>

                    <!-- Product Gallery Section -->
                    <div class="mb-16">
                        <ProductGallery />
                    </div> 

                    <!-- Our Farming Team Section -->
                    <div class="mb-16">
                        <OurFarmingTeam />
                    </div>
                    
                    <!-- Professiona Banner section -->
                    <div>
                        <ProfessionalBanner />
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