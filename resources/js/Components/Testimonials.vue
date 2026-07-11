<script setup>
import {
    StarFilled,
    MessageOutlined
} from '@ant-design/icons-vue'

const testimonials = [
    {
        name: 'Sarah Banda',
        location: 'Lusaka',
        image: '/assets/team/bg1.jpg',
        review: 'The vegetables are always fresh and the chickens are healthy. Alinaswe Farm never disappoints.'
    },
    {
        name: 'Peter Phiri',
        location: 'Ndola',
        image: '/assets/team/bg2.jpg',
        review: 'Excellent customer service and quality produce. I highly recommend Alinaswe Farm.'
    },
    {
        name: 'Mary Zulu',
        location: 'Kabwe',
        image: '/assets/team/bg3.jpg',
        review: 'Fresh vegetables every week and quality chickens at affordable prices.'
    }
]
</script>

<template>
<section class="py-24 bg-gradient-to-b from-green-50 to-white">

    <div class="max-w-7xl mx-auto px-6">

        <!-- Heading -->
        <div class="text-center mb-16">

            <span
                class="inline-block text-green-600 font-semibold tracking-widest uppercase">
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

        <div class="grid lg:grid-cols-3 md:grid-cols-2 gap-8">

            <div
                v-for="item in testimonials"
                :key="item.name"
                class="relative bg-white rounded-3xl shadow-lg hover:shadow-2xl transition duration-300 p-8 border border-green-100">

                <MessageOutlined
                    class="absolute right-6 top-6 text-5xl text-green-100"/>

                <div class="flex items-center gap-4">

                    <img
                        :src="item.image"
                        :alt="item.name"
                        class="w-20 h-20 rounded-full object-cover border-4 border-green-100">

                    <div>

                        <h3 class="font-bold text-xl text-slate-800">
                            {{ item.name }}
                        </h3>

                        <p class="text-green-600">
                            {{ item.location }}
                        </p>

                        <div class="flex mt-2">

                            <StarFilled
                                v-for="i in 5"
                                :key="i"
                                class="text-yellow-400 text-sm mr-1"/>

                        </div>

                    </div>

                </div>

                <p class="text-gray-600 leading-8 mt-6 italic">
                    "{{ item.review }}"
                </p>

            </div>

        </div>

    </div>

</section>
</template>