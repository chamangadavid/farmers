<!-- resources/js/Components/PartnersSlider.vue -->
<script setup>
import { ref, onMounted, onUnmounted } from 'vue';

// Define partners data with their logos
const partners = [
  {
    id: 1,
    name: 'ITU',
    logo: '/assets/partners/itu-logo.png',
    description: 'International Telecommunication Union'
  },
  {
    id: 2,
    name: 'Partner 1',
    logo: '/assets/partners/1.png',
    description: 'Strategic Partner'
  },
  {
    id: 3,
    name: 'Partner 2',
    logo: '/assets/partners/2.png',
    description: 'Collaboration Partner'
  },
  {
    id: 4,
    name: 'Partner 3',
    logo: '/assets/partners/3.png',
    description: 'Technical Partner'
  },
  {
    id: 5,
    name: 'Partner 4',
    logo: '/assets/partners/4.png',
    description: 'Industry Partner'
  },
  {
    id: 6,
    name: 'Partner 6',
    logo: '/assets/partners/6.png',
    description: 'Aviation Partner'
  },
  {
    id: 7,
    name: 'CAA',
    logo: '/assets/partners/caa_main-logo.png',
    description: 'Civil Aviation Authority'
  }
];

// Double the array for seamless infinite scroll effect
const duplicatedPartners = [...partners, ...partners];

const currentIndex = ref(0);
const isPaused = ref(false);
let intervalId = null;
let animationFrameId = null;

// Auto-scroll settings
const scrollSpeed = ref(1.5); // pixels per frame - slower for smoother mobile experience
const containerWidth = ref(0);
const contentWidth = ref(0);
const scrollPosition = ref(0);
const isMobile = ref(false);

// Refs for DOM elements
const sliderContainer = ref(null);
const sliderContent = ref(null);

// Check if device is mobile
const checkMobile = () => {
  isMobile.value = window.innerWidth < 768;
  // Adjust scroll speed for mobile
  scrollSpeed.value = isMobile.value ? 1 : 1.5;
};

// Start auto-scroll animation
const startAutoScroll = () => {
  if (animationFrameId) cancelAnimationFrame(animationFrameId);
  
  const animate = () => {
    if (!isPaused.value && sliderContainer.value && sliderContent.value) {
      // Get current dimensions
      if (containerWidth.value === 0) {
        containerWidth.value = sliderContainer.value.offsetWidth;
        contentWidth.value = sliderContent.value.scrollWidth / 2; // Half because we duplicated
      }
      
      // Update scroll position
      scrollPosition.value += scrollSpeed.value;
      
      // Reset when reaching halfway to create seamless loop
      if (scrollPosition.value >= contentWidth.value) {
        scrollPosition.value = 0;
      }
      
      // Apply transform with smooth easing
      sliderContent.value.style.transform = `translateX(-${scrollPosition.value}px)`;
      sliderContent.value.style.transition = 'transform 0.05s linear';
    }
    animationFrameId = requestAnimationFrame(animate);
  };
  
  animate();
};

// Stop auto-scroll animation
const stopAutoScroll = () => {
  if (animationFrameId) {
    cancelAnimationFrame(animationFrameId);
    animationFrameId = null;
  }
};

// Pause on hover/touch
const pauseSlider = () => {
  isPaused.value = true;
};

// Resume on hover leave/touch end
const resumeSlider = () => {
  isPaused.value = false;
};

// Handle window resize
const handleResize = () => {
  checkMobile();
  if (sliderContainer.value && sliderContent.value) {
    containerWidth.value = sliderContainer.value.offsetWidth;
    contentWidth.value = sliderContent.value.scrollWidth / 2;
    scrollPosition.value = 0;
    sliderContent.value.style.transform = 'translateX(0px)';
  }
};

onMounted(() => {
  checkMobile();
  startAutoScroll();
  window.addEventListener('resize', handleResize);
  // Initial size calculation
  setTimeout(handleResize, 100);
});

onUnmounted(() => {
  stopAutoScroll();
  window.removeEventListener('resize', handleResize);
});

