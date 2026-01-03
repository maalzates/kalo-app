<template>
    <v-card rounded="xl" border="sm" elevation="0" class="pa-4">
      <div class="text-subtitle-1 font-weight-bold mb-4 ml-2">Historial de Objetivos</div>
      
      <v-list v-if="macrosStore.macros.length > 0" lines="two">
        <v-list-item
          v-for="(macro, index) in macrosStore.macros"
          :key="macro.id"
          :class="index === 0 ? 'bg-deep-purple-lighten-5 rounded-lg' : ''"
          class="mb-2"
        >
          <template v-slot:prepend>
            <v-avatar color="deep-purple-accent-4" size="small">
              <v-icon color="white" icon="mdi-history" size="18"></v-icon>
            </v-avatar>
          </template>
  
          <v-list-item-title class="font-weight-bold">
            {{ macro.kcal }} kcal
            <v-chip v-if="index === 0" size="x-small" color="deep-purple-accent-4" class="ml-2">Actual</v-chip>
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
  import { useMacrosStore } from '@/stores/useMacrosStore';
  const macrosStore = useMacrosStore();
  </script>
