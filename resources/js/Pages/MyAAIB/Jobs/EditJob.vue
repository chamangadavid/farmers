<script setup>
import { ref, watch } from 'vue';
import { Modal, Input, Select, DatePicker, message, Upload, Button } from 'ant-design-vue';
import { UploadOutlined } from '@ant-design/icons-vue';
import axios from 'axios';
import dayjs from 'dayjs';

const props = defineProps({
    open: Boolean,
    job: Object,
});
const emit = defineEmits(['update:open', 'updated']);

// Modal state
const localOpen = ref(props.open);
watch(() => props.open, val => localOpen.value = val);
watch(localOpen, val => emit('update:open', val));

// Form fields
const title = ref('');
const type = ref('');
const location = ref('');
const deadline = ref(null);
const description = ref('');
const requirements = ref('');
const pdfFile = ref(null);

// Watch job prop to populate form
watch(() => props.job, (newJob) => {
    if (newJob) {
        title.value = newJob.title || '';
        type.value = newJob.type || '';
        location.value = newJob.location || '';
        deadline.value = newJob.deadline ? dayjs(newJob.deadline) : null;
        description.value = newJob.description || '';
        requirements.value = newJob.requirements ? newJob.requirements.join(', ') : '';
        pdfFile.value = null; // reset file input
    }
}, { immediate: true }); // immediate so it populates on first render

// File handling
const beforeUpload = (file) => {
    pdfFile.value = file;
    return false; // prevent auto-upload
};


const updateJob = async () => {
    if (!props.job) return;

    const formData = new FormData();
    formData.append('_method', 'PUT');
    formData.append('title', title.value);
    formData.append('type', type.value);
    formData.append('location', location.value);
    formData.append('deadline', deadline.value ? deadline.value.format('YYYY-MM-DD') : '');
    formData.append('description', description.value);

    // Convert requirements to array
    requirements.value.split(',')
        .map(r => r.trim())
        .filter(r => r)
        .forEach(r => formData.append('requirements[]', r));

    if (pdfFile.value) formData.append('pdf_file', pdfFile.value);

    try {
        await axios.post(`/jobs/${props.job.id}`, formData, {
            headers: { 'Content-Type': 'multipart/form-data' }
        });
        message.success('Job updated successfully');
        emit('updated');
        localOpen.value = false;
    } catch (error) {
        console.error(error);
        if (error.response?.data?.errors) {
            Object.values(error.response.data.errors).flat().forEach(msg => message.error(msg));
        } else {
            message.error('Failed to update job');
        }
    }
};

</script>

<template>
    <Modal v-model:open="localOpen" title="Edit Job" @ok="updateJob">
        <div class="mb-3">
            <label class="font-semibold">Job Title</label>
            <Input v-model:value="title" style="border: 1px solid #e9e9e9; border-radius: 8px;"
                placeholder="Job Title" />
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
                <Button>Select PDF</Button>
            </Upload>
        </div>
    </Modal>
</template>