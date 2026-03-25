<!-- Resources/js/Pages/Site/Contact.vue -->
<script setup>
import PageLayout from '@/Components/PageLayout.vue';
import { message } from 'ant-design-vue';
import axios from 'axios';
import { ref } from 'vue';

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
});

const loading = ref(false);


const formData = ref({
    name: '',
    email: '',
    subject: '',
    message: ''
});

const submitForm = async () => {
    try {
        loading.value = true;

        const response = await axios.post('/contact', formData.value);

        if (response.data.success) {
            message.success('Message sent successfully');

            // reset form
            formData.value = {
                name: '',
                email: '',
                subject: '',
                message: ''
            };
        }
    } catch (error) {
        console.error(error);
        message.error('Failed to send message');
    } finally {
        loading.value = false;
    }
};

</script>

<template>
    <PageLayout title="Contact Us" :canLogin="canLogin" :canRegister="canRegister" :showHero="true">
        <!-- Custom Hero Section for Contact Us -->
        <template #hero>
            <div class="text-center mt-12">
                <h1 class="text-4xl sm:text-5xl lg:text-6xl xl:text-7xl font-bold leading-tight mb-6">
                    <span
                        class="bg-gradient-to-r from-teal-400 via-emerald-400 to-teal-300 bg-clip-text text-transparent">
                        Get in Touch
                    </span>
                </h1>
                <p class="text-lg sm:text-xl text-white/80 max-w-3xl mx-auto">
                    Have questions or need assistance? Our team is here to help.
                </p>
            </div>
        </template>

        <!-- Main Content -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Contact Information -->
            <div class="space-y-6">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Contact Information</h2>
                    <p class="text-gray-600 leading-relaxed">
                        Reach out to us through any of the following channels.
                    </p>
                </div>

                <div class="space-y-4">
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 bg-teal-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                </path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-gray-900 font-semibold mb-1">Address</h3>
                            <p class="text-gray-600 text-sm">Kenneth Kaunda<br>International Airport<br>Lusaka, Zambia
                            </p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 bg-teal-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-gray-900 font-semibold mb-1">Phone</h3>
                            <p class="text-gray-600 text-sm">+260 211 225623 </p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 bg-teal-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-gray-900 font-semibold mb-1">Email</h3>
                            <p class="text-gray-600 text-sm">info@aaib.org.zm<br>reports@aaib.gov.zm</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="bg-gray-50 rounded-xl p-6 border border-gray-200">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Send us a Message</h2>
                <form @submit.prevent="submitForm" class="space-y-4">
                    <div>
                        <label class="block text-gray-700 text-sm mb-2">Your Name *</label>
                        <input type="text" v-model="formData.name"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 text-gray-900"
                            required />
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm mb-2">Email Address *</label>
                        <input type="email" v-model="formData.email"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 text-gray-900"
                            required />
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm mb-2">Subject *</label>
                        <input type="text" v-model="formData.subject"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 text-gray-900"
                            required />
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm mb-2">Message *</label>
                        <textarea rows="5" v-model="formData.message"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 text-gray-900"
                            required></textarea>
                    </div>
                    <button type="submit" :disabled="loading"
                        class="w-full py-3 bg-gradient-to-r from-teal-600 to-emerald-600 text-white rounded-lg font-semibold">
                        {{ loading ? 'Sending...' : 'Send Message' }}
                    </button>
                </form>
            </div>
        </div>
    </PageLayout>
</template>