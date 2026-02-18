// resources/js/Components/forms/SMSForm.vue
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
    phone: props.data?.phone || '',
    message: props.data?.message || '',
    countryCode: props.data?.countryCode || '+1'
});

const countryCodes = [
    { code: '+1', country: 'USA / Canada' },
    { code: '+7', country: 'Russia / Kazakhstan' },
    { code: '+20', country: 'Egypt' },
    { code: '+27', country: 'South Africa' },
    { code: '+30', country: 'Greece' },
    { code: '+31', country: 'Netherlands' },
    { code: '+32', country: 'Belgium' },
    { code: '+33', country: 'France' },
    { code: '+34', country: 'Spain' },
    { code: '+36', country: 'Hungary' },
    { code: '+39', country: 'Italy' },
    { code: '+40', country: 'Romania' },
    { code: '+41', country: 'Switzerland' },
    { code: '+43', country: 'Austria' },
    { code: '+44', country: 'United Kingdom' },
    { code: '+45', country: 'Denmark' },
    { code: '+46', country: 'Sweden' },
    { code: '+47', country: 'Norway' },
    { code: '+48', country: 'Poland' },
    { code: '+49', country: 'Germany' },
    { code: '+51', country: 'Peru' },
    { code: '+52', country: 'Mexico' },
    { code: '+54', country: 'Argentina' },
    { code: '+55', country: 'Brazil' },
    { code: '+56', country: 'Chile' },
    { code: '+57', country: 'Colombia' },
    { code: '+60', country: 'Malaysia' },
    { code: '+61', country: 'Australia' },
    { code: '+62', country: 'Indonesia' },
    { code: '+63', country: 'Philippines' },
    { code: '+64', country: 'New Zealand' },
    { code: '+65', country: 'Singapore' },
    { code: '+66', country: 'Thailand' },
    { code: '+81', country: 'Japan' },
    { code: '+82', country: 'South Korea' },
    { code: '+84', country: 'Vietnam' },
    { code: '+86', country: 'China' },
    { code: '+90', country: 'Turkey' },
    { code: '+91', country: 'India' },
    { code: '+92', country: 'Pakistan' },
    { code: '+93', country: 'Afghanistan' },
    { code: '+94', country: 'Sri Lanka' },
    { code: '+95', country: 'Myanmar' },
    { code: '+98', country: 'Iran' },
    { code: '+212', country: 'Morocco' },
    { code: '+213', country: 'Algeria' },
    { code: '+216', country: 'Tunisia' },
    { code: '+218', country: 'Libya' },
    { code: '+220', country: 'Gambia' },
    { code: '+221', country: 'Senegal' },
    { code: '+233', country: 'Ghana' },
    { code: '+234', country: 'Nigeria' },
    { code: '+254', country: 'Kenya' },
    { code: '+255', country: 'Tanzania' },
    { code: '+256', country: 'Uganda' },
    { code: '+260', country: 'Zambia' },
    { code: '+263', country: 'Zimbabwe' },
    { code: '+264', country: 'Namibia' },
    { code: '+267', country: 'Botswana' },
    { code: '+971', country: 'United Arab Emirates' },
    { code: '+972', country: 'Israel' },
    { code: '+973', country: 'Bahrain' },
    { code: '+974', country: 'Qatar' },
    { code: '+975', country: 'Bhutan' },
    { code: '+976', country: 'Mongolia' },
    { code: '+977', country: 'Nepal' },
    { code: '+880', country: 'Bangladesh' },
    { code: '+886', country: 'Taiwan' }
];

countryCodes.sort((a, b) => a.country.localeCompare(b.country));

const formattedCountries = computed(() =>
    countryCodes.map(country => ({
        label: `${country.country} (${country.code})`,
        value: country.code
    }))
);

watch(localData, (value) => {
    emit('update:data', value);
}, { deep: true });
</script>

<template>
    <div class="space-y-4">
        <h3 class="font-semibold text-lg">SMS Message</h3>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Phone Number <span class="text-red-500">*</span>
            </label>
            <div class="flex gap-2">
                <a-select v-model:value="localData.countryCode" :options="formattedCountries" show-search
                    placeholder="Select country" style="width: 220px" :filter-option="(input, option) =>
                        option.label.toLowerCase().includes(input.toLowerCase())
                        " />

                <input v-model="localData.phone" type="tel"
                    class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                    placeholder="xxx xxx xxx" required>
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Message</label>
            <textarea v-model="localData.message" rows="4"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                placeholder="Enter your SMS message" maxlength="160"></textarea>
            <p class="text-xs text-gray-500 mt-1">
                {{ localData.message.length }}/160 characters
                <span v-if="localData.message.length > 160" class="text-red-500">(Message will be split)</span>
            </p>
        </div>

        <div class="bg-blue-50 p-3 rounded-md">
            <p class="text-xs text-blue-700">
                <i class="fas fa-info-circle mr-1"></i>
                When scanned, this will open the messaging app with the phone number and message pre-filled.
            </p>
        </div>
    </div>
</template>