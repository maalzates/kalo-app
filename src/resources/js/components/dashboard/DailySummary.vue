<template>
    <v-card class="pa-6 text-center" elevation="2" rounded="xl">
      <v-card-title class="text-h5 mb-4">Resumen Diario</v-card-title>
  
      <v-progress-circular
        :model-value="mealStore.calorieUsagePercentage"
        :size="200"
        :width="15"
        :color="mealStore.calorieColor"
      >
        <div class="text-center">
          <span class="text-h4 font-weight-bold">{{
            mealStore.remainingCalories
          }}</span>
          <div class="text-caption text-uppercase">kcal restantes</div>
        </div>
      </v-progress-circular>
  
      <v-row class="mt-6">
        <v-col cols="6">
          <div class="text-subtitle-2 text-grey">Consumidas</div>
          <div class="text-h6">{{ mealStore.totalCalories }}</div>
        </v-col>
        <v-col cols="6">
          <div class="text-subtitle-2 text-grey">Objetivo</div>
          <div class="text-h6">{{ mealStore.calorieGoal }}</div>
        </v-col>
      </v-row>
  
      <v-divider class="my-6"></v-divider>
  
      <div class="text-left">
        <div class="d-flex justify-space-between mb-1">
          <span class="text-subtitle-2 font-weight-bold">Proteína</span>
          <span class="text-caption text-grey">
            {{ Math.round(mealStore.totalProtein) }}g /
            {{ mealStore.proteinGoal }}g
          </span>
        </div>
        <v-progress-linear
          :model-value="mealStore.proteinUsagePercentage"
          color="deep-purple"
          height="8"
          rounded
          class="mb-4"
        ></v-progress-linear>
  
        <div class="d-flex justify-space-between mb-1">
          <span class="text-subtitle-2 font-weight-bold">Carbohidratos</span>
          <span class="text-caption text-grey">
            {{ Math.round(mealStore.totalCarbs) }}g /
            {{ mealStore.carbsGoal }}g
          </span>
        </div>
        <v-progress-linear
          :model-value="mealStore.carbsUsagePercentage"
          color="orange-darken-1"
          height="8"
          rounded
          class="mb-4"
        ></v-progress-linear>
  
        <div class="d-flex justify-space-between mb-1">
          <span class="text-subtitle-2 font-weight-bold">Grasas</span>
          <span class="text-caption text-grey">
            {{ Math.round(mealStore.totalFat) }}g /
            {{ mealStore.fatGoal }}g
          </span>
        </div>
        <v-progress-linear
          :model-value="mealStore.fatUsagePercentage"
          color="cyan-darken-1"
          height="8"
          rounded
        ></v-progress-linear>
      </div>
    </v-card>
  </template>
  
  <script setup>
  import { useMealStore } from "../../stores/useMealStore";
  const mealStore = useMealStore();
  </script>
