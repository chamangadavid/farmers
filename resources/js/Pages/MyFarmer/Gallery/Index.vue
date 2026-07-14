<!-- resources\js\Pages\MyFarmer\Gallery\Index.vue -->
<script setup>

import { ref, h, onMounted, computed } from 'vue';
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

import {
    Button,
    Input,
    Popconfirm,
    message,
    Card,
    Table,
    Tooltip,
    Tag,
    Space
} from 'ant-design-vue';

import {
    EyeOutlined,
    EditOutlined,
    DeleteOutlined,
    PlusOutlined,
    SearchOutlined,
    ClearOutlined,
    PictureOutlined
} from '@ant-design/icons-vue';

import axios from 'axios';
import { debounce } from 'lodash';


import CreateGallery from './CreateGallery.vue';
import ViewGallery from './ViewGallery.vue';
import EditGallery from './EditGallery.vue';



const gallery = ref([])

const loading = ref(false)

const searchTerm = ref('')


const selectedGallery = ref(null)


const showCreateModal = ref(false)

const showEditModal = ref(false)

const showViewModal = ref(false)


const fetchGallery = async()=>{
    loading.value=true
    try{
        const {data}=await axios.get('/gallery',{
            params:{
                search:searchTerm.value
            }
        })

        gallery.value=data
    }
    catch(error){
        message.error(
            'Failed to load gallery'
        )
    }
    finally{
        loading.value=false
    }
}



const handleSearch = debounce(()=>{

    fetchGallery()

},400)



const clearSearch=()=>{

    searchTerm.value=''

    fetchGallery()

}





// const deleteGallery=async(id)=>{


//     try{


//         await axios.delete(
//             `/gallery/${id}`
//         )


//         message.success(
//             'Gallery deleted successfully'
//         )


//         fetchGallery()


//     }
//     catch(error){

//         message.error(
//             'Failed to delete gallery'
//         )

//     }


// }


const deleteGallery = async (id) => {

    try{

        await axios.delete(`/gallery/${id}`)

        message.success('Gallery deleted successfully')

        fetchGallery()

    }
    catch(error){

        console.error(error)

        message.error('Failed to delete gallery')

    }

}



const getImage=(image)=>{


    if(!image){

        return '/assets/no-image.png'

    }


    return image.startsWith('http')

        ?image

        :`/storage/${image}`


}





const columns=[


{
    title:'Gallery',

    dataIndex:'title',

    width:350,

    customRender:({record})=>

        h('div',[

            h(
                'h3',
                {
                    class:'font-bold text-lg'
                },
                record.title
            ),


            h(
                'p',
                {
                    class:'text-gray-500'
                },
                record.description || ''
            )


        ])

},


{
    title: 'Images',
    dataIndex: 'images',
    width: 450,

    customRender: ({ record }) => {

        const images = record.images || []

        return h(
            'div',
            {
                class: 'grid grid-cols-4 gap-2'
            },

            [

                ...images.slice(0, 3).map(img =>

                    h('img', {

                        src: getImage(img.image),

                        class: 'w-20 h-20 rounded-lg object-cover border'

                    })

                ),

                images.length > 3 &&
                h(
                    'div',
                    {
                        class: 'w-20 h-20 rounded-lg bg-gray-700 text-white flex items-center justify-center font-bold'
                    },

                    `+${images.length - 3}`

                )

            ]

        )

    }

},

{

    title:'Actions',

    align:'center',

    customRender:({record})=>

        h(

            Space,

            {},

            ()=>[

                h(

                    Tooltip,

                    { title:'View' },

                    {

                        default:()=>h(

                            Button,

                            {

                                type:'link',

                                icon:h(EyeOutlined),

                                onClick:()=>{

                                    selectedGallery.value = record

                                    showViewModal.value = true

                                }

                            }

                        )

                    }

                ),

                // h(

                //     Tooltip,

                //     { title:'Edit' },

                //     {

                //         default:()=>h(

                //             Button,

                //             {

                //                 type:'link',

                //                 icon:h(EditOutlined),

                //                 onClick:()=>{

                //                     selectedGallery.value = record

                //                     showEditModal.value = true

                //                 }

                //             }

                //         )

                //     }

                // ),

                h(

                    Popconfirm,

                    {

                        title:'Delete this gallery?',

                        description:'All images inside this gallery will also be deleted.',

                        okText:'Yes',

                        cancelText:'No',

                        onConfirm:()=>deleteGallery(record.id)

                    },

                    {

                        default:()=>h(

                            Button,

                            {

                                danger:true,

                                type:'link',

                                icon:h(DeleteOutlined)

                            }

                        )

                    }

                )

            ]

        )

}

// {

// title:'Actions',

// align:'center',


// customRender:({record})=>

// h(

// Space,

// {},()=>[


// h(

// Button,

// {

// type:'link',

// icon:h(EyeOutlined),

// onClick:()=>{

//     selectedGallery.value = record

//     showViewModal.value = true

// }

// }

// ),


// h(

// Button,

// {

// type:'link',

// icon:h(EditOutlined),

// onClick:()=>{

//     selectedGallery.value = record

//     showEditModal.value = true

// }

// }

// )

// // h(

// // Button,

// // {

// // type:'link',

// // icon:h(EditOutlined)

// // }

// // ),


// ]

// )

// }



]





