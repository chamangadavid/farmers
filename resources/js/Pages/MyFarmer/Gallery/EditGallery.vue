<!-- Resources/js/Pages/Site/ReportAccident.vue -->
<script setup>
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
    if (currentIndex.value < allImages.value.length - 1) {
        currentIndex.value++
    }
}

const previousImage = () => {
    if (currentIndex.value > 0) {
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


        <template #hero>
            <div class="text-center mt-16">
                <h1 class="text-6xl font-bold text-white"> Farm Gallery </h1>

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
                    <div v-for="(item, index) in allImages" :key="index"
                        class="bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition">

                        <img :src="item.image" @click="openPreview(index)"
                            class="h-72 w-full object-cover cursor-pointer hover:scale-105 transition">

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

            <div v-if="previewVisible && currentImage"
                class="fixed inset-0 bg-black/90 flex items-center justify-center z-50">
                <button class="absolute top-5 right-8 text-white text-5xl" @click="closePreview"> ✕ </button>
                <button class="absolute left-8 text-white text-6xl" @click="previousImage"
                    :disabled="currentIndex === 0">

                    ❮

                </button>

                <img :src="currentImage.image" class="max-w-[90vw] max-h-[85vh] rounded-xl">

                <button class="absolute right-8 text-white text-6xl" @click="nextImage"
                    :disabled="currentIndex === allImages.length - 1"> ❯ </button>

                <div class="absolute bottom-8 text-center text-white">
                    <h2 class="text-2xl font-bold"> {{ currentImage.title }} </h2>
                    <p> {{ currentIndex + 1 }} / {{ allImages.length }} </p>
                </div>
            </div>
        </div>
    </PageLayout>
</template>