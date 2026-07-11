<script setup>

import PageLayout from '@/Components/PageLayout.vue';
import axios from 'axios';
import { message } from 'ant-design-vue';
import { ref } from 'vue';

import {
    PlusOutlined,
    InboxOutlined,
    DeleteOutlined
} from '@ant-design/icons-vue'


const props = defineProps({

    open:{
        type:Boolean,
        default:false
    }

})


const emit = defineEmits([
    'update:open',
    'created'
])



const title = ref('')

const description = ref('')

const files = ref([])

const previewImages = ref([])

const loading = ref(false)





const closeModal=()=>{

    emit(
        'update:open',
        false
    )

}




const beforeUpload=(file)=>{


    files.value.push(file)



    previewImages.value.push({

        uid:file.uid,

        name:file.name,

        url:URL.createObjectURL(file)

    })



    return false

}





const removeImage=(uid)=>{


    files.value =
        files.value.filter(
            file=>file.uid !== uid
        )


    previewImages.value =
        previewImages.value.filter(
            img=>img.uid !== uid
        )


}







const submit=async()=>{


    if(files.value.length===0){


        message.error(
            'Please upload at least one image'
        )


        return

    }




    const formData=new FormData()



    formData.append(
        'title',
        title.value
    )


    formData.append(
        'description',
        description.value
    )




    files.value.forEach((file)=>{


        formData.append(
            'images[]',
            file
        )


    })





    try{


        loading.value=true



        await axios.post(

            '/gallery',

            formData,

            {

                headers:{

                    'Content-Type':
                    'multipart/form-data'

                }

            }

        )



        message.success(
            'Gallery created successfully'
        )



        emit(
            'created'
        )


        closeModal()


        resetForm()


    }
    catch(error){


        console.log(error)


        message.error(
            'Failed to create gallery'
        )


    }
    finally{


        loading.value=false


    }


}







const resetForm=()=>{


    title.value=''

    description.value=''

    files.value=[]

    previewImages.value=[]


}



</script>





<template>

<PageLayout>
<a-modal

title="Add Gallery"

:open="open"

@cancel="closeModal"

width="700px"

:footer="null"

>



<form

@submit.prevent="submit"

class="space-y-5"

>



<div>


<label class="font-semibold block mb-2">

Title

</label>


<a-input

v-model:value="title"

placeholder="Gallery title"

/>


</div>





<div>


<label class="font-semibold block mb-2">

Description

</label>


<a-textarea

v-model:value="description"

rows="4"

placeholder="Gallery description"

/>


</div>








<div>


<label class="font-semibold block mb-2">

Upload Gallery Images

</label>




<a-upload-dragger

multiple

accept="image/*"

:before-upload="beforeUpload"

:show-upload-list="false"

>



<p class="ant-upload-drag-icon">

<InboxOutlined/>

</p>


<p class="ant-upload-text">

Click or drag images here

</p>


<p class="ant-upload-hint">

Upload multiple gallery pictures

</p>



</a-upload-dragger>



</div>







<!-- Preview -->


<div

v-if="previewImages.length"

class="grid grid-cols-3 gap-4 mt-5"

>


<div

v-for="img in previewImages"

:key="img.uid"

class="relative"


>


<img

:src="img.url"

class="w-full h-32 object-cover rounded-lg"

/>



<button

type="button"

@click="removeImage(img.uid)"

class="absolute top-1 right-1 bg-red-500 text-white rounded-full w-7 h-7"


>


<DeleteOutlined/>

</button>



</div>


</div>









<div class="flex justify-end gap-3 mt-6">


<a-button

@click="closeModal"

>

Cancel

</a-button>



<a-button

type="primary"

html-type="submit"

:loading="loading"

>


<PlusOutlined/>

Save Gallery


</a-button>



</div>






</form>



</a-modal>

</PageLayout>


</template>