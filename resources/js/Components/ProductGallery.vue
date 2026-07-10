<script setup>
import { ref } from 'vue'

const products = ref([
    {
        id: 1,
        category: 'Tomatoes',
        title: 'Fresh Tomatoes',
        description: 'Locally grown, fresh tomatoes delivered to your doorstep.',
        image: '/assets/products/product1.jpg'
    },
     {
        id: 2,
        category: 'Vegetables',
        title: 'Fresh Vegetables',
        description: 'Locally grown, fresh vegetables delivered to your doorstep.',
        image: '/assets/products/product2.jpg'
    },
     {
        id: 3,
        category: 'Chickens',
        title: 'Healthy Chickens',
        description:'Raised in humane conditions, our chickens provide fresh meat.',
        image: '/assets/products/product3.jpg'
    },

])
</script>

<template>
    <section class="bg-[#f7fbf4] py-20">
        <div class="max-w-7xl mx-auto px-6">

            <!-- Header -->
            <div class="text-center mb-14">

                <div
                    class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-green-100 mb-4">
                    <svg
                        class="w-7 h-7 text-green-600"
                        fill="currentColor"
                        viewBox="0 0 20 20">
                        <path
                            d="M10 2C7 4 5 7 5 10a5 5 0 0010 0c0-3-2-6-5-8z" />
                    </svg>
                </div>

                <h2
                    class="text-5xl font-bold text-slate-800 mb-5">
                    Product Gallery
                </h2>

                <p
                    class="text-gray-500 max-w-2xl mx-auto text-sm">
                    We are committed to growing fresh vegetables and raising healthy chickens, 
                    providing quality farm produce that brings freshness, nutrition, and value to every home.
                </p>

            </div>

            <!-- Cards -->
            <div
                class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">

                <div
                    v-for="product in products"
                    :key="product.id"
                    class="bg-white shadow-lg hover:shadow-2xl transition duration-300 overflow-hidden group">

                    <div class="overflow-hidden h-72">

                        <img
                            :src="product.image"
                            class="w-full h-full object-cover group-hover:scale-110 transition duration-500" />

                    </div>

                    <div class="p-8">

                        <span
                            class="inline-block bg-green-600 text-white text-xs uppercase font-semibold px-5 py-2 mb-2">
                            {{ product.category }}
                        </span>

                        <h3
                            class="text-3xl font-bold text-slate-800 leading-tight">
                            {{ product.title }}
                        </h3>

                        <p
                            class="text-gray-500 leading-8">
                            {{ product.description }}
                        </p>

                        <button
                            class="flex items-center font-semibold text-slate-800 hover:text-green-600 transition">

                            <span
                                class="w-8 h-1 bg-green-600 mr-3"></span>

                            VIEW PRODUCT

                        </button>

                    </div>

                </div>

            </div>

        </div>
    </section>
</template>