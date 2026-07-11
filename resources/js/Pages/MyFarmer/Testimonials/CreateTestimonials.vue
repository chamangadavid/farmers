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
    open: Boolean
})

const emit = defineEmits([
    'update:open',
    'created'
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
| Reset Form
|--------------------------------------------------------------------------
*/

const resetForm = () => {

    name.value = ''

    location.value = ''

    review.value = ''

    status.value = true

    image.value = null

    previewImage.value = null

}

/*
|--------------------------------------------------------------------------
| Create Testimonial
|--------------------------------------------------------------------------
*/

const createTestimonial = async () => {

    const formData = new FormData()

    formData.append('name', name.value)

    formData.append('location', location.value)

    formData.append('review', review.value)

    formData.append('status', status.value ? 1 : 0)

    if (image.value) {

        formData.append('image', image.value)

    }

    try {

        await axios.post('/testimonials', formData, {

            headers: {

                'Content-Type': 'multipart/form-data'

            }

        })

        message.success('Testimonial created successfully.')

        emit('created')

        localOpen.value = false

        resetForm()

    }

    catch (error) {

        if (error.response?.data?.errors) {

            Object.values(error.response.data.errors)
                .flat()
                .forEach(err => message.error(err))

        } else {

            message.error('Unable to create testimonial.')

        }

    }

}
</script>

<template>

<Modal
    v-model:open="localOpen"
    title="Create Testimonial"
    width="700px"
    @ok="createTestimonial">

    <!-- Customer Name -->

    <div class="mb-5">

        <label class="font-semibold">

            Customer Name

        </label>

        <Input

            v-model:value="name"

            placeholder="Enter customer name"

        />

    </div>

    <!-- Location -->

    <div class="mb-5">

        <label class="font-semibold">

            Location

        </label>

        <Input

            v-model:value="location"

            placeholder="Enter location"

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

            placeholder="Customer review"

        />

    </div>

    <!-- Image -->

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

            :max-count="1">

            <Button>

                <UploadOutlined />

                Select Image

            </Button>

        </Upload>

    </div>

    <!-- Preview -->

    <div
        v-if="previewImage"
        class="mb-5 text-center">

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