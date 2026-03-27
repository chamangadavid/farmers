<script setup>
import { ref, watch } from 'vue';
import { Modal, Input, Select, DatePicker, message, Upload, Button } from 'ant-design-vue';
import { UploadOutlined } from '@ant-design/icons-vue';
import axios from 'axios';
import dayjs from 'dayjs';

const props = defineProps({
    open: Boolean
});
const emit = defineEmits(['update:open', 'created']);

// Local reactive state for modal
const localOpen = ref(props.open);
watch(() => props.open, val => localOpen.value = val);
watch(localOpen, val => emit('update:open', val));

// Form fields
const title = ref('');
const type = ref('');
const location = ref('');
const deadline = ref(null); // dayjs object
const description = ref('');
const requirements = ref('');
const pdfFile = ref(null);

// Handle file before upload
const beforeUpload = (file) => {
    pdfFile.value = file; // store selected file
    return false; // prevent auto upload
};

// Create Job function
const createJob = async () => {
    const formData = new FormData();
    formData.append('title', title.value);
    formData.append('type', type.value);
    formData.append('location', location.value);
    formData.append('deadline', deadline.value ? deadline.value.format('YYYY-MM-DD') : '');
    formData.append('description', description.value);

    // Convert comma-separated requirements to array
    requirements.value.split(',')
        .map(r => r.trim())
        .filter(r => r)
        .forEach(r => formData.append('requirements[]', r));

    if (pdfFile.value) formData.append('pdf_file', pdfFile.value);

    try {
        await axios.post('/jobs', formData, { headers: { 'Content-Type': 'multipart/form-data' } });
        message.success('Job created successfully');
        emit('created');
        localOpen.value = false;

        // Reset form
        title.value = '';
        type.value = '';
        location.value = '';
        deadline.value = null;
        description.value = '';
        requirements.value = '';
        pdfFile.value = null;
    } catch (error) {
        console.error(error);
        message.error('Failed to create job');
    }
};
</script>

<template>
    <Modal v-model:open="localOpen" title="Create Job" @ok="createJob">
        <div class="mb-3">
            <label class="font-semibold">Job Title</label>
            <Input v-model:value="title" placeholder="Job Title"
                style="border: 1px solid #e9e9e9; border-radius: 8px;" />
        </div>

        <div class="mb-3">
            <label class="font-semibold">Job Type</label>
            <Select v-model:value="type" placeholder="Select Job Type" style="width:100%">
                <Select.Option value="Full-time">Full-time</Select.Option>
                <Select.Option value="Part-time">Part-time</Select.Option>
                <Select.Option value="Contract">Contract</Select.Option>
                <Select.Option value="Temporary">Temporary</Select.Option>
                <Select.Option value="Internship">Internship</Select.Option>
                <Select.Option value="Other">Other</Select.Option>
            </Select>
        </div>

        <div class="mb-3">
            <label class="font-semibold">Location</label>
            <Input v-model:value="location" style="border: 1px solid #e9e9e9; border-radius: 8px;"
                placeholder="Location" />
        </div>

        <div class="mb-3">
            <label class="font-semibold">Deadline</label>
            <DatePicker v-model:value="deadline" style="width:100%" />
        </div>

        <div class="mb-3">
            <label class="font-semibold">Description</label>
            <Input.TextArea v-model:value="description" placeholder="Job Description" rows="3" />
        </div>

        <div class="mb-3">
            <label class="font-semibold">Requirements (comma separated)</label>
            <Input.TextArea v-model:value="requirements" placeholder="Requirement1, Requirement2" rows="2" />
        </div>

        <div class="mb-3">
            <label class="font-semibold">Upload PDF (optional)</label>
            <Upload :before-upload="beforeUpload" :file-list="pdfFile ? [pdfFile] : []" accept=".pdf" max-count="1">
                <Button> Select PDF</Button>
            </Upload>
        </div>
    </Modal>
</template>