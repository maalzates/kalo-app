<template>
  <v-card class="pa-6" rounded="xl" border elevation="0">
    <div class="d-flex flex-column flex-sm-row align-start align-sm-center justify-space-between mb-6">
      <div class="d-flex align-center mb-2 mb-sm-0">
        <v-icon icon="mdi-scale-bathroom" color="primary" class="mr-2"></v-icon>
        <span class="text-subtitle-1 font-weight-bold">Evolución de Peso</span>
      </div>
      
      <v-tabs
        v-model="viewMode"
        density="compact"
        color="primary"
        class="bg-grey-lighten-4 rounded-lg"
      >
        <v-tab value="clean" class="text-caption">Plano</v-tab>
        <v-tab value="composition" class="text-caption">Composición</v-tab>
        <v-tab value="trend" class="text-caption">Tendencia</v-tab>
      </v-tabs>
    </div>

    <div style="height: 300px;">
      <Line 
        v-if="props.data && props.data.length > 0" 
        :data="chartData" 
        :options="chartOptions" 
      />
      <v-sheet v-else class="d-flex align-center justify-center fill-height bg-grey-lighten-4 rounded-lg text-grey">
        Sin datos de peso
      </v-sheet>
    </div>
  </v-card>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Line } from 'vue-chartjs';
import { 
  Chart as ChartJS, Title, Tooltip, Legend, LineElement, 
  PointElement, CategoryScale, LinearScale, Filler 
} from 'chart.js';

ChartJS.register(Title, Tooltip, Legend, LineElement, PointElement, CategoryScale, LinearScale, Filler);

const props = defineProps({
  data: { type: Array, required: true }
});

const viewMode = ref('clean');

// Ordenar datos una sola vez
const sortedData = computed(() => {
  return [...props.data].sort((a, b) => new Date(a.measured_at) - new Date(b.measured_at));
});

const chartData = computed(() => {
  const labels = sortedData.value.map(i => new Date(i.measured_at).toLocaleDateString(undefined, { day: 'numeric', month: 'short' }));
  const weights = sortedData.value.map(i => parseFloat(i.weight));

  // OPCIÓN 1: CLEAN FOCUS (Minimalista con degradado)
  if (viewMode.value === 'clean') {
    return {
      labels,
      datasets: [{
        label: 'Peso (kg)',
        borderColor: '#6200EA',
        borderWidth: 3,
        backgroundColor: (context) => {
          const ctx = context.chart.ctx;
          const gradient = ctx.createLinearGradient(0, 0, 0, 300);
          gradient.addColorStop(0, 'rgba(98, 0, 234, 0.2)');
          gradient.addColorStop(1, 'rgba(98, 0, 234, 0)');
          return gradient;
        },
        data: weights,
        fill: true,
        tension: 0.5,
        pointRadius: 4,
        pointBackgroundColor: '#6200EA'
      }]
    };
  }

  // OPCIÓN 2: COMPOSICIÓN (Peso vs % Grasa con 2 ejes)
  if (viewMode.value === 'composition') {
    return {
      labels,
      datasets: [
        {
          label: 'Peso (kg)',
          borderColor: '#6200EA',
          data: weights,
          yAxisID: 'y',
          tension: 0.3
        },
        {
          label: '% Grasa',
          borderColor: '#00B8D4',
          borderDash: [5, 5],
          data: sortedData.value.map(i => parseFloat(i.fat_percentage)),
          yAxisID: 'y1',
          tension: 0.3
        }
      ]
    };
  }

  // OPCIÓN 3: TREND (Línea de peso + Promedio)
  const average = weights.reduce((a, b) => a + b, 0) / weights.length;
  return {
    labels,
    datasets: [
      {
        label: 'Peso Actual',
        borderColor: '#6200EA',
        data: weights,
        tension: 0.2
      },
      {
        label: 'Promedio Periodo',
        borderColor: 'rgba(0,0,0,0.2)',
        borderWidth: 2,
        borderDash: [10, 5],
        pointStyle: false,
        data: Array(weights.length).fill(average.toFixed(2))
      }
    ]
  };
});

const chartOptions = computed(() => {
  const baseOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
      legend: { display: viewMode.value !== 'clean' } // Ocultar leyenda en modo clean
    },
    scales: {
      x: { grid: { display: false } },
      y: { 
        position: 'left',
        beginAtZero: false,
        title: { display: true, text: 'kg' }
      }
    }
  };

  // Añadir segundo eje solo para composición
  if (viewMode.value === 'composition') {
    baseOptions.scales.y1 = {
      position: 'right',
      beginAtZero: false,
      grid: { drawOnChartArea: false },
      title: { display: true, text: '%' }
    };
  }

  return baseOptions;
});
</script>
