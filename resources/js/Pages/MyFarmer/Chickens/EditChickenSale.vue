<!-- resources\js\Pages\MyFarmer\Chickens\EditChickenSale.vue -->
<script setup>

import { ref, watch } from 'vue'
import dayjs from 'dayjs'
import axios from 'axios'
import { message } from 'ant-design-vue'


const props = defineProps({

    open:Boolean,

    sale:{
        type:Object,
        default:()=>({})
    },
   

})


const emit = defineEmits([

    'update:open',

    'updated'

])


const form = ref({

    sale_date:'',
    quantity:0,
    unit_price:0,
    customer_name:'',
    customer_phone:'',
    notes:''

})


const loading = ref(false)



watch(
    () => props.sale,
    (sale)=>{

        if(sale && sale.id){

            form.value={

                sale_date: sale.sale_date
                    ? dayjs(sale.sale_date)
                    : null,

                quantity: sale.quantity ?? 0,

                unit_price: sale.unit_price ?? 0,

                customer_name: sale.customer_name ?? '',

                customer_phone: sale.customer_phone ?? '',

                notes: sale.notes ?? ''

            }

        }

    },
    {
        immediate:true
    }

)




const closeModal=()=>{

    emit(
        'update:open',
        false
    )

}



const updateSale=async()=>{


    loading.value=true


    try{

        await axios.put(

    `/update-chicken-sales/${props.sale.id}`,

    {

        ...form.value,

        sale_date: form.value.sale_date
            ? form.value.sale_date.format('YYYY-MM-DD')
            : null

    }

)


        message.success(
            'Sale updated successfully'
        )


        emit(
            'updated'
        )


        closeModal()


    }
    catch(error){


        console.log(error)


        message.error(
            error.response?.data?.message ??
            'Unable to update sale'
        )


    }
    finally{

        loading.value=false

    }


}



</script>


<template>


<a-modal

    :open="open"

    title="Edit Chicken Sale"

    :maskClosable="false"

    @cancel="closeModal"


>


<a-form layout="vertical">


<a-form-item label="Sale Date">


<a-date-picker

    v-model:value="form.sale_date"

    style="width:100%"

/>


</a-form-item>



<a-form-item label="Quantity Sold">


<a-input-number

    v-model:value="form.quantity"

    :min="1"

    style="width:100%"

/>


</a-form-item>



<a-form-item label="Price Per Bird">


<a-input-number

    v-model:value="form.unit_price"

    :min="0"

    style="width:100%"

/>


</a-form-item>



<a-form-item label="Customer Name">


<a-input

    v-model:value="form.customer_name"

/>


</a-form-item>



<a-form-item label="Phone">


<a-input

    v-model:value="form.customer_phone"

/>


</a-form-item>



<a-form-item label="Notes">


<a-textarea

    v-model:value="form.notes"

    :rows="4"

/>


</a-form-item>


</a-form>



<template #footer>


<a-button
@click="closeModal">

Cancel

</a-button>


<a-button

type="primary"

:loading="loading"

@click="updateSale"

>

Update Sale

</a-button>


</template>


</a-modal>


</template>