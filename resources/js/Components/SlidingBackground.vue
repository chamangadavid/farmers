<!-- Resources/js/Components/SlidingBackground.vue -->
<template>
  <div class="sliding-background">
    <!-- Sliding Images -->
    <div class="slideshow-container" :style="{ transform: `translateX(${currentOffset}%)` }">
      <div 
        v-for="(image, index) in images" 
        :key="index"
        class="slide"
        :style="{ backgroundImage: `url(${image})` }"
      ></div>
    </div>

    <!-- Particle Canvas -->
    <canvas ref="particleCanvas" class="particle-canvas"></canvas>

    <!-- Gradient Overlay (optional - you can remove if not needed) -->
    <div class="gradient-overlay"></div>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';

const images = [
  '/assets/back.png',
  '/assets/back-1.png',
  '/assets/back-2.png',
  '/assets/back-3.png'
];

// Sliding animation
const currentIndex = ref(0);
const currentOffset = ref(0);
let slideInterval = null;

// Particle system
const particleCanvas = ref(null);
let ctx = null;
let particles = [];
let animationFrame = null;

// Initialize sliding animation
const startSlideshow = () => {
  slideInterval = setInterval(() => {
    currentIndex.value = (currentIndex.value + 1) % images.length;
    currentOffset.value = -currentIndex.value * 100;
  }, 8000); // Change image every 8 seconds
};

// Particle class
class Particle {
  constructor(canvasWidth, canvasHeight) {
    this.x = Math.random() * canvasWidth;
    this.y = Math.random() * canvasHeight;
    this.size = Math.random() * 3 + 1;
    this.speedX = (Math.random() - 0.5) * 0.5;
    this.speedY = (Math.random() - 0.5) * 0.5 + 0.2;
    this.opacity = Math.random() * 0.5 + 0.2;
    this.color = `rgba(20, 184, 166, ${this.opacity})`;
  }

  update(canvasWidth, canvasHeight) {
    this.x += this.speedX;
    this.y += this.speedY;

    if (this.x < 0 || this.x > canvasWidth) {
      this.x = Math.random() * canvasWidth;
    }
    if (this.y < 0 || this.y > canvasHeight) {
      this.y = 0;
      this.x = Math.random() * canvasWidth;
    }
  }

  draw(ctx) {
    ctx.beginPath();
    ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
    ctx.fillStyle = this.color;
    ctx.fill();
  }
}

// Initialize particles
const initParticles = () => {
  if (!particleCanvas.value) return;
  
  const canvas = particleCanvas.value;
  const width = window.innerWidth;
  const height = window.innerHeight;
  
  canvas.width = width;
  canvas.height = height;
  
  particles = [];
  const particleCount = Math.min(150, Math.floor((width * height) / 10000));
  
  for (let i = 0; i < particleCount; i++) {
    particles.push(new Particle(width, height));
  }
};

// Animate particles
const animateParticles = () => {
  if (!ctx || !particleCanvas.value) return;
  
  ctx.clearRect(0, 0, particleCanvas.value.width, particleCanvas.value.height);
  
  particles.forEach(particle => {
    particle.update(particleCanvas.value.width, particleCanvas.value.height);
    particle.draw(ctx);
  });
  
  animationFrame = requestAnimationFrame(animateParticles);
};

// Handle window resize
const handleResize = () => {
  if (!particleCanvas.value) return;
  
  particleCanvas.value.width = window.innerWidth;
  particleCanvas.value.height = window.innerHeight;
  initParticles();
};

onMounted(() => {
  // Start slideshow
  startSlideshow();
  
  // Initialize particle system
  if (particleCanvas.value) {
    ctx = particleCanvas.value.getContext('2d');
    initParticles();
    animateParticles();
  }
  
  // Add resize listener
  window.addEventListener('resize', handleResize);
});

onUnmounted(() => {
  // Cleanup intervals and animation frames
  if (slideInterval) {
    clearInterval(slideInterval);
  }
  if (animationFrame) {
    cancelAnimationFrame(animationFrame);
  }
  window.removeEventListener('resize', handleResize);
});
</script>

<style scoped>
.sliding-background {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  overflow: hidden;
  z-index: 0;
}

.slideshow-container {
  display: flex;
  width: 100%;
  height: 100%;
  transition: transform 1.5s cubic-bezier(0.4, 0, 0.2, 1);
  will-change: transform;
}

.slide {
  flex: 0 0 100%;
  width: 100%;
  height: 100%;
  background-size: cover;
  background-position: center;
  background-repeat: no-repeat;
}

/* Particle Canvas */
.particle-canvas {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  pointer-events: none;
  z-index: 1;
}

/* Gradient Overlay - Light overlay to maintain text readability */
.gradient-overlay {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: linear-gradient(
    135deg,
    rgba(0, 0, 0, 0.3) 0%,
    rgba(0, 0, 0, 0.2) 50%,
    rgba(0, 0, 0, 0.3) 100%
  );
  z-index: 1;
  pointer-events: none;
}

/* Responsive adjustments */
@media (max-width: 768px) {
  .gradient-overlay {
    background: linear-gradient(
      135deg,
      rgba(0, 0, 0, 0.4) 0%,
      rgba(0, 0, 0, 0.3) 50%,
      rgba(0, 0, 0, 0.4) 100%
    );
  }
}
</style>