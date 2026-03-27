<script setup>
import { ref } from 'vue';
import { Modal, Input, Button, Upload, Select, message } from 'ant-design-vue';
import axios from 'axios';

const props = defineProps({ open: Boolean });
const emit = defineEmits(['update:open', 'created']);

const loading = ref(false);

const form = ref({
    title: '',
    document_type: '',
    regulation_file: null
});

const fileList = ref([]);

const handleFileChange = (info) => {
    const file = info.file;

    if (file && file.originFileObj) {
        form.value.regulation_file = file.originFileObj;
    } 
  
    else if (file) {
        form.value.regulation_file = file;
    } else {
        console.error('No file selected');
        return;
    }

    fileList.value = [file];

};

const save = async () => {
    try {
        if (!form.value.regulation_file) {
            message.error('Please select a PDF file');
            return;
        }

        loading.value = true; 

        const formData = new FormData();
        formData.append('title', form.value.title);
        formData.append('document_type', form.value.document_type);
        formData.append('regulation_file', form.value.regulation_file);

        await axios.post('/regulations', formData, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        });

        message.success('Uploaded successfully');

        emit('created');
        emit('update:open', false);

        // reset form
        form.value = {
            title: '',
            document_type: '',
            regulation_file: null
        };
        fileList.value = [];

    } catch (error) {
        console.error(error.response?.data || error);
        message.error('Upload failed');
    } finally {
        loading.value = false; 
    }
};

</script>

<template>
<a-modal :open="open" title="Upload Regulation" @cancel="emit('update:open', false)" :footer="null">

    <Input v-model:value="form.title" style="border: 1px solid #e9e9e9; border-radius: 8px;" placeholder="Enter document title" />
    <br/><br/>

    <Select v-model:value="form.document_type" placeholder="Document Type" style="width: 100%;" >
        <Select.Option value="Annex">Annex</Select.Option>
        <Select.Option value="Regulation">Regulation</Select.Option>
    </Select>
    <br/><br/>

    <Upload
    :before-upload="() => false"
    :file-list="fileList"
    @change="handleFileChange"
    accept="application/pdf"
    :max-count="1"
>
    <Button>Select PDF</Button>
</Upload>
<br/>

<Button 
    type="primary" 
    block 
    :loading="loading"
    :disabled="loading"
    @click="save"
>
    Save
</Button>

</a-modal>
</template>