onMounted(()=>{

    fetchGallery()

})



</script>




<template>


<Head title="Gallery" />


<AuthenticatedLayout>


<div class="p-6">



<!-- Header -->

<div class="flex flex-col md:flex-row md:justify-between md:items-center mb-8">


<div>


<h1 class="text-3xl font-bold text-gray-800">

Gallery Management

</h1>


<p class="text-gray-500 mt-1">

Manage farm gallery images displayed on the website.

</p>


</div>



<Button

type="primary"

size="large"

@click="showCreateModal=true"

>


<template #icon>

<PlusOutlined/>

</template>


Add Gallery


</Button>



</div>





<!-- Statistics -->


<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">



<Card>


<div class="flex items-center">


<div class="w-14 h-14 rounded-full bg-green-100 flex items-center justify-center">


<PictureOutlined class="text-2xl text-green-600"/>


</div>



<div class="ml-4">


<p class="text-gray-500">

Total Images

</p>


<h2 class="text-3xl font-bold">

{{ gallery.length }}

</h2>


</div>


</div>


</Card>





<Card>


<div class="flex items-center">


<div class="w-14 h-14 rounded-full bg-blue-100 flex items-center justify-center">


<PictureOutlined class="text-2xl text-blue-600"/>


</div>



<div class="ml-4">


<p class="text-gray-500">

Active Images

</p>


<h2 class="text-3xl font-bold">

{{gallery.filter(x=>x.status).length}}

</h2>


</div>


</div>


</Card>






<Card>


<div class="flex items-center">


<div class="w-14 h-14 rounded-full bg-red-100 flex items-center justify-center">


<PictureOutlined class="text-2xl text-red-600"/>


</div>



<div class="ml-4">


<p class="text-gray-500">

Inactive Images

</p>


<h2 class="text-3xl font-bold">

{{gallery.filter(x=>!x.status).length}}

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

placeholder="Search gallery..."

@input="handleSearch"

>


<template #prefix>

<SearchOutlined/>

</template>


</Input>




<Button @click="clearSearch">


<template #icon>

<ClearOutlined/>

</template>


Clear


</Button>



</div>


</Card>







<!-- Table -->


<Card>


<a-table

:columns="columns"

:data-source="gallery"

:loading="loading"

row-key="id"


:pagination="{

pageSize:10,

showSizeChanger:true,

showTotal:(total)=>`Total ${total} images`

}"


/>


</Card>






</div>




<CreateGallery

v-model:open="showCreateModal"

@created="fetchGallery"

/>


<ViewGallery

v-model:open="showViewModal"

:gallery="selectedGallery"

/>

<EditGallery

v-model:open="showEditModal"

:gallery="selectedGallery"

@updated="fetchGallery"

/>



</AuthenticatedLayout>


</template>