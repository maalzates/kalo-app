<template>
  <v-card variant="flat" class="mb-4 bg-transparent">
    <v-card-text class="d-flex align-center justify-center">
      <v-btn icon="mdi-chevron-left" variant="text" @click="changeDay(-1)"></v-btn>

      <v-menu :close-on-content-click="false">
        <template v-slot:activator="{ props }">
          <v-btn v-bind="props" variant="tonal" class="mx-2 px-4" color="deep-purple-accent-4">
            <v-icon start>mdi-calendar</v-icon>
            {{ formattedDate }}
          </v-btn>
        </template>
        
        <v-date-picker 
          v-model="selectedDate" 
          color="deep-purple-accent-4"
          @update:model-value="onDateSelected"
        ></v-date-picker>
      </v-menu>

      <v-btn icon="mdi-chevron-right" variant="text" @click="changeDay(1)"></v-btn>
    </v-card-text>
  </v-card>
</template>

<script setup>
import { computed } from 'vue';
import { storeToRefs } from 'pinia'; // Importante para mantener la reactividad
import { useDateStore } from '@/stores/useDateStore';

const dateStore = useDateStore();

// storeToRefs extrae selectedDate de la store manteniendo su poder reactivo
// Así puedes usarlo en v-model sin funciones raras
const { selectedDate } = storeToRefs(dateStore);

const formattedDate = computed(() => {
  return selectedDate.value.toLocaleDateString('es-ES', {
    day: 'numeric',
    month: 'short',
    year: 'numeric'
  });
});

const changeDay = (offset) => {
  const newDate = new Date(selectedDate.value);
  newDate.setDate(newDate.getDate() + offset);
  dateStore.setSelectedDate(newDate);
};

const onDateSelected = (newDate) => {
  dateStore.setSelectedDate(newDate);
};
</script>
