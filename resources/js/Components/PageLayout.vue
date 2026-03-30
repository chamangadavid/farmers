<!-- Resources/js/Components/PageLayout.vue -->
<script setup>
import { Head, Link } from "@inertiajs/vue3";
import { computed, onMounted, onUnmounted, watch } from 'vue';
import { usePage } from '@inertiajs/vue3';
import AppFooter from '@/Components/AppFooter.vue';
import Header from '@/Components/Header.vue';
import { useBreadcrumbs } from '@/composables/useBreadcrumbs';

const props = defineProps({
    title: {
        type: String,
        default: 'Aircraft Accident Investigation Board'
    },
    customBreadcrumbs: {
        type: Array,
        default: null
    },
    canLogin: Boolean,
    canRegister: Boolean,
    showHero: {
        type: Boolean,
        default: false
    }
});

// Use the breadcrumb composable
const { breadcrumbs: autoBreadcrumbs } = useBreadcrumbs();

// Use custom breadcrumbs if provided, otherwise use auto-generated
const finalBreadcrumbs = computed(() => {
    if (props.customBreadcrumbs) {
        return props.customBreadcrumbs;
    }
    return autoBreadcrumbs.value;
});

// Ensure body scrolling works properly
onMounted(() => {
    // Reset scroll position when component mounts
    window.scrollTo(0, 0);
    
    // Ensure body can scroll
    document.body.style.overflow = 'auto';
    document.documentElement.style.overflow = 'auto';
    
    // Check if there's a hash in the URL and scroll to it after page loads
    if (window.location.hash) {
        setTimeout(() => {
            const element = document.querySelector(window.location.hash);
            if (element) {
                element.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        }, 100);
    }
});

// Watch for route changes to handle hash navigation
const page = usePage();
watch(() => page.url, () => {
    // Reset scroll when route changes
    window.scrollTo(0, 0);
    
    // Handle hash after navigation
    setTimeout(() => {
        if (window.location.hash) {
            const element = document.querySelector(window.location.hash);
            if (element) {
                element.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        }
    }, 100);
});

onUnmounted(() => {
    // Clean up
    document.body.style.overflow = '';
    document.documentElement.style.overflow = '';
});
</script>

<template>
    <Head :title="title" />

    <div class="min-h-screen relative overflow-x-hidden">
        <!-- Static Background Image -->
        <div class="fixed inset-0">
            <img 
                src="/assets/back.png" 
                alt="Background" 
                class="w-full h-full object-cover"
            />
            <div class="absolute inset-0 bg-gradient-to-b from-teal-900/90 to-teal-950/90"></div>
        </div>

        <!-- Content Container - Ensure full height and proper scrolling -->
        <div class="relative z-10 flex flex-col min-h-screen">
            <!-- Header Component -->
            <Header :canLogin="canLogin" :canRegister="canRegister" />

            <!-- Hero Section Slot (Custom per page) -->
            <div v-if="showHero" class="relative overflow-hidden flex-shrink-0">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 md:py-24">
                    <slot name="hero" />
                </div>
            </div>

            <!-- Page Content - Make this scrollable -->
            <main class="flex-1 overflow-y-auto mt-10">
                <div class="py-12 px-4 sm:px-6 lg:px-8">
                    <div class="max-w-7xl mx-auto">
                        <!-- Breadcrumbs (only show if not on hero section or if breadcrumbs exist) -->
                        <nav v-if="finalBreadcrumbs.length > 0 && !showHero" class="mb-8">
                            <ol class="flex flex-wrap items-center space-x-2 text-sm">
                                <li>
                                    <Link href="/" class="text-teal-400 hover:text-teal-300 transition-colors">
                                        Home
                                    </Link>
                                </li>
                                <li v-for="(crumb, index) in finalBreadcrumbs" :key="index" class="flex items-center">
                                    <svg class="w-4 h-4 text-white/40 mx-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                    <Link 
                                        v-if="crumb.route" 
                                        :href="crumb.route" 
                                        class="text-white/60 hover:text-teal-400 transition-colors"
                                    >
                                        {{ crumb.label }}
                                    </Link>
                                    <span v-else class="text-white/40">{{ crumb.label }}</span>
                                </li>
                            </ol>
                        </nav>

                        <!-- Page Title (only show if not using hero section) -->
                        <div v-if="!showHero" class="mb-8">
                            <h1 class="text-4xl md:text-5xl font-bold bg-gradient-to-r from-teal-400 to-emerald-400 bg-clip-text text-transparent">
                                {{ title }}
                            </h1>
                            <div class="w-20 h-1 bg-gradient-to-r from-teal-500 to-emerald-500 mt-4 rounded-full"></div>
                        </div>

                        <!-- White Card Content - Separate from background with margin top and bottom -->
                        <div class="bg-white rounded-2xl shadow-2xl p-6 md:p-8 lg:p-10 my-8">
                            <slot />
                        </div>
                    </div>
                </div>
            </main>

            <!-- Footer -->
            <AppFooter />
        </div>
    </div>
</template>

<style scoped>
/* Ensure main is scrollable */
main {
    overflow-y: auto;
    overflow-x: hidden;
    scroll-behavior: smooth;
}

/* Smooth animations for content */
main > div {
    animation: fadeInUp 0.6s ease-out;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Hero section animations */
.relative.overflow-hidden {
    animation: fadeIn 0.8s ease-out;
}

@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

/* Ensure body can scroll properly */
:global(body) {
    overflow: auto !important;
    height: auto !important;
    min-height: 100vh !important;
}

:global(html) {
    overflow: auto !important;
    height: auto !important;
    min-height: 100vh !important;
    scroll-behavior: smooth;
}
</style>