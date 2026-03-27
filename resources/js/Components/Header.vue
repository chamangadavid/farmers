<!-- Resources/js/Components/Header.vue -->
<script setup>
import { Link } from "@inertiajs/vue3";
import { ref, onMounted, onUnmounted } from 'vue';

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
});

// Mobile menu state
const isMobileMenuOpen = ref(false);
const isScrolled = ref(false);
const openMobileDropdown = ref(null);

// Dropdown states for desktop
const activeDropdown = ref(null);
let dropdownTimeout = null;

// Handle scroll effect for navbar
const handleScroll = () => {
    isScrolled.value = window.scrollY > 20;
};

// Close mobile menu on resize if screen becomes large
const handleResize = () => {
    if (window.innerWidth >= 1024) {
        isMobileMenuOpen.value = false;
        openMobileDropdown.value = null;
    }
};

// Desktop dropdown handlers
const showDropdown = (dropdownName) => {
    if (dropdownTimeout) clearTimeout(dropdownTimeout);
    activeDropdown.value = dropdownName;
};

const hideDropdown = () => {
    dropdownTimeout = setTimeout(() => {
        activeDropdown.value = null;
    }, 200);
};

const cancelHideDropdown = () => {
    if (dropdownTimeout) {
        clearTimeout(dropdownTimeout);
    }
};

// Mobile dropdown handlers
const toggleMobileDropdown = (dropdownName) => {
    if (openMobileDropdown.value === dropdownName) {
        openMobileDropdown.value = null;
    } else {
        openMobileDropdown.value = dropdownName;
    }
};

onMounted(() => {
    window.addEventListener('scroll', handleScroll);
    window.addEventListener('resize', handleResize);
});

onUnmounted(() => {
    window.removeEventListener('scroll', handleScroll);
    window.removeEventListener('resize', handleResize);
    if (dropdownTimeout) clearTimeout(dropdownTimeout);
});

// Toggle mobile menu
const toggleMobileMenu = () => {
    isMobileMenuOpen.value = !isMobileMenuOpen.value;
    if (isMobileMenuOpen.value) {
        document.body.style.overflow = 'hidden';
        openMobileDropdown.value = null;
    } else {
        document.body.style.overflow = 'unset';
    }
};

// Handle dropdown link click
const handleDropdownClick = () => {
    activeDropdown.value = null;
};
</script>

