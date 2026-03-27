<script setup>
import { ref, watch } from 'vue';
import { Modal, Input, Select, Upload, message } from 'ant-design-vue';
import axios from 'axios';

const props = defineProps({
    open: Boolean,
    regulation: Object
});

const emit = defineEmits(['update:open', 'updated']);

const form = ref({
    title: '',
    document_type: '',
    regulation_file: null
});

const loading = ref(false);
const fileList = ref([]);

// Populate form when editing
watch(() => props.regulation, (val) => {
    if (val) {
        form.value.title = val.title;
        form.value.document_type = val.document_type;

        // reset file when opening edit
        form.value.regulation_file = null;
        fileList.value = [];
    }
}, { immediate: true });

const handleFileChange = (info) => {
    const fileObj = info.fileList?.[0];

    if (!fileObj) return;

    const rawFile = fileObj.originFileObj || fileObj;

    form.value.regulation_file = rawFile;
    fileList.value = info.fileList;
};

// Submit update
const updateRegulation = async () => {
    try {
        loading.value = true;

        const formData = new FormData();

        formData.append('_method', 'PUT');
        formData.append('title', form.value.title);
        formData.append('document_type', form.value.document_type);

        //ONLY append if it's a real File
        if (form.value.regulation_file instanceof File) {
            formData.append('regulation_file', form.value.regulation_file);
        }

        await axios.post(
            `/regulations/${props.regulation.id}`,
            formData,
            {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            }
        );

        message.success('Updated successfully');

        emit('updated');
        emit('update:open', false);

    } catch (error) {
        console.error(error.response?.data || error);
        message.error('Failed to update');
    } finally {
        loading.value = false;
    }
};
</script>

<template>
    <Modal
        :open="open"
        title="Edit Regulation"
        okText="Update"
        @ok="updateRegulation"
        @cancel="$emit('update:open', false)"
        :confirmLoading="loading"
    >
        <div class="space-y-4">
            <Input v-model:value="form.title" style="border: 1px solid #e9e9e9; border-radius: 8px;" placeholder="Enter title" />

            <Select v-model:value="form.document_type" style="width: 100%;" placeholder="Select Type">
                <Select.Option value="Regulation">Regulation</Select.Option>
                <Select.Option value="Annex">Annex</Select.Option>
                <Select.Option value="Guideline">Guideline</Select.Option>
            </Select> <br/><br/>

            <Upload
                :before-upload="() => false"
                @change="handleFileChange"
                :file-list="fileList"
                accept="application/pdf"
            >
                <a-button>Upload New PDF (optional)</a-button>
            </Upload>
        </div>
    </Modal>
</template>