<!-- Resources/js/Pages/Site/ReportAccident.vue -->
<script setup>
import PageLayout from '@/Components/PageLayout.vue';
import axios from 'axios';
import { message } from 'ant-design-vue';
import { ref, computed, onMounted } from 'vue'

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
});

const gallery = ref([])

const previewVisible = ref(false)
const currentIndex = ref(0)


const loading = ref(false);
const formData = ref({
    reportType: '',
    reporterName: '',
    reporterEmail: '',
    reporterPhone: '',
    accidentDate: '',
    accidentTime: '',
    location: '',
    aircraftRegistration: '',
    aircraftType: '',
    description: '',
    injuries: '',
    damage: ''
});


const fetchGallery = async () => {

    try {

        const { data } = await axios.get('/frontend/gallery')

        gallery.value = data

    } catch (error) {

        console.error(error)

    }

}
const allImages = computed(() => {

    return gallery.value.flatMap(group =>

        group.images.map(image => ({

            title: group.title,

            description: group.description,

            image

        }))

    )

})

const currentImage = computed(() => {

    return allImages.value[currentIndex.value] || null

})

const openPreview = (index) => {

    currentIndex.value = index

    previewVisible.value = true

}

const closePreview = () => {

    previewVisible.value = false

}

const nextImage = () => {

    if(currentIndex.value < allImages.value.length-1){

        currentIndex.value++

    }

}

const previousImage = () => {

    if(currentIndex.value>0){

        currentIndex.value--

    }

}

onMounted(() => {

    fetchGallery()

})

const submitReport = async () => {
    loading.value = true;

    try {
        await axios.post('/report-accident', formData.value);

        message.success('Report submitted successfully');

        // Reset form
        formData.value = {
            reportType: '',
            reporterName: '',
            reporterEmail: '',
            reporterPhone: '',
            accidentDate: '',
            accidentTime: '',
            location: '',
            aircraftRegistration: '',
            aircraftType: '',
            description: '',
            injuries: '',
            damage: ''
        };

    } catch (error) {
        if (error.response?.data?.errors) {
            Object.values(error.response.data.errors).forEach(err => {
                message.error(err[0]);
            });
        } else {
            message.error('Something went wrong');
        }
    } finally {
        loading.value = false;
    }
};

</script>

<template>
    <PageLayout title="Report an Accident" :canLogin="canLogin" :canRegister="canRegister" :showHero="true">
        <!-- Custom Hero Section for Report Accident -->
        <!-- <template #hero>
            <div class="text-center mt-12">
                <h1 class="text-4xl sm:text-5xl lg:text-6xl xl:text-7xl font-bold leading-tight mb-6">
                    <span
                        class="bg-gradient-to-r from-teal-400 via-emerald-400 to-teal-300 bg-clip-text text-transparent">
                        Report an Accident
                    </span>
                </h1>
                <p class="text-lg sm:text-xl text-white/80 max-w-3xl mx-auto">
                    Immediate reporting helps us respond quickly and effectively
                </p>
                <div class="mt-4 text-sm text-white/60">
                    Emergency hotline: <span class="text-teal-400 font-bold">+260 211 123456</span> (24/7)
                </div>
            </div>
        </template> -->

        <template #hero>

<div class="text-center mt-16">

    <h1 class="text-6xl font-bold text-white">

        Farm Gallery

    </h1>

    <p class="text-white/80 mt-4 text-lg">

        Browse our vegetables, poultry and farm activities.

    </p>

</div>

