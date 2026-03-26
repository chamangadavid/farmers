<script setup>
import { ref, watch } from 'vue';
import { Modal, Input, Button, DatePicker, Upload, message } from 'ant-design-vue';
import axios from 'axios';
import dayjs from 'dayjs';

// Props (for modal control)
const props = defineProps({
    open: Boolean
});

const emit = defineEmits(['created', 'update:open']);

// Sync modal
const localOpen = ref(false);
watch(() => props.open, (val) => {
    localOpen.value = val;
});

// Form state
const form = ref({
    title: '',
    date: null,
    summary: '',
    pdf: null
});

// File list (for UI)
const fileList = ref([]);

// Handle file upload
const handleFile = (file) => {
    form.value.pdf = file;
    fileList.value = [file];
    return false; // prevent auto upload
};

// Reset form
const resetForm = () => {
    form.value = {
        title: '',
        date: null,
        summary: '',
        pdf: null
    };
    fileList.value = [];
};

// Save
const save = async () => {
    if (!form.value.title || !form.value.date || !form.value.summary || !form.value.pdf) {
        message.error('All fields are required');
        return;
    }

    const fd = new FormData();
    fd.append('title', form.value.title);
    fd.append('date', dayjs(form.value.date).format('YYYY-MM-DD'));
    fd.append('summary', form.value.summary);
    fd.append('pdf', form.value.pdf);

    try {
        const res = await axios.post('/presses', fd, {
            headers: { 'Content-Type': 'multipart/form-data' }
        });

        message.success(res.data.message);

        resetForm();
        emit('created');
        emit('update:open', false);

    } catch (error) {
        console.error(error);
        message.error('Failed to create press');
    }
};
</script>

<template>
    <a-modal v-model:open="localOpen" title="Create Press Release" @cancel="emit('update:open', false)" :footer="null">
        <div class="space-y-4">

            <!-- Title -->
            <Input v-model:value="form.title" placeholder="Enter title" />

            <!-- Date -->
            <DatePicker v-model:value="form.date" style="width: 100%" placeholder="Select date" />

            <!-- Summary (Textarea instead of Quill) -->
            <Input.TextArea v-model:value="form.summary" placeholder="Enter summary" :rows="5" />
            <br/><br/>

            <!-- Upload PDF -->
            <Upload :beforeUpload="handleFile" :fileList="fileList" accept=".pdf">
                <Button block>Select PDF</Button>
            </Upload>

            <!-- Save -->
            <Button type="primary" block @click="save">
                Save Press
            </Button>

        </div>
    </a-modal>
</template>