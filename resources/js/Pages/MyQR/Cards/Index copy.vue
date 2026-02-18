// resources/js/Pages/MyQR/Cards/Index.vue
<script setup>
import { ref, computed, shallowRef } from 'vue';
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import QRTypeList from '@/Components/QRTypeList.vue';
import QRDisplay from '@/Components/QRDisplay.vue';
import QRHistory from '@/Components/QRHistory.vue';
import axios from 'axios';
import { message } from 'ant-design-vue';

// Form Components
import VCardForm from '@/Components/forms/VCardForm.vue';
import EmailForm from '@/Components/forms/EmailForm.vue';
import SMSForm from '@/Components/forms/SMSForm.vue';
import URLForm from '@/Components/forms/URLForm.vue';
import TextForm from '@/Components/forms/TextForm.vue';
import WifiForm from '@/Components/forms/WifiForm.vue';
import EventForm from '@/Components/forms/EventForm.vue';
import SocialForm from '@/Components/forms/SocialForm.vue';
import { QrcodeOutlined, HistoryOutlined } from '@ant-design/icons-vue';


const props = defineProps({
    mustVerifyEmail: Boolean,
    status: String,
});

// State
const selectedType = ref('vcard');
const formData = ref({});
const qrImage = ref(null);
const isGenerating = ref(false);
const error = ref(null);

const formComponents = {
    vcard: VCardForm,
    email: EmailForm,
    sms: SMSForm,
    url: URLForm,
    text: TextForm,
    wifi: WifiForm,
    event: EventForm,
    facebook: SocialForm,
    twitter: SocialForm,
    linkedin: SocialForm,
    instagram: SocialForm,
    youtube: SocialForm,
    tiktok: SocialForm,
    pinterest: SocialForm,
    snapchat: SocialForm
};
// Computed property for current form component
const currentForm = computed(() => {
    return formComponents[selectedType.value] || null;
});

// Methods
const handleTypeSelect = (type) => {
    selectedType.value = type;
    formData.value = {}; // Reset form data when type changes
    qrImage.value = null; // Clear previous QR code
    error.value = null;
};

const generateQRCode = async () => {

    // Validate based on type
    if (selectedType.value === 'text') {
        if (!formData.value.text || formData.value.text.trim() === '') {
            error.value = 'Please enter text content';
            return;
        }
    }

     // Validate social platforms
    if (selectedType.value === 'social' || 
        ['facebook', 'twitter', 'linkedin', 'instagram', 'youtube', 'tiktok', 'pinterest', 'snapchat'].includes(selectedType.value)) {
        
        if (!formData.value.url && !formData.value.username) {
            error.value = 'Please provide a username or URL for the social profile';
            return;
        }
    }

    if (!formData.value || Object.keys(formData.value).length === 0) {
        error.value = 'Please fill in the form fields';
        return;
    }

    isGenerating.value = true;
    error.value = null;

    try {
        const response = await axios.post('/qr/generate', {
            type: selectedType.value,
            data: formData.value,
            settings: {
                size: 300,
                margin: 1,
                color: { r: 0, g: 0, b: 0 },
                background: { r: 255, g: 255, b: 255 }
            }
        });

        qrImage.value = response.data.file;

        // Show success message with save info
        if (response.data.record) {
            // Show a toast or notification that QR was saved
            console.log('QR Code saved to your history');
        }

    } catch (err) {
        error.value = err.response?.data?.message || 'Failed to generate QR code';
        console.error('QR Generation failed:', err);
    } finally {
        isGenerating.value = false;
    }
};

</script>

<template>

    <Head title="QR Code Generator" />

    <AuthenticatedLayout>
        <!-- Tab 1QR Code Types -->
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                QR Code Generator
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <!-- Two Column Layout -->
                <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
                    <!-- Left Column - QR Types and Forms (md:col-span-8) -->
                    <div class="md:col-span-8 space-y-6">
                        <!-- QR Type List Card -->
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <QRTypeList :selected-type="selectedType" @type-selected="handleTypeSelect" />
                            </div>
                        </div>

                        <!-- Form Card -->
                        <div v-if="currentForm" class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <!-- Dynamic Form Component -->
                                <component :is="currentForm" v-model:data="formData" />

                                <!-- Error Message -->
                                <div v-if="error" class="mt-4 p-3 bg-red-50 text-red-700 rounded-md">
                                    {{ error }}
                                </div>

                                <!-- Generate Button -->
                                <div class="mt-6">
                                    <button @click="generateQRCode" :disabled="isGenerating"
                                        class="w-full px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                                        <span v-if="isGenerating" class="flex items-center justify-center">
                                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white"
                                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                                    stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor"
                                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                </path>
                                            </svg>
                                            Generating...
                                        </span>
                                        <span v-else>Generate QR Code</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column - QR Display (md:col-span-4) -->
                    <div class="md:col-span-4">
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg sticky top-6">
                            <div class="p-6">
                                <QRDisplay :qr-image="qrImage" :is-loading="isGenerating" :type="selectedType" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tab 2 QR Code History -->
    </AuthenticatedLayout>
</template>