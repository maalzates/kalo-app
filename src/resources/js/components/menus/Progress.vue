<template>
    <v-container fluid class="pa-0">
      <header class="d-flex align-center mb-8">
        <div>
          <h1 class="text-h4 font-weight-black">Progreso</h1>
          <span class="text-body-2 text-grey">Análisis de tus métricas personales</span>
        </div>
        <v-spacer />
        <v-btn-toggle v-model="days" color="deep-purple-accent-4" variant="flat" mandatory rounded="xl" @update:model-value="loadAll">
          <v-btn value="7">7D</v-btn>
          <v-btn value="30">1M</v-btn>
          <v-btn value="365">1Y</v-btn>
        </v-btn-toggle>
      </header>
  
      <v-row v-if="!loading">
        <v-col cols="12" md="6">
          <WeightChart :data="filteredWeights" />
        </v-col>
        <v-col cols="12" md="6">
          <MacrosChart :data="filteredMacros" />
        </v-col>
      </v-row>
  
      <v-row v-else>
        <v-col v-for="i in 2" :key="i" cols="12" md="6">
          <v-skeleton-loader type="card" height="400" rounded="xl" />
        </v-col>
      </v-row>
    </v-container>
  </template>
  
  <script setup>
  import { ref, computed, onMounted } from 'vue';
  import { useMacrosStore } from '@/stores/useMacrosStore';
  import { useBiometricsStore } from '@/stores/useBiometricsStore';
  import WeightChart from '@/components/progress/WeightChart.vue';
  import MacrosChart from '@/components/progress/MacrosChart.vue';
  
  const days = ref('7');
  const loading = ref(true);
  const macroStore = useMacrosStore();
  const bioStore = useBiometricsStore();
  
  // Filtros directos y legibles
  const filteredWeights = computed(() => [...bioStore.biometrics].reverse());
  const filteredMacros = computed(() => [...macroStore.macros].slice(0, 15).reverse());
  
  const loadAll = async () => {
    loading.value = true;
    await Promise.all([
      macroStore.fetchMacros({ perPage: 100 }),
      bioStore.fetchBiometrics({ perPage: 100 })
    ]);
    loading.value = false;
  };
  
  onMounted(loadAll);
  </script>
