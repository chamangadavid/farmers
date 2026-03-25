<!-- resources/js/Components/Management/EditMemberModal.vue -->
<script setup>
import { ref, watch } from 'vue';
import { Modal, Form, Input, Upload, Button, message } from 'ant-design-vue';
import { UploadOutlined } from '@ant-design/icons-vue';
import axios from 'axios';

const props = defineProps({
    open: {
        type: Boolean,
        default: false
    },
    member: {
        type: Object,
        default: null
    }
});

const emit = defineEmits(['close', 'success']);

const formRef = ref();
const loading = ref(false);
const formData = ref({
    id: null,
    fullname: '',
    position: '',
    content: '',
    image: null,
    existing_image: null
});

const imageUrl = ref('');

// Load member data when modal opens
watch(() => props.open, (newVal) => {
    if (newVal && props.member) {
        loadMemberData();
    }
});

watch(() => props.member, () => {
    if (props.open && props.member) {
        loadMemberData();
    }
});

const loadMemberData = () => {
    formData.value = {
        id: props.member.id,
        fullname: props.member.fullname,
        position: props.member.position,
        content: props.member.content,
        image: null,
        existing_image: props.member.image
    };
    imageUrl.value = props.member.image ? `/storage/${props.member.image}` : '';
};

const handleSubmit = async () => {
    try {
        loading.value = true;
        
        const formDataToSend = new FormData();
        formDataToSend.append('_method', 'PUT');
        formDataToSend.append('fullname', formData.value.fullname);
        formDataToSend.append('position', formData.value.position);
        formDataToSend.append('content', formData.value.content);
        
        if (formData.value.image) {
            formDataToSend.append('image', formData.value.image);
        }

        const response = await axios.post(`/members/${formData.value.id}`, formDataToSend, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        });

        if (response.data.success) {
            message.success('Member updated successfully');
            emit('success');
            emit('close');
        } else {
            message.error(response.data.message || 'Failed to update member');
        }
    } catch (error) {
        console.error('Error updating member:', error);
        message.error(error.response?.data?.message || 'Failed to update member');
    } finally {
        loading.value = false;
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
    return false;
};
</script>

<template>
    <Modal
        :open="open"
        title="Edit Member"
        :confirmLoading="loading"
        @ok="handleSubmit"
        @cancel="emit('close')"
        width="600px"
        okText="Update"
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
                    placeholder="Enter position"
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
                >
                    <Button>
                        <UploadOutlined /> Change Image
                    </Button>
                </Upload>
                <div v-if="imageUrl" class="mt-3">
                    <img :src="imageUrl" alt="Preview" style="max-width: 150px; max-height: 150px; object-fit: cover; border-radius: 8px;" />
                    <div class="mt-2">
                        <Button size="small" @click="imageUrl = formData.existing_image ? `/storage/${formData.existing_image}` : ''; formData.image = null">Reset</Button>
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
                    placeholder="Enter member's biography"
                    :rows="4"
                />
            </Form.Item>
        </Form>
    </Modal>
</template>