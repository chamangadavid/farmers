<script setup>
import { ref, watch } from 'vue';
import { Modal, Input, Button, message, Select } from 'ant-design-vue';
import axios from 'axios';

const open = ref(false);
const faq = ref({
    id: null,
    category: '',
    question: '',
    answer: ''
});

const emit = defineEmits(['updated', 'update:open']);

// Predefined categories
const categories = [
    'General Information',
    'Report Accidents',
    'Incident',
    'Investigations',
    'Reports & Publications'
];

// Correct prop
const props = defineProps({
    faq: Object
});

// Watch for changes in selected FAQ
watch(
    () => props.faq,
    (val) => {
        if (val) {
            faq.value = { ...val };
            open.value = true;
        } else {
            faq.value = { id: null, category: '', question: '', answer: '' };
        }
    },
    { immediate: true }
);

const resetForm = () => {
    faq.value = { id: null, category: '', question: '', answer: '' };
};

const updateFaq = async () => {
    try {
        const res = await axios.put(`/faqs/${faq.value.id}`, {
            category: faq.value.category,
            question: faq.value.question,
            answer: faq.value.answer
        });
        message.success(res.data.message);
        emit('updated', res.data.faq);
        emit('update:open', false);
        resetForm();
    } catch (error) {
        console.error(error);
        message.error(
            error.response?.data?.errors
                ? Object.values(error.response.data.errors).flat()[0]
                : 'Failed to update FAQ'
        );
    }
};
</script>

<template>
    <a-modal v-model:open="open" title="Edit FAQ" @cancel="emit('update:open', false)" :footer="null">
        <div class="space-y-4">
            <!-- Category Dropdown -->
            <a-select v-model:value="faq.category" placeholder="Select a category" style="width: 100%" allowClear>
                <a-select-option v-for="cat in categories" :key="cat" :value="cat">
                    {{ cat }}
                </a-select-option>
            </a-select>

            <Input v-model:value="faq.question" placeholder="Question"
                style="border: 1px solid #e9e9e9; border-radius: 8px;" />
            <Input.TextArea v-model:value="faq.answer" placeholder="Answer" rows="4" />

            <Button type="primary" block @click="updateFaq">Update</Button>
        </div>
    </a-modal>
</template>