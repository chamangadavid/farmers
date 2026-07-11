<script setup>
import { ref, watch } from 'vue'
import {
    Modal,
    Input,
    Upload,
    Button,
    Switch,
    message
} from 'ant-design-vue'
import {
    UploadOutlined
} from '@ant-design/icons-vue'
import axios from 'axios'

const props = defineProps({

    open: Boolean,

    testimonial: Object

})

const emit = defineEmits([

    'update:open',

    'updated'

])

const localOpen = ref(props.open)

watch(() => props.open, value => {

    localOpen.value = value

})

watch(localOpen, value => {

    emit('update:open', value)

})

/*
|--------------------------------------------------------------------------
| Form Fields
|--------------------------------------------------------------------------
*/

const name = ref('')

const location = ref('')

const review = ref('')

const status = ref(true)

const image = ref(null)

const previewImage = ref(null)

/*
|--------------------------------------------------------------------------
| Populate Form
|--------------------------------------------------------------------------
*/

watch(

    () => props.testimonial,

    (testimonial) => {

        if (!testimonial) return

        name.value = testimonial.name ?? ''

        location.value = testimonial.location ?? ''

        review.value = testimonial.review ?? ''

        status.value = Boolean(testimonial.status)

        image.value = null

        previewImage.value = testimonial.image
            ? testimonial.image.startsWith('http')
                ? testimonial.image
                : `/storage/${testimonial.image}`
            : null

    },

    {

        immediate: true

    }

)

/*
|--------------------------------------------------------------------------
| Upload
|--------------------------------------------------------------------------
*/

const beforeUpload = (file) => {

    image.value = file

    previewImage.value = URL.createObjectURL(file)

    return false

}

/*
|--------------------------------------------------------------------------
| Update
|--------------------------------------------------------------------------
*/

const updateTestimonial = async () => {

    if (!props.testimonial) return

    const formData = new FormData()

    formData.append('_method', 'PUT')

    formData.append('name', name.value)

    formData.append('location', location.value)

    formData.append('review', review.value)

    formData.append('status', status.value ? 1 : 0)

    if (image.value) {

        formData.append('image', image.value)

    }

    try {

        await axios.post(

            `/testimonials/${props.testimonial.id}`,

            formData,

            {

                headers: {

                    'Content-Type': 'multipart/form-data'

                }

            }

        )

        message.success('Testimonial updated successfully.')

        emit('updated')

        localOpen.value = false

    }

    catch (error) {

        if (error.response?.data?.errors) {

            Object.values(error.response.data.errors)
                .flat()
                .forEach(err => message.error(err))

        } else {

            message.error('Unable to update testimonial.')

        }

    }

}
</script>

<template>

<Modal
    v-model:open="localOpen"
    title="Edit Testimonial"
    width="700px"
    @ok="updateTestimonial"
>

    <!-- Customer Name -->

    <div class="mb-5">

        <label class="font-semibold">

            Customer Name

        </label>

        <Input

            v-model:value="name"

            placeholder="Customer Name"

        />

    </div>

    <!-- Location -->

    <div class="mb-5">

        <label class="font-semibold">

            Location

        </label>

        <Input

            v-model:value="location"

            placeholder="Location"

        />

    </div>

    <!-- Review -->

    <div class="mb-5">

        <label class="font-semibold">

            Review

        </label>

        <Input.TextArea

            v-model:value="review"

            :rows="5"

            placeholder="Customer Review"

        />

    </div>

    <!-- Image Upload -->

    <div class="mb-5">

        <label class="font-semibold">

            Customer Photo

        </label>

        <br><br>

        <Upload

            :before-upload="beforeUpload"

            :file-list="image ? [image] : []"

            list-type="picture"

            accept="image/*"

            :max-count="1"

        >

            <Button>

                <UploadOutlined />

                Change Image

            </Button>

        </Upload>

    </div>

    <!-- Preview -->

    <div
        v-if="previewImage"
        class="mb-5 text-center"
    >

        <img

            :src="previewImage"

            class="w-40 h-40 rounded-full object-cover border mx-auto"

        >

    </div>

    <!-- Status -->

    <div>

        <label class="font-semibold">

            Active

        </label>

        <br><br>

        <Switch

            v-model:checked="status"

            checked-children="Yes"

            un-checked-children="No"

        />

    </div>

</Modal>

</template>