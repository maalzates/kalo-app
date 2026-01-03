<template>
  <v-card rounded="xl" border="sm" elevation="0" class="pa-4">
    <div class="d-flex align-center justify-space-between mb-4 px-2">
      <div class="text-subtitle-1 font-weight-bold">Historial de Objetivos</div>
      
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
    
    <v-list v-if="paginatedMacros.length > 0" lines="two" class="pa-0">
      <v-list-item
        v-for="(macro, index) in paginatedMacros"
        :key="macro.id"
        :class="(page === 1 && index === 0) ? 'bg-deep-purple-lighten-5 rounded-lg' : ''"
        class="mb-2"
      >
        <template v-slot:prepend>
          <v-avatar color="deep-purple-accent-4" size="small">
            <v-icon color="white" icon="mdi-history" size="18"></v-icon>
          </v-avatar>
        </template>

        <v-list-item-title class="font-weight-bold">
          {{ macro.kcal }} kcal
          <v-chip v-if="page === 1 && index === 0" size="x-small" color="deep-purple-accent-4" class="ml-2 font-weight-bold">
            Actual
          </v-chip>
        </v-list-item-title>
        
        <v-list-item-subtitle class="text-caption">
          P: {{ macro.prot }}g | C: {{ macro.carb }}g | G: {{ macro.fat }}g
        </v-list-item-subtitle>

        <template v-slot:append>
          <div class="text-caption text-grey">
            {{ new Date(macro.created_at).toLocaleDateString() }}
          </div>
        </template>
      </v-list-item>
    </v-list>

    <v-sheet v-else class="pa-10 text-center bg-grey-lighten-4 rounded-xl">
      <v-icon icon="mdi-database-off" color="grey" size="large" class="mb-2"></v-icon>
      <div class="text-grey text-body-2">No hay registros previos</div>
    </v-sheet>
  </v-card>
</template>

<script setup>
import { ref, computed } from 'vue';
import { useMacrosStore } from '@/stores/useMacrosStore';

const macrosStore = useMacrosStore();

// Configuración de paginación
const page = ref(1);
const itemsPerPage = 5; // Un número menor para que quepa bien en el lateral

// Calculamos el total de páginas
const totalPages = computed(() => {
  return Math.ceil(macrosStore.macros.length / itemsPerPage) || 1;
});

// Segmentamos los datos según la página actual
const paginatedMacros = computed(() => {
  const start = (page.value - 1) * itemsPerPage;
  const end = start + itemsPerPage;
  return macrosStore.macros.slice(start, end);
});

// Manejo explícito del cambio de página
const handlePageChange = (newPage) => {
  page.value = newPage;
};
</script>
