<!-- resources\js\Components\AppNavbar.vue -->
<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    canLogin: { type: Boolean, required: true },
    canRegister: { type: Boolean, required: true },
    authUser: { type: Object, default: null },
});

// State management
const servicesDropdownOpen = ref(false);
const mobileMenuOpen = ref(false);
const isScrolled = ref(false);
const activeLink = ref('home');

// Services menu items for branding company
// const services = [
//     { name: 'Digital Printing', href: route('services.digital-printing') },
//     { name: 'Embroidery', href: route('services.embroidery') },
//     { name: 'Screen Printing', href: route('services.screen-printing') },
//     { name: 'Brand Identity', href: route('services.brand-identity') },
//     { name: 'Packaging Design', href: route('services.packaging') },
//     { name: 'Promotional Products', href: route('services.promotional') },
//     { name: 'Large Format Printing', href: route('services.large-format') },
// ];

// Handle scroll effect
const handleScroll = () => {
    isScrolled.value = window.scrollY > 20;
    // Update active link based on scroll position
    const sections = ['home', 'services', 'gallery', 'contact'];
    sections.forEach(section => {
        const element = document.getElementById(section);
        if (element) {
            const rect = element.getBoundingClientRect();
            if (rect.top <= 100 && rect.bottom >= 100) {
                activeLink.value = section;
            }
        }
    });
};

// Dropdown functions
const toggleServicesDropdown = () => servicesDropdownOpen.value = !servicesDropdownOpen.value;
const openServicesDropdown = () => servicesDropdownOpen.value = true;
const closeServicesDropdown = () => servicesDropdownOpen.value = false;

// Smooth scroll to section
const scrollToSection = (sectionId) => {
    const element = document.getElementById(sectionId);
    if (element) {
        window.scrollTo({
            top: element.offsetTop - 80,
            behavior: 'smooth'
        });
    }
    mobileMenuOpen.value = false;
    closeServicesDropdown();
};

// Outside click handler
const handleClickOutside = (event) => {
    if (!mobileMenuOpen.value && !event.target.closest('.nav-dropdown') && !event.target.closest('.mobile-dropdown-container')) {
        closeServicesDropdown();
    }
};

onMounted(() => {
    document.addEventListener('click', handleClickOutside);
    window.addEventListener('scroll', handleScroll);
    handleScroll(); // Initial check
});

onBeforeUnmount(() => {
    document.removeEventListener('click', handleClickOutside);
    window.removeEventListener('scroll', handleScroll);
});
</script>

