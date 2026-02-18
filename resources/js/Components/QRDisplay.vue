<!-- <script setup>
import { computed } from 'vue';

const props = defineProps({
    qrImage: {
        type: String,
        default: null
    },
    isLoading: {
        type: Boolean,
        default: false
    },
    type: {
        type: String,
        default: 'url'
    }
});

const typeLabels = {
    vcard: 'Contact Card',
    email: 'Email',
    sms: 'SMS',
    url: 'URL',
    text: 'Text',
    wifi: 'WiFi Network',
    event: 'Event',
    facebook: 'Facebook',
    twitter: 'Twitter',
    linkedin: 'LinkedIn',
    instagram: 'Instagram',
    youtube: 'YouTube'
};

const displayLabel = computed(() => {
    return typeLabels[props.type] || 'QR Code';
});

const downloadQR = () => {
    if (!props.qrImage) return;
    
    const link = document.createElement('a');
    link.href = props.qrImage;
    link.download = `qr-code-${props.type}-${Date.now()}.png`;
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
};
</script>

<template>
    <div class="qr-display">
        <h3 class="text-lg font-semibold mb-4">QR Code Preview</h3>
        
        <div v-if="isLoading" class="flex flex-col items-center justify-center py-8">
            <div class="animate-pulse">
                <div class="w-48 h-48 bg-gray-200 rounded-lg mb-4"></div>
                <div class="h-4 bg-gray-200 rounded w-32 mx-auto"></div>
            </div>
        </div>
        
        <div v-else-if="!qrImage" class="text-center py-12">
            <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
            </svg>
            <p class="mt-4 text-gray-500">Generate a QR code to see it here</p>
            <p class="text-sm text-gray-400">Selected type: {{ displayLabel }}</p>
        </div>
        
        <div v-else class="text-center">
            <div class="relative inline-block mb-4 p-4 bg-white rounded-lg shadow-lg">
                <img :src="qrImage" :alt="displayLabel" class="w-48 h-48 mx-auto">
                <span class="absolute top-2 right-2 bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded">
                    {{ displayLabel }}
                </span>
            </div>
            
            <button 
                @click="downloadQR"
                class="w-full px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-colors flex items-center justify-center"
            >
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
                Download QR Code
            </button>
        </div>
    </div>
</template> -->

<script setup>
import { computed, ref, } from 'vue';

const props = defineProps({
    qrImage: {
        type: String,
        default: null
    },
    isLoading: {
        type: Boolean,
        default: false
    },
    type: {
        type: String,
        default: 'url'
    }
});

const typeLabels = {
    vcard: 'Contact Card',
    email: 'Email',
    sms: 'SMS',
    url: 'URL',
    text: 'Text',
    wifi: 'WiFi Network',
    event: 'Event',
    facebook: 'Facebook',
    twitter: 'Twitter',
    linkedin: 'LinkedIn',
    instagram: 'Instagram',
    youtube: 'YouTube',
    tiktok: 'TikTok',
    pinterest: 'Pinterest',
    snapchat: 'Snapchat'
};

const displayLabel = computed(() => {
    return typeLabels[props.type] || 'QR Code';
});

const isSvg = computed(() => {
    return props.qrImage && (props.qrImage.includes('data:image/svg') || props.qrImage.includes('<svg'));
});

const downloadQR = () => {
    if (!props.qrImage) return;

    const link = document.createElement('a');

    // Handle different image types
    if (isSvg.value) {
        // For SVG, we need to handle differently
        if (props.qrImage.startsWith('data:')) {
            link.href = props.qrImage;
        } else {
            // If it's raw SVG, convert to data URL
            const svgDataUrl = 'data:image/svg+xml;charset=utf-8,' + encodeURIComponent(props.qrImage);
            link.href = svgDataUrl;
        }
        link.download = `qr-code-${props.type}-${Date.now()}.svg`;
    } else {
        link.href = props.qrImage;
        link.download = `qr-code-${props.type}-${Date.now()}.png`;
    }

    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
};

// Helper to check if the image is valid
const imageError = ref(false);

const handleImageError = () => {
    imageError.value = true;
    console.error('Failed to load QR image');
};
</script>

<template>
    <div class="qr-display">
        <h3 class="text-lg font-semibold mb-4">QR Code Preview</h3>

        <!-- Loading State -->
        <div v-if="isLoading" class="flex flex-col items-center justify-center py-8">
            <div class="animate-pulse">
                <div class="w-48 h-48 bg-gray-200 rounded-lg mb-4"></div>
                <div class="h-4 bg-gray-200 rounded w-32 mx-auto"></div>
            </div>
        </div>

        <!-- No QR Code State -->
        <div v-else-if="!qrImage" class="text-center py-12">
            <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                    d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
            </svg>
            <p class="mt-4 text-gray-500">Generate a QR code to see it here</p>
            <p class="text-sm text-gray-400">Selected type: {{ displayLabel }}</p>
        </div>

        <!-- Error State -->
        <div v-else-if="imageError" class="text-center py-12">
            <svg class="mx-auto h-16 w-16 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <p class="mt-4 text-red-500">Failed to load QR code</p>
            <button @click="imageError = false; $emit('retry')" class="mt-2 text-sm text-blue-600 hover:text-blue-800">
                Try again
            </button>
        </div>

        <!-- QR Code Display -->
        <div v-else class="text-center">
            <div class="relative inline-block mb-4 p-4 bg-white rounded-lg shadow-lg">
                <!-- Use object for SVG to ensure proper rendering -->
                <object v-if="isSvg" :data="qrImage" type="image/svg+xml" class="w-48 h-48 mx-auto"
                    @error="handleImageError">
                    <!-- Fallback if object fails -->
                    <img :src="qrImage" :alt="displayLabel" class="w-48 h-48 mx-auto" @error="handleImageError">
                </object>

                <!-- Use img for PNG/other formats -->
                <img v-else :src="qrImage" :alt="displayLabel" class="w-48 h-48 mx-auto" @error="handleImageError">

                <span class="absolute top-2 right-2 bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded">
                    {{ displayLabel }}
                </span>
            </div>

            <!-- Download Button -->
            <button @click="downloadQR"
                class="w-full px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-colors flex items-center justify-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
                Download QR Code ({{ isSvg ? 'SVG' : 'PNG' }})
            </button>

            <!-- Format Info -->
            <p class="mt-2 text-xs text-gray-500">
                SVG format - Scalable without quality loss
            </p>
        </div>
    </div>
</template>