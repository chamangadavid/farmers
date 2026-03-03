<!-- resources/js/Pages/MyQR/Cards/Index.vue -->
<script setup>
import { ref, computed } from 'vue';
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import QRTypeList from '@/Components/QRTypeList.vue';
import QRDisplay from '@/Components/QRDisplay.vue';
import QRHistory from '@/Components/QRHistory.vue';
import axios from 'axios';
import { message } from 'ant-design-vue';

// Import Ant Design icons
import { QrcodeOutlined, HistoryOutlined } from '@ant-design/icons-vue';

// Form Components
import VCardForm from '@/Components/forms/VCardForm.vue';
import EmailForm from '@/Components/forms/EmailForm.vue';
import SMSForm from '@/Components/forms/SMSForm.vue';
import URLForm from '@/Components/forms/URLForm.vue';
import TextForm from '@/Components/forms/TextForm.vue';
import WifiForm from '@/Components/forms/WifiForm.vue';
import EventForm from '@/Components/forms/EventForm.vue';
import SocialForm from '@/Components/forms/SocialForm.vue';

const props = defineProps({
    mustVerifyEmail: Boolean,
    status: String,
});

// Tabs state
const activeTab = ref('generator');

// Generator state
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
    formData.value = {};
    qrImage.value = null;
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

        if (response.data.record) {
            message.success('QR Code generated and saved successfully');
        }

    } catch (err) {
        error.value = err.response?.data?.message || 'Failed to generate QR code';
        message.error(error.value);
        console.error('QR Generation failed:', err);
    } finally {
        isGenerating.value = false;
    }
};

</script>

<template>

    <Head title="QR Code Generator" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                QR Code Manager
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-4">
                <!-- Ant Design Tabs -->
                <a-card :bordered="false">
                    <a-tabs v-model:activeKey="activeTab">
                        <!-- Tab 1: QR Generator -->
                        <a-tab-pane key="generator" tab="QR Generator">
                            <template #tab>
                                <span>
                                    <qrcode-outlined />
                                    Generate QR Code
                                </span>
                            </template>

                            <!-- Two Column Layout -->
                            <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
                                <!-- Left Column - QR Types and Forms -->
                                <div class="md:col-span-8 space-y-6">
                                    <!-- QR Type List Card -->
                                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                                        <div class="p-6">
                                            <QRTypeList :selected-type="selectedType"
                                                @type-selected="handleTypeSelect" />
                                        </div>
                                    </div>

                                    <!-- Form Card -->
                                    <div v-if="currentForm" class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                                        <div class="p-6">
                                            <!-- Dynamic Form Component -->
                                            <component :is="currentForm" v-model:data="formData" />

                                            <!-- Error Message -->
                                            <a-alert v-if="error" :message="error" type="error" show-icon
                                                class="mt-4" />

                                            <!-- Generate Button -->
                                            <div class="mt-6">
                                                <a-button type="primary" block size="large" :loading="isGenerating"
                                                    @click="generateQRCode">
                                                    {{ isGenerating ? 'Generating...' : 'Generate QR Code' }}
                                                </a-button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Right Column - QR Display -->
                                <div class="md:col-span-4">
                                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg sticky top-6">
                                        <div class="p-6">
                                            <QRDisplay :qr-image="qrImage" :is-loading="isGenerating"
                                                :type="selectedType" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a-tab-pane>

                        <!-- Tab 2: QR History -->
                        <a-tab-pane key="history" tab="QR History">
                            <template #tab>
                                <span>
                                    <history-outlined />
                                    History
                                </span>
                            </template>

                            <!-- History Component -->
                            <QRHistory />
                        </a-tab-pane>
                    </a-tabs>
                </a-card>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
:deep(.ant-tabs-card > .ant-tabs-nav .ant-tabs-tab) {
    padding: 8px 16px;
}

:deep(.ant-tabs-card > .ant-tabs-nav .ant-tabs-tab-active) {
    background-color: #1890ff;
    color: white;
}

:deep(.ant-tabs-card > .ant-tabs-nav .ant-tabs-tab-active .ant-tabs-tab-btn) {
    color: white;
}

:deep(.ant-tabs-content) {
    margin-top: 24px;
}
</style>