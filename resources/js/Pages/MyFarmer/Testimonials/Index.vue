<!-- resources\js\Pages\MyFarmer\Testimonials\Index.vue -->
<script setup>
import { ref, h, onMounted, computed } from 'vue';
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Button, Input, Popconfirm, message, Table, Tooltip, Tag, Space, Card, Tabs } from 'ant-design-vue';
import { 
  EyeOutlined,

    EditOutlined,

    DeleteOutlined,

    PlusOutlined,

    SearchOutlined,

    ClearOutlined,

    UserOutlined
} from '@ant-design/icons-vue';
import dayjs from 'dayjs';
import axios from 'axios';
import { debounce } from 'lodash';

import CreateTestimonials from './CreateTestimonials.vue';
import EditTestimonials from './EditTestimonials.vue';
import ViewTestimonials from './ViewTestimonials.vue';


const testimonials = ref([])

const loading = ref(false)

const searchTerm = ref('')

const selectedTestimonial = ref(null)

const showCreateModal = ref(false)

const showEditModal = ref(false)

const showViewModal = ref(false)

const jobs = ref([]);
const applications = ref([]);
const selectedJob = ref(null);
const selectedApplication = ref(null);

// const showCreateModal = ref(false);
// const showEditModal = ref(false);
// const showViewModal = ref(false);
const showApplicationModal = ref(false);

// const searchTerm = ref('');
const applicationSearchTerm = ref('');
// const loading = ref(false);
const applicationsLoading = ref(false);
const activeTab = ref('jobs');

// Status color mapping for jobs
const getStatusColor = (status) => {
    const colors = {
        'Open': 'green',
        'Closed': 'red',
        'Pending': 'orange',
        'Draft': 'default',
        'Published': 'blue',
        'Filled': 'purple'
    };
    return colors[status] || 'default';
};

const fetchTestimonials = async () => {

    loading.value = true

    try {

        const { data } = await axios.get('/testimonials', {

            params: {

                search: searchTerm.value

            }

        })

        testimonials.value = data.testimonials

    }

    catch (e) {

        console.log(e)

        message.error('Failed to load testimonials.')

    }

    finally {

        loading.value = false

    }

}

const handleSearch = debounce(() => {

    fetchTestimonials()

},400)

const clearSearch = () => {

    searchTerm.value=''

    fetchTestimonials()

}

// Fetch Jobs
// const fetchJobs = async () => {
//     loading.value = true;
//     try {
//         const res = await axios.get('/jobs', { params: { search: searchTerm.value } });
//         jobs.value = res.data.jobs;
//     } catch (err) {
//         message.error('Failed to fetch jobs');
//         console.error(err);
//     } finally {
//         loading.value = false;
//     }
// };

// Fetch Applications
const fetchApplications = async () => {
    applicationsLoading.value = true;
    try {
        const res = await axios.get('/job-applications', { 
            params: { search: applicationSearchTerm.value } 
        });
        applications.value = res.data.applications;
    } catch (err) {
        message.error('Failed to fetch applications');
        console.error(err);
    } finally {
        applicationsLoading.value = false;
    }
};

// Search handlers
// const handleSearch = debounce(() => {
//     if (activeTab.value === 'jobs') {
//         fetchJobs();
//     } else {
//         fetchApplications();
//     }
// }, 500);

// const clearSearch = () => {
//     if (activeTab.value === 'jobs') {
//         searchTerm.value = '';
//         fetchJobs();
//     } else {
//         applicationSearchTerm.value = '';
//         fetchApplications();
//     }
// };

const deleteTestimonial = async(id)=>{

    try{

        await axios.delete(`/testimonials/${id}`)

        message.success('Deleted successfully.')

        fetchTestimonials()

    }

    catch(e){

        message.error('Unable to delete testimonial.')

    }

}

// Delete job
// const deleteJob = async (id) => {
//     try {
//         await axios.delete(`/jobs/${id}`);
//         message.success('Job deleted successfully');
//         fetchJobs();
//     } catch (err) {
//         message.error('Failed to delete job');
//     }
// };

// Delete application
const deleteApplication = async (id) => {
    try {
        await axios.delete(`/job-applications/${id}`);
        message.success('Application deleted successfully');
        fetchApplications();
    } catch (err) {
        message.error('Failed to delete application');
    }
};

