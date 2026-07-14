<script setup>
import { reactive, watch, ref } from 'vue'
import axios from 'axios'
import { message } from 'ant-design-vue'

const props = defineProps({

    open: Boolean,

    vegetable: Object

})

const emit = defineEmits([
    'close',
    'updated'
])

const loading = ref(false)

const form = reactive({

    id: null,

    name: '',

    variety: '',

    unit: 'Kg',

    description: '',

    status: true

})

watch(() => props.vegetable, (v) => {

    if (!v) return

    Object.assign(form, v)

}, { immediate: true })

const updateVegetable = async () => {

    loading.value = true

    try {

        await axios.put(

            `/vegetable-types/${form.id}`,

            form

        )

        message.success(

            'Updated successfully.'

        )

        emit('updated')

        emit('close')

    }

    finally {

        loading.value = false

    }

}
</script>

<template>

    <a-modal :open="open" title="Edit Vegetable" @cancel="$emit('close')" @ok="updateVegetable"
        :confirm-loading="loading" width="700px">

        <a-form layout="vertical">
            <a-row :gutter="16">
                <a-col :span="12">
                    <a-form-item label="Vegetable">
                        <a-input v-model:value="form.name" />
                    </a-form-item>
                </a-col>

                <a-col :span="12">
                    <a-form-item label="Variety">
                        <a-input v-model:value="form.variety" />
                    </a-form-item>
                </a-col>

                <a-col :span="12">
                    <a-form-item label="Unit">
                        <a-select v-model:value="form.unit">
                            <a-select-option value="Kg">Kg</a-select-option>
                            <a-select-option value="Tonnes">Tonnes</a-select-option>
                            <a-select-option value="Boxes">Boxes</a-select-option>
                            <a-select-option value="Crates">Crates</a-select-option>
                            <a-select-option value="Bags">Bags</a-select-option>
                        </a-select>
                    </a-form-item>
                </a-col>

                <a-col :span="12">
                    <a-form-item label="Status">
                        <a-switch v-model:checked="form.status" />
                    </a-form-item>
                </a-col>

                <a-col :span="24">
                    <a-form-item label="Description">
                        <a-textarea v-model:value="form.description" :rows="4" />
                    </a-form-item>
                </a-col>
            </a-row>
        </a-form>
    </a-modal>

</template>