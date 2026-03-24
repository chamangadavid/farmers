<!-- Resources/js/Pages/Site/About.vue -->
<script setup>
import PageLayout from '@/Components/PageLayout.vue';

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
});
</script>

<template>
    <PageLayout 
        title="About AAIB" 
        :canLogin="canLogin"
        :canRegister="canRegister"
    >
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <Link href="/management-team" class="p-6 bg-white/5 rounded-xl border border-teal-500/20 hover:border-teal-500/50 transition-all">
                <h3 class="text-xl font-bold text-white mb-2">Management Team</h3>
                <p class="text-white/60">Meet our leadership and key personnel</p>
            </Link>
            
            <Link href="/mandates" class="p-6 bg-white/5 rounded-xl border border-teal-500/20 hover:border-teal-500/50 transition-all">
                <h3 class="text-xl font-bold text-white mb-2">Mandate</h3>
                <p class="text-white/60">Our legal framework and responsibilities</p>
            </Link>
            
            <Link href="/history-details" class="p-6 bg-white/5 rounded-xl border border-teal-500/20 hover:border-teal-500/50 transition-all">
                <h3 class="text-xl font-bold text-white mb-2">History</h3>
                <p class="text-white/60">The story of AAIB since its establishment</p>
            </Link>
        </div>
    </PageLayout>
</template>