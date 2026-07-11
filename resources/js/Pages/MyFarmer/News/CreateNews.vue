<script setup>
import { ref } from 'vue';
import {
    Modal,
    Input,
    Button,
    DatePicker,
    Switch,
    Upload,
    Select,
    message
} from 'ant-design-vue';

import axios from 'axios';
import dayjs from 'dayjs';

const props = defineProps({ open: Boolean });
const emit = defineEmits(['update:open', 'created']);

const form = ref({
    title: '',
    date: '',
    category: '',
    summary: '',
    author: '',
    image: null,
    featured: false,
    read_time: ''
});

// CATEGORY OPTIONS
const categories = [
    'General',
    'Vegetables',
    'Fresh Fruits',
    'Poultry',
    'Farm Services',
    'Orders & Delivery',
    'Payments'
];

// file upload
const handleFileChange = (info) => {
    form.value.image = info.file.originFileObj;
};

const fileList = ref([]);

const beforeUpload = (file) => {
    fileList.value = [file];   // keep only 1 file
    form.value.image = file;   // IMPORTANT: store real file
    return false; // prevent auto upload
};

const handleRemove = () => {
    fileList.value = [];
    form.value.image = null;
};

const save = async () => {
    try {
        const formData = new FormData();

        formData.append('title', form.value.title);
        formData.append('date', dayjs(form.value.date).format('YYYY-MM-DD'));
        formData.append('category', form.value.category);
        formData.append('summary', form.value.summary || '');
        formData.append('author', form.value.author);
        formData.append('featured', form.value.featured ? 1 : 0);
        formData.append('read_time', form.value.read_time);


        if (form.value.image instanceof File) {
            formData.append('image', form.value.image);
        }

        await axios.post('/news', formData, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        });

        message.success('Created successfully');

        emit('created');
        emit('update:open', false);

    } catch (e) {
        console.log(e);
        message.error('Failed to create');
    }
};
</script>

<template>
    <a-modal :open="open" title="Create News" @cancel="emit('update:open', false)" :footer="null">
        <div class="space-y-3">

            <Input v-model:value="form.title" style="border: 1px solid #e9e9e9; border-radius: 8px;" placeholder="Title" />

            <DatePicker v-model:value="form.date" style="width:100%" />

            <!-- CATEGORY DROPDOWN -->
            <Select v-model:value="form.category" placeholder="Select Category" style="width:100%">
                <Select.Option v-for="item in categories" :key="item" :value="item">
                    {{ item }}
                </Select.Option>
            </Select>

            <Input v-model:value="form.author" style="border: 1px solid #e9e9e9; border-radius: 8px;" placeholder="Author" />

            <Input v-model:value="form.read_time" style="border: 1px solid #e9e9e9; border-radius: 8px;" placeholder="Read Time (e.g. 5 min read)" />

            <Input.TextArea v-model:value="form.summary" style="border: 1px solid #e9e9e9; border-radius: 8px;" placeholder="Summary" />

            <br/><br/>
            <!-- Upload -->
            <Upload :before-upload="beforeUpload" :file-list="fileList" @remove="handleRemove">
                <Button>Select Image</Button>
            </Upload>

            <div class="flex items-center gap-2">
                <span>Featured:</span>
                <Switch v-model:checked="form.featured" />
            </div>

            <Button type="primary" block @click="save">Save</Button>
        </div>
    </a-modal>
</template>
