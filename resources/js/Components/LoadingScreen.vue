<!-- Resources/js/Components/LoadingScreen.vue -->
<template>
  <div class="loading-screen" :class="{ 'fade-out': isFadingOut }">
    <div class="loading-content">
      <!-- Animated Logo Container -->
      <div class="logo-container">
        <div class="logo-wrapper">
          <!-- <img
            src="/assets/marz-logo.png"
            alt="AAIB"
            class="loading-logo"
          /> -->
           <img
            src="/assets/marz-logo.png"
            alt="AAIB"
            class="loading-logo"
          />
          <div class="logo-ring"></div>
        </div>
      </div>

      <!-- Company Name with Gradient -->
      <div class="company-info">
        <h1 class="company-name">Alinaswe Farm</h1>
        <p class="company-tagline">Cultivating Freshness, Raising Excellence</p>
      </div>

      <!-- Progress Bar -->
      <div class="progress-container">
        <div class="progress-bar">
          <div 
            class="progress-fill"
            :style="{ width: `${progress}%` }"
          ></div>
        </div>
        <div class="progress-text">
          <span class="percentage">{{ Math.round(progress) }}%</span>
          <span class="loading-text">Loading...</span>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    progress: {
      type: Number,
      default: 0
    }
  },
  data() {
    return {
      isFadingOut: false
    };
  },
  watch: {
    progress(newVal) {
      // Start fade out when progress reaches 100%
      if (newVal >= 100) {
        setTimeout(() => {
          this.isFadingOut = true;
        }, 800); // Wait 800ms after reaching 100% before fading
      }
    }
  }
}
</script>

