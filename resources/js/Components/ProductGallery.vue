<!-- resources\js\Components\ProductGallery.vue -->
<script setup>

import { ref, computed, onMounted, onUnmounted } from 'vue'
import axios from 'axios'

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

const handleKeydown = (event) => {

    if (!previewVisible.value) return

    if (event.key === 'ArrowRight') {

        nextImage()

    }

    if (event.key === 'ArrowLeft') {

        previousImage()

    }

    if (event.key === 'Escape') {

        closePreview()

    }

}
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
    <section class="bg-white py-20">

        <div class="max-w-6xl mx-auto px-6">

            <!-- Heading -->
            <div class="text-center mb-14">

                <!-- Leaf Icon -->
                <img
                    src="/assets/leaf.png"
                    class="w-12 mx-auto mb-4"
                    alt=""
                >

                <h2 class="text-5xl font-bold text-slate-800 mb-4">
                    Our Product Gallery
                </h2>
                
                <p class="text-gray-500 text-sm max-w-xl mx-auto">
                    Alinaswe Farm is committed to growing fresh vegetables and raising quality chickens, delivering healthy, farm-fresh produce you can trust.
                </p>

            </div>

            <!-- Gallery -->

            <div class="max-h-[700px] overflow-y-auto pr-2">

    <div class="grid grid-cols-2 lg:grid-cols-3 gap-5">

        <div

            v-for="(item, index) in allImages"

            :key="index"

            class="overflow-hidden rounded-xl bg-white shadow"

        >

            <img

                :src="item.image"

                class="w-full h-72 object-cover cursor-pointer transition duration-300 hover:scale-105"

                @click="openPreview(index)"

            >

            <div class="p-3">

                <h3 class="font-semibold">

                    {{ item.gallery }}

                </h3>

            </div>

        </div>

    </div>

</div>

<div

    v-if="previewVisible && currentImage"

    class="fixed inset-0 bg-black/90 flex items-center justify-center z-50"

>

    <!-- Close -->

    <button

        class="absolute top-5 right-8 text-white text-5xl hover:text-red-500"

        @click="closePreview"

    >

        ✕

    </button>

    <!-- Previous -->

    <button

        class="absolute left-6 text-white text-6xl"

        @click="previousImage"

        :disabled="currentIndex === 0"

    >

        ❮

    </button>

    <!-- Image -->

    <div class="text-center">

        <img

            :src="currentImage.image"

            class="max-w-[90vw] max-h-[80vh] rounded-xl shadow-2xl"

        >

        <h2 class="text-white text-2xl font-bold mt-6">

            {{ currentImage.gallery }}

        </h2>

        <p class="text-gray-300 mt-2">

            {{ currentIndex + 1 }} / {{ allImages.length }}

        </p>

    </div>

    <!-- Next -->

    <button

        class="absolute right-6 text-white text-6xl"

        @click="nextImage"

        :disabled="currentIndex === allImages.length - 1"

    >

        ❯

    </button>

</div>

        </div>

    </section>
</template>