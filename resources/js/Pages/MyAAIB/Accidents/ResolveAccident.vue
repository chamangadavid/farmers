<script setup>
import { ref, watch } from 'vue';
import { Modal, Select, Upload, message } from 'ant-design-vue';
import axios from 'axios';

const props = defineProps({
    open: Boolean,
    report: Object
});

const emit = defineEmits(['update:open', 'updated']);

const status = ref('');
const summary = ref('');
const file = ref(null);
const loading = ref(false);

const statuses = [
    'Under Investigation',
    'On-Site Investigation',
    'Analysis Ongoing',
    'Preliminary Report',
    'Final Report Pending',
    'Closed'
];

// set current status when modal opens
// watch(() => props.report, (val) => {
//     if (val) status.value = val.status;
// });

// set current values when modal opens
watch(() => props.report, (val) => {
    if (val) {
        status.value = val.status;
        summary.value = val.summary || '';
    }
});

// handle file select
const handleFile = (e) => {
    file.value = e.file.originFileObj;
};

const submit = async () => {
    loading.value = true;

    try {
        const formData = new FormData();
        formData.append('status', status.value);
        formData.append('summary', summary.value);


        if (file.value) {
            formData.append('file', file.value);
        }

        await axios.post(`/accident-reports/${props.report.id}/resolve`, formData);

        message.success('Status updated successfully');

        emit('updated');
        emit('update:open', false);

    } catch (e) {
        message.error('Failed to update');
    } finally {
        loading.value = false;
    }
};
</script>

<template>
    <Modal :open="open" title="Update Investigation Status" @ok="submit" :confirmLoading="loading"
        @cancel="$emit('update:open', false)">
        <div class="space-y-4">

            <!-- Status -->
            <div>
                <label class="block mb-2 font-medium">Status</label>
                <Select v-model:value="status" :options="statuses.map(s => ({ label: s, value: s }))"
                    style="width: 100%" />
            </div>

            <!-- Summary -->
            <div>
                <label class="block mb-2 font-medium">Investigation Summary</label>
                <textarea v-model="summary" rows="4"
                    placeholder="Write investigation findings, actions taken, or conclusions..."
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-teal-500"></textarea>
            </div>

            <!-- File Upload -->
            <div>
                <label class="block mb-2 font-medium">Upload Follow-up Document</label>
                <Upload :beforeUpload="() => false" @change="handleFile">
                    <a-button>Click to Upload</a-button>
                </Upload>
            </div>

        </div>
    </Modal>
</template>