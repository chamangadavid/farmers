<!-- Resources/js/Pages/Site/FAQ.vue -->
<script setup>
import PageLayout from '@/Components/PageLayout.vue';
import { ref } from 'vue';

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
});

const faqs = ref([
    {
        category: 'General Information',
        questions: [
            {
                q: 'What is the Aircraft Accident Investigation Board (AAIB)?',
                a: 'The AAIB is an independent government agency responsible for investigating aircraft accidents and incidents to improve aviation safety. We operate under international standards set by ICAO Annex 13.'
            },
            {
                q: 'What is the primary mission of AAIB?',
                a: 'Our mission is to improve aviation safety through independent, thorough, and timely investigations of aircraft accidents and incidents, identifying causes and making safety recommendations.'
            },
            {
                q: 'Is AAIB part of the Civil Aviation Authority?',
                a: 'No, AAIB operates independently from the Civil Aviation Authority to ensure impartial investigations free from regulatory influence.'
            }
        ]
    },
    {
        category: 'Investigations',
        questions: [
            {
                q: 'What types of occurrences does AAIB investigate?',
                a: 'We investigate aircraft accidents, serious incidents, and other occurrences that may pose a risk to aviation safety. This includes commercial aviation, general aviation, and military aircraft operations.'
            },
            {
                q: 'How long does an investigation typically take?',
                a: 'Investigation duration varies depending on complexity. Preliminary reports are usually issued within 30 days, while final reports may take 12-24 months for complex investigations.'
            },
            {
                q: 'Who participates in an investigation?',
                a: 'Our investigation team includes specialists in aircraft operations, engineering, human factors, air traffic control, and meteorology. We also collaborate with international experts when needed.'
            }
        ]
    },
    {
        category: 'Reports & Publications',
        questions: [
            {
                q: 'Where can I find accident investigation reports?',
                a: 'All published reports are available on our website under "Accident Reports". You can search by year, aircraft type, or occurrence date.'
            },
            {
                q: 'Are investigation reports publicly available?',
                a: 'Yes, final investigation reports are published publicly in accordance with ICAO Annex 13 guidelines. Some information may be redacted for privacy or security reasons.'
            },
            {
                q: 'Can I request a copy of an old report?',
                a: 'Yes, please contact our archives department at archives@aaib.gov.zm with the report reference number and date.'
            }
        ]
    },
    {
        category: 'Reporting an Accident',
        questions: [
            {
                q: 'How do I report an aircraft accident or incident?',
                a: 'You can report an accident through our online reporting form, call our 24-hour hotline at +260 211 123456, or email reports@aaib.gov.zm.'
            },
            {
                q: 'What information should I provide when reporting?',
                a: 'Please provide the aircraft registration, location, date and time, description of the occurrence, injuries or damage, and your contact details.'
            },
            {
                q: 'Is there a time limit for reporting?',
                a: 'Reports should be submitted as soon as possible. Immediate reporting helps preserve evidence and ensures timely investigation.'
            }
        ]
    },
    {
        category: 'Safety Recommendations',
        questions: [
            {
                q: 'What happens to safety recommendations?',
                a: 'Safety recommendations are issued to relevant authorities, organizations, and operators. We track implementation and follow up to ensure action is taken.'
            },
            {
                q: 'Can I suggest safety improvements?',
                a: 'Absolutely. We welcome safety suggestions from aviation professionals and the public. Please email safety@aaib.gov.zm with your suggestions.'
            }
        ]
    }
]);

const searchQuery = ref('');
const selectedCategory = ref('all');
const openQuestion = ref(null);

const categories = ['all', ...new Set(faqs.value.map(f => f.category))];

const filteredFaqs = ref(faqs.value);

const filterFaqs = () => {
    let filtered = faqs.value;
    
    if (selectedCategory.value !== 'all') {
        filtered = filtered.filter(f => f.category === selectedCategory.value);
    }
    
    if (searchQuery.value.trim()) {
        filtered = filtered.map(category => ({
            ...category,
            questions: category.questions.filter(q => 
                q.q.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
                q.a.toLowerCase().includes(searchQuery.value.toLowerCase())
            )
        })).filter(category => category.questions.length > 0);
    }
    
    filteredFaqs.value = filtered;
};

