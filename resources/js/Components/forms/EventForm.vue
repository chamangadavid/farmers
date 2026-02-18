// resources/js/Components/forms/EventForm.vue
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
    title: props.data?.title || '',
    description: props.data?.description || '',
    location: props.data?.location || '',
    startDate: props.data?.startDate || '',
    startTime: props.data?.startTime || '',
    endDate: props.data?.endDate || '',
    endTime: props.data?.endTime || '',
    timezone: props.data?.timezone || Intl.DateTimeFormat().resolvedOptions().timeZone,
    url: props.data?.url || '',
    organizer: props.data?.organizer || '',
    organizerEmail: props.data?.organizerEmail || ''
});

const timezones = Intl.supportedValuesOf('timeZone');

watch(localData, (value) => {
    emit('update:data', value);
}, { deep: true });
</script>

<template>
    <div class="space-y-4">
        <h3 class="font-semibold text-lg">Calendar Event</h3>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Event Title <span class="text-red-500">*</span>
            </label>
            <input v-model="localData.title" type="text"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                placeholder="Conference Meeting" required>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
            <textarea v-model="localData.description" rows="3"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                placeholder="Event description"></textarea>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Location</label>
            <input v-model="localData.location" type="text"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                placeholder="Conference Room A or Online">
        </div>

        <!-- Date and Time -->
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Start Date <span class="text-red-500">*</span>
                </label>
                <input v-model="localData.startDate" type="date"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                    required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Start Time <span class="text-red-500">*</span>
                </label>
                <input v-model="localData.startTime" type="time"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                    required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                <input v-model="localData.endDate" type="date"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">End Time</label>
                <input v-model="localData.endTime" type="time"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Timezone</label>
            <select v-model="localData.timezone"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                <option v-for="tz in timezones" :key="tz" :value="tz">{{ tz }}</option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Event URL</label>
            <input v-model="localData.url" type="url"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                placeholder="https://event-page.com">
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Organizer</label>
                <input v-model="localData.organizer" type="text"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Organizer name">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Organizer Email</label>
                <input v-model="localData.organizerEmail" type="email"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                    placeholder="organizer@example.com">
            </div>
        </div>

        <div class="bg-blue-50 p-3 rounded-md">
            <p class="text-xs text-blue-700">
                <i class="fas fa-info-circle mr-1"></i>
                When scanned, this will create a calendar event that users can add to their calendar app.
            </p>
        </div>
    </div>
</template>