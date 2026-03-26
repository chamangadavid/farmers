<script setup>
import { ref, watch } from 'vue';
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

const props = defineProps({
    open: Boolean,
    news: Object
});

const emit = defineEmits(['update:open', 'updated']);

const form = ref({
    id: null,
    title: '',
    date: null,
    category: '',
    summary: '',
    author: '',
    image: null,
    featured: false,
    read_time: ''
});

const imagePreview = ref(null);

const categories = [
    'General',
    'Training',
    'Event',
    'Reports',
    'Accident',
    'Incidents'
];

// load data properly
watch(() => props.news, (val) => {
    if (val) {
        form.value = {
            id: val.id, 
            title: val.title,
            date: val.date ? dayjs(val.date) : null,
            category: val.category,
            summary: val.summary,
            author: val.author,
            image: null,
            featured: val.featured ? true : false,
            read_time: val.read_time
        };

        imagePreview.value = val.image ? `/storage/${val.image}` : null;
    }
}, { immediate: true });

// file change
const handleFileChange = (info) => {
    const file = info.file.originFileObj;
    form.value.image = file;
    imagePreview.value = URL.createObjectURL(file);
};

// update
const update = async () => {
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

        await axios.post(`/news/${form.value.id}?_method=PUT`, formData, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        });

        message.success('Updated successfully');

        emit('updated');
        emit('update:open', false);

    } catch (e) {
        console.log(e);
        message.error('Failed to update');
    }
};
</script>
<template>
    <a-modal :open="open" title="Edit News" @cancel="emit('update:open', false)" :footer="null">
        <div class="space-y-3">

            <!-- Title -->
            <Input v-model:value="form.title" style="border: 1px solid #e9e9e9; border-radius: 8px;" placeholder="Title" />

            <!-- Date -->
            <DatePicker v-model:value="form.date" style="width:100%" />

            <!-- CATEGORY DROPDOWN -->
            <Select v-model:value="form.category" placeholder="Select Category" style="width:100%">
                <Select.Option v-for="item in categories" :key="item" :value="item">
                    {{ item }}
                </Select.Option>
            </Select>

            <!-- Author -->
            <Input v-model:value="form.author" style="border: 1px solid #e9e9e9; border-radius: 8px;" placeholder="Author" />

            <!-- Read Time -->
            <Input v-model:value="form.read_time" style="border: 1px solid #e9e9e9; border-radius: 8px;" placeholder="Read Time (e.g. 5 min read)" />

            <!-- Summary -->
            <Input.TextArea v-model:value="form.summary" style="border: 1px solid #e9e9e9; border-radius: 8px;" placeholder="Summary" rows="3" />


            <!-- IMAGE PREVIEW -->
            <div v-if="imagePreview" class="mb-2">
                <img :src="imagePreview" class="w-full h-48 object-cover rounded-lg border" />
            </div> <br/><br/>

            <!-- FILE UPLOAD -->
            <Upload :before-upload="() => false" @change="handleFileChange" max-count="1">
                <Button>Select New Image</Button>
            </Upload>

         
            <!-- Featured -->
            <div class="flex items-center gap-2">
                <span>Featured:</span>
                <Switch v-model:checked="form.featured" />
            </div>

            <!-- Submit -->
            <Button type="primary" block @click="update">
                Update
            </Button>

        </div>
    </a-modal>
</template>