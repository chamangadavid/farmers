<script setup>
import { ref } from 'vue';
import { Modal, Input, message } from 'ant-design-vue';
import axios from 'axios';

const props = defineProps({ open: Boolean });
const emit = defineEmits(['update:open', 'created']);

const name = ref('');

const createFolder = async () => {
    try {
        await axios.post('/documents/folder', { name: name.value });

        message.success('Folder created');

        emit('created');
        emit('update:open', false);

        name.value = '';
    } catch (error) {
        if (error.response && error.response.data.errors) {
            const errors = error.response.data.errors;

            //validation error
            if (errors.name) {
                message.error(errors.name[0]);
            }
        } else {
            message.error('Something went wrong');
        }
    }
};

</script>

<template>
    <Modal :open="open" title="Create Folder" @ok="createFolder" @cancel="$emit('update:open', false)">
        <Input v-model:value="name" style="border: 1px solid #e9e9e9; border-radius: 8px;" placeholder="Folder name" />
    </Modal>
</template>