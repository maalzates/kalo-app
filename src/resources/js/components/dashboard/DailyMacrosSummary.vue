<template>
    <v-card class="pa-6 text-center" elevation="2" rounded="xl">
      <v-card-title class="text-h5 mb-4">Resumen Diario</v-card-title>
  
      <v-progress-circular
        :model-value="mealLogsStore.calorieUsagePercentage"
        :size="200"
        :width="15"
        :color="mealLogsStore.calorieColor"
      >
        <div class="text-center">
          <span class="text-h4 font-weight-bold">{{
            mealLogsStore.remainingCalories
          }}</span>
          <div class="text-caption text-uppercase">kcal restantes</div>
        </div>
      </v-progress-circular>
  
      <v-row class="mt-6">
        <v-col cols="6">
          <div class="text-subtitle-2 text-grey">Consumidas</div>
          <div class="text-h6">{{ mealLogsStore.totalCalories }}</div>
        </v-col>
        <v-col cols="6">
          <div class="text-subtitle-2 text-grey">Objetivo</div>
          <div class="text-h6">{{ mealLogsStore.calorieGoal }}</div>
        </v-col>
      </v-row>
  
      <v-divider class="my-6"></v-divider>
  
      <div class="text-left">
        <div class="d-flex justify-space-between mb-1">
          <span class="text-subtitle-2 font-weight-bold">Proteína</span>
          <span class="text-caption text-grey">
            {{ Math.round(mealLogsStore.totalProtein) }}g /
            {{ mealLogsStore.proteinGoal }}g
          </span>
        </div>
        <v-progress-linear
          :model-value="mealLogsStore.proteinUsagePercentage"
          color="deep-purple"
          height="8"
          rounded
          class="mb-4"
        ></v-progress-linear>
  
        <div class="d-flex justify-space-between mb-1">
          <span class="text-subtitle-2 font-weight-bold">Carbohidratos</span>
          <span class="text-caption text-grey">
            {{ Math.round(mealLogsStore.totalCarbs) }}g /
            {{ mealLogsStore.carbsGoal }}g
          </span>
        </div>
        <v-progress-linear
          :model-value="mealLogsStore.carbsUsagePercentage"
          color="orange-darken-1"
          height="8"
          rounded
          class="mb-4"
        ></v-progress-linear>
  
        <div class="d-flex justify-space-between mb-1">
          <span class="text-subtitle-2 font-weight-bold">Grasas</span>
          <span class="text-caption text-grey">
            {{ Math.round(mealLogsStore.totalFat) }}g /
            {{ mealLogsStore.fatGoal }}g
          </span>
        </div>
        <v-progress-linear
          :model-value="mealLogsStore.fatUsagePercentage"
          color="cyan-darken-1"
          height="8"
          rounded
        ></v-progress-linear>
      </div>
    </v-card>
  </template>
  
  <script setup>
  import { useMealLogsStore } from '@/stores/useMealLogsStore';
  const mealLogsStore = useMealLogsStore();
  </script>
