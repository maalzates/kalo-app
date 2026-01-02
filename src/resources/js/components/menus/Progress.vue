<template>
  <v-container fluid class="pa-6">
    <header class="mb-8">
      <h1 class="text-h4 font-weight-black">Mi Progreso</h1>
      <p class="text-body-2 text-grey">Visualización de métricas corporales y nutrición</p>
    </header>

    <v-row v-if="!loading">
      <v-col cols="12" md="6">
        <v-card class="pa-6" rounded="xl" border elevation="0">
          <div class="d-flex align-center mb-4">
            <v-icon icon="mdi-scale-bathroom" color="deep-purple-accent-4" class="mr-2"></v-icon>
            <span class="text-subtitle-1 font-weight-bold">Evolución de Peso (kg)</span>
          </div>
          <div style="height: 300px;">
            <Line v-if="biometrics.length > 0" :data="weightChartData" :options="chartOptions" />
            <v-sheet v-else class="d-flex align-center justify-center fill-height bg-grey-lighten-4 rounded-lg text-grey">
              Sin datos de peso registrados
            </v-sheet>
          </div>
        </v-card>
      </v-col>

      <v-col cols="12" md="6">
        <v-card class="pa-6" rounded="xl" border elevation="0">
          <div class="d-flex align-center mb-4">
            <v-icon icon="mdi-fire" color="orange-darken-2" class="mr-2"></v-icon>
            <span class="text-subtitle-1 font-weight-bold">Consumo Calórico (kcal)</span>
          </div>
          <div style="height: 300px;">
            <Bar v-if="macros.length > 0" :data="macroChartData" :options="chartOptions" />
            <v-sheet v-else class="d-flex align-center justify-center fill-height bg-grey-lighten-4 rounded-lg text-grey">
              Sin datos de calorías registrados
            </v-sheet>
          </div>
        </v-card>
      </v-col>
    </v-row>

    <v-row v-else>
      <v-col cols="12" md="6" v-for="i in 2" :key="i">
        <v-skeleton-loader type="card" height="380" rounded="xl" />
      </v-col>
    </v-row>
  </v-container>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import { Line, Bar } from 'vue-chartjs';
import { useBiometricsStore } from '@/stores/useBiometricsStore';
import { useMacrosStore } from '@/stores/useMacrosStore';
import { 
  Chart as ChartJS, Title, Tooltip, Legend, LineElement, BarElement, 
  CategoryScale, LinearScale, PointElement, Filler 
} from 'chart.js';

ChartJS.register(Title, Tooltip, Legend, LineElement, BarElement, CategoryScale, LinearScale, PointElement, Filler);

const bioStore = useBiometricsStore();
const macroStore = useMacrosStore();
const loading = ref(true);

// --- ACCESO A DATOS (YA SON ARRAYS) ---
const biometrics = computed(() => bioStore.biometrics);
const macros = computed(() => macroStore.macros);

// Configuración Gráfico de Peso
const weightChartData = computed(() => {
  // Ordenar de más antiguo a más reciente para el eje X
  const sorted = [...biometrics.value].sort((a, b) => new Date(a.measured_at) - new Date(b.measured_at));
  
  return {
    labels: sorted.map(i => new Date(i.measured_at).toLocaleDateString(undefined, { day: 'numeric', month: 'short' })),
    datasets: [{
      label: 'Peso (kg)',
      borderColor: '#6200EA',
      backgroundColor: 'rgba(98, 0, 234, 0.1)',
      data: sorted.map(i => parseFloat(i.weight)),
      fill: true,
      tension: 0.4,
      pointRadius: 4
    }]
  };
});

// Configuración Gráfico de Calorías
const macroChartData = computed(() => {
  // Ordenar de más antiguo a más reciente
  const sorted = [...macros.value].sort((a, b) => new Date(a.created_at) - new Date(b.created_at));
  
  return {
    labels: sorted.map(i => new Date(i.created_at).toLocaleDateString(undefined, { day: 'numeric', month: 'short' })),
    datasets: [{
      label: 'Calorías',
      backgroundColor: '#D1C4E9',
      data: sorted.map(i => i.kcal),
      borderRadius: 6
    }]
  };
});

const chartOptions = {
  responsive: true,
  maintainAspectRatio: false,
  scales: {
    y: { 
      beginAtZero: false, // El peso se aprecia mejor si no empieza en 0
      grid: { color: 'rgba(0,0,0,0.05)' } 
    },
    x: { grid: { display: false } }
  },
  plugins: { 
    legend: { display: false },
    tooltip: { padding: 12 }
  }
};

onMounted(async () => {
  loading.value = true;
  await Promise.all([
    bioStore.fetchBiometrics(),
    macroStore.fetchMacros()
  ]);
  loading.value = false;
});
</script>