<template>
    <header 
        class="fixed w-full z-50 transition-all duration-500 ease-out"
        :class="{
            'bg-white/95 backdrop-blur-xl shadow-2xl py-3 border-b border-gray-100': isScrolled,
            'bg-gradient-to-b from-white via-white/90 to-transparent py-5': !isScrolled
        }"
    >
        <div class="container mx-auto px-6 lg:px-8 flex justify-between items-center">
            <!-- Professional Logo - Using your logo.svg -->
            <Link href="/" class="flex items-center group relative">
                <div class="relative">
                    <div class="absolute -inset-1 bg-gradient-to-r from-blue-600 to-purple-600 rounded-lg blur opacity-30 group-hover:opacity-100 transition duration-300"></div>
                    <div class="relative bg-white p-2 rounded-lg shadow-lg flex items-center justify-center">
                        <img 
                            src="/assets/logo.svg" 
                            alt="Marz Innovations Logo" 
                            class="h-8 w-8 transition-transform duration-300 group-hover:scale-110"
                        />
                    </div>
                </div>
                <div class="flex flex-col ml-3">
                    <span class="font-bold text-xl bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                        Marz Innovations
                    </span>
                    <span class="text-xs text-gray-500 font-medium">Marz Innovations Limited</span>
                </div>
            </Link>

            <!-- Desktop Navigation -->
            <nav class="hidden lg:flex items-center space-x-1">
                <button 
                    @click="scrollToSection('home')"
                    class="nav-link px-5 py-3 rounded-xl relative group"
                    :class="{ 'active-link': activeLink === 'home' }"
                >
                    <span class="relative z-10">Home</span>
                    <span class="absolute inset-0 bg-gradient-to-r from-blue-50 to-purple-50 rounded-xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></span>
                </button>

                <Link 
                    :href="route('aboutUs')" 
                    class="nav-link px-5 py-3 rounded-xl relative group"
                    :class="{ 'active-link': $page.url.startsWith('/about') }"
                >
                    <span class="relative z-10">About Us</span>
                    <span class="absolute inset-0 bg-gradient-to-r from-blue-50 to-purple-50 rounded-xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></span>
                </Link>

                <!-- Services Dropdown -->
                <div 
                    class="nav-dropdown relative"
                    @mouseenter="openServicesDropdown"
                    @mouseleave="closeServicesDropdown"
                >
                    <button 
                        class="nav-link px-5 py-3 rounded-xl relative group inline-flex items-center"
                        :class="{ 'active-link': activeLink === 'services' }"
                    >
                        <span class="relative z-10">Services</span>
                        <svg 
                            class="ml-2 h-4 w-4 relative z-10 transition-transform duration-300" 
                            :class="{ 'rotate-180': servicesDropdownOpen }"
                            xmlns="http://www.w3.org/2000/svg" 
                            viewBox="0 0 20 20" 
                            fill="currentColor"
                        >
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                        <span class="absolute inset-0 bg-gradient-to-r from-blue-50 to-purple-50 rounded-xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></span>
                    </button>
                    
                    <transition
                        enter-active-class="transition-all duration-300 ease-out"
                        enter-from-class="opacity-0 -translate-y-2"
                        enter-to-class="opacity-100 translate-y-0"
                        leave-active-class="transition-all duration-200 ease-in"
                        leave-from-class="opacity-100 translate-y-0"
                        leave-to-class="opacity-0 -translate-y-2"
                    >
                        <div 
                            v-if="servicesDropdownOpen" 
                            class="absolute left-0 mt-2 w-64 origin-top-left rounded-2xl bg-white shadow-2xl ring-2 ring-gray-100 focus:outline-none z-50 overflow-hidden"
                        >
                            <div class="p-2">
                                <div v-for="service in services" :key="service.name" class="relative">
                                    <Link 
                                        :href="service.href" 
                                        class="dropdown-item group flex w-full items-center rounded-xl px-4 py-3 text-sm"
                                        @click="closeServicesDropdown"
                                    >
                                        <div class="flex items-center space-x-3">
                                            <div class="w-8 h-8 flex items-center justify-center rounded-lg bg-gradient-to-br from-blue-100 to-purple-100">
                                                <svg class="w-4 h-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            </div>
                                            <span class="text-gray-700 group-hover:text-blue-600 transition-colors font-medium">{{ service.name }}</span>
                                        </div>
                                    </Link>
                                </div>
                                <div class="px-4 py-3 mt-2 border-t border-gray-100">
                                    <Link 
                                        :href="route('servicesList')" 
                                        class="text-sm font-semibold text-blue-600 hover:text-blue-700 flex items-center"
                                        @click="closeServicesDropdown"
                                    >
                                        View All Services
                                        <svg class="ml-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </transition>
                </div>

                <Link 
                    :href="route('contactDetails')" 
                    class="nav-link px-5 py-3 rounded-xl relative group"
                    :class="{ 'active-link': $page.url.startsWith('/contact') }"
                >
                    <span class="relative z-10">Contact</span>
                    <span class="absolute inset-0 bg-gradient-to-r from-blue-50 to-purple-50 rounded-xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></span>
                </Link>

                <!-- Portfolio/Projects Link -->
                <!-- <button 
                    @click="scrollToSection('gallery')"
                    class="nav-link px-5 py-3 rounded-xl relative group"
                    :class="{ 'active-link': activeLink === 'gallery' }"
                >
                    <span class="relative z-10">Portfolio</span>
                    <span class="absolute inset-0 bg-gradient-to-r from-blue-50 to-purple-50 rounded-xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></span>
                </button> -->

                <!-- Auth Buttons Section -->
                <div class="flex items-center space-x-3 ml-6">
                    <!-- Login/Register or Dashboard -->
                    <template v-if="canLogin">
                        <template v-if="authUser">
                            <!-- Dashboard Button -->
                            <Link
                                :href="route('dashboard')"
                                class="px-6 py-3 rounded-xl bg-gradient-to-r from-blue-600 to-purple-600 text-white font-semibold shadow-lg hover:shadow-xl hover:scale-105 transform transition-all duration-300 flex items-center space-x-2"
                            >
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                                </svg>
                                <span>Dashboard</span>
                            </Link>
                        </template>
                        <template v-else>
                            <!-- Login Button -->
                            <Link
                                :href="route('login')"
                                class="px-6 py-3 rounded-xl border-2 border-blue-500 text-blue-600 font-semibold hover:bg-blue-50 hover:border-blue-600 transition-all duration-300 flex items-center space-x-2 group"
                            >
                                <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                                </svg>
                                <span>Log In</span>
                            </Link>
                            
                            <!-- Register Button -->
                            <Link
                                v-if="canRegister"
                                :href="route('register')"
                                class="px-6 py-3 rounded-xl bg-gradient-to-r from-green-500 to-emerald-600 text-white font-semibold shadow-lg hover:shadow-xl hover:scale-105 transform transition-all duration-300 flex items-center space-x-2"
                            >
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                                </svg>
                                <span>Register</span>
                            </Link>
                        </template>
                    </template>
                    
                    <!-- Get Quote Button -->
                    <a 
                        href="tel:+260966390807"
                        class="px-6 py-3 rounded-xl bg-gradient-to-r from-blue-600 to-purple-600 text-white font-semibold shadow-lg hover:shadow-xl hover:scale-105 transform transition-all duration-300 flex items-center space-x-2"
                    >
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                        </svg>
                        <span>Get Quote</span>
                    </a>
                </div>
            </nav>

            <!-- Mobile Menu Button -->
            <button 
                class="lg:hidden p-3 rounded-xl bg-gradient-to-r from-gray-50 to-white shadow-lg hover:shadow-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500"
                @click="mobileMenuOpen = !mobileMenuOpen"
                :aria-expanded="mobileMenuOpen"
            >
                <div class="relative w-6 h-6">
                    <span class="absolute top-1/2 left-1/2 w-6 h-0.5 bg-gradient-to-r from-blue-600 to-purple-600 transform -translate-x-1/2 -translate-y-1/2 transition-all duration-300"
                        :class="{ 'rotate-45': mobileMenuOpen, '-translate-y-2': !mobileMenuOpen }"></span>
                    <span class="absolute top-1/2 left-1/2 w-6 h-0.5 bg-gradient-to-r from-blue-600 to-purple-600 transform -translate-x-1/2 -translate-y-1/2 transition-all duration-300"
                        :class="{ 'opacity-0': mobileMenuOpen }"></span>
                    <span class="absolute top-1/2 left-1/2 w-6 h-0.5 bg-gradient-to-r from-blue-600 to-purple-600 transform -translate-x-1/2 -translate-y-1/2 transition-all duration-300"
                        :class="{ '-rotate-45': mobileMenuOpen, 'translate-y-2': !mobileMenuOpen }"></span>
                </div>
            </button>
        </div>

        <!-- Mobile Navigation Menu -->
        <transition
            enter-active-class="transition-all duration-300 ease-out"
            enter-from-class="opacity-0 -translate-y-4"
            enter-to-class="opacity-100 translate-y-0"
            leave-active-class="transition-all duration-200 ease-in"
            leave-from-class="opacity-100 translate-y-0"
            leave-to-class="opacity-0 -translate-y-4"
        >
            <div 
                v-if="mobileMenuOpen" 
                class="lg:hidden bg-white/95 backdrop-blur-xl shadow-2xl border-t border-gray-100 z-40"
            >
                <div class="container mx-auto px-6 py-6">
                    <div class="space-y-2">
                        <button 
                            @click="scrollToSection('home')"
                            class="mobile-nav-link w-full text-left px-4 py-4 rounded-xl flex items-center justify-between group"
                        >
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 flex items-center justify-center rounded-lg bg-gradient-to-br from-blue-50 to-purple-50">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                    </svg>
                                </div>
                                <span class="font-medium">Home</span>
                            </div>
                            <svg class="w-5 h-5 text-gray-400 group-hover:text-blue-600 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </button>

                        <Link 
                            :href="route('aboutUs')" 
                            class="mobile-nav-link w-full text-left px-4 py-4 rounded-xl flex items-center justify-between group"
                            @click="mobileMenuOpen = false"
                        >
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 flex items-center justify-center rounded-lg bg-gradient-to-br from-blue-50 to-purple-50">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <span class="font-medium">About Us</span>
                            </div>
                            <svg class="w-5 h-5 text-gray-400 group-hover:text-blue-600 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </Link>

                        <!-- Mobile Services Dropdown -->
                        <div class="px-4 py-4 rounded-xl mobile-dropdown-container" @click.stop>
                            <button 
                                @click="toggleServicesDropdown" 
                                class="mobile-nav-link w-full text-left flex justify-between items-center"
                            >
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 flex items-center justify-center rounded-lg bg-gradient-to-br from-blue-50 to-purple-50">
                                        <svg class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                        </svg>
                                    </div>
                                    <span class="font-medium">Services</span>
                                </div>
                                <svg 
                                    class="h-5 w-5 text-gray-400 transition-transform duration-300" 
                                    :class="{ 'rotate-180': servicesDropdownOpen }"
                                    xmlns="http://www.w3.org/2000/svg" 
                                    viewBox="0 0 20 20" 
                                    fill="currentColor"
                                >
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                            
                            <transition
                                enter-active-class="transition-all duration-300 ease-out"
                                enter-from-class="opacity-0 -translate-y-2"
                                enter-to-class="opacity-100 translate-y-0"
                                leave-active-class="transition-all duration-200 ease-in"
                                leave-from-class="opacity-100 translate-y-0"
                                leave-to-class="opacity-0 -translate-y-2"
                            >
                                <div 
                                    v-if="servicesDropdownOpen" 
                                    class="pl-12 mt-3 space-y-2 mobile-dropdown-content"
                                    @click.stop
                                >
                                    <div v-for="service in services.slice(0, 4)" :key="service.name">
                                        <Link 
                                            :href="service.href" 
                                            class="block px-3 py-2.5 text-gray-600 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all duration-200"
                                            @click="mobileMenuOpen = false; servicesDropdownOpen = false"
                                        >
                                            {{ service.name }}
                                        </Link>
                                    </div>
                                    <Link 
                                        :href="route('servicesList')" 
                                        class="block px-3 py-2.5 text-blue-600 font-semibold hover:bg-blue-50 rounded-lg transition-all duration-200"
                                        @click="mobileMenuOpen = false; servicesDropdownOpen = false"
                                    >
                                        View All Services →
                                    </Link>
                                </div>
                            </transition>
                        </div>

                        <!-- Contact and Portfolio -->
                        <Link 
                            :href="route('contactDetails')" 
                            class="mobile-nav-link w-full text-left px-4 py-4 rounded-xl flex items-center justify-between group"
                            @click="mobileMenuOpen = false"
                        >
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 flex items-center justify-center rounded-lg bg-gradient-to-br from-blue-50 to-purple-50">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                    </svg>
                                </div>
                                <span class="font-medium">Contact</span>
                            </div>
                            <svg class="w-5 h-5 text-gray-400 group-hover:text-blue-600 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </Link>

                        <button 
                            @click="scrollToSection('gallery')"
                            class="mobile-nav-link w-full text-left px-4 py-4 rounded-xl flex items-center justify-between group"
                        >
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 flex items-center justify-center rounded-lg bg-gradient-to-br from-blue-50 to-purple-50">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <span class="font-medium">Portfolio</span>
                            </div>
                            <svg class="w-5 h-5 text-gray-400 group-hover:text-blue-600 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </button>

                        <!-- Mobile Auth Buttons -->
                        <div class="pt-4 mt-4 border-t border-gray-100 space-y-3">
                            <template v-if="canLogin">
                                <template v-if="authUser">
                                    <!-- Mobile Dashboard Button -->
                                    <Link
                                        :href="route('dashboard')"
                                        class="w-full px-4 py-4 rounded-xl bg-gradient-to-r from-blue-600 to-purple-600 text-white font-semibold shadow-lg hover:shadow-xl transition-all duration-300 flex items-center justify-center space-x-3"
                                        @click="mobileMenuOpen = false"
                                    >
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                                        </svg>
                                        <span>Dashboard</span>
                                    </Link>
                                </template>
                                <template v-else>
                                    <!-- Mobile Login Button -->
                                    <Link
                                        :href="route('login')"
                                        class="w-full px-4 py-4 rounded-xl border-2 border-blue-500 text-blue-600 font-semibold hover:bg-blue-50 transition-all duration-300 flex items-center justify-center space-x-3"
                                        @click="mobileMenuOpen = false"
                                    >
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                                        </svg>
                                        <span>Log In</span>
                                    </Link>
                                    
                                    <!-- Mobile Register Button -->
                                    <Link
                                        v-if="canRegister"
                                        :href="route('register')"
                                        class="w-full px-4 py-4 rounded-xl bg-gradient-to-r from-green-500 to-emerald-600 text-white font-semibold shadow-lg hover:shadow-xl transition-all duration-300 flex items-center justify-center space-x-3"
                                        @click="mobileMenuOpen = false"
                                    >
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                                        </svg>
                                        <span>Register</span>
                                    </Link>
                                </template>
                            </template>
                            
                            <!-- Mobile Get Quote Button -->
                            <a 
                                href="tel:+260966390807"
                                class="w-full px-4 py-4 rounded-xl bg-gradient-to-r from-blue-600 to-purple-600 text-white font-semibold shadow-lg hover:shadow-xl transition-all duration-300 flex items-center justify-center space-x-3"
                                @click="mobileMenuOpen = false"
                            >
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                                <span>Get Instant Quote</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </transition>
    </header>
