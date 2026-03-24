<!-- Resources/js/Components/AnimatedHero.vue -->
<template>
  <div class="hero-container">
    <div class="hero-content">
      <!-- Typing Animation Title -->
      <h1 class="hero-title">
        <span class="gradient-text">
          <span class="typed-text">{{ displayedText }}</span>
          <span class="cursor" :class="{ 'typing': isTyping }">|</span>
        </span>
        <br />
        <span class="white-text animate-fade-in">
          Investigation Board
        </span>
      </h1>
      
      <!-- Animated Description -->
      <p class="hero-description animate-fade-up">
        Committed to improving aviation safety through independent, 
        thorough, and timely investigations of aircraft accidents and incidents.
      </p>
      
      <!-- Animated Buttons -->
      <div class="button-group animate-fade-up-delay">
        <a
          href="/report-accident"
          class="btn-primary"
        >
          Report an Accident
          <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
          </svg>
        </a>
        <a
          href="/about"
          class="btn-secondary"
        >
          Learn More
        </a>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';

// Typing animation configuration
const fullText = "Aircraft Accident";
const displayedText = ref('');
const isTyping = ref(true);
let currentIndex = 0;

// Typing speed configuration
const typingSpeed = 150; // milliseconds per character
const pauseBeforeDelete = 2000; // pause before restarting
const deleteSpeed = 80; // speed of deletion

// Typing animation
const typeText = () => {
  if (currentIndex < fullText.length) {
    displayedText.value += fullText.charAt(currentIndex);
    currentIndex++;
    setTimeout(typeText, typingSpeed);
  } else {
    // Text fully typed - pause before restart
    setTimeout(() => {
      deleteText();
    }, pauseBeforeDelete);
  }
};

// Delete animation
const deleteText = () => {
  if (displayedText.value.length > 0) {
    displayedText.value = displayedText.value.slice(0, -1);
    setTimeout(deleteText, deleteSpeed);
  } else {
    currentIndex = 0;
    // Restart typing animation
    setTimeout(typeText, 500);
  }
};

// Start typing animation on mount
onMounted(() => {
  setTimeout(typeText, 500);
});
</script>

<style scoped>
.hero-container {
  position: relative;
  z-index: 2;
  flex: 1;
  display: flex;
  align-items: center;
  justify-content: flex-start;
  min-height: 100vh;
  padding-top: 5rem;
}

.hero-content {
  max-width: 1200px;
  margin: 0;
  padding: 2rem 2rem 2rem 4rem;
  text-align: left;
}

@media (min-width: 1024px) {
  .hero-content {
    padding: 2rem 2rem 2rem 6rem;
  }
}

@media (max-width: 768px) {
  .hero-content {
    padding: 2rem 1.5rem;
    text-align: left;
  }
  
  .hero-container {
    align-items: flex-start;
    padding-top: 6rem;
  }
}

/* Title Styles */
.hero-title {
  font-size: 3rem;
  font-weight: 800;
  line-height: 1.2;
  margin-bottom: 1.5rem;
}

@media (min-width: 640px) {
  .hero-title {
    font-size: 3.5rem;
  }
}

@media (min-width: 768px) {
  .hero-title {
    font-size: 4rem;
  }
}

@media (min-width: 1024px) {
  .hero-title {
    font-size: 5rem;
  }
}

@media (min-width: 1280px) {
  .hero-title {
    font-size: 5.5rem;
  }
}