const getImage=(image)=>{

    if(!image){

        return '/assets/no-image.png'

    }

    return image.startsWith('http')

        ? image

        : `/storage/${image}`

}

const columns=[

{
    title:'Photo',

    dataIndex:'image',

    width:100,

    customRender:({record})=>

        h('img',{

            src:getImage(record.image),

            class:'w-16 h-16 rounded-full object-cover border'

        })

},

{

    title:'Customer',

    dataIndex:'name'

},

{

    title:'Location',

    dataIndex:'location'

},

{

    title:'Review',

    dataIndex:'review',

    customRender:({record})=>

        record.review.length>70

        ?record.review.substring(0,70)+'...'

        :record.review

},

{

    title:'Status',

    dataIndex:'status',

    customRender:({record})=>

        h(

            Tag,

            {

                color:record.status?'green':'red'

            },

            ()=>record.status?'Active':'Inactive'

        )

},

{

title:'Actions',

width:160,

align:'center',

customRender:({record})=>

h(

Space,

{},()=>[

h(

Tooltip,

{title:'View'},

{

default:()=>h(Button,{

type:'link',

icon:h(EyeOutlined),

onClick:()=>{

selectedTestimonial.value=record

showViewModal.value=true

}

})

}

),

h(

Tooltip,

{title:'Edit'},

{

default:()=>h(Button,{

type:'link',

icon:h(EditOutlined),

onClick:()=>{

selectedTestimonial.value=record

showEditModal.value=true

}

})

}

),

h(

Popconfirm,

{

title:'Delete testimonial?',

onConfirm:()=>deleteTestimonial(record.id)

},

{

default:()=>h(Button,{

danger:true,

type:'link',

icon:h(DeleteOutlined)

})

}

)

]

)

}

]

// View application
const viewApplication = (application) => {
    selectedApplication.value = application;
    showApplicationModal.value = true;
};

// Helper to truncate text
const truncate = (text, length = 30) => {
    if (!text) return '';
    return text.trim().length > length 
        ? text.trim().substring(0, length) + '...' 
        : text.trim();
};

// Format deadline
const formatDeadline = (deadline) => {
    if (!deadline) return 'Not specified';
    const date = dayjs(deadline);
    const today = dayjs();
    const daysUntil = date.diff(today, 'day');
    
    if (daysUntil < 0) return `Expired (${date.format('YYYY-MM-DD')})`;
    if (daysUntil === 0) return `Today (${date.format('YYYY-MM-DD')})`;
    if (daysUntil <= 7) return `In ${daysUntil} day(s) - ${date.format('YYYY-MM-DD')}`;
    return date.format('YYYY-MM-DD');
};

