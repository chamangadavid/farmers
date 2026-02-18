// resources/js/Components/forms/URLForm.vue
<script setup>
import { ref, watch, computed } from 'vue';

const props = defineProps({
    data: {
        type: Object,
        default: () => ({})
    }
});

const emit = defineEmits(['update:data']);

// Initialize with proper default values
const localData = ref({
    url: props.data?.url || '',
    protocol: props.data?.protocol || 'https://',
    createShortUrl: props.data?.createShortUrl || false,
    trackAnalytics: props.data?.trackAnalytics || false
});

const protocols = [
    { value: 'https://', label: 'https://' },
    { value: 'http://', label: 'http://' },
    { value: 'ftp://', label: 'ftp://' },
    { value: 'mailto:', label: 'mailto:' }
];

// Watch for changes and emit to parent
watch(localData, (value) => {
    emit('update:data', value);
}, { deep: true });

// Computed property with safe null checking
const fullUrl = computed(() => {
    const url = localData.value?.url || '';
    // Only process if there's a URL
    if (!url) return localData.value?.protocol || 'https://';

    // Remove any existing protocol from the URL before adding the selected one
    const cleanUrl = url.replace(/^(https?:\/\/)/, '');
    return (localData.value?.protocol || 'https://') + cleanUrl;
});

// Method to validate URL
const isValidUrl = computed(() => {
    const url = fullUrl.value;
    if (!url || url === 'https://') return false;

    try {
        new URL(url);
        return true;
    } catch {
        return false;
    }
});
</script>

<template>
    <div class="space-y-4">
        <h3 class="font-semibold text-lg">Website URL</h3>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                URL <span class="text-red-500">*</span>
            </label>
            <div class="flex gap-2">
                <select v-model="localData.protocol"
                    class="w-28 px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                    <option v-for="p in protocols" :key="p.value" :value="p.value">
                        {{ p.label }}
                    </option>
                </select>
                <input v-model="localData.url" type="url"
                    class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                    placeholder="example.com" required>
            </div>
        </div>

        <!-- Show full URL preview only if there's content -->
        <div v-if="localData.url && localData.url.trim() !== ''" class="bg-gray-50 p-3 rounded-md"
            :class="{ 'border border-green-500': isValidUrl, 'border border-yellow-500': !isValidUrl }">
            <p class="text-sm text-gray-600">
                <span class="font-medium">Full URL:</span><br>
                <span class="text-blue-600 break-all">{{ fullUrl }}</span>
            </p>
            <p v-if="!isValidUrl" class="text-xs text-yellow-600 mt-1">
                ⚠️ This doesn't look like a valid URL
            </p>
            <p v-else class="text-xs text-green-600 mt-1">
                ✓ Valid URL format
            </p>
        </div>

        <!-- Optional features -->
        <div class="space-y-2 pt-2">
            <label class="flex items-center">
                <input type="checkbox" v-model="localData.createShortUrl"
                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                <span class="ml-2 text-sm text-gray-700">Create short URL (requires account)</span>
            </label>

            <label class="flex items-center">
                <input type="checkbox" v-model="localData.trackAnalytics"
                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                <span class="ml-2 text-sm text-gray-700">Track scans (dynamic QR code)</span>
            </label>
        </div>

        <p class="text-xs text-gray-500">
            Tip: Make sure to test the URL before generating to ensure it works correctly.
        </p>
    </div>
</template>