<!-- Resources/js/Pages/Site/ManagementTeam.vue -->
<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import PageLayout from '@/Components/PageLayout.vue';
import { router } from '@inertiajs/vue3';


defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
});

const team = ref([]);
const loading = ref(true);

const fetchTeam = async () => {
    try {
        loading.value = true;
        const response = await axios.get('/members');

        team.value = response.data.members.map(member => ({
            id: member.id, 
            name: member.fullname,
            title: member.position,
            bio: member.content,
            image: member.image
        }));

    } catch (error) {
        console.error('Failed to fetch team members', error);
    } finally {
        loading.value = false;
    }
};

onMounted(() => {
    fetchTeam();
});

const stripHtml = (html) => {
    const tempDiv = document.createElement('div');
    tempDiv.innerHTML = html;
    return tempDiv.textContent || tempDiv.innerText || '';
};

const goToDetails = (member) => {
    if (!member?.id) {
        console.error('Missing ID:', member);
        return;
    }
    router.visit(`/management-team/${member.id}`);
};


</script>

<template>
    <PageLayout title="Management Team" :canLogin="canLogin" :canRegister="canRegister" :showHero="true">
        <!-- Custom Hero Section for Management Team -->
        <template #hero>
            <div class="text-center mt-12">
                <h1 class="text-4xl sm:text-5xl lg:text-6xl xl:text-7xl font-bold leading-tight mb-6">
                    <span
                        class="bg-gradient-to-r from-teal-400 via-emerald-400 to-teal-300 bg-clip-text text-transparent">
                        Leadership & Management Team
                    </span>
                </h1>
                <p class="text-lg sm:text-xl text-white/80 max-w-3xl mx-auto">
                    Meet our distinguished team of professionals committed to upholding the highest standards of
                    aviation safety, governance, and operational excellence.
                </p>
            </div>
        </template>

        <!-- Main Content -->
        <div class="space-y-8">
            <div v-if="loading" class="text-center py-8 text-white/80">Loading team members...</div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                   <div v-for="member in team" :key="member.id" @click="goToDetails(member)"
                    class="bg-gray-50 rounded-xl p-6 border border-gray-200 text-center cursor-pointer hover:shadow-lg transition">
                    <div
                        class="w-32 h-32 rounded-full mx-auto mb-4 flex items-center justify-center overflow-hidden border">
                        <img v-if="member.image" :src="`/storage/${member.image}`" alt="Profile"
                            class="w-full h-full object-cover" />
                        <span v-else class="text-4xl text-white font-bold">
                            {{ member.name.charAt(0) }}
                        </span>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">{{ member.name }}</h3>
                    <p class="text-teal-600 font-semibold mb-2">{{ member.title }}</p>
                    <p class="text-gray-600 text-sm">
                        {{ stripHtml(member.bio).length > 25 ? stripHtml(member.bio).substring(0, 25) + '...' :
                            stripHtml(member.bio) }}
                    </p>

                </div>
            </div>
        </div>
    </PageLayout>
</template>