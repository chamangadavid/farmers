<!-- resources\js\Components\FaqSection.vue -->
<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const activeKey = ref(['1'])

const faqItems = ref([])

const fetchFaqs = async () => {

    const { data } = await axios.get('/frontend/faqs')

    faqItems.value = data.map((faq, index) => ({
        key: String(index + 1),
        title: faq.question,
        content: faq.answer,
        category: faq.category
    }))
}


onMounted(() => {
    fetchFaqs()
})

</script>

<template>
    <section class="py-24 bg-gradient-to-b from-white to-green-50">

        <div class="max-w-7xl mx-auto px-6">

            <!-- Heading -->
            <div class="text-center mb-16">
                <span class="text-green-600 font-semibold uppercase tracking-widest">
                    FAQ
                </span>

                <h2 class="text-5xl font-bold text-slate-800 mt-4">
                    Frequently Asked Questions
                </h2>

                <p class="text-gray-500 max-w-2xl mx-auto mt-5">
                    Find answers to the most common questions about Alinaswe Farm,
                    our vegetables and quality chickens.
                </p>
            </div>

            <div class="grid lg:grid-cols-2 gap-14 items-center">

                <!-- Left Image -->

                <div class="flex justify-center">
                    <img src="/assets/fruit-basket.png" alt="Fresh Vegetables" class="w-full max-w-lg object-contain" />
                </div>

                <!-- FAQ -->
                <div class="faq-scroll">
                    <a-collapse v-model:activeKey="activeKey" accordion class="farm-collapse">
                        <a-collapse-panel v-for="item in faqItems" :key="item.key" :header="item.title">
                            <p class="text-gray-600 leading-8">
                                {{ item.content }}
                            </p>
                        </a-collapse-panel>
                    </a-collapse>
                </div>
            </div>
        </div>
    </section>
</template>

<style scoped>
.farm-collapse {
    background: transparent;
    border: none;
}

:deep(.farm-collapse .ant-collapse-item) {
    border: 1px solid #dcfce7;
    border-radius: 14px;
    overflow: hidden;
    margin-bottom: 18px;
    background: #fff;
}

:deep(.farm-collapse .ant-collapse-header) {
    background: #16a34a !important;
    color: #fff !important;
    font-size: 16px;
    font-weight: 600;
    padding: 18px 24px !important;
}

:deep(.farm-collapse .ant-collapse-expand-icon) {
    color: white !important;
}

:deep(.farm-collapse .ant-collapse-content) {
    border-top: none;
    background: #fff;
}

:deep(.farm-collapse .ant-collapse-content-box) {
    padding: 20px 24px !important;
    color: #64748b;
    line-height: 1.9;
}

.faq-scroll {
    max-height: 450px;
    /* adjust to your preferred height */
    overflow-y: auto;
    padding-right: 8px;
}

.faq-scroll::-webkit-scrollbar {
    width: 8px;
}

.faq-scroll::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 10px;
}

.faq-scroll::-webkit-scrollbar-thumb {
    background: #16a34a;
    border-radius: 10px;
}

.faq-scroll::-webkit-scrollbar-thumb:hover {
    background: #15803d;
}

.faq-scroll {
    scrollbar-width: thin;
    scrollbar-color: #16a34a #f1f5f9;
}
</style>