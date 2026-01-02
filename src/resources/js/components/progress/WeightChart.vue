<template>
    <v-card rounded="xl" border elevation="0" class="pa-6 fill-height">
      <div class="d-flex align-center mb-6">
        <v-avatar color="deep-purple-lighten-5" rounded="lg" class="mr-3">
          <v-icon color="deep-purple-accent-4">mdi-weight-kilogram</v-icon>
        </v-avatar>
        <div class="text-subtitle-1 font-weight-bold">Evolución del Peso</div>
      </div>
      
      <div style="height: 300px;">
        <Line :data="chartData" :options="chartOptions" />
      </div>
    </v-card>
  </template>
  
  <script setup>
  import { computed } from 'vue';
  import { Line } from 'vue-chartjs';
  
  const props = defineProps({
    data: { type: Array, required: true }
  });
  
  const chartData = computed(() => ({
    labels: props.data.map(b => new Date(b.measured_at).toLocaleDateString(undefined, { day: 'numeric', month: 'short' })),
    datasets: [{
      data: props.data.map(b => b.weight),
      borderColor: '#6200EA',
      backgroundColor: 'rgba(98, 0, 234, 0.1)',
      fill: true,
      tension: 0.4
    }]
  }));
  
  const chartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: { legend: { display: false } },
    scales: {
      x: { grid: { display: false } },
      y: { grid: { borderDash: [5, 5] } }
    }
  };
  </script>
