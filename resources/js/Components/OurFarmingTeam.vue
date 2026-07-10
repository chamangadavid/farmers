<script setup>
import { TeamOutlined } from '@ant-design/icons-vue'

const team = [
    {
        name: 'John Banda',
        role: 'Farm Manager',
        image: '/assets/team/bg1.jpg'
    },
    {
        name: 'Mary Phiri',
        role: 'Vegetable Specialist',
        image: '/assets/team/bg2.jpg'
    },
    {
        name: 'Peter Zulu',
        role: 'Poultry Supervisor',
        image: '/assets/team/bg3.jpg'
    },
    {
        name: 'Grace Mwila',
        role: 'Harvest Coordinator',
        image: '/assets/team/bg4.jpg'
    }
]
</script>

<template>
<section class="py-24 bg-gradient-to-b from-green-50 to-white">

    <div class="max-w-7xl mx-auto px-6">

        <!-- Heading -->
        <div class="text-center mb-16">

            <div
                class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-green-100 mb-5">

                <TeamOutlined class="text-3xl text-green-600"/>

            </div>

            <h2 class="text-5xl font-bold text-slate-800 mb-4">
                Our Farming Team
            </h2>

            <p class="text-gray-500 max-w-2xl mx-auto">
                Meet the dedicated people behind Alinaswe Farm, working every
                day to grow fresh vegetables and raise healthy chickens with
                passion and care.
            </p>

        </div>

        <!-- Team -->
        <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-4">

            <div
                v-for="member in team"
                :key="member.name"
                class="bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition duration-300 group">

                <div class="overflow-hidden">

                    <img
                        :src="member.image"
                        :alt="member.name"
                        class="w-full h-80 object-cover group-hover:scale-110 transition duration-500"/>

                </div>

                <div class="p-6 text-center">

                    <h3 class="text-2xl font-bold text-slate-800">
                        {{ member.name }}
                    </h3>

                    <p class="text-green-600 font-medium mt-2">
                        {{ member.role }}
                    </p>

                </div>

            </div>

        </div>

    </div>

</section>
</template>