<script setup>
import { ref, watch, computed } from 'vue'
import { Modal, Tag, Avatar } from 'ant-design-vue'
import { UserOutlined } from '@ant-design/icons-vue'

const props = defineProps({

    open: Boolean,

    testimonial: Object

})

const emit = defineEmits([

    'update:open'

])

const localOpen = ref(props.open)

watch(() => props.open, value => {

    localOpen.value = value

})

watch(localOpen, value => {

    emit('update:open', value)

})

const imageUrl = computed(() => {

    if (!props.testimonial?.image) {

        return null

    }

    return props.testimonial.image.startsWith('http')

        ? props.testimonial.image

        : `/storage/${props.testimonial.image}`

})
</script>

<template>

<Modal

    v-model:open="localOpen"

    title="Testimonial Details"

    :footer="null"

    width="700px"

>

    <div
        v-if="testimonial"
        class="space-y-6"
    >

        <!-- Profile -->

        <div class="flex flex-col items-center">

            <Avatar

                v-if="imageUrl"

                :size="140"

                :src="imageUrl"

            />

            <Avatar

                v-else

                :size="140"

            >

                <UserOutlined />

            </Avatar>

            <h2
                class="mt-5 text-2xl font-bold text-gray-800"
            >

                {{ testimonial.name }}

            </h2>

            <p
                class="text-gray-500"
            >

                {{ testimonial.location }}

            </p>

            <Tag

                class="mt-4"

                :color="testimonial.status ? 'green' : 'red'"

            >

                {{ testimonial.status ? 'Active' : 'Inactive' }}

            </Tag>

        </div>

        <!-- Information -->

        <div
            class="border rounded-xl p-6 bg-gray-50"
        >

            <h3
                class="font-semibold text-lg mb-4 text-gray-700"
            >

                Customer Review

            </h3>

            <p
                class="leading-8 text-gray-600 whitespace-pre-line"
            >

                {{ testimonial.review }}

            </p>

        </div>

        <!-- Footer Information -->

        <div
            class="grid grid-cols-2 gap-6 text-sm border-t pt-6"
        >

            <div>

                <p class="text-gray-500">

                    Created

                </p>

                <p class="font-semibold">

                    {{ testimonial.created_at }}

                </p>

            </div>

            <div>

                <p class="text-gray-500">

                    Last Updated

                </p>

                <p class="font-semibold">

                    {{ testimonial.updated_at }}

                </p>

            </div>

        </div>

    </div>

</Modal>

</template>