.gradient-text {
  background: linear-gradient(135deg, #14b8a6, #10b981, #14b8a6);
  background-size: 200% 200%;
  -webkit-background-clip: text;
  background-clip: text;
  color: transparent;
  display: inline-block;
  position: relative;
}

.white-text {
  color: white;
  display: inline-block;
  animation: fadeInText 1s ease-out 0.5s forwards;
  opacity: 0;
  animation-fill-mode: forwards;
}

/* Typing Cursor */
.cursor {
  display: inline-block;
  width: 3px;
  margin-left: 2px;
  background: linear-gradient(135deg, #14b8a6, #10b981);
  animation: blink 1s infinite;
  font-weight: 100;
}

.cursor.typing {
  animation: blink 0.8s infinite;
}

@keyframes blink {
  0%, 50% {
    opacity: 1;
  }
  51%, 100% {
    opacity: 0;
  }
}

.typed-text {
  display: inline-block;
}

/* Description */
.hero-description {
  font-size: 1rem;
  color: rgba(255, 255, 255, 0.95);
  max-width: 600px;
  margin: 0 0 2rem 0;
  line-height: 1.6;
  text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
}

@media (min-width: 768px) {
  .hero-description {
    font-size: 1.125rem;
    max-width: 550px;
  }
}

@media (min-width: 1024px) {
  .hero-description {
    font-size: 1.25rem;
    max-width: 600px;
  }
}

/* Button Group */
.button-group {
  display: flex;
  flex-direction: column;
  gap: 1rem;
  justify-content: flex-start;
  align-items: flex-start;
}

@media (min-width: 640px) {
  .button-group {
    flex-direction: row;
    gap: 1.5rem;
  }
}

/* Button Styles */
.btn-primary {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.875rem 2rem;
  background: linear-gradient(135deg, #14b8a6, #10b981);
  color: white;
  border-radius: 0.75rem;
  font-weight: 600;
  font-size: 1rem;
  transition: all 0.3s ease;
  box-shadow: 0 4px 15px rgba(20, 184, 166, 0.3);
  cursor: pointer;
  text-decoration: none;
}

.btn-primary:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 25px rgba(20, 184, 166, 0.4);
}

.btn-icon {
  width: 1.25rem;
  height: 1.25rem;
  transition: transform 0.3s ease;
}

.btn-primary:hover .btn-icon {
  transform: translateX(4px);
}

.btn-secondary {
  display: inline-flex;
  align-items: center;
  padding: 0.875rem 2rem;
  background: rgba(255, 255, 255, 0.1);
  backdrop-filter: blur(10px);
  border: 1px solid rgba(20, 184, 166, 0.4);
  color: white;
  border-radius: 0.75rem;
  font-weight: 600;
  font-size: 1rem;
  transition: all 0.3s ease;
  cursor: pointer;
  text-decoration: none;
}

.btn-secondary:hover {
  background: rgba(255, 255, 255, 0.2);
  border-color: rgba(20, 184, 166, 0.6);
  transform: translateY(-2px);
}

/* Animations */
@keyframes textGlow {
  0%, 100% {
    background-position: 0% 50%;
  }
  50% {
    background-position: 100% 50%;
  }
}

@keyframes fadeInText {
  from {
    opacity: 0;
    transform: translateX(-20px);
  }
  to {
    opacity: 1;
    transform: translateX(0);
  }
}

@keyframes fadeUp {
  from {
    opacity: 0;
    transform: translateY(30px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.gradient-text {
  animation: textGlow 3s ease-in-out infinite;
}

.animate-fade-in {
  animation: fadeInText 0.8s ease-out 0.5s forwards;
  opacity: 0;
  animation-fill-mode: forwards;
}

.animate-fade-up {
  animation: fadeUp 0.8s ease-out forwards;
  opacity: 0;
  animation-delay: 0.3s;
  animation-fill-mode: forwards;
}

.animate-fade-up-delay {
  animation: fadeUp 0.8s ease-out 0.6s forwards;
  opacity: 0;
  animation-fill-mode: forwards;
}

/* Optional: Add a subtle left border accent */
.hero-content::before {
  content: '';
  position: absolute;
  left: 0;
  top: 20%;
  width: 4px;
  height: 60%;
  background: linear-gradient(135deg, #14b8a6, #10b981);
  border-radius: 2px;
  display: none;
}

@media (min-width: 1024px) {
  .hero-content::before {
    display: block;
  }
}

/* Responsive adjustments for left alignment */
@media (max-width: 1024px) {
  .hero-content {
    padding: 2rem 1.5rem;
  }
}
</style>