const toggleQuestion = (index) => {
    openQuestion.value = openQuestion.value === index ? null : index;
};
</script>

<template>
    <PageLayout 
        title="Frequently Asked Questions" 
        :canLogin="canLogin"
        :canRegister="canRegister"
        :showHero="true"
    >
        <!-- Custom Hero Section for FAQ -->
        <template #hero>
            <div class="text-center mt-12">
                <h1 class="text-4xl sm:text-5xl lg:text-6xl xl:text-7xl font-bold leading-tight mb-6">
                    <span class="bg-gradient-to-r from-teal-400 via-emerald-400 to-teal-300 bg-clip-text text-transparent">
                        Frequently Asked Questions
                    </span>
                </h1>
                <p class="text-lg sm:text-xl text-white/80 max-w-3xl mx-auto mb-6">
                    Find answers to common questions about our work, investigations, and services
                </p>
                <div class="max-w-2xl mx-auto">
                    <div class="relative">
                        <input 
                            v-model="searchQuery"
                            @input="filterFaqs"
                            type="text" 
                            placeholder="Search for answers..."
                            class="w-full px-6 py-4 bg-white/10 backdrop-blur-sm border border-teal-400/30 rounded-xl text-white placeholder-white/50 focus:outline-none focus:border-teal-400"
                        />
                        <svg class="absolute right-4 top-1/2 transform -translate-y-1/2 w-5 h-5 text-white/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </template>

        <!-- Main Content -->
        <div class="space-y-8">
            <!-- Category Filter -->
            <div class="flex flex-wrap gap-3 pb-4 border-b border-gray-200">
                <button 
                    v-for="cat in categories" 
                    :key="cat"
                    @click="selectedCategory = cat; filterFaqs()"
                    :class="[
                        'px-4 py-2 rounded-lg font-medium transition-all duration-300',
                        selectedCategory === cat 
                            ? 'bg-teal-600 text-white' 
                            : 'bg-gray-100 text-gray-700 hover:bg-gray-200'
                    ]"
                >
                    {{ cat === 'all' ? 'All Categories' : cat }}
                </button>
            </div>

            <!-- FAQ Accordion -->
            <div class="space-y-8">
                <div v-for="(category, catIndex) in filteredFaqs" :key="catIndex">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">{{ category.category }}</h2>
                    <div class="space-y-3">
                        <div v-for="(faq, qIndex) in category.questions" :key="qIndex" class="border border-gray-200 rounded-lg overflow-hidden">
                            <button 
                                @click="toggleQuestion(`${catIndex}-${qIndex}`)"
                                class="w-full px-6 py-4 text-left flex justify-between items-center hover:bg-gray-50 transition-colors"
                            >
                                <span class="font-semibold text-gray-900">{{ faq.q }}</span>
                                <svg 
                                    class="w-5 h-5 text-teal-600 transition-transform duration-300" 
                                    :class="{ 'rotate-180': openQuestion === `${catIndex}-${qIndex}` }"
                                    fill="none" 
                                    stroke="currentColor" 
                                    viewBox="0 0 24 24"
                                >
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div 
                                v-show="openQuestion === `${catIndex}-${qIndex}`"
                                class="px-6 pb-4 text-gray-600 leading-relaxed border-t border-gray-100"
                            >
                                {{ faq.a }}
                            </div>
                        </div>
                    </div>
                </div>

                <div v-if="filteredFaqs.length === 0" class="text-center py-12">
                    <p class="text-gray-500">No results found. Please try a different search term.</p>
                </div>
            </div>

            <!-- Contact Support -->
            <div class="mt-8 pt-8 border-t border-gray-200 text-center">
                <p class="text-gray-600">Still have questions?</p>
                <a href="/contact-us" class="inline-block mt-2 px-6 py-3 bg-gradient-to-r from-teal-600 to-emerald-600 text-white rounded-lg font-semibold hover:shadow-lg transition-all duration-300">
                    Contact Our Support Team
                </a>
            </div>
        </div>
    </PageLayout>
</template>