</template>

        <!-- Main Content -->
        <div class="space-y-8">
            <!-- Important Notice -->
            <div class="bg-red-50 border border-red-200 rounded-xl p-6">
                <div class="flex items-start gap-3">
                    <svg class="w-6 h-6 text-red-600 flex-shrink-0" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                        </path>
                    </svg>
                    <div>
                        <h3 class="font-bold text-gray-900 mb-1">Important Information</h3>
                        <p class="text-gray-600 text-sm">Please provide as much detail as possible. All information will
                            be
                            treated confidentially and used solely for investigation purposes.</p>
                    </div>
                </div>
            </div>

            <div class="max-w-7xl mx-auto">

    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">

        <div

            v-for="(item,index) in allImages"

            :key="index"

            class="bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition"

        >

            <img

                :src="item.image"

                @click="openPreview(index)"

                class="h-72 w-full object-cover cursor-pointer hover:scale-105 transition"

            >

            <div class="p-5">

                <h2 class="font-bold text-xl">

                    {{ item.title }}

                </h2>

                <p class="text-gray-500 mt-2">

                    {{ item.description }}

                </p>

            </div>

        </div>

    </div>

</div>




<div

v-if="previewVisible && currentImage"

class="fixed inset-0 bg-black/90 flex items-center justify-center z-50"

>

<button

class="absolute top-5 right-8 text-white text-5xl"

@click="closePreview"

>

✕

</button>

<button

class="absolute left-8 text-white text-6xl"

@click="previousImage"

:disabled="currentIndex===0"

>

❮

</button>

<img

:src="currentImage.image"

class="max-w-[90vw] max-h-[85vh] rounded-xl"

>

<button

class="absolute right-8 text-white text-6xl"

@click="nextImage"

:disabled="currentIndex===allImages.length-1"

>

❯

</button>

<div

class="absolute bottom-8 text-center text-white"

>

<h2 class="text-2xl font-bold">

{{ currentImage.title }}

</h2>

<p>

{{ currentIndex+1 }} / {{ allImages.length }}

</p>

</div>

</div>

            <!-- Report Form -->
            <!-- <form @submit.prevent="submitReport" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                
                    <div class="md:col-span-2">
                        <label class="block text-gray-700 font-medium mb-2">Type of Report *</label>
                        <select v-model="formData.reportType" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20">
                            <option value="">Select report type</option>
                            <option value="Accident">Accident</option>
                            <option value="Incident">Incident</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Your Name *</label>
                        <input v-model="formData.reporterName" type="text" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20">
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Email Address *</label>
                        <input v-model="formData.reporterEmail" type="email" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20">
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Phone Number</label>
                        <input v-model="formData.reporterPhone" type="tel"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20">
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Date of Occurrence *</label>
                        <input v-model="formData.accidentDate" type="date" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20">
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Time of Occurrence *</label>
                        <input v-model="formData.accidentTime" type="time" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20">
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Location *</label>
                        <input v-model="formData.location" type="text" required
                            placeholder="Airport, coordinates, or description"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20">
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Aircraft Registration</label>
                        <input v-model="formData.aircraftRegistration" type="text" placeholder="e.g., 9J-AAA"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20">
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Aircraft Type</label>
                        <input v-model="formData.aircraftType" type="text" placeholder="e.g., Boeing 737"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20">
                    </div>
                </div>

                <div>
                    <label class="block text-gray-700 font-medium mb-2">Description of Occurrence *</label>
                    <textarea v-model="formData.description" rows="5" required
                        placeholder="Please provide detailed information about what happened..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20"></textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Injuries</label>
                        <textarea v-model="formData.injuries" rows="3" placeholder="Describe any injuries sustained"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20"></textarea>
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Aircraft Damage</label>
                        <textarea v-model="formData.damage" rows="3" placeholder="Describe the extent of damage"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20"></textarea>
                    </div>
                </div>

                <div class="flex justify-end gap-4 pt-4">
                    <button type="button"
                        class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">Cancel</button>

                    <button type="submit" :disabled="loading"
                        class="px-6 py-2 bg-gradient-to-r from-teal-600 to-emerald-600 text-white rounded-lg font-semibold hover:shadow-lg transition-all flex items-center gap-2 justify-center">
                        <span v-if="loading"
                            class="animate-spin border-2 border-white border-t-transparent rounded-full w-4 h-4"></span>

                        {{ loading ? 'Submitting...' : 'Submit Report' }}
                    </button>
                </div>
            </form> -->
        </div>
    </PageLayout>
</template>