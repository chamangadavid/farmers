<!-- resources/js/Components/Management/EditMemberModal.vue -->
<!-- resources/js/Components/Management/EditMemberModal.vue -->
<script setup>
import { ref, watchEffect } from 'vue';
import { Modal, Form, Input, Upload, Button, message } from 'ant-design-vue';
import { UploadOutlined } from '@ant-design/icons-vue';
import axios from 'axios';

// Import Quill editor
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
const quillContent = ref('');

// Watch for modal open and selected member to populate the form
watchEffect(() => {
  if (props.open && props.member) {
    formData.value.id = props.member.id;
    formData.value.fullname = props.member.fullname;
    formData.value.position = props.member.position;
    formData.value.content = props.member.content || '';
    formData.value.existing_image = props.member.image;
    formData.value.image = null;

    quillContent.value = props.member.content || '';
    imageUrl.value = props.member.image ? `/storage/${props.member.image}` : '';
  }
});

// Handle form submit
const handleSubmit = async () => {
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
    formDataToSend.append('_method', 'PUT');
    formDataToSend.append('fullname', formData.value.fullname);
    formDataToSend.append('position', formData.value.position);
    formDataToSend.append('content', quillContent.value);

    if (formData.value.image) {
      formDataToSend.append('image', formData.value.image);
    }

    const response = await axios.post(`/members/${formData.value.id}`, formDataToSend, {
      headers: { 'Content-Type': 'multipart/form-data' }
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

// Handle image upload
const beforeUpload = (file) => {
  const isImage = ['image/jpeg', 'image/png', 'image/jpg'].includes(file.type);
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

const handleEditorChange = (value) => {
  quillContent.value = value;
};
</script>

<template>
  <Modal
    :open="open"
    title="Edit Member"
    :confirmLoading="loading"
    @ok="handleSubmit"
    @cancel="emit('close')"
    width="900px"
    okText="Update"
    cancelText="Cancel"
    :destroyOnClose="true"
  >
    <Form :model="formData" layout="vertical" ref="formRef">
      <Form.Item label="Full Name" name="fullname">
        <Input
          v-model:value="formData.fullname"
          placeholder="Enter full name"
          size="small"
          style="border: 1px solid #e9e9e9; border-radius: 8px;"
        />
      </Form.Item>

      <Form.Item label="Position" name="position">
        <Input
          v-model:value="formData.position"
          placeholder="Enter position"
          size="small"
          style="border: 1px solid #e9e9e9; border-radius: 8px;"
        />
      </Form.Item>

      <Form.Item label="Content/Bio" name="content">
        <div class="quill-editor-wrapper">
          <QuillEditor
            v-model:content="quillContent"
            contentType="html"
            :options="{ modules: quillModules, formats: quillFormats, placeholder: 'Write member bio...' }"
            @update:content="handleEditorChange"
          />
        </div>
        <div class="text-xs text-gray-500 mt-2">
          Tip: You can format text, add links, images, and more using the toolbar above.
        </div>
      </Form.Item>

      <Form.Item label="Profile Image" name="image">
        <Upload
          :beforeUpload="beforeUpload"
          :showUploadList="false"
        >
          <Button>
            <UploadOutlined /> Change Image
          </Button>
        </Upload>
        <div v-if="imageUrl" class="mt-3">
          <img
            :src="imageUrl"
            alt="Preview"
            style="max-width: 150px; max-height: 150px; object-fit: cover; border-radius: 8px;"
          />
          <div class="mt-2">
            <Button size="small" @click="imageUrl = formData.existing_image ? `/storage/${formData.existing_image}` : ''; formData.image = null">
              Reset
            </Button>
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
</style>