<style scoped>
.loading-screen {
  position: fixed;
  inset: 0;
  background: linear-gradient(135deg, #0f172a 0%, #115e59 50%, #0f172a 100%);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 9999;
  transition: opacity 1s ease-out, visibility 1s ease-out;
  opacity: 1;
  visibility: visible;
}

.loading-screen.fade-out {
  opacity: 0;
  visibility: hidden;
}

.loading-content {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 2rem;
  padding: 2rem;
  animation: slideUp 1s cubic-bezier(0.4, 0, 0.2, 1);
}

@keyframes slideUp {
  from {
    opacity: 0;
    transform: translateY(50px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* Logo Container */
.logo-container {
  position: relative;
}

.logo-wrapper {
  position: relative;
  width: 140px;
  height: 140px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.loading-logo {
  width: 90px;
  height: auto;
  z-index: 2;
  position: relative;
  animation: gentlePulse 2s cubic-bezier(0.4, 0, 0.2, 1) infinite;
}

@keyframes gentlePulse {
  0%, 100% {
    transform: scale(1);
    opacity: 1;
  }
  50% {
    transform: scale(1.08);
    opacity: 0.95;
  }
}

.logo-ring {
  position: absolute;
  inset: 0;
  border-radius: 50%;
  border: 3px solid transparent;
  border-top-color: #14b8a6;
  border-right-color: #10b981;
  animation: slowSpin 2.5s linear infinite;
}

@keyframes slowSpin {
  to {
    transform: rotate(360deg);
  }
}

/* Secondary ring for depth */
.logo-wrapper::before {
  content: '';
  position: absolute;
  inset: -8px;
  border-radius: 50%;
  background: radial-gradient(circle, rgba(20, 184, 166, 0.1), transparent);
  animation: pulseGlow 2s ease-in-out infinite;
}

@keyframes pulseGlow {
  0%, 100% {
    opacity: 0.3;
    transform: scale(1);
  }
  50% {
    opacity: 0.6;
    transform: scale(1.1);
  }
}

/* Company Info */
.company-info {
  text-align: center;
  animation: fadeInUp 1.2s cubic-bezier(0.4, 0, 0.2, 1);
}

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

.company-name {
  font-size: 2rem;
  font-weight: 700;
  background: linear-gradient(135deg, #ffffff 0%, #14b8a6 50%, #10b981 100%);
  -webkit-background-clip: text;
  background-clip: text;
  color: transparent;
  margin-bottom: 0.75rem;
  letter-spacing: -0.5px;
  animation: gradientShift 3s ease infinite;
  background-size: 200% 200%;
}

@keyframes gradientShift {
  0%, 100% {
    background-position: 0% 50%;
  }
  50% {
    background-position: 100% 50%;
  }
}

.company-tagline {
  color: rgba(255, 255, 255, 0.7);
  font-size: 0.95rem;
  font-weight: 500;
  letter-spacing: 2px;
  text-transform: uppercase;
  animation: fadeIn 1.5s ease-out;
}

@keyframes fadeIn {
  from {
    opacity: 0;
  }
  to {
    opacity: 1;
  }
}

/* Progress Container */
.progress-container {
  width: 350px;
  max-width: 80vw;
  animation: fadeInUp 1.4s cubic-bezier(0.4, 0, 0.2, 1);
}

.progress-bar {
  width: 100%;
  height: 6px;
  background: rgba(255, 255, 255, 0.1);
  border-radius: 100px;
  overflow: hidden;
  margin-bottom: 1rem;
  box-shadow: 0 0 10px rgba(20, 184, 166, 0.2);
}

.progress-fill {
  height: 100%;
  background: linear-gradient(90deg, #14b8a6, #10b981, #14b8a6);
  border-radius: 100px;
  transition: width 0.5s cubic-bezier(0.4, 0, 0.2, 1);
  position: relative;
  background-size: 200% 100%;
  animation: shimmer 2s linear infinite;
}

@keyframes shimmer {
  0% {
    background-position: 0% 0%;
  }
  100% {
    background-position: 200% 0%;
  }
}

.progress-fill::after {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: linear-gradient(
    90deg,
    transparent,
    rgba(255, 255, 255, 0.4),
    transparent
  );
  animation: shine 2s infinite;
}

@keyframes shine {
  0% {
    transform: translateX(-100%);
  }
  100% {
    transform: translateX(100%);
  }
}

.progress-text {
  display: flex;
  justify-content: space-between;
  align-items: center;
  font-size: 0.9rem;
  color: rgba(255, 255, 255, 0.9);
  font-weight: 500;
}

.percentage {
  color: #14b8a6;
  font-weight: 700;
  font-size: 1.1rem;
  animation: pulse 1.5s ease-in-out infinite;
}

@keyframes pulse {
  0%, 100% {
    opacity: 1;
  }
  50% {
    opacity: 0.7;
  }
}

.loading-text {
  letter-spacing: 3px;
  font-size: 0.85rem;
  text-transform: uppercase;
}

/* Responsive Design */
@media (max-width: 768px) {
  .company-name {
    font-size: 1.5rem;
  }
  
  .company-tagline {
    font-size: 0.8rem;
  }
  
  .logo-wrapper {
    width: 120px;
    height: 120px;
  }
  
  .loading-logo {
    width: 75px;
  }
  
  .progress-container {
    width: 280px;
  }
  
  .percentage {
    font-size: 1rem;
  }
}

@media (max-width: 480px) {
  .company-name {
    font-size: 1.2rem;
  }
  
  .company-tagline {
    font-size: 0.7rem;
    letter-spacing: 1px;
  }
  
  .logo-wrapper {
    width: 100px;
    height: 100px;
  }
  
  .loading-logo {
    width: 60px;
  }
  
  .progress-container {
    width: 250px;
  }
  
  .loading-content {
    gap: 1.5rem;
  }
}
</style>



<!-- <template>
  <div class="loading-screen">
    <div class="loading-content">
      <div class="gradient-loader"></div>

      <h1 class="company-name">Aircraft Accidents Investigation Board</h1>

      <a-progress
        :percent="progress"
        :stroke-color="['#7e22ce', '#a855f7']"
        :show-info="false"
        stroke-linecap="round"
      />
    </div>
  </div>
</template>

<script>
export default {
  props: {
    progress: {
      type: Number,
      default: 0
    }
  }
}
</script>

<style scoped>
.loading-screen {
  position: fixed;
  inset: 0;
  background: white;
  display: flex;
  justify-content: center;
  align-items: center;
}

.loading-content {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 2rem;
}

.gradient-loader {
  width: 100px;
  height: 100px;
  border-radius: 50%;
  background: conic-gradient(
    from 0deg,
    #7e22ce,
    #a855f7,
    #d8b4fe,
    #7e22ce
  );
  animation: rotate 1.2s linear infinite;
  mask: radial-gradient(farthest-side, transparent 70%, black 71%);
}

@keyframes rotate {
  100% { transform: rotate(360deg); }
}

.company-name {
  color: #4B5563;
  font-size: 1.3rem;
  font-weight: 600;
}
</style> -->
