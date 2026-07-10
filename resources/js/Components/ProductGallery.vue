<script setup>
const gallery = [
    { id: 1, image: '/assets/products/product1.jpg' },
    { id: 2, image: '/assets/products/product2.jpg' },
    { id: 3, image: '/assets/products/product3.jpg' },
    { id: 4, image: '/assets/products/product4.jpg' },
    { id: 5, image: '/assets/products/product5.jpg' },
    { id: 6, image: '/assets/products/product6.jpg' },
]
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
            <div
                class="grid grid-cols-2 lg:grid-cols-3 gap-5">

                <div
                    v-for="item in gallery"
                    :key="item.id"
                    class="overflow-hidden group cursor-pointer">

                    <img
                        :src="item.image"
                        class="w-full h-72 object-cover transition duration-500 group-hover:scale-110"
                    >

                </div>

            </div>

        </div>

    </section>
</template>