</template>

<style scoped>
/* Navigation Link Styles */
.nav-link {
    color: #4b5563; /* Tailwind text-gray-600 */
    font-weight: 500;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
}

.nav-link:hover {
    color: #1e40af; /* Tailwind text-blue-800 */
}

.active-link {
    color: #1d4ed8; /* Tailwind text-blue-700 */
    font-weight: 600;
}

.active-link::before {
    content: '';
    position: absolute;
    bottom: 0;
    left: 50%;
    transform: translateX(-50%);
    width: 24px;
    height: 3px;
    background: linear-gradient(to right, #3b82f6, #8b5cf6);
    border-radius: 2px;
    animation: slideIn 0.3s ease-out;
}

@keyframes slideIn {
    from {
        width: 0;
        opacity: 0;
    }
    to {
        width: 24px;
        opacity: 1;
    }
}

.mobile-nav-link {
    color: #374151; /* Tailwind text-gray-700 */
    font-weight: 500;
    transition: all 0.2s ease;
    border-radius: 0.75rem;
}

.mobile-nav-link:hover {
    background: linear-gradient(to right, rgba(59, 130, 246, 0.05), rgba(139, 92, 246, 0.05));
    color: #1d4ed8;
}

/* Dropdown Item Styles */
.dropdown-item {
    transition: all 0.2s ease;
    border-radius: 0.75rem;
    margin: 2px 0;
}

.dropdown-item:hover {
    background: linear-gradient(to right, #eff6ff, #f5f3ff);
    transform: translateX(4px);
}

/* Smooth transitions */
header {
    will-change: transform, background-color, box-shadow, backdrop-filter;
    backdrop-filter: blur(20px);
}

/* Better focus states */
button:focus, a:focus {
    outline: none;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.3);
}

/* Auth button hover effects */
.auth-button-hover:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px -5px rgba(59, 130, 246, 0.3);
}

/* Logo hover animation */
.logo-container:hover img {
    transform: rotate(-5deg) scale(1.1);
}

.logo-container:hover .logo-glow {
    opacity: 0.5;
    filter: blur(20px);
}
</style>