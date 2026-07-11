<script setup>
import {
    CheckCircleOutlined,
    SafetyCertificateOutlined,
    GlobalOutlined
} from '@ant-design/icons-vue';


const features = [
    {
        title: 'Farm Fresh Produce',
        description:
            'We grow fresh, nutritious vegetables that are harvested with care to ensure quality and freshness.',
        icon: CheckCircleOutlined,
        active: false
    },
    {
        title: 'Healthy Poultry',
        description:
            'Our chickens are raised with proper care and good farming practices to provide quality poultry products.',
        icon: SafetyCertificateOutlined,
        active: true
    },
    {
        title: 'Quality You Can Trust',
        description:
            'At Alinaswe Farm, we are committed to delivering fresh, wholesome farm products that families can rely on.',
        icon: GlobalOutlined,
        active: false
    }
];

</script>

<template>
    <section class="relative py-24 overflow-hidden bg-[#f7fff4]">

        <div class="max-w-7xl mx-auto px-6 relative z-10">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-10 items-center">

                <div v-for="feature in features" :key="feature.title" :class="[
                    feature.active
                        ? 'bg-white shadow-2xl scale-105'
                        : 'bg-transparent',
                    'text-center rounded-sm px-10 py-14 transition-all duration-500 hover:bg-white hover:shadow-xl hover:-translate-y-2'
                ]">

                    <!-- Icon -->
                    <div :class="[
                        feature.active
                            ? 'bg-orange-400 text-white'
                            : 'border-2 border-dashed border-orange-400 text-green-500',
                        'w-20 h-20 rounded-full mx-auto flex items-center justify-center text-4xl mb-8'
                    ]">

                        <component :is="feature.icon" />

                    </div>

                    <!-- Title -->
                    <h3 class="text-3xl font-bold text-slate-800 mb-5">
                        {{ feature.title }}
                    </h3>

                    <!-- Description -->
                    <p class="text-gray-500 leading-2 mb-2">
                        {{ feature.description }}
                    </p>

                    <a href="#" class="font-semibold text-green-600 hover:text-green-700 transition">
                        Read More
                    </a>

                </div>

            </div>

        </div>
    </section>
</template>

<style scoped>
section {
    background: linear-gradient(to right, #f9fff4, #f6fff1);
}

img {
    opacity: .95;
}
</style>