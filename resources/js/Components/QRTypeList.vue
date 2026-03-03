<!-- <script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const props = defineProps({
    selectedType: {
        type: String,
        default: 'vcard'
    }
});

const emit = defineEmits(['type-selected']);

const types = ref([]);
const loading = ref(false);

const fetchTypes = async () => {
    loading.value = true;
    try {
        const response = await axios.get('/qr-types');
        types.value = response.data.data;
    } catch (error) {
        console.error('Failed to fetch QR types:', error);
    } finally {
        loading.value = false;
    }
};

const selectType = (slug) => {
    emit('type-selected', slug);
};

onMounted(() => {
    fetchTypes();
});
</script>

<template>
    <div class="qr-type-list">
        <h3 class="text-lg font-semibold mb-4">QR Code Types</h3>
        
        <div v-if="loading" class="text-center py-4">
            <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
        </div>
        
        <div v-else class="grid grid-cols-2 gap-3">
            <button
                v-for="type in types"
                :key="type.id"
                @click="selectType(type.slug)"
                class="flex flex-col items-center p-4 border rounded-lg transition-all hover:shadow-md"
                :class="{
                    'border-blue-600 bg-blue-50': selectedType === type.slug,
                    'border-gray-200 hover:border-blue-300': selectedType !== type.slug
                }"
            >
                <i :class="[type.icon, 'text-2xl mb-2', selectedType === type.slug ? 'text-blue-600' : 'text-gray-600']"></i>
                <span class="text-sm font-medium" :class="{ 'text-blue-600': selectedType === type.slug }">
                    {{ type.name }}
                </span>
            </button>
        </div>
    </div>
</template> -->


<script setup>
import { ref, onMounted, watch } from 'vue';
import axios from 'axios';

const props = defineProps({
    selectedType: {
        type: String,
        default: 'vcard'
    }
});

const emit = defineEmits(['type-selected']);

const types = ref([]);
const loading = ref(false);
const selected = ref(props.selectedType);

const fetchTypes = async () => {
    loading.value = true;
    try {
        const response = await axios.get('/qr-types');
        types.value = response.data.data;
    } catch (error) {
        console.error('Failed to fetch QR types:', error);
    } finally {
        loading.value = false;
    }
};

const handleChange = () => {
    emit('type-selected', selected.value);
};

watch(
    () => props.selectedType,
    (newVal) => {
        selected.value = newVal;
    }
);

onMounted(() => {
    fetchTypes();
});
</script>

<template>
    <div class="qr-type-dropdown">
        <h3 class="text-lg font-semibold mb-4">QR Code Types</h3>

        <div v-if="loading" class="text-center py-4">
            <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
        </div>

        <div v-else>
            <select v-model="selected" @change="handleChange"
                class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option v-for="type in types" :key="type.id" :value="type.slug">
                    {{ type.name }}
                </option>
            </select>
        </div>
    </div>
</template>