// Get gap size based on screen size
const getGapSize = () => {
  if (window.innerWidth < 640) return 'gap-4';      // 1rem (16px)
  if (window.innerWidth < 768) return 'gap-6';      // 1.5rem (24px)
  if (window.innerWidth < 1024) return 'gap-8';     // 2rem (32px)
  return 'gap-12';                                   // 3rem (48px)
};

// Get logo size based on screen size
const getLogoSize = () => {
  if (window.innerWidth < 640) return 'w-20 h-20';   // 80px
  if (window.innerWidth < 768) return 'w-24 h-24';   // 96px
  if (window.innerWidth < 1024) return 'w-32 h-32';  // 128px
  return 'w-40 h-40';                                 // 160px
};
</script>

<template>
  <div class="partners-section py-8 md:py-16 bg-gradient-to-b from-teal-50 to-emerald-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Section Header -->
      <div class="text-center mb-8 md:mb-12">
        <h2 class="text-2xl md:text-3xl lg:text-4xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-teal-600 to-emerald-600 mb-3 md:mb-4">
          Our Partners & Collaborators
        </h2>
        <div class="w-20 h-1 bg-gradient-to-r from-teal-500 to-emerald-500 mx-auto mb-3 md:mb-4"></div>
        <p class="text-sm md:text-base text-gray-600 max-w-2xl mx-auto px-4">
          We work with leading organizations worldwide to ensure aviation safety and excellence
        </p>
      </div>

      <!-- Logo Slider Container -->
      <div 
        ref="sliderContainer"
        class="slider-container relative overflow-hidden rounded-xl md:rounded-2xl bg-white/80 backdrop-blur-sm shadow-lg md:shadow-xl"
        @mouseenter="pauseSlider"
        @mouseleave="resumeSlider"
        @touchstart="pauseSlider"
        @touchend="resumeSlider"
      >
        <div 
          ref="sliderContent"
          class="slider-content flex py-4 md:py-6 lg:py-8 px-2 md:px-4"
          :class="getGapSize()"
          :style="{ width: 'max-content' }"
        >
          <!-- Render duplicated partners for seamless scroll -->
          <div 
            v-for="(partner, index) in duplicatedPartners" 
            :key="`${partner.id}-${index}`"
            class="partner-card flex-shrink-0 group"
          >
            <div 
              class="flex items-center justify-center bg-white rounded-lg md:rounded-xl shadow-md hover:shadow-xl transition-all duration-300 transform hover:scale-105"
              :class="getLogoSize()"
            >
              <img 
                :src="partner.logo" 
                :alt="partner.name"
                loading="lazy"
                class="w-full h-full object-contain p-2 md:p-3 lg:p-4 filter grayscale hover:grayscale-0 transition-all duration-300"
                @error="(e) => e.target.src = '/assets/partners/placeholder.png'"
              />
            </div>
            <!-- Hide description on mobile to save space -->
            <div class="text-center mt-2 md:mt-3 opacity-0 group-hover:opacity-100 transition-opacity duration-300 hidden md:block">
              <p class="text-xs md:text-sm font-semibold text-teal-700">{{ partner.name }}</p>
              <p class="text-xs text-gray-500 hidden lg:block">{{ partner.description }}</p>
            </div>
            <!-- Mobile: show only name on hover -->
            <div class="text-center mt-1 md:hidden opacity-0 group-hover:opacity-100 transition-opacity duration-300">
              <p class="text-xs font-semibold text-teal-700">{{ partner.name }}</p>
            </div>
          </div>
        </div>

        <!-- Gradient Overlays for smooth edges - adjusted for mobile -->
        <div class="absolute left-0 top-0 bottom-0 w-12 sm:w-16 md:w-20 bg-gradient-to-r from-teal-50/90 to-transparent pointer-events-none"></div>
        <div class="absolute right-0 top-0 bottom-0 w-12 sm:w-16 md:w-20 bg-gradient-to-l from-emerald-50/90 to-transparent pointer-events-none"></div>
        
        <!-- Navigation Dots - hidden on mobile for cleaner look -->
        <div class="absolute bottom-2 md:bottom-4 left-1/2 transform -translate-x-1/2 gap-1 md:gap-2 hidden sm:flex">
          <div 
            v-for="(partner, idx) in partners" 
            :key="idx"
            class="w-1.5 h-1.5 md:w-2 md:h-2 rounded-full bg-teal-300 hover:bg-teal-600 transition-all cursor-pointer"
            :class="{ 'w-3 md:w-4 bg-teal-600': Math.floor(scrollPosition / (contentWidth / partners.length)) === idx }"
            @click="scrollPosition = idx * (contentWidth / partners.length)"
          ></div>
        </div>
        
        <!-- Touch indicator for mobile -->
        <div class="absolute bottom-2 left-1/2 transform -translate-x-1/2 sm:hidden text-center">
          <p class="text-[10px] text-teal-500/60">← swipe to pause →</p>
        </div>
      </div>

      <!-- Stats or Additional Info - responsive grid -->
      <div class="mt-8 md:mt-12 grid grid-cols-1 sm:grid-cols-3 gap-4 md:gap-6 text-center">
        <div class="bg-white/60 backdrop-blur-sm rounded-lg md:rounded-xl p-4 md:p-6 hover:bg-white/80 transition-all">
          <div class="text-2xl md:text-3xl font-bold text-teal-600 mb-1 md:mb-2">7+</div>
          <div class="text-xs md:text-sm text-gray-600">International Partners</div>
        </div>
        <div class="bg-white/60 backdrop-blur-sm rounded-lg md:rounded-xl p-4 md:p-6 hover:bg-white/80 transition-all">
          <div class="text-2xl md:text-3xl font-bold text-emerald-600 mb-1 md:mb-2">15+</div>
          <div class="text-xs md:text-sm text-gray-600">Years of Collaboration</div>
        </div>
        <div class="bg-white/60 backdrop-blur-sm rounded-lg md:rounded-xl p-4 md:p-6 hover:bg-white/80 transition-all">
          <div class="text-2xl md:text-3xl font-bold text-teal-600 mb-1 md:mb-2">Global</div>
          <div class="text-xs md:text-sm text-gray-600">Worldwide Network</div>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.slider-container {
  position: relative;
  cursor: pointer;
  -webkit-tap-highlight-color: transparent;
}

