// resources/js/Components/forms/EmailForm.vue
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
    email: props.data?.email || '',
    subject: props.data?.subject || '',
    body: props.data?.body || '',
    cc: props.data?.cc || '',
    bcc: props.data?.bcc || ''
});

watch(localData, (value) => {
    emit('update:data', value);
}, { deep: true });
</script>

<template>
    <div class="space-y-4">
        <h3 class="font-semibold text-lg">Email Message</h3>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Recipient Email <span class="text-red-500">*</span>
            </label>
            <input v-model="localData.email" type="email"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                placeholder="email@example.com" required>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Subject</label>
            <input v-model="localData.subject" type="text"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                placeholder="Email subject">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Message Body</label>
            <textarea v-model="localData.body" rows="4"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                placeholder="Enter your message"></textarea>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">CC</label>
                <input v-model="localData.cc" type="email"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                    placeholder="cc@example.com">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">BCC</label>
                <input v-model="localData.bcc" type="email"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                    placeholder="bcc@example.com">
            </div>
        </div>

        <p class="text-xs text-gray-500 mt-2">
            <span class="text-red-500">*</span> Required fields
        </p>
    </div>
</template>