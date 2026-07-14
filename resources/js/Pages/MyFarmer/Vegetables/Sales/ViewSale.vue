<!-- resources/js/Pages/MyFarmer/Vegetables/Sales/ViewSale.vue -->

<script setup>

import dayjs from 'dayjs'

import {
    EyeOutlined,
    CloseOutlined,
    PrinterOutlined,
    DownloadOutlined
} from '@ant-design/icons-vue'


const props = defineProps({

    open: {

        type: Boolean,

        default: false

    },

    sale: {

        type: Object,

        default: null

    }

})


const emit = defineEmits([

    'update:open'

])


const closeModal = () => {

    emit(
        'update:open',
        false
    )

}


const printSale = () => {

    window.print()

}

const downloadPdf = () => {

    window.open(

        `/download-vegetable-sales/${props.sale.id}/pdf`,

        '_blank'

    )

}


</script>


<template>


    <a-modal :open="open" title="Vegetable Sale Details" width="1200px" :footer="null" @cancel="closeModal">


        <div v-if="sale">


            <!-- Summary Cards -->

            <a-row :gutter="16" class="mb-5">


                <a-col :span="6">


                    <a-card>

                        <a-statistic title="Quantity" :value="sale.quantity" suffix="Kg" />


                    </a-card>


                </a-col>



                <a-col :span="6">


                    <a-card>


                        <a-statistic title="Unit Price" prefix="K" :value="sale.unit_price" />


                    </a-card>


                </a-col>



                <a-col :span="6">


                    <a-card>


                        <a-statistic title="Total Amount" prefix="K" :value="sale.total_amount" :valueStyle="{
                            color: '#52c41a'
                        }" />


                    </a-card>


                </a-col>



                <a-col :span="6">


                    <a-card>


                        <a-statistic title="Status" :value="sale.status" />


                    </a-card>


                </a-col>


            </a-row>




            <!-- Sale Information -->


            <a-card title="Sale Information" class="mb-4">


                <a-descriptions bordered :column="2">


                    <a-descriptions-item label="Invoice Number">


                        {{ sale.invoice_number || '-' }}


                    </a-descriptions-item>



                    <a-descriptions-item label="Sale Date">


                        {{

                            dayjs(sale.sale_date)

                        .format('DD MMM YYYY')

                        }}


                    </a-descriptions-item>



                    <a-descriptions-item label="Payment Method">


                        <a-tag color="blue">

                            {{ sale.payment_method }}

                        </a-tag>


                    </a-descriptions-item>



                    <a-descriptions-item label="Created">


                        {{

                            dayjs(sale.created_at)

                        .format('DD MMM YYYY HH:mm')

                        }}


                    </a-descriptions-item>


                </a-descriptions>


            </a-card>






            <!-- Customer Information -->


            <a-card title="Customer Information" class="mb-4">


                <a-descriptions bordered :column="2">


                    <a-descriptions-item label="Customer Name">


                        {{ sale.customer_name || '-' }}


                    </a-descriptions-item>



                    <a-descriptions-item label="Phone">


                        {{ sale.customer_phone || '-' }}


                    </a-descriptions-item>



                </a-descriptions>


            </a-card>







            <!-- Vegetable Information -->


            <a-card title="Vegetable Information" class="mb-4">


                <a-descriptions bordered :column="2">


                    <a-descriptions-item label="Crop">


                        {{

                            sale.harvest

                                ?.production

                        ?.vegetable_type

                        ?.name

                        || '-'

                        }}


                    </a-descriptions-item>




                    <a-descriptions-item label="Variety">


                        {{

                            sale.harvest

                                ?.production

                        ?.vegetable_type

                        ?.variety

                        || '-'

                        }}


                    </a-descriptions-item>



                    <a-descriptions-item label="Batch Number">


                        {{

                            sale.harvest

                                ?.production

                        ?.batch_number

                        || '-'

                        }}


                    </a-descriptions-item>



                    <a-descriptions-item label="Unit">


                        {{

                            sale.harvest

                                ?.production

                        ?.vegetable_type

                        ?.unit

                        || '-'

                        }}


                    </a-descriptions-item>



                </a-descriptions>


            </a-card>






            <!-- Harvest Information -->


            <a-card title="Harvest Information" class="mb-4">


                <a-descriptions bordered :column="2">


                    <a-descriptions-item label="Harvest Date">


                        {{

                            dayjs(

                                sale.harvest?.harvest_date

                        )

                        .format('DD MMM YYYY')

                        }}


                    </a-descriptions-item>



                    <a-descriptions-item label="Grade">


                        <a-tag color="green">

                            {{ sale.harvest?.grade }}

                        </a-tag>


                    </a-descriptions-item>




                    <a-descriptions-item label="Harvested By">


                        {{ sale.harvest?.harvested_by || '-' }}


                    </a-descriptions-item>




                    <a-descriptions-item label="Harvest Quantity">


                        {{ sale.harvest?.quantity }} Kg


                    </a-descriptions-item>




                    <a-descriptions-item label="Waste">


                        {{ sale.harvest?.waste }} Kg


                    </a-descriptions-item>



                </a-descriptions>


            </a-card>





            <!-- Notes -->


            <a-card title="Notes">


                <p>

                    {{ sale.notes || 'No notes available' }}

                </p>


            </a-card>





            <!-- Footer Buttons -->

            <div class="flex justify-end gap-3 mt-5">


                <a-button @click="downloadPdf">

                    <DownloadOutlined />

                    Download PDF

                </a-button>


                <a-button @click="printSale">

                    <PrinterOutlined />

                    Print

                </a-button>



                <a-button type="primary" @click="closeModal">

                    Close

                </a-button>


            </div>


        </div>



        <div v-else>


            <a-empty description="No sale selected" />


        </div>



    </a-modal>


</template>