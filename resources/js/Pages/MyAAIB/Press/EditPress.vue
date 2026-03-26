<script setup>
import { ref, watch } from 'vue';
import { Modal, Input, Button, DatePicker, Upload, message } from 'ant-design-vue';
import axios from 'axios';
import dayjs from 'dayjs';

const props = defineProps({
    open: Boolean,
    press: Object
});

const emit = defineEmits(['updated', 'update:open']);

const localOpen = ref(false);

const form = ref({
    id: null,
    title: '',
    date: null,
    summary: '',
    pdf: null
});

// Sync modal open
watch(() => props.open, (val) => {
    localOpen.value = val;
});

// Populate form when press changes
watch(() => props.press, (val) => {
    if (val) {
        form.value = {
            id: val.id,
            title: val.title,
            date: val.date ? dayjs(val.date) : null,
            summary: val.summary,
            pdf: null
        };
    }
}, { immediate: true });

// File handler
const handleFile = (file) => {
    form.value.pdf = file;
    return false;
};

// Update
const updatePress = async () => {
    const fd = new FormData();
    fd.append('title', form.value.title);
    fd.append('date', dayjs(form.value.date).format('YYYY-MM-DD'));
    fd.append('summary', form.value.summary);

    if (form.value.pdf) {
        fd.append('pdf', form.value.pdf);
    }

    try {
        const res = await axios.post(`/presses/${form.value.id}`, fd);

        message.success(res.data.message);

        emit('updated');
        emit('update:open', false);
    } catch (error) {
        console.error(error);
        message.error('Failed to update press');
    }
};
</script>

<template>
    <a-modal :open="localOpen" title="Edit Press Release" @cancel="emit('update:open', false)" :footer="null">
        <div class="space-y-4">
            <Input v-model:value="form.title" />

            <DatePicker v-model:value="form.date" style="width:100%" />

            <Input.TextArea v-model:value="form.summary" :rows="5" />
            <br/><br/>

            <Upload :beforeUpload="handleFile" :maxCount="1">
                <Button block>Replace PDF</Button>
            </Upload>

            <Button type="primary" block @click="updatePress">
                Update
            </Button>
        </div>
    </a-modal>
</template>