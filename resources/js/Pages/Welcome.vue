<script setup>
import { Head, Link } from "@inertiajs/vue3";
import AppFooter from '@/Components/AppFooter.vue';
import { ref, onMounted, onUnmounted } from 'vue';

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
});

// Mobile menu state
const isMobileMenuOpen = ref(false);
const isScrolled = ref(false);

// Handle scroll effect for navbar
const handleScroll = () => {
    isScrolled.value = window.scrollY > 20;
};

// Close mobile menu on resize if screen becomes large
const handleResize = () => {
    if (window.innerWidth >= 768) {
        isMobileMenuOpen.value = false;
    }
};

onMounted(() => {
    window.addEventListener('scroll', handleScroll);
    window.addEventListener('resize', handleResize);
});

onUnmounted(() => {
    window.removeEventListener('scroll', handleScroll);
    window.removeEventListener('resize', handleResize);
});

// Toggle mobile menu
const toggleMobileMenu = () => {
    isMobileMenuOpen.value = !isMobileMenuOpen.value;
    // Prevent body scroll when menu is open
    if (isMobileMenuOpen.value) {
        document.body.style.overflow = 'hidden';
    } else {
        document.body.style.overflow = 'unset';
    }
};
</script>

<template>
    <Head title="Welcome - CAA QR Generator" />

    <div class="min-h-screen relative text-white overflow-x-hidden">
        <!-- Background Image with Parallax Effect -->
        <div class="fixed inset-0">
            <img
                src="/assets/one.png"
                alt="Background"
                class="w-full h-full object-cover transform scale-105 transition-transform duration-10000 animate-slow-zoom"
                :class="{ 'scale-110': isScrolled }"
            />
            <div class="absolute inset-0 bg-gradient-to-br from-[#0A1929]/80 via-[#0F2B3F]/70 to-[#1A3B4F]/80 backdrop-blur-[2px]"></div>
        </div>

        <!-- Content Container -->
        <div class="relative z-10 flex flex-col min-h-screen">
            <!-- Top Navigation -->
            <nav class="fixed top-0 left-0 right-0 z-50 transition-all duration-500"
                 :class="[
                     isScrolled 
                         ? 'bg-[#0A1929]/40 backdrop-blur-xl py-3 shadow-2xl' 
                         : 'bg-transparent py-5'
                 ]">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between items-center">
                        <!-- Logo + Brand with Animation -->
                        <div class="flex items-center space-x-3 group cursor-pointer">
                            <div class="relative">
                                <img
                                    src="/assets/marz-logo.png"
                                    alt="CAA QR Generator"
                                    class="h-10 w-auto transition-transform duration-300 group-hover:scale-110"
                                />
                                <div class="absolute -inset-2 bg-gradient-to-r from-[#0078D4] to-[#00BCF2]/20 rounded-full blur-xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            </div>
                            <span class="text-lg font-semibold tracking-wide bg-gradient-to-r from-white via-[#E1F0FF] to-[#B3E0FF] bg-clip-text text-transparent animate-pulse-slow">
                                CAA QR GENERATOR
                            </span>
                        </div>

                        <!-- Desktop Auth Buttons -->
                        <div class="hidden md:flex items-center space-x-6">
                            <template v-if="$page.props.auth.user">
                                <Link
                                    :href="route('dashboard')"
                                    class="relative text-white hover:text-[#00BCF2] transition font-medium group"
                                >
                                    Dashboard
                                    <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-gradient-to-r from-[#0078D4] to-[#00BCF2] transition-all duration-300 group-hover:w-full"></span>
                                </Link>
                            </template>

                            <template v-else>
                                <Link
                                    v-if="canLogin"
                                    :href="route('login')"
                                    class="relative text-white hover:text-[#00BCF2] transition font-medium group"
                                >
                                    Login
                                    <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-gradient-to-r from-[#0078D4] to-[#00BCF2] transition-all duration-300 group-hover:w-full"></span>
                                </Link>

                                <Link
                                    v-if="canRegister"
                                    :href="route('register')"
                                    class="px-6 py-2.5 bg-gradient-to-r from-[#0078D4] to-[#00BCF2] text-white rounded-lg font-medium hover:shadow-lg hover:shadow-[#0078D4]/30 transition-all duration-300 transform hover:-translate-y-0.5"
                                >
                                    Register
                                </Link>
                            </template>
                        </div>

                        <!-- Mobile Menu Button -->
                        <button 
                            @click="toggleMobileMenu"
                            class="md:hidden relative w-10 h-10 focus:outline-none"
                            aria-label="Toggle menu"
                        >
                            <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-6">
                                <span 
                                    class="absolute h-0.5 w-6 bg-gradient-to-r from-[#0078D4] to-[#00BCF2] transform transition-all duration-300"
                                    :class="{ 'rotate-45 top-0': isMobileMenuOpen, '-translate-y-2': !isMobileMenuOpen }"
                                    style="top: -8px"
                                ></span>
                                <span 
                                    class="absolute h-0.5 w-6 bg-gradient-to-r from-[#0078D4] to-[#00BCF2] transition-all duration-300"
                                    :class="{ 'opacity-0': isMobileMenuOpen }"
                                    style="top: 0"
                                ></span>
                                <span 
                                    class="absolute h-0.5 w-6 bg-gradient-to-r from-[#0078D4] to-[#00BCF2] transform transition-all duration-300"
                                    :class="{ '-rotate-45 top-0': isMobileMenuOpen, 'translate-y-2': !isMobileMenuOpen }"
                                    style="top: 8px"
                                ></span>
                            </div>
                        </button>
                    </div>
                </div>
            </nav>

            <!-- Mobile Menu Dropdown with Animation -->
            <transition
                enter-active-class="transition-all duration-300 ease-out"
                enter-from-class="opacity-0 -translate-y-10"
                enter-to-class="opacity-100 translate-y-0"
                leave-active-class="transition-all duration-200 ease-in"
                leave-from-class="opacity-100 translate-y-0"
                leave-to-class="opacity-0 -translate-y-10"
            >
                <div 
                    v-if="isMobileMenuOpen"
                    class="fixed top-[72px] left-0 right-0 z-40 md:hidden"
                >
                    <div class="bg-[#0A1929]/95 backdrop-blur-xl border-b border-[#00BCF2]/20 shadow-2xl">
                        <div class="px-4 py-6 space-y-4">
                            <template v-if="$page.props.auth.user">
                                <Link
                                    :href="route('dashboard')"
                                    class="block px-4 py-3 text-white hover:bg-gradient-to-r hover:from-[#0078D4]/20 hover:to-[#00BCF2]/20 rounded-lg transition-all duration-300 text-lg font-medium"
                                    @click="toggleMobileMenu"
                                >
                                    Dashboard
                                </Link>
                            </template>

                            <template v-else>
                                <Link
                                    v-if="canLogin"
                                    :href="route('login')"
                                    class="block px-4 py-3 text-white hover:bg-gradient-to-r hover:from-[#0078D4]/20 hover:to-[#00BCF2]/20 rounded-lg transition-all duration-300 text-lg font-medium"
                                    @click="toggleMobileMenu"
                                >
                                    Login
                                </Link>

                                <Link
                                    v-if="canRegister"
                                    :href="route('register')"
                                    class="block px-4 py-3 text-white bg-gradient-to-r from-[#0078D4] to-[#00BCF2] rounded-lg transition-all duration-300 text-lg font-medium"
                                    @click="toggleMobileMenu"
                                >
                                    Register
                                </Link>
                            </template>

                            <!-- Additional Mobile Menu Items -->
                            <!-- <div class="pt-4 mt-4 border-t border-[#00BCF2]/20">
                                <Link
                                    href="#features"
                                    class="block px-4 py-3 text-white/80 hover:text-[#00BCF2] hover:bg-gradient-to-r hover:from-[#0078D4]/10 hover:to-[#00BCF2]/10 rounded-lg transition-all duration-300"
                                    @click="toggleMobileMenu"
                                >
                                    Features
                                </Link>
                                <Link
                                    href="#pricing"
                                    class="block px-4 py-3 text-white/80 hover:text-[#00BCF2] hover:bg-gradient-to-r hover:from-[#0078D4]/10 hover:to-[#00BCF2]/10 rounded-lg transition-all duration-300"
                                    @click="toggleMobileMenu"
                                >
                                    Pricing
                                </Link>
                                <Link
                                    href="#contact"
                                    class="block px-4 py-3 text-white/80 hover:text-[#00BCF2] hover:bg-gradient-to-r hover:from-[#0078D4]/10 hover:to-[#00BCF2]/10 rounded-lg transition-all duration-300"
                                    @click="toggleMobileMenu"
                                >
                                    Contact
                                </Link>
                            </div> -->
                        </div>
                    </div>
                </div>
            </transition>

            <!-- Hero Content with Overlay for Mobile Menu -->
            <div class="flex-1 flex items-center pt-20 sm:pt-24 md:pt-28"
                 :class="{ 'pointer-events-none opacity-50 md:pointer-events-auto md:opacity-100': isMobileMenuOpen }">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
                    <div class="flex flex-col lg:flex-row items-center justify-between gap-12 lg:gap-20">
                        <!-- Left Text with Animations -->
                        <div class="max-w-2xl text-center lg:text-left animate-fade-in-up">
                            <h1 class="text-4xl sm:text-5xl lg:text-6xl xl:text-7xl font-light leading-tight">
                                <span class="block animate-slide-in-left">E-Business Card</span>
                                <span class="font-semibold bg-gradient-to-r from-[#0078D4] to-[#00BCF2] bg-clip-text text-transparent animate-slide-in-right">
                                    Generator
                                </span>
                            </h1>

                            <p class="mt-6 text-base sm:text-lg text-white/80 max-w-xl mx-auto lg:mx-0 animate-fade-in-delayed">
                                Smart E-Business Card generation designed to showcase
                                your brand, connect instantly, and share your details 
                                with a single tap.
                            </p>

                            <!-- CTA Buttons -->
                            <div class="mt-10 flex flex-col sm:flex-row gap-4 justify-center lg:justify-start animate-fade-in-delayed-more">
                                <Link
                                    :href="route('register')"
                                    class="group relative px-8 py-4 bg-gradient-to-r from-[#0078D4] to-[#00BCF2] text-white rounded-xl font-semibold overflow-hidden transition-all duration-300 hover:shadow-2xl hover:shadow-[#0078D4]/30 hover:-translate-y-1"
                                >
                                    <span class="relative z-10">Get Started Free</span>
                                    <div class="absolute inset-0 bg-gradient-to-r from-[#00BCF2] to-[#0078D4] opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                </Link>
                            </div>

                            <!-- Rating -->
                            <div class="mt-10 flex items-center justify-center lg:justify-start space-x-4 animate-fade-in">
                                <div class="flex -space-x-2">
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-r from-[#0078D4] to-[#00BCF2] border-2 border-white/50 flex items-center justify-center text-xs font-bold">JD</div>
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-r from-[#00BCF2] to-[#0078D4] border-2 border-white/50 flex items-center justify-center text-xs font-bold">MK</div>
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-r from-[#005A9E] to-[#0078D4] border-2 border-white/50 flex items-center justify-center text-xs font-bold">SL</div>
                                    <div class="w-10 h-10 rounded-full bg-[#0A1929]/50 backdrop-blur-sm border-2 border-white/50 flex items-center justify-center text-xs font-bold">+50</div>
                                </div>
                                <div>
                                    <div class="flex items-center gap-1">
                                        <div v-for="i in 5" :key="i" class="text-[#00BCF2] text-xl animate-pulse-slow" :style="{ animationDelay: `${i * 0.2}s` }">★</div>
                                    </div>
                                    <div class="text-sm text-white/70">
                                        <span class="font-semibold text-[#00BCF2]">4.7</span> users+
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right Card with Animation -->
                        <div class="w-full max-w-sm lg:max-w-md animate-float">
                            <div class="relative group">
                                <!-- Glow Effect -->
                                <div class="absolute -inset-1 bg-gradient-to-r from-[#0078D4] to-[#00BCF2] rounded-2xl blur-xl opacity-25 group-hover:opacity-50 transition-opacity duration-500"></div>
                                
                                <!-- Card Content -->
                                <div class="relative p-6 sm:p-8 rounded-2xl bg-[#0A1929]/40 backdrop-blur-xl border border-[#00BCF2]/30 shadow-2xl overflow-hidden">
                                    <!-- Animated Background Pattern -->
                                    <div class="absolute inset-0 opacity-5">
                                        <div class="absolute inset-0 bg-gradient-to-br from-[#0078D4] to-[#00BCF2] animate-slow-spin"></div>
                                    </div>

                                    <div class="relative z-10">
                                        <h3 class="text-xl sm:text-2xl font-semibold mb-6 bg-gradient-to-r from-white via-[#E1F0FF] to-[#B3E0FF] bg-clip-text text-transparent">
                                            E-Business Card
                                        </h3>

                                        <div class="space-y-4">
                                            <div class="flex justify-between items-center pb-4 border-b border-[#00BCF2]/20">
                                                <span class="text-white/60">Staff</span>
                                                <span class="font-medium text-white bg-gradient-to-r from-[#0078D4]/30 to-[#00BCF2]/30 px-3 py-1 rounded-full text-sm border border-[#00BCF2]/30">All Staff</span>
                                            </div>
                                            <div class="flex justify-between items-center pb-4 border-b border-[#00BCF2]/20">
                                                <span class="text-white/60">Quote</span>
                                                <span class="font-medium text-white bg-gradient-to-r from-[#0078D4]/30 to-[#00BCF2]/30 px-3 py-1 rounded-full text-sm border border-[#00BCF2]/30">Free QR Codes</span>
                                            </div>
                                            <div class="flex justify-between items-center">
                                                <span class="text-white/60">Support</span>
                                                <span class="font-medium text-white flex items-center gap-2">
                                                    <span class="relative flex h-3 w-3">
                                                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#00BCF2] opacity-75"></span>
                                                        <span class="relative inline-flex rounded-full h-3 w-3 bg-[#0078D4]"></span>
                                                    </span>
                                                    24/7
                                                </span>
                                            </div>
                                        </div>

                                        <div class="mt-8 flex items-center justify-between">
                                            <div class="text-sm text-white/60">
                                                Need assistance?
                                            </div>

                                            <button
                                                class="group relative px-6 py-2.5 bg-gradient-to-r from-[#0078D4] to-[#00BCF2] text-white rounded-lg font-medium overflow-hidden transition-all duration-300 hover:shadow-lg hover:shadow-[#0078D4]/30"
                                            >
                                                <span class="relative z-10">Help Center</span>
                                                <div class="absolute inset-0 bg-gradient-to-r from-[#00BCF2] to-[#0078D4] opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add padding for footer -->
    <div class="relative z-10">
        <AppFooter />
    </div>
</template>

<style scoped>
/* Custom Animations */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes slideInLeft {
    from {
        opacity: 0;
        transform: translateX(-50px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

@keyframes slideInRight {
    from {
        opacity: 0;
        transform: translateX(50px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

@keyframes float {
    0%, 100% {
        transform: translateY(0px);
    }
    50% {
        transform: translateY(-20px);
    }
}

@keyframes slowZoom {
    from {
        transform: scale(1);
    }
    to {
        transform: scale(1.1);
    }
}

@keyframes slowSpin {
    from {
        transform: rotate(0deg);
    }
    to {
        transform: rotate(360deg);
    }
}

.animate-fade-in-up {
    animation: fadeInUp 1s ease-out;
}

.animate-slide-in-left {
    animation: slideInLeft 0.8s ease-out;
}

.animate-slide-in-right {
    animation: slideInRight 0.8s ease-out 0.2s both;
}

.animate-fade-in-delayed {
    animation: fadeInUp 0.8s ease-out 0.4s both;
}

.animate-fade-in-delayed-more {
    animation: fadeInUp 0.8s ease-out 0.6s both;
}

.animate-fade-in {
    animation: fadeInUp 0.8s ease-out 0.8s both;
}

.animate-float {
    animation: float 6s ease-in-out infinite;
}

.animate-slow-zoom {
    animation: slowZoom 20s ease-in-out infinite alternate;
}

.animate-slow-spin {
    animation: slowSpin 20s linear infinite;
}

.animate-pulse-slow {
    animation: pulse 3s ease-in-out infinite;
}

/* Responsive adjustments */
@media (max-width: 640px) {
    .animate-float {
        animation: float 4s ease-in-out infinite;
    }
}

/* Smooth scrolling */
html {
    scroll-behavior: smooth;
}

/* Prevent body scroll when mobile menu is open */
:deep(body) {
    overflow: hidden;
}
</style>