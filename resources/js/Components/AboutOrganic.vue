<script setup>
import { CheckOutlined } from '@ant-design/icons-vue'

const features = [
    {
        title: 'Fresh Vegetables',
        description:
            'We grow a variety of fresh, nutritious vegetables that are carefully cultivated to provide quality and freshness every day.'
    },
    {
        title: 'Quality Chickens',
        description:
            'Our healthy chickens are raised with care, ensuring quality poultry products for your home and business.'
    },
    {
        title: 'Trusted Farm Produce',
        description:
            'Alinaswe Farm is committed to providing fresh vegetables and quality chickens, delivering farm-fresh products you can trust.'
    }
];

</script>

<template>
    <section class="bg-white py-24">
        <div class="max-w-7xl mx-auto px-6">

            <div class="grid lg:grid-cols-2 gap-20 items-center">

                <!-- Left -->
                <div>

                    <!-- Heading -->
                    <h2 class="text-5xl lg:text-6xl font-extrabold text-slate-800 leading-tight mb-8">
                        Fresh From
                        <br>
                        Alinaswe Farm
                    </h2>

                    <!-- Feature List -->
                    <div
                        v-for="feature in features"
                        :key="feature.title"
                        class="flex gap-6 mb-5">

                        <div
                            class="w-14 h-14 rounded-full bg-orange-400 flex items-center justify-center shadow flex-shrink-0">
                            <CheckOutlined class="text-white text-xl"/>
                        </div>

                        <div>
                            <h3
                                class="text-2xl font-bold text-slate-800 mb-2">

                                {{ feature.title }}

                            </h3>

                            <p
                                class="text-gray-500 leading-8">

                                {{ feature.description }}

                            </p>

                        </div>

                    </div>

                </div>

                <!-- Right -->

                <div class="flex justify-center">

                    <img
                        src="/assets/fruit-basket.png"
                        alt=""
                        class="w-full max-w-xl object-contain"
                    >

                </div>

            </div>

        </div>
    </section>
</template>