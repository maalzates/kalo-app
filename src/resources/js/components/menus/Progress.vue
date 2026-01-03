<template>
  <v-container fluid class="pa-4 pa-sm-6">
    <v-row align="center" class="mb-6">
      <v-col cols="12" sm="6" class="text-center text-sm-left">
        <h1 class="text-h4 font-weight-black">Mi Progreso</h1>
      </v-col>
      
      <v-col cols="12" sm="6" class="d-flex justify-center justify-sm-end">
        <v-btn-toggle
          v-model="periodo"
          color="primary"
          variant="outlined"
          divided
          mandatory
          density="comfortable"
          class="bg-surface"
        >
          <v-btn value="7">7D</v-btn>
          <v-btn value="30">1M</v-btn>
          <v-btn value="90">3M</v-btn>
          <v-btn value="365">1A</v-btn>
          <v-btn value="all">Todo</v-btn>
        </v-btn-toggle>
      </v-col>
    </v-row>

    <v-row v-if="!loading">
      <v-col cols="12" md="6">
        <WeightChart 
          :data="biometricsFiltrados" 
          :key="`weight-${periodo}`" 
        />
      </v-col>
      
      <v-col cols="12" md="6">
        <MacrosChart 
          :data="macrosFiltrados" 
          :key="`macros-${periodo}`" 
        />
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
import { useBiometricsStore } from '@/stores/useBiometricsStore';
import { useMacrosStore } from '@/stores/useMacrosStore';
import WeightChart from '@/components/progress/WeightChart.vue';
import MacrosChart from '@/components/progress/MacrosChart.vue';

const bioStore = useBiometricsStore();
const macroStore = useMacrosStore();
const loading = ref(true);

const periodo = ref('30');

const filtrarPorDias = (lista, campoFecha, dias) => {
  if (dias === 'all') return lista;
  const hoy = new Date();
  hoy.setHours(23, 59, 59, 999);
  const fechaLimite = new Date();
  fechaLimite.setDate(hoy.getDate() - parseInt(dias));
  fechaLimite.setHours(0, 0, 0, 0);
  
  return lista.filter(item => {
    const fechaItem = new Date(item[campoFecha]);
    return fechaItem >= fechaLimite;
  });
};

const biometricsFiltrados = computed(() => filtrarPorDias(bioStore.biometrics, 'measured_at', periodo.value));
const macrosFiltrados = computed(() => filtrarPorDias(macroStore.macros, 'created_at', periodo.value));

onMounted(async () => {
  loading.value = true;
  await Promise.all([
    bioStore.fetchBiometrics({ perPage: 200 }),
    macroStore.fetchMacros({ perPage: 200 })
  ]);
  loading.value = false;
});
</script>
