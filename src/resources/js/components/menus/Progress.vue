<template>
  <v-container fluid class="pa-4 pa-sm-6">
    <v-row align="center" class="mb-6">
      <v-col cols="12" sm="6">
        <h1 class="text-h4 font-weight-black">Mi Progreso</h1>
      </v-col>
      <v-col cols="12" sm="6" class="d-flex justify-sm-end">
        <v-btn-toggle v-model="periodo" color="primary" variant="outlined" mandatory density="comfortable">
          <v-btn value="7">7D</v-btn>
          <v-btn value="30">1M</v-btn>
          <v-btn value="90">3M</v-btn>
          <v-btn value="all">Todo</v-btn>
        </v-btn-toggle>
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
    </v-row>

    <v-row v-else>
      <v-col cols="12" md="6" v-for="i in 2" :key="i">
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

const bioStore = useBiometricsStore();
const macroStore = useMacrosStore();
const mealStore = useMealLogsStore();
const loading = ref(true);
const periodo = ref('30');

const filtrarPorDias = (lista, campoFecha, dias) => {
  if (!lista || dias === 'all') return lista;
  const fechaLimite = new Date();
  fechaLimite.setDate(fechaLimite.getDate() - parseInt(dias));
  fechaLimite.setHours(0, 0, 0, 0);
  return lista.filter(item => {
    const val = item[campoFecha];
    return val && new Date(val) >= fechaLimite;
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
