<script setup>

const props = defineProps({

    open: Boolean,

    vegetable: Object

})

</script>

<template>

    <a-modal :open="open" title="Vegetable Details" :footer="null" width="700px">

        <a-descriptions bordered :column="2">

            <a-descriptions-item label="Vegetable">
                {{ vegetable?.name }}
            </a-descriptions-item>

            <a-descriptions-item label="Variety">
                {{ vegetable?.variety }}
            </a-descriptions-item>

            <a-descriptions-item label="Unit">
                {{ vegetable?.unit }}
            </a-descriptions-item>

            <a-descriptions-item label="Status">
                <a-tag :color="vegetable?.status ? 'green' : 'red'">
                    {{ vegetable?.status ? 'Active' : 'Inactive' }}
                </a-tag>
            </a-descriptions-item>

            <a-descriptions-item label="Description" :span="2">
                {{ vegetable?.description }}
            </a-descriptions-item>

            <a-descriptions-item label="Created">
                {{ vegetable?.created_at }}
            </a-descriptions-item>

            <a-descriptions-item label="Updated">
                {{ vegetable?.updated_at }}
            </a-descriptions-item>
        </a-descriptions>

    </a-modal>

</template>