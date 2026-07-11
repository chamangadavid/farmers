<script setup>
import { ref, watch } from 'vue';
import { Modal, Input, Button, DatePicker, message } from 'ant-design-vue';
import axios from 'axios';
import dayjs from 'dayjs';

const props = defineProps({
    open: Boolean,
    announcement: Object
});

const emit = defineEmits(['update:open', 'updated']);

const form = ref({});

// Convert incoming date to dayjs
watch(() => props.announcement, (val) => {
    if (val) {
        form.value = {
            ...val,
            date: val.date ? dayjs(val.date) : null
        };
    }
}, { immediate: true });

// Format date before sending
const update = async () => {
    try {
        const payload = {
            ...form.value,
            date: form.value.date
                ? dayjs(form.value.date).format('YYYY-MM-DD')
                : null
        };

        await axios.put(`/announcements/${form.value.id}`, payload);

        message.success('Updated');
        emit('updated');
        emit('update:open', false);
    } catch (e) {
        message.error('Failed to update');
    }
};
</script>

<template>
<a-modal :open="open" title="Edit Announcement" @cancel="emit('update:open', false)" :footer="null">
    <div class="space-y-3">
        <Input v-model:value="form.title" style="border: 1px solid #e9e9e9; border-radius: 8px;"  placeholder="Title" />
        <DatePicker v-model:value="form.date" style="width:100%" />
        <Input v-model:value="form.category" style="border: 1px solid #e9e9e9; border-radius: 8px;"/>
        <Input v-model:value="form.priority"style="border: 1px solid #e9e9e9; border-radius: 8px;" />
        <Input.TextArea v-model:value="form.summary" style="border: 1px solid #e9e9e9; border-radius: 8px;"/>
        <Input.TextArea v-model:value="form.details" rows="4" style="border: 1px solid #e9e9e9; border-radius: 8px;"/>

        <Button type="primary" block @click="update">Update</Button>
    </div>
</a-modal>
</template>