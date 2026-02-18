// resources/js/Components/forms/TextForm.vue
<script setup>
import { ref, watch, computed } from 'vue';

const props = defineProps({
    data: {
        type: Object,
        default: () => ({})
    }
});

const emit = defineEmits(['update:data']);

const localData = ref({
    text: props.data?.text || ''
});

watch(localData, (value) => {
    emit('update:data', value);
}, { deep: true });

// Computed property to check if text is valid (not empty)
const isValid = computed(() => {
    return localData.value.text && localData.value.text.trim() !== '';
});
</script>

<template>
    <div class="space-y-4">
        <h3 class="font-semibold text-lg">Plain Text</h3>
        
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Text Content <span class="text-red-500">*</span>
            </label>
            <textarea 
                v-model="localData.text" 
                rows="4"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                :class="{ 'border-red-500': !isValid && localData.text }"
                placeholder="Enter text to encode in QR code"
                required
            ></textarea>
            <p v-if="!isValid && localData.text" class="text-xs text-red-600 mt-1">
                Text cannot be empty
            </p>
            <p class="text-xs text-gray-500 mt-1">
                Enter any text to encode in the QR code
            </p>
        </div>
    </div>
</template>