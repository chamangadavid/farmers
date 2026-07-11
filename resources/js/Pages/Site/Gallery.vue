<!-- resources/js/Components/Gallery.vue -->
<script setup>
import PageLayout from '@/Components/PageLayout.vue';
import { ref, computed, onMounted } from 'vue'
import axios from 'axios'

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
});


const gallery = ref([])

const previewVisible = ref(false)
const currentIndex = ref(0)

const allImages = computed(() => {

    return gallery.value.flatMap(item =>

        (item.images || []).map(image => ({

            gallery: item.title,

            image

        }))

    )

})

const currentImage = computed(() => {

    return allImages.value[currentIndex.value] || null

})

const fetchGallery = async () => {

    try {

        const { data } = await axios.get('/frontend/gallery')

        gallery.value = data

    }
    catch (error) {

        console.error(error)

    }

}

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

onMounted(fetchGallery)
</script>

<template>
  <PageLayout title="Product Gallery" :canLogin="canLogin" :canRegister="canRegister" :showHero="true">
<section class="py-20 bg-gray-10">

    <div class="max-w-7xl mx-auto px-6">

        <div class="text-center mb-12">

            <h2 class="text-5xl font-bold text-slate-800">

                Gallery

            </h2>

            <p class="text-gray-500 mt-3 max-w-2xl mx-auto">

                Browse our collection of images showcasing our farm, products and activities.

            </p>

        </div>

        <div class="max-h-[700px] overflow-y-auto pr-2">

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

                <div
                    v-for="(item,index) in allImages"
                    :key="index"
                    class="bg-white rounded-xl shadow overflow-hidden"
                >

                    <img
                        :src="item.image"
                        class="w-full h-72 object-cover cursor-pointer hover:scale-105 transition duration-300"
                        @click="openPreview(index)"
                    >

                    <div class="p-4">

                        <h3 class="font-semibold">

                            {{ item.gallery }}

                        </h3>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <!-- Preview -->

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
            class="absolute left-5 text-white text-6xl"
            @click="previousImage"
            :disabled="currentIndex===0"
        >

            ❮

        </button>

        <div class="text-center">

            <img
                :src="currentImage.image"
                class="max-w-[90vw] max-h-[80vh] rounded-xl"
            >

            <h3 class="text-white text-2xl mt-5">

                {{ currentImage.gallery }}

            </h3>

            <p class="text-gray-300">

                {{ currentIndex + 1 }} / {{ allImages.length }}

            </p>

        </div>

        <button
            class="absolute right-5 text-white text-6xl"
            @click="nextImage"
            :disabled="currentIndex===allImages.length-1"
        >

            ❯

        </button>

    </div>

</section>

  </PageLayout>

</template>