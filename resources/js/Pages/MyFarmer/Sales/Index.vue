<!-- resources\js\Pages\MyFarmer\Sales\Index.vue -->
<script setup>
import { ref, onMounted, h, computed } from 'vue';
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

import CreateSales from './CreateSales.vue';
import EditSales from './EditSales.vue';
import ViewSales from './ViewSales.vue';

import { Tabs, Button, Input, message, Popconfirm, Tag, Space, Tooltip, } from 'ant-design-vue';
import { 
  SearchOutlined, 
  PlusOutlined, 
  ReloadOutlined, 
  EyeOutlined, 
  EditOutlined, 
  DeleteOutlined,
  CalendarOutlined,
  UserOutlined,
  StarOutlined
} from '@ant-design/icons-vue';
import axios from 'axios';
import { debounce } from 'lodash';


</script>

<template>
     <Head title="Vegetable Management" />

     <AuthenticatedLayout>
          <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Sales Management
            </h2>
        </template>
     </AuthenticatedLayout>
</template>