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
            message.success({
                content: 'Message sent successfully! We\'ll get back to you soon.',
                duration: 4,
            });

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
        message.error(error.response?.data?.message || 'Failed to send message. Please try again.');
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
                <p class="text-lg sm:text-xl text-white/80 max-w-3xl mx-auto mb-6">
                    Have questions or need assistance? Our team is here to help.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="#contact-form"
                        class="px-8 py-4 bg-gradient-to-r from-teal-600 to-emerald-600 text-white rounded-xl font-semibold hover:shadow-2xl hover:shadow-teal-500/30 transition-all duration-300 transform hover:-translate-y-1">
                        Send Message
                    </a>
                    <a href="#contact-info"
                        class="px-8 py-4 bg-white/10 backdrop-blur-sm border border-teal-400/30 text-white rounded-xl font-semibold hover:bg-white/20 transition-all duration-300">
                        Contact Info
                    </a>
                </div>
            </div>
        </template>

        <!-- Main Content -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Contact Information -->
            <div id="contact-info" class="space-y-6 scroll-mt-24">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <span class="w-1 h-6 bg-teal-600 rounded-full"></span>
                        Contact Information
                    </h2>
                    <p class="text-gray-600 leading-relaxed">
                        Reach out to us through any of the following channels. We're available Monday through Friday, 8:00 AM - 5:00 PM.
                    </p>
                </div>

                <div class="space-y-6">
                    <!-- Address -->
                    <div class="group flex items-start gap-4 p-4 rounded-xl transition-all duration-300 hover:bg-gray-50">
                        <div class="w-12 h-12 bg-teal-100 rounded-xl flex items-center justify-center flex-shrink-0 group-hover:bg-teal-200 transition-colors duration-300">
                            <svg class="w-6 h-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                </path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-gray-900 font-semibold mb-1 text-lg">Office Address</h3>
                            <p class="text-gray-600 text-sm leading-relaxed">
                                Kenneth Kaunda International Airport<br>
                                P.O. Box 12345<br>
                                Lusaka, Zambia
                            </p>
                        </div>
                    </div>

                    <!-- Phone -->
                    <div class="group flex items-start gap-4 p-4 rounded-xl transition-all duration-300 hover:bg-gray-50">
                        <div class="w-12 h-12 bg-teal-100 rounded-xl flex items-center justify-center flex-shrink-0 group-hover:bg-teal-200 transition-colors duration-300">
                            <svg class="w-6 h-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-gray-900 font-semibold mb-1 text-lg">Phone Numbers</h3>
                            <p class="text-gray-600 text-sm">
                                <span class="font-medium">Main:</span> +260 211 225623<br>
                                <span class="font-medium">Emergency:</span> +260 977 123456
                            </p>
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="group flex items-start gap-4 p-4 rounded-xl transition-all duration-300 hover:bg-gray-50">
                        <div class="w-12 h-12 bg-teal-100 rounded-xl flex items-center justify-center flex-shrink-0 group-hover:bg-teal-200 transition-colors duration-300">
                            <svg class="w-6 h-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-gray-900 font-semibold mb-1 text-lg">Email Addresses</h3>
                            <p class="text-gray-600 text-sm">
                                <span class="font-medium">General Inquiries:</span><br>
                                <a href="mailto:info@aaib.org.zm" class="text-teal-600 hover:text-teal-700 transition-colors">info@aaib.org.zm</a><br>
                                <span class="font-medium">Reports & Investigations:</span><br>
                                <a href="mailto:reports@aaib.gov.zm" class="text-teal-600 hover:text-teal-700 transition-colors">reports@aaib.gov.zm</a>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Office Hours -->
                <div class="mt-8 p-4 bg-teal-50 rounded-xl border border-teal-200">
                    <div class="flex items-center gap-3 mb-3">
                        <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <h3 class="font-semibold text-gray-900">Office Hours</h3>
                    </div>
                    <div class="space-y-1 text-sm text-gray-700">
                        <p>Monday - Friday: 8:00 AM - 5:00 PM</p>
                        <p>Saturday - Sunday: Closed</p>
                        <p class="text-xs text-gray-500 mt-2">For emergencies, please call our 24/7 emergency line.</p>
                    </div>
                </div>
            </div>

            <!-- Contact Form -->
            <div id="contact-form" class="scroll-mt-24">
                <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden hover:shadow-2xl transition-shadow duration-300">
                    <div class="bg-gradient-to-r from-teal-600 to-emerald-600 px-6 py-5">
                        <h2 class="text-xl font-bold text-white flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            Send us a Message
                        </h2>
                        <p class="text-white/80 text-sm mt-1">We'll get back to you within 24-48 hours</p>
                    </div>
                    
                    <form @submit.prevent="submitForm" class="p-6 space-y-5">
                        <div class="space-y-1">
                            <label class="block text-sm font-medium text-gray-700">
                                Your Name <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="text" 
                                v-model="formData.name"
                                placeholder="Enter your full name"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 text-gray-900 transition-all duration-200"
                                required 
                            />
                        </div>
                        
                        <div class="space-y-1">
                            <label class="block text-sm font-medium text-gray-700">
                                Email Address <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="email" 
                                v-model="formData.email"
                                placeholder="your.email@example.com"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 text-gray-900 transition-all duration-200"
                                required 
                            />
                        </div>
                        
                        <div class="space-y-1">
                            <label class="block text-sm font-medium text-gray-700">
                                Subject <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="text" 
                                v-model="formData.subject"
                                placeholder="What is this regarding?"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 text-gray-900 transition-all duration-200"
                                required 
                            />
                        </div>
                        
                        <div class="space-y-1">
                            <label class="block text-sm font-medium text-gray-700">
                                Message <span class="text-red-500">*</span>
                            </label>
                            <textarea 
                                rows="5" 
                                v-model="formData.message"
                                placeholder="Please provide details about your inquiry..."
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 text-gray-900 transition-all duration-200 resize-none"
                                required
                            ></textarea>
                            <p class="text-xs text-gray-500 mt-1">Maximum 500 characters</p>
                        </div>
                        
                        <button 
                            type="submit" 
                            :disabled="loading"
                            class="w-full py-3 bg-gradient-to-r from-teal-600 to-emerald-600 text-white rounded-lg font-semibold hover:from-teal-700 hover:to-emerald-700 transition-all duration-300 transform hover:scale-[1.02] disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:scale-100 shadow-md hover:shadow-lg"
                        >
                            <span v-if="!loading" class="flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                Send Message
                            </span>
                            <span v-else class="flex items-center justify-center gap-2">
                                <svg class="animate-spin h-5 w-5" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Sending...
                            </span>
                        </button>
                        
                        <p class="text-center text-xs text-gray-400 mt-4">
                            By submitting this form, you agree to our 
                            <a href="#" class="text-teal-600 hover:text-teal-700">Privacy Policy</a>
                        </p>
                    </form>
                </div>
            </div>
        </div>

        <!-- Map Section -->
        <div class="mt-12">
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-teal-600 to-emerald-600 px-6 py-4">
                    <h3 class="text-lg font-semibold text-white flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                        </svg>
                        Our Location
                    </h3>
                </div>
                <div class="p-2">
                    <iframe 
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3845.901058076101!2d28.330765314648233!3d-15.42420968924023!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x19408e50e2bafaa9%3A0x3dce8b90420d5f14!2sKenneth%20Kaunda%20International%20Airport!5e0!3m2!1sen!2szm!4v1647794234567!5m2!1sen!2szm"
                        width="100%" 
                        height="300" 
                        style="border:0;" 
                        allowfullscreen="" 
                        loading="lazy"
                        class="rounded-lg"
                    ></iframe>
                </div>
            </div>
        </div>
    </PageLayout>
</template>

<style scoped>
/* Smooth scrolling for anchor links */
.scroll-mt-24 {
    scroll-margin-top: 6rem;
}

/* Form input focus effects */
input:focus, textarea:focus {
    outline: none;
    border-color: #14b8a6;
    box-shadow: 0 0 0 3px rgba(20, 184, 166, 0.1);
}

/* Animation for cards */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.group {
    animation: fadeInUp 0.4s ease-out;
}

/* Hover effects for contact cards */
.group {
    transition: all 0.3s ease;
}

/* Custom scrollbar for textarea */
textarea::-webkit-scrollbar {
    width: 8px;
}

textarea::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 4px;
}

textarea::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 4px;
}

textarea::-webkit-scrollbar-thumb:hover {
    background: #14b8a6;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .scroll-mt-24 {
        scroll-margin-top: 4rem;
    }
}

/* Button loading animation */
@keyframes spin {
    to {
        transform: rotate(360deg);
    }
}

.animate-spin {
    animation: spin 1s linear infinite;
}
</style>