<template>
  <v-card class="pa-6" rounded="xl" border elevation="0">
    <div class="d-flex align-center mb-6">
      <v-icon icon="mdi-human" color="primary" class="mr-2"></v-icon>
      <span class="text-subtitle-1 font-weight-bold">Composición Corporal</span>
    </div>

    <div style="height: 300px;">
      <Line
        v-if="chartData"
        :data="chartData"
        :options="chartOptions"
      />
      <v-sheet
        v-else
        class="d-flex align-center justify-center fill-height bg-grey-lighten-4 rounded-lg"
      >
        <div class="text-center text-grey">
          <v-icon size="48" color="grey-lighten-1">mdi-chart-line-variant</v-icon>
          <p class="text-caption mt-2">Necesitas registrar peso y % de grasa</p>
        </div>
      </v-sheet>
    </div>

    <!-- Stats -->
    <div v-if="compositionData.length > 0" class="mt-4 d-flex justify-space-around">
      <div class="text-center">
        <div class="text-caption text-grey">Última Grasa</div>
        <div class="text-h6 font-weight-bold" style="color: #FF8A65;">
          {{ lastFatMass }} kg
        </div>
      </div>
      <div class="text-center">
        <div class="text-caption text-grey">Última Magra</div>
        <div class="text-h6 font-weight-bold" style="color: #64B5F6;">
          {{ lastLeanMass }} kg
        </div>
      </div>
      <div class="text-center">
        <div class="text-caption text-grey">Cambio Neto</div>
        <div class="text-h6 font-weight-bold" :style="{ color: netChange >= 0 ? '#66BB6A' : '#EF5350' }">
          {{ netChange > 0 ? '+' : '' }}{{ netChange }} kg
        </div>
      </div>
    </div>
  </v-card>
</template>

<script setup>
import { computed } from 'vue';
import { Line } from 'vue-chartjs';
import { useStatsCalculator } from '@/composables/useStatsCalculator';
import {
  Chart as ChartJS,
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
  Title,
  Tooltip,
  Legend,
  Filler
} from 'chart.js';

ChartJS.register(
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
  Title,
  Tooltip,
  Legend,
  Filler
);

const props = defineProps({
  biometrics: { type: Array, default: () => [] }
});

const { calculateBodyComposition } = useStatsCalculator();

const compositionData = computed(() => calculateBodyComposition(props.biometrics));

const chartData = computed(() => {
  if (compositionData.value.length === 0) return null;

  const labels = compositionData.value.map(d => {
    // Extraer solo la parte de fecha (YYYY-MM-DD) en caso de que venga con timestamp
    const dateOnly = d.date.split('T')[0];
    const [year, month, day] = dateOnly.split('-');
    return new Date(year, month - 1, day).toLocaleDateString(undefined, {
      day: 'numeric',
      month: 'short'
    });
  });

  return {
    labels,
    datasets: [
      {
        label: 'Masa Grasa (kg)',
        data: compositionData.value.map(d => d.fatMass),
        borderColor: '#FF8A65',
        backgroundColor: 'rgba(255, 138, 101, 0.3)',
        fill: true,
        tension: 0.4,
        pointRadius: 4,
        pointBackgroundColor: '#FF8A65'
      },
      {
        label: 'Masa Magra (kg)',
        data: compositionData.value.map(d => d.leanMass),
        borderColor: '#64B5F6',
        backgroundColor: 'rgba(100, 181, 246, 0.3)',
        fill: true,
        tension: 0.4,
        pointRadius: 4,
        pointBackgroundColor: '#64B5F6'
      }
    ]
  };
});

const chartOptions = {
  responsive: true,
  maintainAspectRatio: false,
  interaction: {
    mode: 'index',
    intersect: false
  },
  plugins: {
    legend: {
      position: 'bottom',
      labels: {
        usePointStyle: true,
        padding: 15
      }
    },
    tooltip: {
      callbacks: {
        label: (context) => {
          return `${context.dataset.label}: ${context.parsed.y.toFixed(2)} kg`;
        }
      }
    }
  },
  scales: {
    x: {
      grid: { display: false }
    },
    y: {
      beginAtZero: false,
      title: {
        display: true,
        text: 'kg'
      }
    }
  }
};

const lastFatMass = computed(() => {
  if (compositionData.value.length === 0) return 0;
  return compositionData.value[compositionData.value.length - 1].fatMass;
});

const lastLeanMass = computed(() => {
  if (compositionData.value.length === 0) return 0;
  return compositionData.value[compositionData.value.length - 1].leanMass;
});

const netChange = computed(() => {
  if (compositionData.value.length < 2) return 0;
  const first = compositionData.value[0];
  const last = compositionData.value[compositionData.value.length - 1];
  const leanChange = last.leanMass - first.leanMass;
  const fatChange = last.fatMass - first.fatMass;
  return parseFloat((leanChange - fatChange).toFixed(2));
});
</script>
