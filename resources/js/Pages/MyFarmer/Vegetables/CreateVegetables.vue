<script setup>
import { reactive, ref } from 'vue'
import axios from 'axios'
import { message } from 'ant-design-vue'

const props = defineProps({
    open: Boolean
})

const emit = defineEmits([
    'close',
    'saved'
])

const loading = ref(false)

const form = reactive({
    name: '',
    variety: '',
    unit: 'Kg',
    description: '',
    status: true
})

const saveVegetable = async () => {
    loading.value = true
    try {

        await axios.post('/vegetable-types', form)
        message.success('Vegetable type created successfully.')

        emit('saved')
        emit('close')

        Object.assign(form, {
            name: '',
            variety: '',
            unit: 'Kg',
            description: '',
            status: true
        })

    } catch (e) {
        message.error('Unable to save vegetable.')
    } finally {
        loading.value = false

    }

}
</script>

<template>

    <a-modal :open="open" title="Add Vegetable Type" width="700px" :confirm-loading="loading" @cancel="$emit('close')"
        @ok="saveVegetable">

        <a-form layout="vertical">

            <a-row :gutter="16">
                <a-col :span="12">
                    <a-form-item label="Vegetable Name" required>
                        <a-input v-model:value="form.name" placeholder="e.g Tomatoes" />
                    </a-form-item>
                </a-col>

                <a-col :span="12">
                    <a-form-item label="Variety">
                        <a-input v-model:value="form.variety" placeholder="e.g Roma VF" />
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