<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import {
    StarFilled,
    MessageOutlined
} from '@ant-design/icons-vue'

const testimonials = ref([])

const fetchTestimonials = async () => {

    try {

        const { data } = await axios.get('/frontend/testimonials')

        testimonials.value = data

    } catch (error) {

        console.error('Failed to fetch testimonials:', error)

    }

}

onMounted(() => {

    fetchTestimonials()

})
</script>


<template>
    <section class="py-24 bg-gradient-to-b from-green-50 to-white">
        <div class="max-w-7xl mx-auto px-6">

            <!-- Heading -->
            <div class="text-center mb-16">
                <span class="inline-block text-green-600 font-semibold tracking-widest uppercase">
                    Testimonials
                </span>
                <h2 class="text-5xl font-bold text-slate-800 mt-4 mb-5">
                    What Our Customers Say
                </h2>
                <p class="text-gray-500 max-w-2xl mx-auto">
                    We are proud to serve our customers with fresh vegetables and
                    quality chickens. Here's what they have to say.
                </p>

            </div>

            <!-- Cards -->

            <div class="max-h-[350px] overflow-y-auto pr-3 custom-scroll">
                <div class="grid lg:grid-cols-3 md:grid-cols-2 gap-8">
                    <div v-for="item in testimonials" :key="item.name"
                        class="relative bg-white rounded-3xl shadow-lg hover:shadow-2xl transition duration-300 p-8 border border-green-100">

                        <MessageOutlined class="absolute right-6 top-6 text-5xl text-green-100" />
                        <div class="flex items-center gap-4">

                            <img :src="item.image" :alt="item.name"
                                class="w-20 h-20 rounded-full object-cover border-4 border-green-100">

                            <div>
                                <h3 class="font-bold text-xl text-slate-800">
                                    {{ item.name }}
                                </h3>

                                <p class="text-green-600">
                                    {{ item.location }}
                                </p>

                                <div class="flex mt-2">
                                    <StarFilled v-for="i in 5" :key="i" class="text-yellow-400 text-sm mr-1" />
                                </div>
                            </div>
                        </div>

                        <p class="text-gray-600 leading-8 mt-6 italic">
                            "{{ item.review.length > 70 ? item.review.substring(0, 70) + '...' : item.review }}"
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<style scoped>
.custom-scroll {
    scrollbar-width: thin;
    scrollbar-color: #16a34a #f1f5f9;
}

.custom-scroll::-webkit-scrollbar {
    width: 8px;
}

.custom-scroll::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 10px;
}

.custom-scroll::-webkit-scrollbar-thumb {
    background: #16a34a;
    border-radius: 10px;
}

.custom-scroll::-webkit-scrollbar-thumb:hover {
    background: #15803d;
}
</style>