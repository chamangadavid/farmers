<!-- resources/js/Components/Management/ViewMemberModal.vue -->
<script setup>
import { ref, watch } from 'vue';
import { Modal, Descriptions, Tag, Divider } from 'ant-design-vue';
import { UserOutlined, MailOutlined, CalendarOutlined } from '@ant-design/icons-vue';

const props = defineProps({
    open: {
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
</script>

<template>
    <Modal
        :open="open"
        title="Member Details"
        @cancel="emit('close')"
        width="700px"
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
                            <div class="whitespace-pre-wrap">{{ member.content }}</div>
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
}
</style>