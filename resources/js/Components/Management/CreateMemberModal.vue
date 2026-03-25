<!-- resources/js/Components/Management/CreateMemberModal.vue -->
<script setup>
import { ref, watch, onMounted } from 'vue';
import { Modal, Form, Input, Upload, Button, message } from 'ant-design-vue';
import { UploadOutlined } from '@ant-design/icons-vue';
import axios from 'axios';

// Import vue3-quill
import { QuillEditor } from '@vueup/vue-quill';
import '@vueup/vue-quill/dist/vue-quill.snow.css';

// Quill editor configuration
const quillModules = {
  toolbar: [
    ['bold', 'italic', 'underline', 'strike'],
    ['blockquote', 'code-block'],
    [{ 'header': 1 }, { 'header': 2 }],
    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
    [{ 'script': 'sub'}, { 'script': 'super' }],
    [{ 'indent': '-1'}, { 'indent': '+1' }],
    [{ 'direction': 'rtl' }],
    [{ 'size': ['small', false, 'large', 'huge'] }],
    [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
    [{ 'color': [] }, { 'background': [] }],
    [{ 'font': [] }],
    [{ 'align': [] }],
    ['clean'],
    ['link', 'image', 'video']
  ]
};

const quillFormats = [
  'header', 'font', 'size',
  'bold', 'italic', 'underline', 'strike', 'blockquote',
  'list', 'bullet', 'indent',
  'link', 'image', 'video',
  'color', 'background', 'align',
  'script', 'code-block', 'direction'
];

const props = defineProps({
    visible: {
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
const quillContent = ref('');

// Watch for modal visibility
watch(() => props.visible, (newVal) => {
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
    quillContent.value = '';
    imageUrl.value = '';
};

const handleSubmit = async () => {
    // Validate required fields
    if (!formData.value.fullname) {
        message.error('Please enter full name');
        return;
    }
    if (!formData.value.position) {
        message.error('Please enter position');
        return;
    }
    if (!quillContent.value || quillContent.value === '<p><br></p>' || quillContent.value.trim() === '') {
        message.error('Please enter content/bio');
        return;
    }

    try {
        loading.value = true;
        
        const formDataToSend = new FormData();
        formDataToSend.append('fullname', formData.value.fullname);
        formDataToSend.append('position', formData.value.position);
        formDataToSend.append('content', quillContent.value);
        
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
    return false;
};

// Handle editor content change
const handleEditorChange = (value) => {
    quillContent.value = value;
};
</script>

<template>
    <Modal
        :open="visible"
        title="Add New Member"
        :confirmLoading="loading"
        @ok="handleSubmit"
        @cancel="emit('close')"
        width="900px"
        okText="Create"
        cancelText="Cancel"
        :destroyOnClose="true"
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
                    size="small"
                    style="border: 1px solid #e9e9e9; border-radius: 8px;"
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
                    size="small"
                    style="border: 1px solid #e9e9e9; border-radius: 8px;"
                />
            </Form.Item>

            <Form.Item
                label="Content/Bio"
                name="content"
                :rules="[{ required: true, message: 'Please enter content' }]"
            >
                <div class="quill-editor-wrapper">
                    <QuillEditor
                        v-model:content="quillContent"
                        contentType="html"
                        :options="{
                            modules: quillModules,
                            formats: quillFormats,
                            placeholder: 'Write member\'s biography, responsibilities, achievements, etc...'
                        }"
                        @update:content="handleEditorChange"
                    />
                </div>
                <div class="text-xs text-gray-500 mt-2">
                    Tip: You can format text, add links, images, and more using the toolbar above.
                </div>
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

        </Form>
    </Modal>
</template>

<style scoped>
.quill-editor-wrapper {
    margin-bottom: 20px;
    position: relative;
    z-index: 1;
}

/* Ensure Quill editor is interactive */
:deep(.ql-container) {
    min-height: 250px;
    font-size: 14px;
    font-family: inherit;
    background-color: white;
}

:deep(.ql-editor) {
    min-height: 250px;
    background-color: white;
    color: #1f2937;
}

:deep(.ql-editor.ql-blank::before) {
    color: #9ca3af;
    font-style: normal;
}

:deep(.ql-toolbar) {
    border-top-left-radius: 8px;
    border-top-right-radius: 8px;
    background-color: #f8fafc;
    border-color: #e2e8f0;
    position: sticky;
    top: 0;
    z-index: 2;
}

:deep(.ql-container) {
    border-bottom-left-radius: 8px;
    border-bottom-right-radius: 8px;
    border-color: #e2e8f0;
}

/* Make toolbar buttons visible */
:deep(.ql-toolbar button) {
    color: #374151;
}

:deep(.ql-toolbar button:hover) {
    color: #14b8a6;
}

:deep(.ql-toolbar .ql-active) {
    color: #14b8a6;
}

/* Ensure editor content is editable */
:deep(.ql-editor) {
    cursor: text;
}

:deep(.ql-editor:focus) {
    outline: none;
}

/* Style for images in editor */
:deep(.ql-editor img) {
    max-width: 100%;
    height: auto;
    border-radius: 8px;
    margin: 0.5rem 0;
}

/* Style for lists */
:deep(.ql-editor ul),
:deep(.ql-editor ol) {
    padding-left: 1.5rem;
    margin-bottom: 0.75rem;
}

/* Style for blockquotes */
:deep(.ql-editor blockquote) {
    border-left: 4px solid #14b8a6;
    padding-left: 1rem;
    margin: 0.75rem 0;
    color: #4b5563;
}
</style>