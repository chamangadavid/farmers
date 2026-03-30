<!-- resources\js\Components\BarChart.vue -->
<script setup>
import { Bar } from 'vue-chartjs'
import {
  Chart as ChartJS,
  BarElement,
  CategoryScale,
  LinearScale,
  Tooltip,
  Legend
} from 'chart.js'

ChartJS.register(BarElement, CategoryScale, LinearScale, Tooltip, Legend)

const props = defineProps({
  labels: Array,
  data: Array
})

const chartData = {
  labels: props.labels,
  datasets: [
    {
      label: 'Accidents by Status',
      data: props.data,
      backgroundColor: '#14b8a6'
    }
  ]
}
</script>

<template>
  <Bar :data="chartData" />
</template>