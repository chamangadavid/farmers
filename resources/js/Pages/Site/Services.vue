<script setup>
import PageLayout from '@/Components/PageLayout.vue';
import axios from 'axios';
import { message } from 'ant-design-vue';
import { ref } from 'vue';
import { AppstoreOutlined, ShopOutlined, GiftOutlined, InboxOutlined } from '@ant-design/icons-vue'

const services = [
    {
        title: 'Agriculture Products',
        description: 'High-quality agricultural products grown using sustainable farming methods.',
        image: '/assets/products/product2.jpg',
        icon: AppstoreOutlined
    },
    {
        title: 'Poutry Products',
        description: 'Healthy organic produce cultivated naturally without harmful chemicals.',
        image: '/assets/products/product3.jpg',
        icon: ShopOutlined
    },
    {
        title: 'Fresh Vegetables',
        description: 'Fresh vegetables harvested daily to maintain their taste and nutrition.',
        image: '/assets/products/back-2.png',
        icon: GiftOutlined
    },
    {
        title: 'Dairy Products',
        description: 'Fresh dairy products from healthy livestock raised with proper care.',
        image: '/assets/products/product7.jpg',
        icon: InboxOutlined
    }
]
</script>

<template>
    <PageLayout title="Report an Accident" :canLogin="canLogin" :canRegister="canRegister" :showHero="true">
        <section class="relative py-24 bg-[#f8f6f1] overflow-hidden">

            <!-- Background Pattern -->
            <div class="absolute inset-0 opacity-5 bg-center bg-repeat"
                style="background-image:url('/assets/banner1.jpg');">
            </div>

            <div class="relative max-w-7xl mx-auto px-6">

                <!-- Heading -->

                <div class="text-center mb-16">

                    <p class="text-green-700 font-semibold tracking-wide">
                        What We're Doing
                    </p>

                    <h2 class="text-5xl font-light uppercase tracking-wide text-[#223218] mt-3">
                        Services We're Offering
                    </h2>

                    <div class="flex justify-center mt-5">
                        <div class="w-24 h-1 bg-yellow-400 rounded-full">
                        </div>
                    </div>
                </div>

                <!-- Cards -->

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">

                    <div v-for="service in services" :key="service.title"
                        class="relative bg-white rounded-xl shadow-lg hover:shadow-2xl transition duration-500 overflow-hidden group pt-20 pb-10 px-8 text-center">

                        <!-- Image -->
                        <div class="flex justify-center">
                            <img :src="service.image" class="w-40 h-40 rounded-full object-cover">
                        </div>

                        <!-- Icon -->
                        <div class="absolute left-1/2 -translate-x-1/2 top-[180px]
                            w-16 h-16 rounded-full bg-green-700
                            flex items-center justify-center shadow-lg">

                            <component :is="service.icon" class="text-white text-3xl" />

                        </div>

                        <!-- Content -->

                        <div class="mt-12">
                            <h3 class="text-2xl font-bold text-gray-800">
                                {{ service.title }}
                            </h3>
                            <p class="mt-5 text-gray-500 leading-8">

                                {{ service.description }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </PageLayout>
</template>