.slider-content {
  display: flex;
  align-items: center;
  will-change: transform;
  flex-wrap: nowrap;
}

.partner-card {
  transition: all 0.3s ease;
  flex-shrink: 0;
}

/* Improved touch handling for mobile */
@media (max-width: 768px) {
  .slider-container {
    cursor: grab;
    -webkit-overflow-scrolling: touch;
  }
  
  .slider-container:active {
    cursor: grabbing;
  }
  
  .partner-card:active {
    transform: scale(0.98);
  }
}

/* Custom scrollbar for the container (if needed) */
.slider-container::-webkit-scrollbar {
  height: 2px;
}

.slider-container::-webkit-scrollbar-track {
  background: rgba(0, 0, 0, 0.05);
  border-radius: 10px;
}

.slider-container::-webkit-scrollbar-thumb {
  background: linear-gradient(to right, #14b8a6, #10b981);
  border-radius: 10px;
}

/* Animation for logo hover effect */
@keyframes subtlePulse {
  0%, 100% {
    transform: scale(1);
  }
  50% {
    transform: scale(1.02);
  }
}

.partner-card:hover img {
  animation: subtlePulse 0.5s ease-in-out;
}

/* Glass morphism effect */
.bg-white\/80 {
  backdrop-filter: blur(8px);
}

/* Smooth transitions for responsive changes */
* {
  transition: all 0.2s ease-in-out;
}

/* Improve spacing for very small screens */
@media (max-width: 480px) {
  .partner-card {
    margin: 0 4px;
  }
  
  .slider-content {
    gap: 0.75rem;
  }
}
</style>