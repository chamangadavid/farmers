<!-- resources\js\Pages\MyFarmer\Chickens\Index.vue -->
<script setup>
import { ref, onMounted, h, computed } from 'vue';
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import CreateChickens from './CreateChickens.vue';
import EditChickens from './EditChickens.vue';
import ViewChickens from './ViewChickens.vue';
import CreateChickenSale from './CreateChickenSale.vue'
import CreateChickenSalesHistory from './CreateChickenSalesHistory.vue';
import axios from 'axios';
import { debounce } from 'lodash';
import { Button, Input, message, Popconfirm, Tag, Space, Tooltip, Card } from 'ant-design-vue';
import {
  SearchOutlined, PlusOutlined, EyeOutlined, EditOutlined, DeleteOutlined, ClearOutlined, UserOutlined,
  DollarOutlined, CalendarOutlined, UnorderedListOutlined,
} from '@ant-design/icons-vue';


const chickens = ref([])
const loading = ref(false)
const searchTerm = ref('')
const selectedChicken = ref(null)
const showCreateModal = ref(false)
const showEditModal = ref(false)
const showViewModal = ref(false)

const showSaleModal = ref(false)
const selectedBatch = ref(null)

const showSalesHistory = ref(false)




const fetchChickens = async () => {
  loading.value = true
  try {
    const { data } = await axios.get('/chickens', {
      params: {
        search: searchTerm.value
      }
    })

    chickens.value = data.chickens

  }
  catch (error) {
    message.error('Failed to load chickens')
  }
  finally {
    loading.value = false
  }
}

const handleSearch = debounce(() => {
  fetchChickens()

}, 400)

const clearSearch = () => {
  searchTerm.value = ''
  fetchChickens()

}

const deleteChicken = async (id) => {
  try {
    await axios.delete(`/chickens/${id}`)
    message.success('Chicken deleted successfully')
    fetchChickens()

  }
  catch (error) {
    message.error('Unable to delete chicken')
  }
}

const columns = [

  { title: 'Batch No', dataIndex: 'batch_number', },
  { title: 'Arrival Date', dataIndex: 'arrival_date', },
  { title: 'Batch Size', dataIndex: 'batch_size', },
  { title: 'Mortality', dataIndex: 'mortality', },
  { title: 'Birds Sold', dataIndex: 'birds_sold', },
  { title: 'Birds Remaining', dataIndex: 'birds_remaining', },
  {
    title: 'Expenses', dataIndex: 'total_expenses',
    customRender: ({ record }) =>
      `K ${Number(record.total_expenses).toLocaleString()}`
  },

  { title: 'Expected Sale', dataIndex: 'estimated_sale_date' },

  {
    title: 'Status', dataIndex: 'status',
    customRender: ({ record }) =>
      h(Tag, {
        color: record.status == 'Growing' ? 'blue'
          : record.status == 'Ready for Sale' ? 'green'
            : record.status == 'Selling' ? 'orange'
              : record.status == 'Completed' ? 'purple'
                : 'red'
      },
        () => record.status
      )
  },

  {
    title: 'Profit/Loss', dataIndex: 'profit_loss',
    customRender: ({ record }) => h('span', {
      class: record.profit_loss >= 0 ? 'text-green-600 font-bold' : 'text-red-600 font-bold'
    },
      `K ${Number(record.profit_loss).toLocaleString()}`
    )
  },

  {
    title: 'Actions', align: 'center', width: 170,
    customRender: ({ record }) =>
      h(Space, {}, () => [h(Tooltip, { title: 'View' }, {
        default: () => h(Button, {
          type: 'link',
          icon: h(EyeOutlined),
          onClick: () => {
            selectedChicken.value = record
            showViewModal.value = true
          }
        })
      }
      ),

      h(Tooltip, { title: 'Edit' }, {
        default: () => h(Button, {
          type: 'link',
          icon: h(EditOutlined),
          onClick: () => {
            selectedChicken.value = record
            showEditModal.value = true
          }
        })
      }
      ),

        h(Tooltip, { title: 'Record Sale' }, {
          default: () => h(Button, {
            type: 'link',
            icon: h(DollarOutlined),
            onClick: () => {
              selectedBatch.value = record
              showSaleModal.value = true
            }
          })
        }
        ),

        h(Tooltip, { title: 'Sales History' }, {
          default: () => h(Button, {
              type: 'link',
              icon: h(UnorderedListOutlined),
              onClick: () => {
                  selectedBatch.value = record
                  showSalesHistory.value = true
              }
          })
      }),

      h(Popconfirm, {
        title: 'Delete Batch?',
        onConfirm: () => deleteChicken(record.id)
      },

        {
          default: () => h(Button, {
            danger: true,
            type: 'link',
            icon: h(DeleteOutlined)
          })
        }
      )
      ]
      )
  }
];


