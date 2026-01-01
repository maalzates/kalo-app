<template>
  <v-card variant="flat" class="bg-transparent">
    <v-card-text class="d-flex align-center justify-center pb-0 pt-0">
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
          :max="maxDate"
          :min="minDate"
          @update:model-value="onDateSelected"
        ></v-date-picker>
      </v-menu>

      <v-btn icon="mdi-chevron-right" variant="text" @click="changeDay(1)"></v-btn>
    </v-card-text>
  </v-card>
</template>

<script setup>
import { computed, ref, watch, onMounted } from 'vue';
import { storeToRefs } from 'pinia';
import { useDateStore } from '@/stores/useDateStore';
import { useMacrosStore } from '@/stores/useMacrosStore';

const dateStore = useDateStore();
const macrosStore = useMacrosStore();

// storeToRefs extrae selectedDate de la store manteniendo su poder reactivo
const { selectedDate } = storeToRefs(dateStore);

// Fecha máxima: hoy
const maxDate = computed(() => {
  const today = new Date();
  return today.toISOString().split('T')[0];
});

// Fecha mínima: fecha del macro más antiguo
const minDate = computed(() => {
  const oldestMacroDate = macrosStore.getOldestMacroDate();
  if (!oldestMacroDate) {
    // Si no hay macros, permitir cualquier fecha pasada
    return null;
  }
  return oldestMacroDate.toISOString().split('T')[0];
});

const formattedDate = computed(() => {
  return selectedDate.value.toLocaleDateString('es-ES', {
    day: 'numeric',
    month: 'short',
    year: 'numeric'
  });
});

// Validar que la fecha esté dentro de los límites
const validateDate = (date) => {
  const dateToCheck = date instanceof Date ? date : new Date(date);
  const today = new Date();
  today.setHours(23, 59, 59, 999); // Fin del día de hoy
  
  // No permitir fechas futuras
  if (dateToCheck > today) {
    return today;
  }
  
  // No permitir fechas anteriores al macro más antiguo
  const oldestMacroDate = macrosStore.getOldestMacroDate();
  if (oldestMacroDate && dateToCheck < oldestMacroDate) {
    return oldestMacroDate;
  }
  
  return dateToCheck;
};

const changeDay = (offset) => {
  const newDate = new Date(selectedDate.value);
  newDate.setDate(newDate.getDate() + offset);
  const validatedDate = validateDate(newDate);
  dateStore.setSelectedDate(validatedDate);
};

const onDateSelected = (newDate) => {
  if (newDate) {
    const validatedDate = validateDate(newDate);
    dateStore.setSelectedDate(validatedDate);
  }
};

// Cargar macros al montar para obtener la fecha mínima
onMounted(async () => {
  if (macrosStore.macros.length === 0) {
    await macrosStore.fetchMacros();
  }
  // Validar la fecha actual después de cargar los macros
  const validatedDate = validateDate(selectedDate.value);
  if (validatedDate.getTime() !== selectedDate.value.getTime()) {
    dateStore.setSelectedDate(validatedDate);
  }
});
</script>
