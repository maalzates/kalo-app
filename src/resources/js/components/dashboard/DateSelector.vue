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
            v-model="store.selectedDate" 
            @update:model-value="onDateSelected"
            color="deep-purple-accent-4"
          ></v-date-picker>
        </v-menu>
  
        <v-btn icon="mdi-chevron-right" variant="text" @click="changeDay(1)"></v-btn>
      </v-card-text>
    </v-card>
  </template>
  
  <script setup>
  import { computed } from 'vue';
  import { useMealStore } from '@/stores/useMealStore';
  
  const store = useMealStore();
  
  const formattedDate = computed(() => {
    return store.selectedDate.toLocaleDateString('es-ES', {
      day: 'numeric',
      month: 'short',
      year: 'numeric'
    });
  });
  
  const changeDay = (offset) => {
    const newDate = new Date(store.selectedDate);
    newDate.setDate(newDate.getDate() + offset);
    store.selectDate(newDate);
  };
  
  const onDateSelected = (newDate) => {
    store.selectDate(newDate);
  };
  </script>
