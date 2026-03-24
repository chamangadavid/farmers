<!-- Resources/js/Components/AnnouncementsSection.vue -->
<script setup>
import { ref } from 'vue';

const announcements = ref([
    {
        id: 1,
        title: 'Public Consultation on Aviation Safety Regulations',
        date: 'March 15, 2024',
        priority: 'High',
        summary: 'We invite stakeholders and the public to provide feedback on proposed amendments to aviation safety regulations.',
        category: 'Consultation'
    },
    {
        id: 2,
        title: 'Annual Safety Report 2023 Now Available',
        date: 'February 28, 2024',
        priority: 'High',
        summary: 'The 2023 Annual Safety Report highlights key findings and safety improvements achieved over the past year.',
        category: 'Publication'
    },
    {
        id: 3,
        title: 'AAIB Participates in International Aviation Safety Conference',
        date: 'February 10, 2024',
        priority: 'Medium',
        summary: 'Board members will present findings at the International Aviation Safety Conference.',
        category: 'Event'
    },
    {
        id: 4,
        title: 'Call for Experts: Technical Advisory Panel',
        date: 'January 25, 2024',
        priority: 'High',
        summary: 'We are seeking aviation experts to join our Technical Advisory Panel.',
        category: 'Recruitment'
    }
]);

const getPriorityColor = (priority) => {
    switch(priority) {
        case 'High': return 'bg-red-100 text-red-700';
        case 'Medium': return 'bg-yellow-100 text-yellow-700';
        default: return 'bg-green-100 text-green-700';
    }
};
</script>

<template>
    <div class="bg-white rounded-2xl shadow-xl p-6 md:p-8">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-2xl md:text-3xl font-bold text-gray-900">Announcements</h2>
                <p class="text-gray-600 mt-1">Important updates and public notices</p>
            </div>
            <a href="/announcements" class="text-teal-600 hover:text-teal-700 font-medium inline-flex items-center gap-1">
                View All
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </a>
        </div>

        <div class="space-y-4">
            <div v-for="item in announcements" :key="item.id" class="border-b border-gray-100 last:border-0 pb-4 last:pb-0">
                <div class="flex items-start gap-3">
                    <div class="flex-shrink-0 mt-1">
                        <span :class="['inline-block w-2 h-2 rounded-full', item.priority === 'High' ? 'bg-red-500' : item.priority === 'Medium' ? 'bg-yellow-500' : 'bg-green-500']"></span>
                    </div>
                    <div class="flex-1">
                        <div class="flex flex-wrap items-center gap-2 mb-1">
                            <span :class="['px-2 py-1 rounded-full text-xs font-semibold', getPriorityColor(item.priority)]">
                                {{ item.priority }} Priority
                            </span>
                            <span class="text-xs text-gray-500">{{ item.date }}</span>
                            <span class="text-xs px-2 py-1 bg-gray-100 text-gray-600 rounded-full">{{ item.category }}</span>
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-1">{{ item.title }}</h3>
                        <p class="text-gray-600 text-sm">{{ item.summary }}</p>
                        <a href="#" class="inline-flex items-center gap-1 text-teal-600 text-sm font-medium mt-2 hover:gap-2 transition-all">
                            Read more
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>