<!-- Animated QR-Style Squares -->
<template>
  <div class="loading-screen">
    <div class="loading-content">
      <div class="qr-loader">
        <div></div>
        <div></div>
        <div></div>
        <div></div>
      </div>

      <h1 class="company-name">CAA QR GENERATOR</h1>

      <a-progress
        :percent="progress"
        :stroke-color="['#0078D4', '#38BDF8']"
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

.qr-loader {
  display: grid;
  grid-template-columns: repeat(2, 30px);
  gap: 12px;
}

.qr-loader div {
  width: 30px;
  height: 30px;
  background: #0078D4; /* Outlook Blue */
  animation: blink 1.2s infinite ease-in-out;
}

.qr-loader div:nth-child(2) { animation-delay: 0.2s; }
.qr-loader div:nth-child(3) { animation-delay: 0.4s; }
.qr-loader div:nth-child(4) { animation-delay: 0.6s; }

@keyframes blink {
  0%,100% { opacity: 0.3; }
  50% { opacity: 1; }
}

.company-name {
  color: #0078D4; /* Outlook Blue */
  font-size: 1.3rem;
  font-weight: 600;
}
</style>



<!-- Animated QR-Style Squares -->
<!-- <template>
  <div class="loading-screen">
    <div class="loading-content">
      <div class="qr-loader">
        <div></div>
        <div></div>
        <div></div>
        <div></div>
      </div>

      <h1 class="company-name">CAA QR GENERATOR</h1>

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

.qr-loader {
  display: grid;
  grid-template-columns: repeat(2, 30px);
  gap: 12px;
}

.qr-loader div {
  width: 30px;
  height: 30px;
  background: #7e22ce;
  animation: blink 1.2s infinite ease-in-out;
}

.qr-loader div:nth-child(2) { animation-delay: 0.2s; }
.qr-loader div:nth-child(3) { animation-delay: 0.4s; }
.qr-loader div:nth-child(4) { animation-delay: 0.6s; }

@keyframes blink {
  0%,100% { opacity: 0.3; }
  50% { opacity: 1; }
}

.company-name {
  color: #4B5563;
  font-size: 1.3rem;
  font-weight: 600;
}
</style> -->


<!-- Modern Gradient Spinner (Premium Look) -->
<!-- <template>
  <div class="loading-screen">
    <div class="loading-content">
      <div class="gradient-loader"></div>

      <h1 class="company-name">CAA QR GENERATOR</h1>

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



<!-- 3. Circular Ripple Effect -->
<!-- <template>
  <div class="loading-screen">
    <div class="loading-content">
      <div class="ripple">
        <div></div>
        <div></div>
      </div>

      <h1 class="company-name">CAA QR GENERATOR</h1>

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

.ripple {
  position: relative;
  width: 80px;
  height: 80px;
}

.ripple div {
  position: absolute;
  border: 4px solid #7e22ce;
  opacity: 1;
  border-radius: 50%;
  animation: ripple 1.5s cubic-bezier(0,0.2,0.8,1) infinite;
}

.ripple div:nth-child(2) {
  animation-delay: -0.75s;
}

@keyframes ripple {
  0% {
    top: 36px; left: 36px;
    width: 0; height: 0;
    opacity: 1;
  }
  100% {
    top: 0; left: 0;
    width: 72px; height: 72px;
    opacity: 0;
  }
}

.company-name {
  color: #4B5563;
  font-size: 1.3rem;
  font-weight: 600;
}
</style> -->




<!-- 2. Animated Dots — [ Loading... ] -->
<!-- <template>
  <div class="loading-screen">
    <div class="loading-content">
      <div class="loading-text">
        <span>[ Loading</span>
        <span class="dots"></span>
        <span> ]</span>
      </div>

      <h1 class="company-name">CAA QR GENERATOR</h1>

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
  z-index: 9999;
}

.loading-content {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 2rem;
  width: 100%;
  max-width: 400px;
  padding: 2rem;
}

.loading-text {
  font-size: 1.8rem;
  font-weight: 600;
  color: #7e22ce;
  display: flex;
}

.dots::after {
  content: '';
  animation: dots 1.5s steps(3, end) infinite;
}

@keyframes dots {
  0% { content: ''; }
  33% { content: '.'; }
  66% { content: '..'; }
  100% { content: '...'; }
}

.company-name {
  color: #4B5563;
  font-size: 1.3rem;
  font-weight: 600;
}
</style> -->



<!-- 1. screen logo loading effects -->
<!-- <template>
    <div class="loading-screen">
      <div class="loading-content">
        <div class="logo-container">
          <img src="/assets/favivon.png" alt="QR Generator" class="logo" />

        </div>
        <h1 class="company-name">CAA QR GENERATOR</h1>
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
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: white;
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 9999;
  }
  
  .loading-content {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 2rem;
    width: 100%;
    max-width: 400px;
    padding: 2rem;
  }
  
  .logo-container {
    width: 120px;
    height: 120px;
  }
  
  .logo {
    width: 100%;
    height: 100%;
    object-fit: contain;
    animation: pulse 2s infinite;
  }
  
  .company-name {
    color: #4B5563;
    font-size: 1.5rem;
    font-weight: 600;
    margin: 0;
  }
  
  @keyframes pulse {
    0% { transform: scale(1); opacity: 1; }
    50% { transform: scale(1.05); opacity: 0.8; }
    100% { transform: scale(1); opacity: 1; }
  }
  </style> -->