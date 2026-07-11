<!-- resources\js\Components\OurFarmingTeam.vue -->
<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import { TeamOutlined } from '@ant-design/icons-vue'

const team = ref([])

const fetchTeam = async () => {

    try {

        const { data } = await axios.get('/public-members')

        team.value = data.members.map(member => ({

            id: member.id,

            name: member.fullname,

            role: member.position,

            image: member.image

        }))

    }

    catch (error) {

        console.error('Failed to fetch team members', error)

    }

}

onMounted(() => {

    fetchTeam()

})
</script>

<template>
    <section class="py-24 bg-gradient-to-b from-green-50 to-white">

        <div class="max-w-7xl mx-auto px-6">

            <!-- Heading -->
            <div class="text-center mb-16">

                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-green-100 mb-5">

                    <TeamOutlined class="text-3xl text-green-600" />

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

            <div class="max-h-[450px] overflow-y-auto pr-3 custom-scroll">

                <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-4">

                    <div v-for="member in team" :key="member.id"
                        class="bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition duration-300 group">

                        <div class="overflow-hidden">

                            <img :src="`/storage/${member.image}`" :alt="member.name"
                                class="w-full h-80 object-cover group-hover:scale-110 transition duration-500" />

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