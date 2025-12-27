<template>
    <v-card elevation="2" rounded="xl" class="pa-4">
      <v-card-title class="d-flex align-center font-weight-bold px-2">
        <v-icon start color="deep-purple-accent-4">mdi-calculator</v-icon>
        Calculadora de Macros
      </v-card-title>
      
      <v-card-text class="mt-2">
        <v-row dense>
          <v-col cols="6" sm="3">
            <v-text-field v-model="localData.weight" label="Peso (kg)" type="number" variant="outlined" density="compact" rounded="lg" hide-details class="mb-4" />
          </v-col>
          <v-col cols="6" sm="3">
            <v-text-field v-model="localData.height" label="Altura (cm)" type="number" variant="outlined" density="compact" rounded="lg" hide-details class="mb-4" />
          </v-col>
          <v-col cols="6" sm="3">
            <v-text-field v-model="localData.age" label="Edad" type="number" variant="outlined" density="compact" rounded="lg" hide-details class="mb-4" />
          </v-col>
          <v-col cols="6" sm="3">
            <v-select v-model="localData.gender" :items="['Hombre', 'Mujer']" label="Sexo" variant="outlined" density="compact" rounded="lg" hide-details class="mb-4" />
          </v-col>
  
          <v-col cols="12" sm="6">
            <v-select 
              v-model="localData.activityLevel" 
              label="Nivel de Actividad" 
              :items="activityOptions" 
              variant="outlined" 
              density="compact" 
              rounded="lg" 
              hide-details 
              class="mb-4" 
            />
          </v-col>
          <v-col cols="12" sm="6">
            <v-select 
              v-model="localData.goal" 
              label="Objetivo" 
              :items="goalOptions" 
              variant="outlined" 
              density="compact" 
              rounded="lg" 
              hide-details 
              class="mb-4" 
            />
          </v-col>
        </v-row>
  
        <v-btn 
          block 
          color="deep-purple-accent-4" 
          rounded="pill" 
          size="large" 
          class="mt-2 font-weight-bold"
          prepend-icon="mdi-sync"
          @click="calculate"
        >
          Calcular Metas
        </v-btn>
      </v-card-text>
    </v-card>
  </template>
  
  <script setup>
  import { reactive } from 'vue';
  
  const props = defineProps(['initialData']);
  const emit = defineEmits(['calculated']);
  
  const localData = reactive({ ...props.initialData });
  
  const activityOptions = ['Sedentario', 'Ligero', 'Moderado', 'Muy Activo'];
  const goalOptions = ['Perder Peso', 'Mantener', 'Ganar Músculo'];
  
  const calculate = () => {

    const results = {
      calories: 2450,
      protein: 180,
      carbs: 250,
      fat: 70
    };
    emit('calculated', results);
  };
  </script>
