<!-- resources\js\Pages\MyFarmer\Sales\EditSales.vue -->
<script setup>
import { ref, watch } from 'vue';
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

const props = defineProps({
    open: Boolean,
    news: Object
});

const emit = defineEmits(['update:open', 'updated']);

</script>

<template>
    <a-modal :open="open" title="Edit Sales Details" @cancel="emit('update:open', false)" :footer="null">
        <div class="space-y-3">

            <!-- Title -->
            <Input v-model:value="form.title" style="border: 1px solid #e9e9e9; border-radius: 8px;" placeholder="Title" />

            <!-- Date -->
            <DatePicker v-model:value="form.date" style="width:100%" />

            <!-- CATEGORY DROPDOWN -->
            <Select v-model:value="form.category" placeholder="Select Category" style="width:100%">
                <Select.Option v-for="item in categories" :key="item" :value="item">
                    {{ item }}
                </Select.Option>
            </Select>

            <!-- Author -->
            <Input v-model:value="form.author" style="border: 1px solid #e9e9e9; border-radius: 8px;" placeholder="Author" />

            <!-- Read Time -->
            <Input v-model:value="form.read_time" style="border: 1px solid #e9e9e9; border-radius: 8px;" placeholder="Read Time (e.g. 5 min read)" />

            <!-- Summary -->
            <Input.TextArea v-model:value="form.summary" style="border: 1px solid #e9e9e9; border-radius: 8px;" placeholder="Summary" rows="3" />


            <!-- IMAGE PREVIEW -->
            <div v-if="imagePreview" class="mb-2">
                <img :src="imagePreview" class="w-full h-48 object-cover rounded-lg border" />
            </div> <br/><br/>

            <!-- FILE UPLOAD -->
            <Upload :before-upload="() => false" @change="handleFileChange" max-count="1">
                <Button>Select New Image</Button>
            </Upload>

         
            <!-- Featured -->
            <div class="flex items-center gap-2">
                <span>Featured:</span>
                <Switch v-model:checked="form.featured" />
            </div>

            <!-- Submit -->
            <Button type="primary" block @click="update">
                Update
            </Button>

        </div>
    </a-modal>
</template>