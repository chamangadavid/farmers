<!-- Resources/js/Pages/Site/aboutUs.vue -->
<script setup>
import PageLayout from '@/Components/PageLayout.vue';

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
});
</script>

<template>
    <PageLayout 
        title="About Us" 
        :canLogin="canLogin"
        :canRegister="canRegister"
        :showHero="true"
    >
        <!-- Custom Hero Section for About Us -->
        <template #hero>
            <div class="text-center mt-12">
                <h1 class="text-4xl sm:text-5xl lg:text-6xl xl:text-7xl font-bold leading-tight mb-6">
                    <span class="bg-gradient-to-r from-teal-400 via-emerald-400 to-teal-300 bg-clip-text text-transparent">
                        About Aircraft Accident<br>Investigation Board
                    </span>
                </h1>
                <p class="text-lg sm:text-xl text-white/80 max-w-3xl mx-auto mb-10">
                    Committed to improving aviation safety through independent, thorough, and timely investigations
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="#mission" class="px-8 py-4 bg-gradient-to-r from-teal-600 to-emerald-600 text-white rounded-xl font-semibold hover:shadow-2xl hover:shadow-teal-500/30 transition-all duration-300 transform hover:-translate-y-1">
                        Our Mission
                    </a>
                    <a href="#values" class="px-8 py-4 bg-white/10 backdrop-blur-sm border border-teal-400/30 text-white rounded-xl font-semibold hover:bg-white/20 transition-all duration-300">
                        Our Values
                    </a>
                </div>
            </div>
        </template>

        <!-- Main Content -->
        <div class="space-y-8">
            <section id="mission">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Our Mission</h2>
                <p class="text-gray-700 leading-relaxed">
                    To provide internationally approved quality aircraft accident and serious incident investigations 
                    and reports in order to advance civil aviation safety and growth of the aviation sector in Zambia.
                </p>
            </section>

            <section id="vision">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Our Vision</h2>
                <p class="text-gray-700 leading-relaxed">
                    To be a renowned investigator of aircraft accidents and serious incidents in Southern Africa 
                    and beyond.
                </p>
            </section>

            <section id="values">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Core Values</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                        <h3 class="text-xl font-semibold text-teal-600 mb-2">Discipline</h3>
                        <p class="text-gray-600">Conducting investigations free from external influence.</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                        <h3 class="text-xl font-semibold text-teal-600 mb-2">Ingenuity</h3>
                        <p class="text-gray-600">Striving for the highest standards in investigation quality.</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                        <h3 class="text-xl font-semibold text-teal-600 mb-2">Integrity</h3>
                        <p class="text-gray-600">Upholding the highest ethical standards.</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                        <h3 class="text-xl font-semibold text-teal-600 mb-2">Transparency</h3>
                        <p class="text-gray-600">Sharing findings openly to promote safety.</p>
                    </div>
                     <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                        <h3 class="text-xl font-semibold text-teal-600 mb-2">Hard work</h3>
                        <p class="text-gray-600">Sharing findings openly to promote safety.</p>
                    </div>
                      <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                        <h3 class="text-xl font-semibold text-teal-600 mb-2">Precision</h3>
                        <p class="text-gray-600">Sharing findings openly to promote safety.</p>
                    </div>
                </div>
            </section>
        </div>
    </PageLayout>
</template>