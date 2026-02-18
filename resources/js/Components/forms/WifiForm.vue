// resources/js/Components/forms/WifiForm.vue
<script setup>
import { ref, watch } from 'vue';

const props = defineProps({
    data: {
        type: Object,
        default: () => ({})
    }
});

const emit = defineEmits(['update:data']);

const localData = ref({
    ssid: props.data?.ssid || '',
    password: props.data?.password || '',
    encryption: props.data?.encryption || 'WPA',
    hidden: props.data?.hidden || false
});

watch(localData, (value) => {
    emit('update:data', value);
}, { deep: true });
</script>

<template>
    <div class="space-y-4">
        <h3 class="font-semibold text-lg">WiFi Network</h3>
        
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Network Name (SSID)</label>
            <input 
                v-model="localData.ssid" 
                type="text" 
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                placeholder="Enter WiFi network name"
            >
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
            <input 
                v-model="localData.password" 
                type="password" 
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                placeholder="Enter WiFi password"
            >
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Encryption</label>
            <select 
                v-model="localData.encryption"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
            >
                <option value="WPA">WPA/WPA2</option>
                <option value="WEP">WEP</option>
                <option value="nopass">No Encryption</option>
            </select>
        </div>
        
        <div class="flex items-center">
            <input 
                type="checkbox" 
                v-model="localData.hidden"
                id="hidden-network"
                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
            >
            <label for="hidden-network" class="ml-2 text-sm text-gray-700">Hidden network</label>
        </div>
    </div>
</template>