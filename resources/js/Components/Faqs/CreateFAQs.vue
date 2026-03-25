<script setup>
import { ref } from 'vue';
import { Modal, Input, Button, message, Select } from 'ant-design-vue';
import axios from 'axios';

const open = ref(false);
const category = ref('');
const question = ref('');
const answer = ref('');

const emit = defineEmits(['created', 'update:open']);

// Predefined categories
const categories = [
  'General Information',
  'Report Accidents',
  'Incident',
  'Investigations',
  'Reports & Publications'
];

const resetForm = () => {
  category.value = '';
  question.value = '';
  answer.value = '';
};

const saveFaq = async () => {
  try {
    const res = await axios.post('/faqs', {
      category: category.value,
      question: question.value,
      answer: answer.value
    });
    message.success(res.data.message);
    resetForm();
    emit('created', res.data.faq);
    emit('update:open', false);
  } catch (error) {
    console.error(error);
    message.error(
      error.response?.data?.errors
        ? Object.values(error.response.data.errors).flat()[0]
        : 'Failed to create FAQ'
    );
  }
};
</script>

<template>
  <a-modal v-model:open="open" title="Create FAQ" @cancel="emit('update:open', false)" :footer="null">
    <div class="space-y-4">
      <!-- Category Dropdown -->
      <a-select v-model:value="category" placeholder="Select a category" style="width: 100%" allowClear>
        <a-select-option v-for="cat in categories" :key="cat" :value="cat">
          {{ cat }}
        </a-select-option>
      </a-select>

      <Input v-model:value="question" placeholder="Question" style="border: 1px solid #e9e9e9; border-radius: 8px;" />
      <Input.TextArea v-model:value="answer" placeholder="Answer" rows="4" />

      <Button type="primary" block @click="saveFaq">Save</Button>
    </div>
  </a-modal>
</template>