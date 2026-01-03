<template>
    <v-card class="pa-6 text-center" elevation="2" rounded="xl">
      <v-card-title class="text-h5 mb-4">Resumen Diario</v-card-title>
  
      <v-progress-circular
        :model-value="calorieUsagePercentageForDate"
        :size="200"
        :width="15"
        :color="calorieColorForDate"
      >
        <div class="text-center">
          <span class="text-h4 font-weight-bold">{{
            Math.round(remainingCaloriesForDate)
          }}</span>
          <div class="text-caption text-uppercase">kcal restantes</div>
        </div>
      </v-progress-circular>
  
      <v-row class="mt-6">
        <v-col cols="6">
          <div class="text-subtitle-2 text-grey">Consumidas</div>
          <div class="text-h6">{{ Math.round(totalCaloriesForDate) }}</div>
        </v-col>
        <v-col cols="6">
          <div class="text-subtitle-2 text-grey">Objetivo</div>
          <div class="text-h6">{{ calorieGoalForDate }}</div>
        </v-col>
      </v-row>
  
      <v-divider class="my-6"></v-divider>
  
      <div class="text-left">
        <div class="d-flex justify-space-between mb-1">
          <span class="text-subtitle-2 font-weight-bold">Proteína</span>
          <span class="text-caption text-grey">
            {{ Math.round(totalProteinForDate) }}g /
            {{ Math.round(proteinGoalForDate) }}g
          </span>
        </div>
        <v-progress-linear
          :model-value="proteinUsagePercentageForDate"
          color="deep-purple"
          height="8"
          rounded
          class="mb-4"
        ></v-progress-linear>
  
        <div class="d-flex justify-space-between mb-1">
          <span class="text-subtitle-2 font-weight-bold">Carbohidratos</span>
          <span class="text-caption text-grey">
            {{ Math.round(totalCarbsForDate) }}g /
            {{ Math.round(carbsGoalForDate) }}g
          </span>
        </div>
        <v-progress-linear
          :model-value="carbsUsagePercentageForDate"
          color="orange-darken-1"
          height="8"
          rounded
          class="mb-4"
        ></v-progress-linear>
  
        <div class="d-flex justify-space-between mb-1">
          <span class="text-subtitle-2 font-weight-bold">Grasas</span>
          <span class="text-caption text-grey">
            {{ Math.round(totalFatForDate) }}g /
            {{ Math.round(fatGoalForDate) }}g
          </span>
        </div>
        <v-progress-linear
          :model-value="fatUsagePercentageForDate"
          color="cyan-darken-1"
          height="8"
          rounded
        ></v-progress-linear>
      </div>
    </v-card>
  </template>
  
  <script setup>
  import { useMealLogsStore } from '@/stores/useMealLogsStore';
  import { useMacrosStore } from '@/stores/useMacrosStore';
  import { useDateStore } from '@/stores/useDateStore';
  import { computed, watch, onMounted } from 'vue';
  
  const mealLogsStore = useMealLogsStore();
  const macrosStore = useMacrosStore();
  const dateStore = useDateStore();

  // Filtrar meal logs por fecha seleccionada para los cálculos
  const filteredMealLogsForDate = computed(() => {
    const selectedDateStr = dateStore.selectedDate instanceof Date
      ? dateStore.selectedDate.toISOString().split('T')[0]
      : dateStore.selectedDate;
    
    return mealLogsStore.mealLogs.filter(meal => {
      const mealDate = meal.logged_at ? meal.logged_at.split('T')[0] : null;
      return mealDate === selectedDateStr;
    });
  });

  // Recalcular totales basados en los meal logs filtrados
  // Los meal logs tienen ingredient o recipe con los valores nutricionales
  const totalCaloriesForDate = computed(() => {
    return filteredMealLogsForDate.value.reduce((acc, meal) => {
      const calories = meal.ingredient?.kcal || meal.recipe?.total_kcal || 0;
      const quantity = parseFloat(meal.quantity) || 0;
      const baseAmount = meal.ingredient?.amount || meal.recipe?.servings || 1;
      if (quantity === 0 || baseAmount === 0) return acc;
      const factor = quantity / baseAmount;
      return acc + (calories * factor);
    }, 0);
  });

  const totalProteinForDate = computed(() => {
    return filteredMealLogsForDate.value.reduce((acc, meal) => {
      const protein = parseFloat(meal.ingredient?.prot || meal.recipe?.total_prot || 0);
      const quantity = parseFloat(meal.quantity) || 0;
      const baseAmount = meal.ingredient?.amount || meal.recipe?.servings || 1;
      if (quantity === 0 || baseAmount === 0) return acc;
      const factor = quantity / baseAmount;
      return acc + (protein * factor);
    }, 0);
  });

  const totalCarbsForDate = computed(() => {
    return filteredMealLogsForDate.value.reduce((acc, meal) => {
      const carbs = parseFloat(meal.ingredient?.carb || meal.recipe?.total_carb || 0);
      const quantity = parseFloat(meal.quantity) || 0;
      const baseAmount = meal.ingredient?.amount || meal.recipe?.servings || 1;
      if (quantity === 0 || baseAmount === 0) return acc;
      const factor = quantity / baseAmount;
      return acc + (carbs * factor);
    }, 0);
  });

  const totalFatForDate = computed(() => {
    return filteredMealLogsForDate.value.reduce((acc, meal) => {
      const fat = parseFloat(meal.ingredient?.fat || meal.recipe?.total_fat || 0);
      const quantity = parseFloat(meal.quantity) || 0;
      const baseAmount = meal.ingredient?.amount || meal.recipe?.servings || 1;
      if (quantity === 0 || baseAmount === 0) return acc;
      const factor = quantity / baseAmount;
      return acc + (fat * factor);
    }, 0);
  });

  // Obtener el macro para la fecha seleccionada (busca el más cercano hacia atrás)
  const macroForDate = computed(() => {
    return macrosStore.getMacroForDate(dateStore.selectedDate);
  });

  // Goals basados en el macro encontrado para la fecha
  const calorieGoalForDate = computed(() => {
    return macroForDate.value?.kcal || 0;
  });

  const proteinGoalForDate = computed(() => {
    return parseFloat(macroForDate.value?.prot) || 0;
  });

  const carbsGoalForDate = computed(() => {
    return parseFloat(macroForDate.value?.carb) || 0;
  });

  const fatGoalForDate = computed(() => {
    return parseFloat(macroForDate.value?.fat) || 0;
  });

  const calorieUsagePercentageForDate = computed(() => {
    if (!calorieGoalForDate.value || calorieGoalForDate.value === 0) return 0;
    return (totalCaloriesForDate.value / calorieGoalForDate.value) * 100;
  });

  const remainingCaloriesForDate = computed(() => {
    return calorieGoalForDate.value - totalCaloriesForDate.value;
  });

  const calorieColorForDate = computed(() => {
    const pct = calorieUsagePercentageForDate.value;
    switch (true) {
      case pct < 60:
        return "orange";
      case pct >= 60 && pct < 80:
        return "amber";
      case pct >= 80 && pct < 90:
        return "green";
      case pct >= 90 && pct <= 100:
        return "deep-purple";
      default:
        return "red";
    }
  });

  const proteinUsagePercentageForDate = computed(() => {
    if (!proteinGoalForDate.value || proteinGoalForDate.value === 0) return 0;
    return (totalProteinForDate.value / proteinGoalForDate.value) * 100;
  });

  const carbsUsagePercentageForDate = computed(() => {
    if (!carbsGoalForDate.value || carbsGoalForDate.value === 0) return 0;
    return (totalCarbsForDate.value / carbsGoalForDate.value) * 100;
  });

  const fatUsagePercentageForDate = computed(() => {
    if (!fatGoalForDate.value || fatGoalForDate.value === 0) return 0;
    return (totalFatForDate.value / fatGoalForDate.value) * 100;
  });

  // Cargar macros al montar y cuando cambie la fecha
  const loadMacrosForDate = async () => {
    if (macrosStore.macros.length === 0) {
      await macrosStore.fetchMacros();
    }
  };

  watch(() => dateStore.selectedDate, () => {
    loadMacrosForDate();
  }, { immediate: true });

  onMounted(() => {
    loadMacrosForDate();
  });
  </script>
