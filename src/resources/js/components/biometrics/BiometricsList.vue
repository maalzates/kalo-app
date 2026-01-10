<template>
  <v-card rounded="xl" border="sm" elevation="0" class="pa-4">
    <div class="d-flex align-center justify-space-between mb-4 px-2">
      <div class="text-subtitle-1 font-weight-bold">Historial de Mediciones</div>

      <v-pagination
        :model-value="page"
        :length="totalPages"
        :disabled="totalPages <= 1"
        total-visible="3"
        density="compact"
        active-color="deep-purple-accent-4"
        size="x-small"
        variant="flat"
        @update:model-value="handlePageChange"
      ></v-pagination>
    </div>

    <v-list v-if="paginatedBiometrics.length > 0" lines="three" class="pa-0">
      <v-list-item
        v-for="(biometric, index) in paginatedBiometrics"
        :key="biometric.id"
        :class="(page === 1 && index === 0) ? 'bg-deep-purple-lighten-5 rounded-lg' : ''"
        class="mb-2"
      >
        <template v-slot:prepend>
          <v-avatar color="deep-purple-accent-4" size="small">
            <v-icon color="white" icon="mdi-scale-bathroom" size="18"></v-icon>
          </v-avatar>
        </template>

        <v-list-item-title class="font-weight-bold">
          {{ biometric.weight }} kg
          <v-chip v-if="page === 1 && index === 0" size="x-small" color="deep-purple-accent-4" class="ml-2 font-weight-bold">
            Reciente
          </v-chip>
        </v-list-item-title>

        <v-list-item-subtitle class="text-caption">
          <div v-if="biometric.fat_percentage">
            Grasa: {{ biometric.fat_percentage }}%
          </div>
          <div v-if="biometric.clean_mass">
            Masa Magra: {{ biometric.clean_mass }} kg
          </div>
          <div v-if="biometric.waist_circumference">
            Cintura: {{ biometric.waist_circumference }} cm
          </div>
        </v-list-item-subtitle>

        <template v-slot:append>
          <div class="text-caption text-grey">
            {{ formatDate(biometric.measured_at) }}
          </div>
        </template>
      </v-list-item>
    </v-list>

    <v-sheet v-else class="pa-10 text-center bg-grey-lighten-4 rounded-xl">
      <v-icon icon="mdi-database-off" color="grey" size="large" class="mb-2"></v-icon>
      <div class="text-grey text-body-2">No hay mediciones registradas</div>
    </v-sheet>
  </v-card>
</template>

<script setup>
import { ref, computed } from 'vue';
import { useBiometricsStore } from '@/stores/useBiometricsStore';

const biometricsStore = useBiometricsStore();

// Configuración de paginación
const page = ref(1);
const itemsPerPage = 5;

// Calculamos el total de páginas
const totalPages = computed(() => {
  return Math.ceil(biometricsStore.biometrics.length / itemsPerPage) || 1;
});

// Segmentamos los datos según la página actual
const paginatedBiometrics = computed(() => {
  const start = (page.value - 1) * itemsPerPage;
  const end = start + itemsPerPage;
  return biometricsStore.biometrics.slice(start, end);
});

// Manejo explícito del cambio de página
const handlePageChange = (newPage) => {
  page.value = newPage;
};

// Formatea la fecha para mostrarla
const formatDate = (dateString) => {
  if (!dateString) return '';
  const date = new Date(dateString);
  return date.toLocaleDateString('es-ES', {
    year: 'numeric',
    month: 'short',
    day: 'numeric'
  });
};
</script>
