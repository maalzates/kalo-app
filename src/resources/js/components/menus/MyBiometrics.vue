<template>
  <div class="pa-4 ml-0 ml-md-6">
    <v-row>
      <v-col cols="12" lg="7">
        <div class="text-overline mb-2 ml-1 text-grey-darken-1 font-weight-bold">
          Registrar Medición
        </div>
        <AddBiometric />
      </v-col>

      <v-col cols="12" lg="5">
        <div class="text-overline mb-2 ml-1 text-grey-darken-1 font-weight-bold">
          Historial de Mediciones
        </div>
        <BiometricsList />
      </v-col>
    </v-row>
  </div>
</template>

<script setup>
import AddBiometric from '@/components/biometrics/AddBiometric.vue';
import BiometricsList from '@/components/biometrics/BiometricsList.vue';
import { onMounted } from 'vue';
import { useBiometricsStore } from '@/stores/useBiometricsStore';

const biometricsStore = useBiometricsStore();

onMounted(async () => {
  await biometricsStore.fetchBiometrics();
});
</script>
