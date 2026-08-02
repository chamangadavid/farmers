<!-- resources\js\Pages\MyFarmer\Chickens\EditChickenSale.vue -->
<script setup>

import { ref, watch, computed } from 'vue'
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

    //sale_date:'',
    sale_date: null,
    quantity:0,
    unit_price:0,
    customer_name:'',
    customer_phone:'',
    notes:'',
    payment_method: null,
    payment_amount: null

})

const totalAmount = computed(() => {

    return Number(
        props.sale?.total_amount ?? 0
    )

})


const totalPaid = computed(() => {

    return Number(
        props.sale?.amount_paid ?? 0
    )

})


const balance = computed(() => {

    return Math.max(

        0,

        totalAmount.value -
        totalPaid.value

    )

})


const loading = ref(false)



watch(
    () => props.sale,
    (sale)=>{

        if(sale && sale.id){

            form.value={

                sale_date: sale.sale_date ? dayjs(sale.sale_date) : null,

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

//         await axios.put(

//     `/update-chicken-sales/${props.sale.id}`,

//     {

//         ...form.value,

//         sale_date: form.value.sale_date
//             ? form.value.sale_date.format('YYYY-MM-DD')
//             : null

//     }

// )

await axios.put(

    `/update-chicken-sales/${props.sale.id}`,

    {

        sale_date: form.value.sale_date
            ? form.value.sale_date.format('YYYY-MM-DD')
            : null,

        quantity: form.value.quantity,

        unit_price: form.value.unit_price,

        customer_name: form.value.customer_name,

        customer_phone: form.value.customer_phone,

        notes: form.value.notes,

        payment_method:
            form.value.payment_method,

        payment_amount:
            form.value.payment_amount

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

<a-modal :open="open" title="Edit Chicken Sale" width="800px" 
:maskClosable="false" @cancel="closeModal">


<a-form layout="vertical">
<a-form-item label="Sale Date">
<a-date-picker v-model:value="form.sale_date" style="width:100%"/>
</a-form-item>



<a-form-item label="Quantity Sold">
<a-input-number v-model:value="form.quantity" :min="1" style="width:100%" />
</a-form-item>



<a-form-item label="Price Per Bird">
<a-input-number v-model:value="form.unit_price" :min="0" style="width:100%" />
</a-form-item>

<a-form-item label="Total Amount">

    <a-input
        :value="Number(totalAmount).toLocaleString()"
        readonly
    />

</a-form-item>

<a-form-item label="Total Amount Paid">

    <a-input
        :value="Number(totalPaid).toLocaleString()"
        readonly
    />

</a-form-item>

<a-form-item label="Balance">

    <a-input
        :value="Number(balance).toLocaleString()"
        readonly
    />

</a-form-item>

<a-divider>
    Record New Payment
</a-divider>

<a-form-item label="Payment Method">

    <a-select
        v-model:value="form.payment_method"
        style="width: 100%"
        placeholder="Select payment method"
    >

        <a-select-option value="Cash">
            Cash
        </a-select-option>

        <a-select-option value="Card">
            Card
        </a-select-option>

        <a-select-option value="Cheque">
            Cheque
        </a-select-option>

        <a-select-option value="Mobile Money">
            Mobile Money
        </a-select-option>

        <a-select-option value="Bank Transfer">
            Bank Transfer
        </a-select-option>

    </a-select>

</a-form-item>


<a-form-item label="Amount Paid">

    <a-input-number

        v-model:value="form.payment_amount"

        :min="0"

        :max="balance"

        style="width: 100%"

        placeholder="Enter payment amount"

    />

    <small class="text-gray-500">

        Leave empty if you are not recording a payment.

    </small>

</a-form-item>



<a-form-item label="Customer Name">
<a-input v-model:value="form.customer_name"
style="border: 1px solid #e9e9e9; border-radius: 8px;"/>

</a-form-item>

<a-form-item label="Phone">
<a-input v-model:value="form.customer_phone"
style="border: 1px solid #e9e9e9; border-radius: 8px;"/>

</a-form-item>
<a-form-item label="Notes">
<a-textarea v-model:value="form.notes" :rows="4" 
style="border: 1px solid #e9e9e9; border-radius: 8px;"/>
</a-form-item>

</a-form>



<template #footer>
<a-button @click="closeModal">
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