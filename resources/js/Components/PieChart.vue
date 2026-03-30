<!-- resources\js\Components\PieChart.vue -->
<script setup>
import { Pie } from 'vue-chartjs'
import {
  Chart as ChartJS,
  ArcElement,
  Tooltip,
  Legend
} from 'chart.js'

ChartJS.register(ArcElement, Tooltip, Legend)

const props = defineProps({
  labels: Array,
  data: Array
})

const chartData = {
  labels: props.labels,
  datasets: [
    {
      data: props.data,
      backgroundColor: [
        '#14b8a6',
        '#10b981',
        '#f59e0b',
        '#ef4444',
        '#6366f1',
        '#8b5cf6'
      ]
    }
  ]
}
</script>

<template>
  <Pie :data="chartData" />
</template>