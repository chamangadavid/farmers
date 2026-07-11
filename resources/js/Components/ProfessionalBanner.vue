<script setup>

defineProps({
    title: {
        type: String,
        default: 'Alinaswe Farm'
    },
    description: {
        type: String,
        default: 'We grows fresh, nutritious vegetables and raises healthy chickens, delivering quality farm produce you can trust every day.'
    },
    buttonText: {
        type: String,
        default: 'Explore Our Farm'
    }
})


const background =
    '/assets/banner4.jpg'
</script>

<template>
    <section
        class="relative overflow-hidden py-24"
        :style="{
            backgroundImage: `url(${background})`,
            backgroundSize: 'cover',
            backgroundPosition: 'center'
        }"
    >
        <!-- Green Overlay -->
        <div class="absolute inset-0 bg-green-700/85"></div>

        <div
            class="relative max-w-7xl mx-auto px-6 lg:px-8 flex flex-col lg:flex-row items-center justify-between gap-10"
        >
            <!-- Left -->
            <div class="max-w-3xl">

                <h2
                    class="text-white text-4xl md:text-5xl font-bold leading-tight mb-6"
                >
                    {{ title }}
                </h2>

                <p
                    class="text-green-50 text-lg leading-8 max-w-2xl"
                >
                    {{ description }}
                </p>

            </div>

            <!-- Right -->
            <div class="flex-shrink-0">

                <button
                    class="bg-green-500 hover:bg-green-600 transition-all duration-300 text-white font-semibold px-12 py-4 rounded shadow-lg hover:shadow-xl"
                >
                    {{ buttonText }}
                </button>

            </div>
        </div>
    </section>
</template>