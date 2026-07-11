<script setup>
import { Modal } from 'ant-design-vue';
import dayjs from 'dayjs';

const props = defineProps({
    open: Boolean,
    news: Object
});

const emit = defineEmits(['update:open']);
</script>

<template>
<a-modal
    :open="open"
    title="View News"
    @cancel="emit('update:open', false)"
    :footer="null"
    width="700px"
>
    <div v-if="news" class="space-y-4">

        <!-- ✅ IMAGE (FIXED) -->
        <div v-if="news.image">
            <img 
                :src="`/storage/${news.image}`"
                alt="news image"
                class="w-full h-64 object-cover rounded-lg"
                @error="(e) => e.target.style.display = 'none'"
            />
        </div>

        <!-- Title -->
        <h2 class="text-xl font-bold text-gray-900">
            {{ news.title }}
        </h2>

        <!-- Meta -->
        <div class="flex flex-wrap gap-3 text-sm text-gray-500">
            <span>📅 {{ dayjs(news.date).format('MMM D, YYYY') }}</span>
            <span>👤 {{ news.author }}</span>
            <span>📖 {{ news.read_time }}</span>

            <span
                v-if="news.featured"
                class="px-2 py-1 bg-green-100 text-green-700 rounded"
            >
                Featured
            </span>
        </div>

        <!-- Category -->
        <div>
            <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-semibold">
                {{ news.category }}
            </span>
        </div>

        <!-- Summary -->
        <div class="text-gray-700 leading-relaxed">
            {{ news.summary }}
        </div>

    </div>
</a-modal>
</template>