<template>
    <!-- Top Navigation -->
    <nav class="fixed top-0 left-0 right-0 z-50 transition-all duration-500"
         :class="[
             isScrolled 
                 ? 'bg-teal-900/40 backdrop-blur-xl py-2 shadow-2xl' 
                 : 'bg-transparent py-3'
         ]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- First Line: Logo, Job Vacancies, FAQs, Login, Register -->
            <div class="flex justify-between items-center">
                <!-- Logo -->
                <Link href="/" class="flex items-center space-x-3 group cursor-pointer">
                    <div class="relative">
                        <img
                            src="/assets/marz-logo.png"
                            alt="AAIB"
                            class="h-10 w-auto transition-transform duration-300 group-hover:scale-110"
                        />
                        <div class="absolute -inset-2 bg-gradient-to-r from-teal-500 to-emerald-500/20 rounded-full blur-xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-sm font-bold tracking-wide bg-gradient-to-r from-white via-teal-100 to-emerald-200 bg-clip-text text-transparent">
                            AAIB
                        </span>
                        <span class="text-[10px] text-teal-200/80 hidden sm:block">Aircraft Accident Investigation Board</span>
                    </div>
                </Link>

                <!-- Desktop First Line Menu -->
                <div class="hidden lg:flex items-center space-x-4">
                   

                    <Link :href="route('jobVacancies')" class="block px-4 py-3 text-white hover:bg-teal-800/50 rounded-lg transition font-medium" @click="toggleMobileMenu">
                        Job Vacancies
                    </Link>

                    <Link :href="route('faq')" class="px-3 py-1.5 text-sm text-white hover:text-teal-300 transition font-medium rounded-lg hover:bg-white/10">
                        FAQs
                    </Link>

                      <Link :href="route('contactDetails')" class="px-3 py-1.5 text-sm text-white hover:text-teal-300 transition font-medium rounded-lg hover:bg-white/10">
                    Contact Us
                </Link>

                    <template v-if="$page.props.auth.user">
                        <Link :href="route('dashboard')" class="px-3 py-1.5 text-sm text-white hover:text-teal-300 transition font-medium rounded-lg hover:bg-white/10">
                            Dashboard
                        </Link>
                    </template>
                    <template v-else>
                        <Link v-if="canLogin" :href="route('login')" class="px-3 py-1.5 text-sm text-white hover:text-teal-300 transition font-medium rounded-lg hover:bg-white/10">
                            Login
                        </Link>
                        <Link v-if="canRegister" :href="route('register')" class="px-4 py-1.5 text-sm bg-gradient-to-r from-teal-600 to-emerald-600 text-white rounded-lg font-medium hover:shadow-lg hover:shadow-teal-500/30 transition-all">
                            Register
                        </Link>
                    </template>
                </div>

                <!-- Mobile Menu Button -->
                <button 
                    @click="toggleMobileMenu"
                    class="lg:hidden relative w-8 h-8 focus:outline-none"
                    aria-label="Toggle menu"
                >
                    <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-5">
                        <span 
                            class="absolute h-0.5 w-5 bg-gradient-to-r from-teal-500 to-emerald-500 transform transition-all duration-300"
                            :class="{ 'rotate-45 top-0': isMobileMenuOpen, '-translate-y-1.5': !isMobileMenuOpen }"
                            style="top: -6px"
                        ></span>
                        <span 
                            class="absolute h-0.5 w-5 bg-gradient-to-r from-teal-500 to-emerald-500 transition-all duration-300"
                            :class="{ 'opacity-0': isMobileMenuOpen }"
                            style="top: 0"
                        ></span>
                        <span 
                            class="absolute h-0.5 w-5 bg-gradient-to-r from-teal-500 to-emerald-500 transform transition-all duration-300"
                            :class="{ '-rotate-45 top-0': isMobileMenuOpen, 'translate-y-1.5': !isMobileMenuOpen }"
                            style="top: 6px"
                        ></span>
                    </div>
                </button>
            </div>

            <!-- Second Line: Home, About, Accident, Media, Contact us, Report Accident (Desktop Only) -->
            <div class="hidden lg:flex items-center justify-end space-x-6 mt-2 pt-2 border-t border-teal-500/20 relative z-10">
                <Link href="/" class="px-3 py-1.5 text-sm text-white hover:text-teal-300 transition font-medium rounded-lg hover:bg-white/10">
                    Home
                </Link>

                <!-- About Dropdown -->
                <div 
                    class="relative" 
                    @mouseenter="showDropdown('about')" 
                    @mouseleave="hideDropdown"
                >
                    <button class="px-3 py-1.5 text-sm text-white hover:text-teal-300 transition font-medium rounded-lg hover:bg-white/10 flex items-center gap-1">
                        About
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    
                    <div 
                        v-if="activeDropdown === 'about'" 
                        class="absolute top-full left-0 mt-1 w-56 bg-teal-900 backdrop-blur-xl rounded-lg shadow-2xl border border-teal-500/30 overflow-hidden z-[100]"
                        @mouseenter="cancelHideDropdown"
                        @mouseleave="hideDropdown"
                    >
                        <Link 
                            :href="route('managementTeam')"
                            class="block px-4 py-2 text-sm text-white hover:bg-teal-800/50 hover:text-teal-200 transition"
                            @click="handleDropdownClick"
                        >
                            Management Team
                        </Link>
                        <Link 
                            :href="route('aboutUs')"
                            class="block px-4 py-2 text-sm text-white hover:bg-teal-800/50 hover:text-teal-200 transition"
                            @click="handleDropdownClick"
                        >
                            Who We Are
                        </Link>
                        <!-- <Link 
                            :href="route('mandate')"
                            class="block px-4 py-2 text-sm text-white hover:bg-teal-800/50 hover:text-teal-200 transition"
                            @click="handleDropdownClick"
                        >
                            Mandate
                        </Link> -->
                        <Link 
                            :href="route('history')"
                            class="block px-4 py-2 text-sm text-white hover:bg-teal-800/50 hover:text-teal-200 transition"
                            @click="handleDropdownClick"
                        >
                            History
                        </Link>
                    </div>
                </div>

                <!-- Accidents Dropdown -->
                <div 
                    class="relative" 
                    @mouseenter="showDropdown('accidents')" 
                    @mouseleave="hideDropdown"
                >
                    <button class="px-3 py-1.5 text-sm text-white hover:text-teal-300 transition font-medium rounded-lg hover:bg-white/10 flex items-center gap-1">
                        Accidents
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    
                    <div 
                        v-if="activeDropdown === 'accidents'" 
                        class="absolute top-full left-0 mt-1 w-56 bg-teal-900 backdrop-blur-xl rounded-lg shadow-2xl border border-teal-500/30 z-[100]"
                        @mouseenter="cancelHideDropdown"
                        @mouseleave="hideDropdown"
                    >
                        <Link 
                            :href="route('documentRepository')"
                            class="block px-4 py-2 text-sm text-white hover:bg-teal-800/50 hover:text-teal-200 transition"
                            @click="handleDropdownClick"
                        >
                            Document Repository
                        </Link>
                        <Link 
                            :href="route('nationalRegulation')"
                            class="block px-4 py-2 text-sm text-white hover:bg-teal-800/50 hover:text-teal-200 transition"
                            @click="handleDropdownClick"
                        >
                            Regulation & Annexes
                        </Link>
                        <!-- <Link 
                            :href="route('investigationPage')"
                            class="block px-4 py-2 text-sm text-white hover:bg-teal-800/50 hover:text-teal-200 transition"
                            @click="handleDropdownClick"
                        >
                            Investigation Process
                        </Link> -->
                        <Link 
                            :href="route('accidentPage')"
                            class="block px-4 py-2 text-sm text-white hover:bg-teal-800/50 hover:text-teal-200 transition"
                            @click="handleDropdownClick"
                        >
                            Accident  Reports
                        </Link>
                    </div>
                </div>

                <!-- Media Dropdown -->
                <div 
                    class="relative" 
                    @mouseenter="showDropdown('media')" 
                    @mouseleave="hideDropdown"
                >
                    <button class="px-3 py-1.5 text-sm text-white hover:text-teal-300 transition font-medium rounded-lg hover:bg-white/10 flex items-center gap-1">
                        Media
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    
                    <div 
                        v-if="activeDropdown === 'media'" 
                        class="absolute top-full left-0 mt-1 w-56 bg-teal-900 backdrop-blur-xl rounded-lg shadow-2xl border border-teal-500/30 z-[100]"
                        @mouseenter="cancelHideDropdown"
                        @mouseleave="hideDropdown"
                    >
                        <Link 
                            :href="route('newsPage')"
                            href="/media/news" 
                            class="block px-4 py-2 text-sm text-white hover:bg-teal-800/50 hover:text-teal-200 transition"
                            @click="handleDropdownClick"
                        >
                            News
                        </Link>
                        <Link 
                            :href="route('pressReleasesPage')"
                            class="block px-4 py-2 text-sm text-white hover:bg-teal-800/50 hover:text-teal-200 transition"
                            @click="handleDropdownClick"
                        >
                            Press Releases
                        </Link>
                        <Link 
                           :href="route('announcementsPage')"
                            class="block px-4 py-2 text-sm text-white hover:bg-teal-800/50 hover:text-teal-200 transition"
                            @click="handleDropdownClick"
                        >
                            Announcements
                        </Link>
                    </div>
                </div>

                <!-- <Link href="/contact" class="px-3 py-1.5 text-sm text-white hover:text-teal-300 transition font-medium rounded-lg hover:bg-white/10">
                    Contact Us
                </Link> -->

                <Link :href="route('reportAccident')" class="px-4 py-1.5 text-sm bg-gradient-to-r from-teal-600 to-emerald-600 text-white rounded-lg font-medium hover:shadow-lg hover:shadow-teal-500/30 transition-all transform hover:-translate-y-0.5">
                    Report Accident
                </Link>
            </div>
        </div>
    </nav>

    <!-- Mobile Menu Dropdown -->
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
            class="fixed top-[72px] left-0 right-0 z-40 lg:hidden max-h-[calc(100vh-72px)] overflow-y-auto"
        >
            <div class="bg-teal-900/95 backdrop-blur-xl border-b border-teal-500/20 shadow-2xl">
                <div class="px-4 py-4 space-y-2">
                    <!-- First Section - Home -->
                    <Link href="/" class="block px-4 py-3 text-white hover:bg-teal-800/50 rounded-lg transition font-medium" @click="toggleMobileMenu">
                        Home
                    </Link>

                    <!-- About Dropdown -->
                    <div class="space-y-1">
                        <button 
                            @click="toggleMobileDropdown('about')"
                            class="w-full flex justify-between items-center px-4 py-3 text-white hover:bg-teal-800/50 rounded-lg transition font-medium"
                        >
                            <span>About</span>
                            <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': openMobileDropdown === 'about' }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div v-if="openMobileDropdown === 'about'" class="pl-6 space-y-1">
                            <Link :href="route('managementTeam')" class="block px-4 py-2 text-white/90 hover:bg-teal-800/50 rounded-lg transition" @click="toggleMobileMenu">
                                Management Team
                            </Link>
                            <Link :href="route('aboutUs')" class="block px-4 py-2 text-white/90 hover:bg-teal-800/50 rounded-lg transition" @click="toggleMobileMenu">
                                Who We Are
                            </Link>
                            <!-- <Link :href="route('mandate')" class="block px-4 py-2 text-white/90 hover:bg-teal-800/50 rounded-lg transition" @click="toggleMobileMenu">
                                Mandate
                            </Link> -->
                            <Link :href="route('history')" class="block px-4 py-2 text-white/90 hover:bg-teal-800/50 rounded-lg transition" @click="toggleMobileMenu">
                                History
                            </Link>
                        </div>
                    </div>

                    <!-- Accidents Dropdown -->
                    <div class="space-y-1">
                        <button 
                            @click="toggleMobileDropdown('accidents')"
                            class="w-full flex justify-between items-center px-4 py-3 text-white hover:bg-teal-800/50 rounded-lg transition font-medium"
                        >
                            <span>Accidents</span>
                            <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': openMobileDropdown === 'accidents' }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div v-if="openMobileDropdown === 'accidents'" class="pl-6 space-y-1">
                            <Link :href="route('documentRepository')" class="block px-4 py-2 text-white/90 hover:bg-teal-800/50 rounded-lg transition" @click="toggleMobileMenu">
                                Document Repository
                            </Link>
                            <Link :href="route('nationalRegulation')" class="block px-4 py-2 text-white/90 hover:bg-teal-800/50 rounded-lg transition" @click="toggleMobileMenu">
                                Regulation & Annexes
                            </Link>
                            <!-- <Link :href="route('investigationPage')" class="block px-4 py-2 text-white/90 hover:bg-teal-800/50 rounded-lg transition" @click="toggleMobileMenu">
                                Investigation Process
                            </Link> -->
                            <Link :href="route('accidentPage')" class="block px-4 py-2 text-white/90 hover:bg-teal-800/50 rounded-lg transition" @click="toggleMobileMenu">
                                Accident Reports
                            </Link>
                        </div>
                    </div>

                    <!-- Media Dropdown -->
                    <div class="space-y-1">
                        <button 
                            @click="toggleMobileDropdown('media')"
                            class="w-full flex justify-between items-center px-4 py-3 text-white hover:bg-teal-800/50 rounded-lg transition font-medium"
                        >
                            <span>Media</span>
                            <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': openMobileDropdown === 'media' }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div v-if="openMobileDropdown === 'media'" class="pl-6 space-y-1">
                            <Link :href="route('newsPage')" class="block px-4 py-2 text-white/90 hover:bg-teal-800/50 rounded-lg transition" @click="toggleMobileMenu">
                                News
                            </Link>
                            <Link :href="route('pressReleasesPage')" class="block px-4 py-2 text-white/90 hover:bg-teal-800/50 rounded-lg transition" @click="toggleMobileMenu">
                                Press Releases
                            </Link>
                            <Link :href="route('announcementsPage')" class="block px-4 py-2 text-white/90 hover:bg-teal-800/50 rounded-lg transition" @click="toggleMobileMenu">
                                Announcements
                            </Link>
                        </div>
                    </div>

                    <Link :href="route('contactDetails')" class="block px-4 py-3 text-white hover:bg-teal-800/50 rounded-lg transition font-medium" @click="toggleMobileMenu">
                        Contact Us
                    </Link>

                    <Link :href="route('reportAccident')" class="block px-4 py-3 text-white bg-gradient-to-r from-teal-600 to-emerald-600 rounded-lg transition font-medium text-center" @click="toggleMobileMenu">
                        Report Accident
                    </Link>

                    <Link :href="route('jobVacancies')" class="block px-4 py-3 text-white hover:bg-teal-800/50 rounded-lg transition font-medium" @click="toggleMobileMenu">
                        Job Vacancies
                    </Link>

                    <Link :href="route('faq')" class="block px-4 py-3 text-white hover:bg-teal-800/50 rounded-lg transition font-medium" @click="toggleMobileMenu">
                        FAQs
                    </Link>

                    <div class="border-t border-teal-500/30 my-3 pt-3 space-y-2">
                        <template v-if="$page.props.auth.user">
                            <Link :href="route('dashboard')" class="block px-4 py-3 text-white hover:bg-teal-800/50 rounded-lg transition" @click="toggleMobileMenu">
                                Dashboard
                            </Link>
                        </template>
                        <template v-else>
                            <Link v-if="canLogin" :href="route('login')" class="block px-4 py-3 text-white hover:bg-teal-800/50 rounded-lg transition" @click="toggleMobileMenu">
                                Login
                            </Link>
                            <Link v-if="canRegister" :href="route('register')" class="block px-4 py-3 text-white bg-gradient-to-r from-teal-600 to-emerald-600 rounded-lg transition text-center" @click="toggleMobileMenu">
                                Register
                            </Link>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </transition>
</template>

<style scoped>
/* Ensure dropdowns have proper stacking context */
nav {
    isolation: isolate;
}

/* Make sure dropdowns appear above all other content */
.absolute {
    z-index: 100 !important;
}

/* Ensure second line doesn't overlap dropdowns */
.relative {
    z-index: 1;
}

/* Add background opacity fix for dropdowns */
.bg-teal-900 {
    background-color: rgba(17, 94, 89, 0.95);
}

/* Custom scrollbar for mobile menu */
@media (max-width: 1023px) {
    .overflow-y-auto::-webkit-scrollbar {
        width: 6px;
    }
    
    .overflow-y-auto::-webkit-scrollbar-track {
        background: rgba(255, 255, 255, 0.1);
        border-radius: 3px;
    }
    
    .overflow-y-auto::-webkit-scrollbar-thumb {
        background: rgba(20, 184, 166, 0.5);
        border-radius: 3px;
    }
}
</style>