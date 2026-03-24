<!-- Resources/js/Pages/Site/Mandate.vue -->
<script setup>
import PageLayout from '@/Components/PageLayout.vue';

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
});

const sections = [
    {
        title: 'Our Mandate',
        content: 'To independently investigate civil aircraft accidents and serious aircraft incidents in compliance with Annex 13 to the convention on International Civil Aviation (Chicago Convention). The functions of the Aircraft Accident Investigation Board are to advance safety of civil aviation by:',
        icon: '🌍',
        points: [
           
            'Cooperating with relevant authorities and agencies outside Zambia in the exercise of its functions under this section.',
            'Establishing policies with regard to the classes of civil aircraft accidents and serious aircraft incidents to be investigated.',
            'Overseeing and exercising general control over the performance of the functions and activities of authorised persons.',
            'Submitting to the Minister, within three months after completion of an investigation, a final report on its findings and recommendations.',
            'Submitting a final report to all recipient States in compliance with Chapter 6 of Annex 13',
            'Making rules regarding the proceedings of, and conduct at, meetings of the Aircraft Accident Investigation Board.',
            'Performing any other functions prescribed under any other law.',
            'Reviewing reports submitted to the Board by the Director of Investigations.',
            'Independently investigating civil aircraft accidents and serious aircraft incidents in compliance with Annex 13.',
            'Discharging all functions and obligations in compliance with the provisions and procedures of Annex 13.',
             'Exercising on behalf of the Minister, the responsibility of the Minister under subsection (3) of section forty-three of the Civil Aviation Authority Act, 2012, relating to the investigation of civil aircraft accidents and serious aircraft incidents.',
             'Establishing, either generally or in relation to specific classes of civil aircraft accidents and serious aircraft incidents, procedures and practices to be followed in the conduct of investigations; Functions of Aircraft Accident Investigation Board Act No. 7 of 2012 Civil Aviation No. 5 of 2016.',
        ]
    },
    // {
    //     title: 'Powers & Responsibilities',
    //     content: 'The Board is empowered to conduct thorough investigations, access all relevant information, and issue safety recommendations.',
    //     points: [
    //         'Enter and inspect accident sites',
    //         'Collect and preserve evidence',
    //         'Interview witnesses',
    //         'Access aircraft records and data',
    //         'Issue binding safety recommendations'
    //     ]
    // }
];
</script>

<template>
    <PageLayout 
        title="Our Mandate" 
        :canLogin="canLogin"
        :canRegister="canRegister"
        :showHero="true"
    >
        <!-- Custom Hero Section for Mandate -->
        <template #hero>
            <div class="text-center mt-12">
                <h1 class="text-4xl sm:text-5xl lg:text-6xl xl:text-7xl font-bold leading-tight mb-6">
                    <span class="bg-gradient-to-r from-teal-400 via-emerald-400 to-teal-300 bg-clip-text text-transparent">
                        Our Mandate
                    </span>
                </h1>
                <p class="text-lg sm:text-xl text-white/80 max-w-3xl mx-auto">
                    Independent, impartial, and focused on aviation safety
                </p>
            </div>
        </template>

        <!-- Main Content -->
        <div class="space-y-8">
            <div v-for="section in sections" :key="section.title" class="space-y-4">
                <div class="flex items-center gap-3">
                    <div class="text-3xl">{{ section.icon }}</div>
                    <h2 class="text-2xl font-bold text-gray-900">{{ section.title }}</h2>
                </div>
                <p class="text-gray-600 leading-relaxed">{{ section.content }}</p>
                <ul v-if="section.points" class="list-disc list-inside space-y-2 ml-4">
                    <li v-for="point in section.points" :key="point" class="text-gray-600">{{ point }}</li>
                </ul>
            </div>

            <!-- Core Principles -->
            <section class="bg-gradient-to-r from-teal-50 to-emerald-50 rounded-xl p-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Core Principles</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-teal-600 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                        <div>
                            <strong class="text-gray-900">Independence</strong>
                            <p class="text-gray-600 text-sm">Free from regulatory or political influence</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-teal-600 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                        <div>
                            <strong class="text-gray-900">Impartiality</strong>
                            <p class="text-gray-600 text-sm">Fair investigation without bias</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-teal-600 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                        <div>
                            <strong class="text-gray-900">Transparency</strong>
                            <p class="text-gray-600 text-sm">Open reporting and public access</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-teal-600 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                        <div>
                            <strong class="text-gray-900">Accountability</strong>
                            <p class="text-gray-600 text-sm">Responsible to the public interest</p>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </PageLayout>
</template>