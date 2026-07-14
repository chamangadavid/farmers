<!-- resources\js\Pages\MyFarmer\Vegetables\Index.vue -->
<script setup>
import { ref, reactive, onMounted, h } from 'vue'
import { Head } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

import CreateVegetables from './CreateVegetables.vue'
import EditVegetables from './EditVegetables.vue'
import ViewVegetables from './ViewVegetables.vue'

import axios from 'axios'
import { debounce } from 'lodash'

import {
    message,
    Tag,
    Button,
    Space
} from 'ant-design-vue'

import {
    PlusOutlined,
    ReloadOutlined,
    SearchOutlined,
    EyeOutlined,
    EditOutlined,
    DeleteOutlined
} from '@ant-design/icons-vue'

const loading = ref(false)

const vegetables = ref([])

const search = ref('')

const pagination = reactive({

    current:1,

    pageSize:10,

    total:0

})

const createModal = ref(false)

const editModal = ref(false)

const viewModal = ref(false)

const selectedVegetable = ref({})

const columns = [

{

title:'Vegetable',

dataIndex:'name',

key:'name'

},

{

title:'Variety',

dataIndex:'variety',

key:'variety'

},

{

title:'Unit',

dataIndex:'unit',

key:'unit'

},

{

title:'Status',

dataIndex:'status',

key:'status',

customRender:({record})=>

h(

Tag,

{

color:record.status ? 'green':'red'

},

()=>record.status ? 'Active':'Inactive'

)

},

{

title:'Created',

dataIndex:'created_at',

key:'created_at'

},

{

title:'Actions',

key:'actions'

}

]

const fetchVegetables = async(page = 1)=>{

loading.value = true

try{

const {data} = await axios.get('/vegetable-types',{

params:{

page,

search:search.value

}

})

vegetables.value = data.data

pagination.current = data.current_page

pagination.pageSize = data.per_page

pagination.total = data.total

}
catch(error){

console.log(error)

message.error('Unable to load vegetables.')

}
finally{

loading.value = false

}

}

const onSearch = debounce(()=>{

fetchVegetables()

},500)

const refresh = ()=>{

search.value=''

fetchVegetables()

}

const openCreate = ()=>{

createModal.value=true

}

const openView = (record)=>{

selectedVegetable.value = {...record}

viewModal.value = true

}

const openEdit = (record)=>{

selectedVegetable.value = {...record}

editModal.value = true

}

const deleteVegetable = async(record)=>{

try{

await axios.delete(

`/vegetable-types/${record.id}`

)

message.success(

'Vegetable deleted successfully.'

)

fetchVegetables(pagination.current)

}
catch(error){

console.log(error)

message.error(

'Unable to delete vegetable.'

)

}

}

const handleTableChange = (pager)=>{

fetchVegetables(

pager.current

)

}

const closeCreate = ()=>{

createModal.value=false

}

const closeEdit = ()=>{

editModal.value=false

}

const closeView = ()=>{

viewModal.value=false

}

// const reloadTable = ()=>{

// fetchVegetables(

// pagination.current

// )

// }


const statistics = ref({

    total:0,

    active:0,

    inactive:0,

    units:0

})

const fetchStatistics = async()=>{

    const {data} = await axios.get('/vegetable-types/statistics')

    statistics.value = data

}

onMounted(()=>{

fetchVegetables()

 fetchStatistics()

})

const reloadTable = ()=>{

    fetchVegetables(pagination.current)

    fetchStatistics()

}

</script>


<template>

<Head title="Vegetable Management" />

<AuthenticatedLayout>

<template #header>

<div class="flex justify-between items-center">

<h2 class="text-xl font-semibold">

Vegetable Management

</h2>

</div>

</template>

<div class="p-6">

<!-- ======================= -->
<!-- SUMMARY -->
<!-- ======================= -->

<div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-6">

<a-card>

<div class="text-center">

<p class="text-gray-500">

Total Vegetable Types

</p>

<h2 class="text-3xl font-bold text-green-600">

<!-- {{ pagination.total }} -->
{{ statistics.total }}

</h2>

</div>

</a-card>

<a-card>

<div class="text-center">

<p class="text-gray-500">

Active Types

</p>

<h2 class="text-3xl font-bold text-blue-600">

<!-- {{ vegetables.filter(v=>v.status).length }} -->
{{ statistics.active }}

</h2>

</div>

</a-card>

<a-card>

<div class="text-center">

<p class="text-gray-500">

Units

</p>

<h2 class="text-3xl font-bold text-purple-600">
{{ statistics.units }}
Kg

</h2>

</div>

</a-card>

</div>

<!-- ======================= -->
<!-- TOOLBAR -->
<!-- ======================= -->

<a-card class="mb-6">

<div
class="flex flex-col lg:flex-row lg:justify-between gap-4">

<div class="flex flex-wrap gap-2">

<a-input

v-model:value="search"

placeholder="Search vegetables..."

allow-clear

style="width:300px"

@input="onSearch"

>

<template #prefix>

<SearchOutlined />

</template>

</a-input>

<a-button

@click="refresh">

<ReloadOutlined />

Refresh

</a-button>

</div>

<div>

<a-button

type="primary"

@click="openCreate">

<PlusOutlined />

New Vegetable

</a-button>

</div>

</div>

</a-card>

<!-- ======================= -->
<!-- TABLE -->
<!-- ======================= -->

<a-card>

<a-table

:loading="loading"

:data-source="vegetables"

:columns="columns"

:pagination="pagination"

:scroll="{

x:1200,

y:550

}"

rowKey="id"

@change="handleTableChange">

<!-- Status -->

<template

#bodyCell="{column,record}">

<!-- ACTIONS -->

<template

v-if="column.key=='actions'">

<Space>

<a-tooltip title="View">

<a-button

type="primary"

size="small"

@click="openView(record)">

<EyeOutlined />

</a-button>

</a-tooltip>

<a-tooltip title="Edit">

<a-button

type="primary"

ghost

size="small"

@click="openEdit(record)">

<EditOutlined />

</a-button>

</a-tooltip>

<a-popconfirm

title="Delete this vegetable?"

ok-text="Yes"

cancel-text="No"

@confirm="deleteVegetable(record)">

<a-tooltip title="Delete">

<a-button

danger

size="small">

<DeleteOutlined />

</a-button>

</a-tooltip>

</a-popconfirm>

</Space>

</template>

</template>

</a-table>

</a-card>

<!-- ======================= -->
<!-- CREATE -->
<!-- ======================= -->

<CreateVegetables

:open="createModal"

@close="closeCreate"

@saved="reloadTable"

/>

<!-- ======================= -->
<!-- EDIT -->
<!-- ======================= -->

<EditVegetables

:open="editModal"

:vegetable="selectedVegetable"

@close="closeEdit"

@updated="reloadTable"

/>

<!-- ======================= -->
<!-- VIEW -->
<!-- ======================= -->

<ViewVegetables

:open="viewModal"

:vegetable="selectedVegetable"

@close="closeView"

/>

</div>

</AuthenticatedLayout>

</template>
