<!-- resources\js\Pages\MyFarmer\Chickens\ViewChickens.vue -->
 <script setup>
import { Modal } from 'ant-design-vue';
import dayjs from 'dayjs';

const props = defineProps({
    open: Boolean,
    news: Object
});

const emit = defineEmits(['update:open']);
</script>

<template>
<a-modal
    :open="open"
    title="View Details"
    @cancel="emit('update:open', false)"
    :footer="null"
    width="1200px"
>
   
<a-descriptions bordered :column="2" v-if="news">

    <a-descriptions-item label="Batch Number">
        {{ news.batch_number }}
    </a-descriptions-item>

    <a-descriptions-item label="Batch Name">
        {{ news.batch_name }}
    </a-descriptions-item>

    <a-descriptions-item label="Arrival Date">
        {{ news.arrival_date }}
    </a-descriptions-item>

    <a-descriptions-item label="Estimated Sale">
        {{ news.estimated_sale_date }}
    </a-descriptions-item>

    <a-descriptions-item label="Breed">
        {{ news.breed }}
    </a-descriptions-item>

    <a-descriptions-item label="Supplier">
        {{ news.supplier }}
    </a-descriptions-item>

    <a-descriptions-item label="Purchase Price">
        {{ news.purchase_price }}
    </a-descriptions-item>

    <a-descriptions-item label="Batch Size">
        {{ news.batch_size }}
    </a-descriptions-item>

    <a-descriptions-item label="Mortality">
        {{ news.mortality }}
    </a-descriptions-item>

    <a-descriptions-item label="Birds Sold">
        {{ news.birds_sold }}
    </a-descriptions-item>

    <a-descriptions-item label="Birds Remaining">
        {{ news.birds_remaining }}
    </a-descriptions-item>

    <a-descriptions-item label="Status">
        {{ news.status }}
    </a-descriptions-item>

    <a-descriptions-item label="Total Expenses">
        {{ news.total_expenses }}
    </a-descriptions-item>

    <a-descriptions-item label="Profit/Loss">
        {{ news.profit_loss }}
    </a-descriptions-item>

</a-descriptions>


<h3 class="mt-6 mb-3 font-semibold text-lg">
    Expenses
</h3>

<a-table
     :dataSource="news?.expenses || []"
    :pagination="false"
    rowKey="id"
>

    <a-table-column
        title="Date"
        dataIndex="expense_date"
    />

    <a-table-column
        title="Item"
        dataIndex="item"
    />

    <a-table-column
        title="Qty"
        dataIndex="quantity"
    />

    <a-table-column
        title="Unit Price"
        dataIndex="unit_price"
    />

    <a-table-column
        title="Amount"
        dataIndex="amount"
    />

</a-table>



</a-modal>
</template>