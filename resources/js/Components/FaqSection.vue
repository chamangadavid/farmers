<script setup>
import { ref } from 'vue'

const activeKey = ref(['1'])

const faqItems = [
    {
        key: '1',
        title: 'What products does Alinaswe Farm offer?',
        content:
            'We grow a variety of fresh vegetables and raise healthy chickens, providing fresh and quality farm produce.'
    },
    {
        key: '2',
        title: 'Do you sell fresh chickens?',
        content:
            'Yes. We raise healthy chickens and supply fresh poultry products for homes, businesses and special events.'
    },
    {
        key: '3',
        title: 'Can I place bulk orders?',
        content:
            'Absolutely. We accept bulk orders for vegetables and chickens from retailers, restaurants and organizations.'
    },
    {
        key: '4',
        title: 'Where is Alinaswe Farm located?',
        content:
            'Alinaswe Farm proudly serves the local community with fresh vegetables and quality poultry products.'
    },
    {
        key: '5',
        title: 'How can I contact Alinaswe Farm?',
        content:
            'You can contact us through our Contact page, phone number or WhatsApp for orders and enquiries.'
    }
]
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

                <img
                    src="/assets/fruit-basket.png"
                    alt="Fresh Vegetables"
                    class="w-full max-w-lg object-contain"/>

            </div>

            <!-- FAQ -->

            <div>

                <a-collapse
                    v-model:activeKey="activeKey"
                    accordion
                    class="farm-collapse"
                >

                    <a-collapse-panel
                        v-for="item in faqItems"
                        :key="item.key"
                        :header="item.title"
                    >

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

.farm-collapse{
    background:transparent;
    border:none;
}

:deep(.farm-collapse .ant-collapse-item){
    border:1px solid #dcfce7;
    border-radius:14px;
    overflow:hidden;
    margin-bottom:18px;
    background:#fff;
}

:deep(.farm-collapse .ant-collapse-header){
    background:#16a34a !important;
    color:#fff !important;
    font-size:16px;
    font-weight:600;
    padding:18px 24px !important;
}

:deep(.farm-collapse .ant-collapse-expand-icon){
    color:white !important;
}

:deep(.farm-collapse .ant-collapse-content){
    border-top:none;
    background:#fff;
}

:deep(.farm-collapse .ant-collapse-content-box){
    padding:20px 24px !important;
    color:#64748b;
    line-height:1.9;
}

</style>