onMounted(() => {
  fetchChickens()

})

</script>

<template>

  <Head title="Chicken Management" />

  <AuthenticatedLayout>
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
      <div class="mt-6">

        <h1 class="text-3xl font-bold">
          Chicken Management
        </h1>
        <p class="text-gray-500">
          Manage poultry batches, expenses, sales and flock performance.
        </p>
      </div>

      <Button type="primary" size="large" @click="showCreateModal = true">
        <template #icon>
          <PlusOutlined />
        </template>
        Add Chicken
      </Button>
    </div>

    <!-- Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">

      <Card>
        <div class="flex items-center">
          <div class="w-14 h-14 rounded-full bg-green-100 flex items-center justify-center">
            <UserOutlined class="text-2xl text-green-600" />
          </div>

          <div class="ml-4">
            <p class="text-gray-500">
              Total Batches
            </p>

            <h2 class="text-3xl font-bold">
              {{ chickens.length }}
            </h2>
          </div>
        </div>
      </Card>

      <Card>
        <div class="flex items-center">
          <div class="w-14 h-14 rounded-full bg-blue-100 flex items-center justify-center">
            <UserOutlined class="text-2xl text-blue-600" />
          </div>

          <div class="ml-4">
            <p class="text-gray-500">
              Growing
            </p>
            <h2 class="text-3xl font-bold">
              {{chickens.filter(x => x.status == 'Growing').length}}
            </h2>
          </div>
        </div>
      </Card>

      <Card>
        <div class="flex items-center">
          <div class="w-14 h-14 rounded-full bg-red-100 flex items-center justify-center">
            <UserOutlined class="text-2xl text-red-600" />
          </div>

          <div class="ml-4">
            <p class="text-gray-500">
              Ready For Sale
            </p>
            <h2 class="text-3xl font-bold">
              {{chickens.filter(x => x.status == 'Ready for Sale').length}}
            </h2>
          </div>
        </div>
      </Card>

      <Card>
        <div class="flex items-center">
          <div class="w-14 h-14 rounded-full bg-red-100 flex items-center justify-center">
            <UserOutlined class="text-2xl text-red-600" />
          </div>

          <div class="ml-4">
            <p class="text-gray-500"> Total Birds </p>
            <h2 class="text-3xl font-bold">
              {{chickens.reduce((t, x) => t + x.batch_size, 0)}}
            </h2>
          </div>
        </div>
      </Card>
    </div>

    <!-- Search -->

    <Card class="mb-6">
      <div class="flex gap-3">
        <Input v-model:value="searchTerm" placeholder="Search batch number..." @input="handleSearch">
          <template #prefix>
            <SearchOutlined />
          </template>
        </Input>
        <Button @click="clearSearch">
          <template #icon>
            <ClearOutlined />
          </template>
          Clear
        </Button>
      </div>
    </Card>

    <!-- table -->
    <Card>
      <a-table :columns="columns" :data-source="chickens" :loading="loading" row-key="id" :pagination="{
        pageSize: 10, showSizeChanger: true, showTotal: (total) => `Total ${total} chickens`
      }" />
    </Card>

    <CreateChickens v-model:open="showCreateModal" @created="fetchChickens" />

    <EditChickens v-model:open="showEditModal" :chicken="selectedChicken" @updated="fetchChickens" />

    <ViewChickens :open="showViewModal" :news="selectedChicken" @update:open="showViewModal = $event" />

    <CreateChickenSale v-model:open="showSaleModal" :batch="selectedBatch" @saved="fetchChickens" />

   <CreateChickenSalesHistory v-model:open="showSalesHistory" :batch="selectedBatch" />

  </AuthenticatedLayout>
</template>