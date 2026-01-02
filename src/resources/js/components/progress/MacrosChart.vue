<template>
    <v-card rounded="xl" border elevation="0" class="pa-6 fill-height">
      <div class="d-flex align-center mb-6">
        <v-avatar color="orange-lighten-5" rounded="lg" class="mr-3">
          <v-icon color="orange-darken-2">mdi-fire</v-icon>
        </v-avatar>
        <div class="text-subtitle-1 font-weight-bold">Historial de Metas</div>
      </div>
  
      <div style="height: 300px;">
        <Bar :data="chartData" :options="chartOptions" />
      </div>
    </v-card>
  </template>
  
  <script setup>
  import { computed } from 'vue';
  import { Bar } from 'vue-chartjs';
  
  const props = defineProps({
    data: { type: Array, required: true }
  });
  
  const chartData = computed(() => ({
    labels: props.data.map(m => new Date(m.created_at).toLocaleDateString(undefined, { day: 'numeric', month: 'short' })),
    datasets: [{
      data: props.data.map(m => m.kcal),
      backgroundColor: '#D1C4E9',
      borderRadius: 6
    }]
  }));
  
  const chartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: { legend: { display: false } },
    scales: {
      x: { grid: { display: false } },
      y: { grid: { display: false } }
    }
  };
  </script>