// Jobs Table Columns
// const jobColumns = [
//     { 
//         title: 'Job Title', 
//         dataIndex: 'title',
//         width: '25%',
//         customRender: ({ record }) => h('div', { class: 'font-medium text-gray-900' }, truncate(record.title, 35))
//     },
//     { 
//         title: 'Type', 
//         dataIndex: 'type',
//         width: '12%',
//         customRender: ({ record }) => record.type 
//             ? h(Tag, { color: 'blue', class: 'px-2 py-1' }, record.type)
//             : h('span', { class: 'text-gray-400' }, '—')
//     },
//     { 
//         title: 'Location', 
//         dataIndex: 'location',
//         width: '15%',
//         customRender: ({ record }) => record.location 
//             ? h('div', { class: 'flex items-center gap-1' }, [
//                 h('span', '📍'),
//                 h('span', truncate(record.location, 20))
//               ])
//             : h('span', { class: 'text-gray-400' }, '—')
//     },
//     {
//         title: 'Deadline',
//         dataIndex: 'deadline',
//         width: '18%',
//         customRender: ({ record }) => {
//             if (!record.deadline) return h('span', { class: 'text-gray-400' }, 'Not specified');
//             const isExpired = dayjs(record.deadline).isBefore(dayjs());
//             return h('div', { class: 'flex items-center gap-1' }, [
//                 h('span', '⏰'),
//                 h('span', { 
//                     class: isExpired ? 'text-red-600 font-medium' : 'text-gray-700' 
//                 }, formatDeadline(record.deadline))
//             ]);
//         }
//     },
//     { 
//         title: 'Status', 
//         dataIndex: 'status',
//         width: '12%',
//         customRender: ({ record }) => record.status 
//             ? h(Tag, { 
//                 color: getStatusColor(record.status),
//                 class: 'px-2 py-1 font-medium'
//               }, record.status.toUpperCase())
//             : h(Tag, { color: 'default' }, 'UNKNOWN')
//     },
//     {
//         title: 'Actions',
//         width: '18%',
//         align: 'center',
//         customRender: ({ record }) => h(Space, { size: 'small' }, [
//             h(Tooltip, { title: 'View Job Details' }, {
//                 default: () => h(Button, { 
//                     size: 'small', 
//                     type: 'link',
//                     icon: h(EyeOutlined),
//                     onClick: () => { 
//                         selectedJob.value = record; 
//                         showViewModal.value = true; 
//                     },
//                     class: 'text-blue-600 hover:text-blue-800'
//                 })
//             }),
//             h(Tooltip, { title: 'Edit Job' }, {
//                 default: () => h(Button, { 
//                     size: 'small', 
//                     type: 'link',
//                     icon: h(EditOutlined),
//                     onClick: () => { 
//                         selectedJob.value = record; 
//                         showEditModal.value = true; 
//                     },
//                     class: 'text-amber-600 hover:text-amber-800'
//                 })
//             }),
//             h(Tooltip, { title: 'Delete Job' }, {
//                 default: () => h(Popconfirm, { 
//                     title: 'Delete Job',
//                     description: 'Are you sure you want to delete this job? This action cannot be undone.',
//                     okText: 'Yes, Delete',
//                     cancelText: 'Cancel',
//                     okType: 'danger',
//                     onConfirm: () => deleteJob(record.id) 
//                 }, {
//                     default: () => h(Button, { 
//                         danger: true, 
//                         size: 'small', 
//                         type: 'link',
//                         icon: h(DeleteOutlined),
//                         class: 'text-red-600 hover:text-red-800'
//                     })
//                 })
//             })
//         ])
//     }
// ];

onMounted(()=>{

    fetchTestimonials()

})

// onMounted(() => {
//     fetchJobs();
// });
</script>

<template>

<Head title="Testimonials" />

<AuthenticatedLayout>

    <div class="p-6">

        <!-- Header -->

        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">

            <div>

                <h1 class="text-3xl font-bold text-gray-800">
                    Testimonials
                </h1>

                <p class="text-gray-500 mt-1">
                    Manage customer testimonials displayed on the Alinaswe Farm website.
                </p>

            </div>

            <Button
                type="primary"
                size="large"
                @click="showCreateModal = true">

                <template #icon>
                    <PlusOutlined />
                </template>

                Add Testimonial

            </Button>

        </div>

        <!-- Statistics -->

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

            <Card>

                <div class="flex items-center">

                    <div
                        class="w-14 h-14 rounded-full bg-green-100 flex items-center justify-center">

                        <UserOutlined
                            class="text-2xl text-green-600"/>

                    </div>

                    <div class="ml-4">

                        <p class="text-gray-500">
                            Total Testimonials
                        </p>

                        <h2 class="text-3xl font-bold">

                            {{ testimonials.length }}

                        </h2>

                    </div>

                </div>

            </Card>

            <Card>

                <div class="flex items-center">

                    <div
                        class="w-14 h-14 rounded-full bg-blue-100 flex items-center justify-center">

                        <UserOutlined
                            class="text-2xl text-blue-600"/>

                    </div>

                    <div class="ml-4">

                        <p class="text-gray-500">

                            Active

                        </p>

                        <h2 class="text-3xl font-bold">

                            {{ testimonials.filter(x => x.status).length }}

                        </h2>

                    </div>

                </div>

            </Card>

            <Card>

                <div class="flex items-center">

                    <div
                        class="w-14 h-14 rounded-full bg-red-100 flex items-center justify-center">

                        <UserOutlined
                            class="text-2xl text-red-600"/>

                    </div>

                    <div class="ml-4">

                        <p class="text-gray-500">

                            Inactive

                        </p>

                        <h2 class="text-3xl font-bold">

                            {{ testimonials.filter(x => !x.status).length }}

                        </h2>

                    </div>

                </div>

            </Card>

        </div>

        <!-- Search -->

        <Card class="mb-6">

            <div class="flex gap-3">

                <Input

                    v-model:value="searchTerm"

                    placeholder="Search testimonials..."

                    @input="handleSearch">

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

        <!-- Table -->

        <Card>

            <a-table

                :columns="columns"

                :data-source="testimonials"

                :loading="loading"

                row-key="id"

                :pagination="{

                    pageSize:10,

                    showSizeChanger:true,

                    showTotal:(total)=>`Total ${total} testimonials`

                }"

            />

        </Card>

    </div>

    <!-- Create -->

    <CreateTestimonials

        v-model:open="showCreateModal"

        @created="fetchTestimonials"

    />

    <!-- Edit -->

    <EditTestimonials

        v-model:open="showEditModal"

        :testimonial="selectedTestimonial"

        @updated="fetchTestimonials"

    />

    <!-- View -->

    <ViewTestimonials

        v-model:open="showViewModal"

        :testimonial="selectedTestimonial"

    />

