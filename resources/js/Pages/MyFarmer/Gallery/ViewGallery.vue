<!-- resources\js\Pages\MyFarmer\Gallery\ViewGallery.vue -->
<script setup>

import {
    Image,
    Empty
} from 'ant-design-vue'


const props = defineProps({

    open:{
        type:Boolean,
        default:false
    },

    gallery:{
        type:Object,
        default:null
    }

})


const emit = defineEmits([
    'update:open'
])



const closeModal=()=>{

    emit(
        'update:open',
        false
    )

}



const getImage=(image)=>{


    if(!image){

        return '/assets/no-image.png'

    }


    return image.startsWith('http')

        ?image

        :`/storage/${image}`


}


</script>




<template>


<a-modal

:title="gallery?.title || 'Gallery Images'"

:open="open"

@cancel="closeModal"

width="800px"

:footer="null"

>


<div v-if="gallery">



<p class="text-gray-500 mb-5">

{{gallery.description}}

</p>





<div

v-if="gallery.images.length"

class="grid grid-cols-3 gap-5"

>


<div

v-for="image in gallery.images"

:key="image.id"

>


<a-image

:src="getImage(image.image)"

class="rounded-lg object-cover w-full h-48"

/>


</div>



</div>





<div v-else>

<a-empty description="No images available"/>

</div>





</div>



</a-modal>


</template>