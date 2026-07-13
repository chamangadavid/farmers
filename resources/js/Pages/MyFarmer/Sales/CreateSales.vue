<!-- resources\js\Pages\MyFarmer\Sales\CreateSales.vue -->
 <script setup>
import { ref } from 'vue';
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

const props = defineProps({ open: Boolean });
const emit = defineEmits(['update:open', 'created']);

</script>

<template>
    <a-modal :open="open" title="Add sales Details" @cancel="emit('update:open', false)" :footer="null">
        <div class="space-y-3">

            <Input v-model:value="form.title" style="border: 1px solid #e9e9e9; border-radius: 8px;" placeholder="Title" />

            <DatePicker v-model:value="form.date" style="width:100%" />

            <!-- CATEGORY DROPDOWN -->
            <Select v-model:value="form.category" placeholder="Select Category" style="width:100%">
                <Select.Option v-for="item in categories" :key="item" :value="item">
                    {{ item }}
                </Select.Option>
            </Select>

            <Input v-model:value="form.author" style="border: 1px solid #e9e9e9; border-radius: 8px;" placeholder="Author" />

            <Input v-model:value="form.read_time" style="border: 1px solid #e9e9e9; border-radius: 8px;" placeholder="Read Time (e.g. 5 min read)" />

            <Input.TextArea v-model:value="form.summary" style="border: 1px solid #e9e9e9; border-radius: 8px;" placeholder="Summary" />

            <br/><br/>
            <!-- Upload -->
            <Upload :before-upload="beforeUpload" :file-list="fileList" @remove="handleRemove">
                <Button>Select Image</Button>
            </Upload>

            <div class="flex items-center gap-2">
                <span>Featured:</span>
                <Switch v-model:checked="form.featured" />
            </div>

            <Button type="primary" block @click="save">Save</Button>
        </div>
    </a-modal>
</template>