</AuthenticatedLayout>

</template>

<style scoped>
/* Professional Table Styling */
.jobs-table :deep(.ant-table),
.applications-table :deep(.ant-table) {
  font-family: inherit;
}

.jobs-table :deep(.ant-table-thead > tr > th),
.applications-table :deep(.ant-table-thead > tr > th) {
  background: #f8fafc;
  font-weight: 600;
  color: #1e293b;
  border-bottom: 2px solid #e2e8f0;
  padding: 12px 16px;
}

.jobs-table :deep(.ant-table-tbody > tr > td),
.applications-table :deep(.ant-table-tbody > tr > td) {
  padding: 16px;
  border-bottom: 1px solid #f1f5f9;
}

.jobs-table :deep(.ant-table-tbody > tr:hover > td),
.applications-table :deep(.ant-table-tbody > tr:hover > td) {
  background: #fafbff;
}

/* Search Input Styling */
.search-input :deep(.ant-input) {
  border-radius: 8px;
  border-color: #e2e8f0;
  transition: all 0.3s ease;
}

.search-input :deep(.ant-input:focus) {
  border-color: #14b8a6;
  box-shadow: 0 0 0 2px rgba(20, 184, 166, 0.1);
}

.search-input :deep(.ant-input:hover) {
  border-color: #14b8a6;
}

/* Button Styling */
:deep(.ant-btn-primary) {
  background: #14b8a6;
  border-color: #14b8a6;
  border-radius: 8px;
  font-weight: 500;
  transition: all 0.3s ease;
}

:deep(.ant-btn-primary:hover) {
  background: #0d9488;
  border-color: #0d9488;
  transform: translateY(-1px);
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
}

:deep(.ant-btn-link) {
  border-radius: 6px;
}

:deep(.ant-btn-link:hover) {
  transform: scale(1.05);
}

/* Tag Styling */
:deep(.ant-tag) {
  border-radius: 6px;
  padding: 2px 10px;
  font-weight: 500;
  border: none;
}

:deep(.ant-tag-green) {
  background: #dcfce7;
  color: #166534;
}

:deep(.ant-tag-red) {
  background: #fee2e2;
  color: #991b1b;
}

:deep(.ant-tag-orange) {
  background: #ffedd5;
  color: #9a3412;
}

:deep(.ant-tag-blue) {
  background: #dbeafe;
  color: #1e40af;
}

:deep(.ant-tag-purple) {
  background: #f3e8ff;
  color: #6b21a5;
}

/* Tabs Styling */
.custom-tabs :deep(.ant-tabs-nav) {
  margin-bottom: 24px;
}

.custom-tabs :deep(.ant-tabs-tab) {
  padding: 12px 20px;
  font-size: 15px;
  font-weight: 500;
}

.custom-tabs :deep(.ant-tabs-tab-active) {
  color: #14b8a6;
}

.custom-tabs :deep(.ant-tabs-ink-bar) {
  background: #14b8a6;
}

/* Card Hover Effect */
:deep(.ant-card) {
  border-radius: 12px;
  transition: all 0.3s ease;
}

:deep(.ant-card:hover) {
  transform: translateY(-2px);
}

/* Responsive Adjustments */
@media (max-width: 640px) {
  .search-input :deep(.ant-input) {
    width: 100%;
  }
  
  :deep(.ant-table) {
    font-size: 12px;
  }
  
  :deep(.ant-table-thead > tr > th),
  :deep(.ant-table-tbody > tr > td) {
    padding: 8px 12px;
  }
}
</style>