<!-- resources/js/Components/Management/ViewMemberModal.vue -->
<script setup>
import { ref, watch } from 'vue';
import { Modal, Descriptions, Tag, Divider } from 'ant-design-vue';
import { UserOutlined } from '@ant-design/icons-vue';

const props = defineProps({
    visible: {
        type: Boolean,
        default: false
    },
    member: {
        type: Object,
        default: null
    }
});

const emit = defineEmits(['close']);

const formatDate = (date) => {
    if (!date) return 'N/A';
    return new Date(date).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
};

// Strip HTML tags for preview (optional)
const stripHtml = (html) => {
    if (!html) return '';
    const tmp = document.createElement('DIV');
    tmp.innerHTML = html;
    return tmp.textContent || tmp.innerText || '';
};
</script>

<template>
    <Modal
        :open="visible"
        title="Member Details"
        @cancel="emit('close')"
        width="800px"
        :footer="null"
    >
        <div v-if="member" class="member-details">
            <div class="flex flex-col md:flex-row gap-6">
                <!-- Image Section -->
                <div class="flex-shrink-0">
                    <div class="w-32 h-32 md:w-40 md:h-40 rounded-full overflow-hidden bg-gradient-to-br from-teal-100 to-emerald-100 flex items-center justify-center">
                        <img 
                            v-if="member.image"
                            :src="`/storage/${member.image}`" 
                            :alt="member.fullname"
                            class="w-full h-full object-cover"
                        />
                        <UserOutlined v-else class="text-6xl text-teal-600" />
                    </div>
                </div>

                <!-- Info Section -->
                <div class="flex-1">
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">{{ member.fullname }}</h3>
                    <Tag color="teal" class="mb-4">{{ member.position }}</Tag>
                    
                    <Divider class="my-4" />
                    
                    <Descriptions :column="1" bordered>
                        <Descriptions.Item label="Full Name">
                            {{ member.fullname }}
                        </Descriptions.Item>
                        <Descriptions.Item label="Position">
                            {{ member.position }}
                        </Descriptions.Item>
                        <Descriptions.Item label="Bio/Content">
                            <div class="bio-content" v-html="member.content"></div>
                        </Descriptions.Item>
                        <Descriptions.Item label="Created At">
                            {{ formatDate(member.created_at) }}
                        </Descriptions.Item>
                        <Descriptions.Item label="Last Updated">
                            {{ formatDate(member.updated_at) }}
                        </Descriptions.Item>
                    </Descriptions>
                </div>
            </div>
        </div>
    </Modal>
</template>

<style scoped>
.member-details {
    max-height: 60vh;
    overflow-y: auto;
}

:deep(.ant-descriptions-item-label) {
    font-weight: 600;
    background-color: #f8fafc;
    width: 120px;
}

:deep(.ant-descriptions-item-content) {
    background-color: #ffffff;
}

.bio-content {
    line-height: 1.6;
    color: #374151;
}

.bio-content :deep(p) {
    margin-bottom: 0.75rem;
}

.bio-content :deep(h1), 
.bio-content :deep(h2), 
.bio-content :deep(h3) {
    margin-top: 1rem;
    margin-bottom: 0.5rem;
    font-weight: 600;
}

.bio-content :deep(ul), 
.bio-content :deep(ol) {
    padding-left: 1.5rem;
    margin-bottom: 0.75rem;
}

.bio-content :deep(strong) {
    font-weight: 700;
    color: #0f172a;
}

.bio-content :deep(em) {
    font-style: italic;
}

.bio-content :deep(a) {
    color: #14b8a6;
    text-decoration: none;
}

.bio-content :deep(a:hover) {
    text-decoration: underline;
}

.bio-content :deep(img) {
    max-width: 100%;
    height: auto;
    border-radius: 8px;
    margin: 0.5rem 0;
}

.bio-content :deep(blockquote) {
    border-left: 4px solid #14b8a6;
    padding-left: 1rem;
    margin: 0.75rem 0;
    color: #4b5563;
    font-style: italic;
}

.bio-content :deep(code) {
    background-color: #f1f5f9;
    padding: 0.2rem 0.4rem;
    border-radius: 4px;
    font-family: monospace;
    font-size: 0.875em;
}

.bio-content :deep(pre) {
    background-color: #1e293b;
    color: #e2e8f0;
    padding: 1rem;
    border-radius: 8px;
    overflow-x: auto;
    margin: 0.75rem 0;
}

.bio-content :deep(pre code) {
    background-color: transparent;
    color: inherit;
    padding: 0;
}
</style>