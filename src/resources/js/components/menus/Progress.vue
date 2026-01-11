<template>
  <v-container fluid class="pa-4 pa-sm-6">
    <v-row align="center" class="mb-6">
      <!-- Columna izquierda: Título -->
      <v-col cols="12" md="6">
        <h1 class="text-h4 font-weight-black">Mi Progreso</h1>
      </v-col>

      <!-- Columna derecha: Filtros + Adherencia apilados -->
      <v-col cols="12" md="6">
        <div class="d-flex flex-column ga-3">
          <!-- Filtros de fecha -->
          <div class="d-flex justify-start justify-md-end">
            <v-btn-toggle v-model="periodo" color="primary" variant="outlined" mandatory density="comfortable">
              <v-btn value="1">1D</v-btn>
              <v-btn value="7">1S</v-btn>
              <v-btn value="90">3M</v-btn>
              <v-btn value="365">1A</v-btn>
            </v-btn-toggle>
          </div>

          <!-- Adherencia -->
          <div class="d-flex justify-start justify-md-end">
            <AdherenceHeatmap
              v-if="!loading"
              :meal-logs="mealsFiltrados"
              :macro-goals="macroStore.macros"
              :key="`adherencia-${periodo}`"
            />
            <v-skeleton-loader v-else type="card" height="70" rounded="lg" />
          </div>
        </div>
      </v-col>
    </v-row>

    <v-row v-if="!loading">
      <v-col cols="12" md="6">
        <WeightChart :data="biometricsFiltrados" :key="`weight-${periodo}`" />
      </v-col>

      <v-col cols="12" md="6">
        <NutritionProgressChart
          :macro-goals="macroStore.macros"
          :meal-logs="mealsFiltrados"
          :key="`nutrition-${periodo}`"
        />
      </v-col>

      <v-col cols="12" md="6">
        <BodyCompositionChart :biometrics="biometricsFiltrados" :key="`composition-${periodo}`" />
      </v-col>
    </v-row>

    <v-row v-if="loading">
      <v-col cols="12" md="6" v-for="i in 3" :key="i">
        <v-skeleton-loader type="card" height="400" rounded="xl" />
      </v-col>
    </v-row>
  </v-container>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import { useBiometricsStore } from '@/stores/useBiometricsStore';
import { useMacrosStore } from '@/stores/useMacrosStore';
import { useMealLogsStore } from '@/stores/useMealLogsStore';

import WeightChart from '@/components/progress/WeightChart.vue';
import NutritionProgressChart from '@/components/progress/NutritionProgressChart.vue';
import AdherenceHeatmap from '@/components/progress/AdherenceHeatmap.vue';
import BodyCompositionChart from '@/components/progress/BodyCompositionChart.vue';

const bioStore = useBiometricsStore();
const macroStore = useMacrosStore();
const mealStore = useMealLogsStore();
const loading = ref(true);
const periodo = ref('7');

const filtrarPorDias = (lista, campoFecha, dias) => {
  if (!lista || dias === 'all') return lista;

  const hoy = new Date();
  hoy.setHours(23, 59, 59, 999); // Fin del día de hoy

  const diasNum = parseInt(dias);
  const fechaLimite = new Date();
  fechaLimite.setDate(fechaLimite.getDate() - (diasNum - 1)); // -1 porque incluimos hoy
  fechaLimite.setHours(0, 0, 0, 0); // Inicio del día límite

  return lista.filter(item => {
    const val = item[campoFecha];
    if (!val) return false;

    const itemDate = new Date(val);
    return itemDate >= fechaLimite && itemDate <= hoy;
  });
};

const biometricsFiltrados = computed(() => filtrarPorDias(bioStore.biometrics, 'measured_at', periodo.value));
const mealsFiltrados = computed(() => filtrarPorDias(mealStore.mealLogs, 'logged_at', periodo.value));

onMounted(async () => {
  loading.value = true;
  try {
    await Promise.all([
      bioStore.fetchBiometrics({ perPage: 100 }),
      macroStore.fetchMacros({ perPage: 50 }),
      mealStore.fetchMealLogs({ perPage: 500 }) 
    ]);
  } catch (e) {
    console.error("Error cargando datos:", e);
  } finally {
    loading.value = false;
  }
});
</script>
