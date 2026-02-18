<template>
    <section id="home" class="relative text-white overflow-hidden min-h-screen flex items-center">
        <!-- Animated Background -->
        <div class="absolute inset-0 z-0">
            <!-- Main background -->
            <div class="absolute inset-0 bg-gradient-to-br from-gray-900 via-blue-900 to-purple-900"></div>
            
            <!-- Animated particles -->
            <div class="absolute inset-0 overflow-hidden">
                <div v-for="i in 30" :key="`particle-${i}`" 
                     class="absolute rounded-full bg-white/10 animate-pulse"
                     :style="{
                         width: `${Math.random() * 4 + 1}px`,
                         height: `${Math.random() * 4 + 1}px`,
                         left: `${Math.random() * 100}%`,
                         top: `${Math.random() * 100}%`,
                         animationDelay: `${Math.random() * 2}s`,
                         animationDuration: `${Math.random() * 3 + 2}s`
                     }"></div>
            </div>
            
            <!-- Gradient mesh overlay -->
            <div class="absolute inset-0 opacity-30"
                 style="background-image: radial-gradient(circle at 20% 80%, rgba(59, 130, 246, 0.3) 0%, transparent 50%),
                        radial-gradient(circle at 80% 20%, rgba(139, 92, 246, 0.3) 0%, transparent 50%),
                        radial-gradient(circle at 40% 40%, rgba(255, 255, 255, 0.1) 0%, transparent 50%);"></div>
        </div>

        <!-- Image slider with professional content -->
        <div class="absolute inset-0 z-10 overflow-hidden">
            <transition-group name="slide-fade" tag="div" class="relative w-full h-full">
                <div 
                    v-for="(slide, index) in slides"
                    :key="slide.id"
                    v-show="currentIndex === index"
                    class="absolute inset-0 w-full h-full"
                >
                    <!-- Background image with overlay -->
                    <div class="absolute inset-0">
                        <img
                            :src="slide.image"
                            :alt="slide.title"
                            class="w-full h-full object-cover"
                            loading="lazy"
                        />
                        <div class="absolute inset-0 bg-gradient-to-r from-gray-900/90 via-gray-900/70 to-transparent"></div>
                        <div class="absolute inset-0 bg-gradient-to-t from-gray-900 via-transparent to-transparent"></div>
                    </div>
                </div>
            </transition-group>
        </div>

        <!-- Hero Content - Aligned to left -->
        <div class="container mx-auto px-6 lg:px-8 relative z-20 pt-24 lg:pt-32">
            <div class="max-w-2xl lg:max-w-3xl">
                <!-- Badge with animation - Left aligned -->
                <div class="inline-flex items-center space-x-2 mb-6 lg:mb-8 animate-fade-in-up" style="animation-delay: 0.2s">
                    <span class="w-8 lg:w-12 h-0.5 bg-gradient-to-r from-blue-400 to-purple-400"></span>
                    <span class="text-xs lg:text-sm font-semibold tracking-wider uppercase bg-gradient-to-r from-blue-400 to-purple-400 bg-clip-text text-transparent">
                        CAA QR GENERATOR
                    </span>
                    <span class="w-8 lg:w-12 h-0.5 bg-gradient-to-r from-blue-400 to-purple-400"></span>
                </div>

                <!-- Main title with typing effect - Left aligned -->
                <div class="mb-6 lg:mb-8 text-left">
                    <h1 class="text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-bold leading-tight">
                        <span class="block animate-fade-in-up" style="animation-delay: 0.4s">
                            E-Business
                        </span>
                        <span class="block bg-gradient-to-r from-blue-400 via-purple-400 to-pink-400 bg-clip-text text-transparent animate-gradient-shift mt-2 lg:mt-4">
                            Card Generation
                        </span>
                    </h1>
                </div>

                <!-- Subtitle - Left aligned -->
                <p class="text-base sm:text-lg md:text-xl lg:text-2xl text-gray-200 mb-8 lg:mb-10 max-w-xl animate-fade-in-up text-left" style="animation-delay: 0.6s">
                    Professional printing, embroidery, and branding solutions that make your business stand out.
                </p>

                <!-- User Login Section - Left aligned -->
                <div class="animate-fade-in-up text-left" style="animation-delay: 1s">
                    <div class="inline-flex items-center space-x-2 bg-white/5 backdrop-blur-sm border border-white/10 rounded-full px-4 sm:px-5 lg:px-6 py-2 sm:py-2 lg:py-3">
                        <svg class="w-3 h-3 sm:w-4 sm:h-4 lg:w-5 lg:h-5 text-blue-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                        <span class="text-gray-300 text-xs sm:text-sm">Existing User?</span>
                        <Link 
                            :href="route('login')" 
                            class="text-blue-300 hover:text-white font-semibold text-xs sm:text-sm transition-colors"
                        >
                            Log In →
                        </Link>
                        <span class="text-gray-400 mx-1">|</span>
                        <Link 
                            :href="route('register')" 
                            class="text-emerald-300 hover:text-white font-semibold text-xs sm:text-sm transition-colors"
                        >
                            Register
                        </Link>
                    </div>
                </div>

                <!-- Stats - Left aligned -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 sm:gap-5 lg:gap-6 mt-12 sm:mt-14 lg:mt-16 animate-fade-in-up text-left" style="animation-delay: 1.2s">
                    <div v-for="stat in stats" :key="stat.label" class="text-left">
                        <div class="text-xl sm:text-2xl md:text-3xl lg:text-4xl font-bold bg-gradient-to-r from-blue-400 to-purple-400 bg-clip-text text-transparent mb-1 sm:mb-2">
                            {{ stat.value }}
                        </div>
                        <div class="text-xs sm:text-sm text-gray-300 uppercase tracking-wider">
                            {{ stat.label }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Slider controls - Centered at bottom -->
        <div class="absolute bottom-4 sm:bottom-6 lg:bottom-8 left-1/2 transform -translate-x-1/2 z-20">
            <div class="flex items-center space-x-3 sm:space-x-4">
                <button 
                    @click="prevSlide"
                    class="w-8 h-8 sm:w-10 sm:h-10 lg:w-12 lg:h-12 rounded-full bg-white/10 backdrop-blur-sm border border-white/20 flex items-center justify-center hover:bg-white/20 transition-all duration-300 group"
                >
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 lg:w-6 lg:h-6 text-white transform group-hover:-translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>
                
                <!-- Slide indicators -->
                <div class="flex items-center space-x-1 sm:space-x-2">
                    <button 
                        v-for="(slide, index) in slides"
                        :key="`indicator-${slide.id}`"
                        @click="goToSlide(index)"
                        class="relative"
                    >
                        <div class="w-2 h-2 sm:w-2 sm:h-2 lg:w-3 lg:h-3 rounded-full bg-white/30 transition-all duration-300"
                             :class="{ 'w-4 sm:w-5 lg:w-8 bg-white': currentIndex === index }"></div>
                    </button>
                </div>
                
                <button 
                    @click="nextSlide"
                    class="w-8 h-8 sm:w-10 sm:h-10 lg:w-12 lg:h-12 rounded-full bg-white/10 backdrop-blur-sm border border-white/20 flex items-center justify-center hover:bg-white/20 transition-all duration-300 group"
                >
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 lg:w-6 lg:h-6 text-white transform group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Scroll indicator -->
        <div class="absolute bottom-20 sm:bottom-24 lg:bottom-32 left-1/2 transform -translate-x-1/2 z-20 animate-bounce hidden lg:block">
            <div class="flex flex-col items-center">
                <span class="text-xs sm:text-sm text-gray-300 mb-1 sm:mb-2">Scroll to explore</span>
                <svg class="w-3 h-3 sm:w-4 sm:h-4 lg:w-6 lg:h-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                </svg>
            </div>
        </div>
    </section>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import { Link } from '@inertiajs/vue3';

const slides = ref([
    {
        id: 1,
        image: 'https://images.unsplash.com/photo-1589939705384-5185137a7f0f?auto=format&fit=crop&w=1600&q=80',
        title: 'Premium Printing Solutions',
        subtitle: 'High-quality digital and large format printing'
    },
    {
        id: 2,
        image: 'https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?auto=format&fit=crop&w=1600&q=80',
        title: 'Custom Embroidery',
        subtitle: 'Professional embroidery for uniforms and merchandise'
    },

]);

const stats = ref([
    { value: '500+', label: 'Happy Users' },
    { value: '50k+', label: 'Cards Generated' },
    { value: '24/7', label: 'User Support' }
]);

const currentIndex = ref(0);
let intervalId = null;
let touchStartX = 0;
let touchEndX = 0;

const nextSlide = () => {
    currentIndex.value = (currentIndex.value + 1) % slides.value.length;
};

const prevSlide = () => {
    currentIndex.value = (currentIndex.value - 1 + slides.value.length) % slides.value.length;
};

const goToSlide = (index) => {
    currentIndex.value = index;
};

const scrollToContact = () => {
    const contactSection = document.getElementById('contact');
    if (contactSection) {
        contactSection.scrollIntoView({ behavior: 'smooth' });
    }
};

// Touch swipe handling
const handleTouchStart = (e) => {
    touchStartX = e.changedTouches[0].screenX;
};

const handleTouchEnd = (e) => {
    touchEndX = e.changedTouches[0].screenX;
    handleSwipe();
};

const handleSwipe = () => {
    const swipeThreshold = 50;
    const diff = touchStartX - touchEndX;

    if (Math.abs(diff) > swipeThreshold) {
        if (diff > 0) {
            nextSlide();
        } else {
            prevSlide();
        }
    }
};

onMounted(() => {
    intervalId = setInterval(nextSlide, 8000);
    
    // Add touch event listeners
    const slider = document.querySelector('.relative.w-full.h-full');
    if (slider) {
        slider.addEventListener('touchstart', handleTouchStart);
        slider.addEventListener('touchend', handleTouchEnd);
    }
});

onUnmounted(() => {
    if (intervalId) clearInterval(intervalId);
    
    // Remove touch event listeners
    const slider = document.querySelector('.relative.w-full.h-full');
    if (slider) {
        slider.removeEventListener('touchstart', handleTouchStart);
        slider.removeEventListener('touchend', handleTouchEnd);
    }
});
</script>

<style scoped>
/* Slide transition animations */
.slide-fade-enter-active,
.slide-fade-leave-active {
    transition: all 1.2s cubic-bezier(0.83, 0, 0.17, 1);
}

.slide-fade-enter-from {
    opacity: 0;
    transform: translateX(100%) scale(1.1);
}

.slide-fade-enter-to {
    opacity: 1;
    transform: translateX(0) scale(1);
}

.slide-fade-leave-from {
    opacity: 1;
    transform: translateX(0) scale(1);
}

.slide-fade-leave-to {
    opacity: 0;
    transform: translateX(-100%) scale(0.9);
}

/* Custom animations */
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

.animate-fade-in-up {
    animation: fadeInUp 0.8s cubic-bezier(0.33, 1, 0.68, 1) forwards;
    opacity: 0;
}

@keyframes gradientShift {
    0%, 100% {
        background-position: 0% 50%;
    }
    50% {
        background-position: 100% 50%;
    }
}

.animate-gradient-shift {
    background-size: 200% 200%;
    animation: gradientShift 3s ease infinite;
}

/* Particle animation */
@keyframes pulse {
    0%, 100% {
        opacity: 0.1;
        transform: scale(1);
    }
    50% {
        opacity: 0.3;
        transform: scale(1.2);
    }
}

.animate-pulse {
    animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

/* Ensure content is below navbar */
.min-h-screen {
    min-height: 100vh;
}

/* Left alignment */
.text-left {
    text-align: left !important;
}

/* Darker gradient for better text visibility */
.bg-gradient-to-r {
    background-image: linear-gradient(to right, var(--tw-gradient-stops));
}

/* Smooth scroll behavior */
html {
    scroll-behavior: smooth;
}

/* Responsive adjustments */
@media (max-width: 640px) {
    .container {
        padding-left: 1.5rem !important;
        padding-right: 1.5rem !important;
    }
}
</style>





<!-- <template>
    <section class="bg-hero-purple text-white py-20 relative overflow-hidden">
        <div class="container mx-auto px-5 flex flex-col md:flex-row items-center justify-between gap-10 text-center md:text-left">
            <div class="flex-1 max-w-xl z-10">
                <p class="bg-white bg-opacity-20 inline-block px-4 py-1 rounded-full text-sm mb-5">Creating Value Together!</p>
                <h1 class="text-5xl md:text-6xl font-bold leading-tight mb-5">Marz Innovations <span class="text-[#92e623]">Limited,</span></h1>
                <p class="text-lg leading-relaxed mb-8">
                    We are a Digital and Branding company that strives to grow the industry by providing quality products and services.</p>
                <div class="flex flex-col sm:flex-row justify-center md:justify-start gap-5">
                    <button class="btn-primary">Get Instant Price</button>
                    <a href="tel:3033094226" class="btn-secondary">(+260) 966 390 807</a>
                </div>
            </div>

            <div class="flex-1 text-right relative md:static -right-24 -top-5 md:right-0 md:top-0 md:max-w-md">
                <div class="relative w-full h-96 overflow-hidden rounded-lg">
                    <transition-group name="slide" tag="div" class="absolute inset-0">
                        <img
                            v-for="(image, index) in images"
                            :key="image"
                            :src="image"
                            :alt="`Cleaning professional ${index + 1}`"
                            v-show="index === currentIndex"
                            class="absolute inset-0 w-full h-full object-cover rounded-lg"
                        >
                    </transition-group>
                </div>
            </div>
        </div>
    </section>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';

const images = ref([
    '/assets/hero/five.png',
    '/assets/hero/two.png', 
    '/assets/hero/three.png', 
]);

const currentIndex = ref(0);
let intervalId = null; 

const nextImage = () => {
    currentIndex.value = (currentIndex.value + 1) % images.value.length;
};

onMounted(() => {
    intervalId = setInterval(nextImage, 5000);
});

onUnmounted(() => {
    if (intervalId) {
        clearInterval(intervalId);
    }
});
</script>

<style scoped>
/* Slide transition */
.slide-enter-active,
.slide-leave-active {
  transition: all 1s ease;
}

.slide-enter-from {
  transform: translateX(100%);
  opacity: 0;
}

.slide-enter-to {
  transform: translateX(0);
  opacity: 1;
}

.slide-leave-from {
  transform: translateX(0);
  opacity: 1;
}

.slide-leave-to {
  transform: translateX(-100%);
  opacity: 0;
}

.slide-leave-active {
  position: absolute;
}
</style> -->