<script setup>
import { ref } from 'vue';
import { Modal, Input, Button, Select, DatePicker, message } from 'ant-design-vue';
import axios from 'axios';
import dayjs from 'dayjs';


const props = defineProps({ open: Boolean });
const emit = defineEmits(['update:open', 'created']);

const form = ref({
    title: '',
    date: '',
    category: '',
    priority: '',
    summary: '',
    details: ''
});

const categories = [
    'General',
    'Accident & Incident',
    'Investigation',
    'Press Releases & Publication'
];

const priorities = ['High', 'Medium', 'Low'];

const save = async () => {
    try {
        const payload = {
            ...form.value,
            date: form.value.date
                ? dayjs(form.value.date).format('YYYY-MM-DD')
                : null
        };

        await axios.post('/announcements', payload);

        message.success('Created successfully');
        emit('created');
        emit('update:open', false);
    } catch (e) {
        message.error('Failed');
    }
};
</script>

<template>
    <a-modal :open="open" title="Create Announcement" @cancel="emit('update:open', false)" :footer="null">
        <div class="space-y-3">
            <Input v-model:value="form.title" style="border: 1px solid #e9e9e9; border-radius: 8px;"  placeholder="Title" />

            <DatePicker v-model:value="form.date" style="width:100%" />

            <a-select v-model:value="form.category" style="width:50%" placeholder="Category">
                <a-select-option v-for="c in categories" :key="c" :value="c">{{ c }}</a-select-option>
            </a-select>

            <a-select v-model:value="form.priority" style="width:50%" placeholder="Priority">
                <a-select-option v-for="p in priorities" :key="p" :value="p">{{ p }}</a-select-option>
            </a-select>

            <Input.TextArea v-model:value="form.summary" placeholder="Summary" />
            <Input.TextArea v-model:value="form.details" placeholder="Details" rows="4" />

            <Button type="primary" block @click="save">Save</Button>
        </div>
    </a-modal>
</template>