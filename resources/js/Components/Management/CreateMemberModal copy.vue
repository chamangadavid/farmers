<!-- resources/js/Components/Management/CreateMemberModal.vue -->
<script setup>
import { ref, watch } from 'vue';
import { Modal, Form, Input, Upload, Button, message } from 'ant-design-vue';
import { UploadOutlined } from '@ant-design/icons-vue';
import axios from 'axios';

const props = defineProps({
    open: {
        type: Boolean,
        default: false
    }
});

const emit = defineEmits(['close', 'success']);

const formRef = ref();
const loading = ref(false);
const formData = ref({
    fullname: '',
    position: '',
    content: '',
    image: null
});

const imageUrl = ref('');
const imagePreviewopen = ref(false);

// Reset form when modal opens
watch(() => props.open, (newVal) => {
    if (newVal) {
        resetForm();
    }
});

const resetForm = () => {
    formData.value = {
        fullname: '',
        position: '',
        content: '',
        image: null
    };
    imageUrl.value = '';
};

const handleSubmit = async () => {
    try {
        loading.value = true;
        
        const formDataToSend = new FormData();
        formDataToSend.append('fullname', formData.value.fullname);
        formDataToSend.append('position', formData.value.position);
        formDataToSend.append('content', formData.value.content);
        
        if (formData.value.image) {
            formDataToSend.append('image', formData.value.image);
        }

        const response = await axios.post('/members', formDataToSend, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        });

        if (response.data.success) {
            message.success('Member created successfully');
            emit('success');
            emit('close');
        } else {
            message.error(response.data.message || 'Failed to create member');
        }
    } catch (error) {
        console.error('Error creating member:', error);
        message.error(error.response?.data?.message || 'Failed to create member');
    } finally {
        loading.value = false;
    }
};

const handleImageChange = (info) => {
    if (info.file.status === 'done') {
        // Handle successful upload
        formData.value.image = info.file.originFileObj;
        imageUrl.value = URL.createObjectURL(info.file.originFileObj);
    } else if (info.file.status === 'error') {
        message.error('Image upload failed');
    }
};

const beforeUpload = (file) => {
    const isImage = file.type === 'image/jpeg' || file.type === 'image/png' || file.type === 'image/jpg';
    if (!isImage) {
        message.error('You can only upload JPG/PNG files!');
        return false;
    }
    const isLt2M = file.size / 1024 / 1024 < 2;
    if (!isLt2M) {
        message.error('Image must be smaller than 2MB!');
        return false;
    }
    formData.value.image = file;
    imageUrl.value = URL.createObjectURL(file);
    return false; // Prevent auto upload
};
</script>

<template>
    <Modal
        :open="open"
        title="Add New Member"
        :confirmLoading="loading"
        @ok="handleSubmit"
        @cancel="emit('close')"
        width="600px"
        okText="Create"
        cancelText="Cancel"
    >
        <Form :model="formData" layout="vertical" ref="formRef">
            <Form.Item
                label="Full Name"
                name="fullname"
                :rules="[{ required: true, message: 'Please enter full name' }]"
            >
                <Input
                    v-model:value="formData.fullname"
                    placeholder="Enter full name"
                    size="large"
                />
            </Form.Item>

            <Form.Item
                label="Position"
                name="position"
                :rules="[{ required: true, message: 'Please enter position' }]"
            >
                <Input
                    v-model:value="formData.position"
                    placeholder="Enter position (e.g., Director, Manager)"
                    size="large"
                />
            </Form.Item>

            <Form.Item
                label="Profile Image"
                name="image"
            >
                <Upload
                    :beforeUpload="beforeUpload"
                    :showUploadList="false"
                    @change="handleImageChange"
                >
                    <Button v-if="!imageUrl">
                        <UploadOutlined /> Upload Image
                    </Button>
                </Upload>
                <div v-if="imageUrl" class="mt-3">
                    <img :src="imageUrl" alt="Preview" style="max-width: 150px; max-height: 150px; object-fit: cover; border-radius: 8px;" />
                    <div class="mt-2">
                        <Button size="small" @click="imageUrl = ''; formData.image = null">Remove</Button>
                    </div>
                </div>
                <div class="text-xs text-gray-500 mt-1">
                    Supported formats: JPG, PNG. Max size: 2MB
                </div>
            </Form.Item>

            <Form.Item
                label="Content/Bio"
                name="content"
                :rules="[{ required: true, message: 'Please enter content' }]"
            >
                <Input.TextArea
                    v-model:value="formData.content"
                    placeholder="Enter member's biography, responsibilities, etc."
                    :rows="4"
                />
            </Form.Item>
        </Form>
    </Modal>
</template>