<template>
    <div>
    <v-card elevation="2" rounded="xl" class="pa-4">
      <v-card-title class="d-flex justify-space-between align-center px-4">
        <span class="text-h5 font-weight-bold text-wrap">Comidas al {{ dateStore.dayAndMonth }} </span>
        <v-btn
          icon="mdi-plus"
          color="deep-purple"
          variant="tonal"
          size="small"
          @click="openCreateMealLogDialog"
        ></v-btn>
      </v-card-title>
  
      <v-list lines="two" class="bg-transparent">
        <v-list-item
          v-for="meal in filteredMealLogs"
          :key="meal.id"
          class="mb-2 border-b-sm"
        >
          <template v-slot:prepend>
            <v-avatar color="grey-lighten-4" size="48">
              <v-icon color="deep-purple">mdi-food-apple</v-icon>
            </v-avatar>
          </template>
  
          <v-list-item-title class="text-subtitle-1 font-weight-bold text-wrap d-flex align-center ga-2">
            <span>{{ meal.ingredient?.name || meal.recipe?.name || 'Sin nombre' }}</span>
            <v-chip 
              size="x-small" 
              :color="meal.ingredient_id ? 'blue' : 'orange'"
              variant="tonal"
              class="font-weight-bold"
            >
              {{ meal.ingredient_id ? 'Alimento' : 'Receta' }}
            </v-chip>
          </v-list-item-title>
  
          <v-list-item-subtitle class="text-caption">
            <span class="text-deep-purple font-weight-medium">
              {{ calculateCalories(meal) }} kcal
            </span>
            <span class="text-grey ml-2">
              P: {{ calculateProtein(meal) }}g • C: {{ calculateCarbs(meal) }}g • G: {{ calculateFat(meal) }}g
            </span>
            <span class="text-grey-darken-1 ml-2 text-caption">
              ({{ meal.quantity }}{{ meal.unit }})
            </span>
          </v-list-item-subtitle>
  
          <template v-slot:append>
            <v-btn
              icon="mdi-delete-outline"
              variant="text"
              color="red"
              @click="handleDeleteMeal(meal.id)"
            ></v-btn>
          </template>
        </v-list-item>
      </v-list>
  
      <div
        v-if="filteredMealLogs.length === 0 && !mealLogsStore.loading"
        class="pa-8 text-center text-grey"
      >
        <v-icon size="48" class="mb-2">mdi-clipboard-text-outline</v-icon>
        <p>Aún no has registrado nada hoy</p>
      </div>
    </v-card>
    <CreateMealLog v-model="isCreateMealLogDialogOpen" />
    </div>
  </template>
  
  <script setup>
  import { useMealLogsStore } from "@/stores/useMealLogsStore";
  import { useDateStore } from "@/stores/useDateStore";
  import { ref, computed, watch, onMounted } from 'vue';
  import CreateMealLog from '@/components/meals/CreateMealLog.vue';
  
  const mealLogsStore = useMealLogsStore();
  const dateStore = useDateStore();
  const isCreateMealLogDialogOpen = ref(false);

  const openCreateMealLogDialog = () => {
    isCreateMealLogDialogOpen.value = true;
  };

  const formattedDateForAPI = computed(() => {
    const d = dateStore.selectedDate;
    if (d instanceof Date) {
      return d.toISOString().split('T')[0];
    }
    return d;
  });

  const fetchMealLogsForDate = async () => {
    await mealLogsStore.fetchMealLogs({
      date_from: formattedDateForAPI.value,
      date_to: formattedDateForAPI.value
    });
  };

  // Filtrar meal logs por fecha seleccionada
  const filteredMealLogs = computed(() => {
    const selectedDateStr = formattedDateForAPI.value;
    return mealLogsStore.mealLogs.filter(meal => {
      const mealDate = meal.logged_at ? meal.logged_at.split('T')[0] : null;
      return mealDate === selectedDateStr;
    });
  });

  // Calcular calorías basadas en la cantidad consumida
  const calculateCalories = (meal) => {
    const quantity = parseFloat(meal.quantity) || 0;
    if (quantity === 0) return 0;

    if (meal.ingredient) {
      // Para ingredientes: calcular basado en amount base
      const baseAmount = parseFloat(meal.ingredient.amount) || 100;
      const baseKcal = meal.ingredient.kcal || 0;
      const factor = quantity / baseAmount;
      return Math.round(baseKcal * factor);
    } else if (meal.recipe) {
      // Para recetas: calcular basado en servings
      const servings = parseFloat(meal.recipe.servings) || 1;
      const totalKcal = meal.recipe.total_kcal || 0;
      const factor = quantity / servings;
      return Math.round(totalKcal * factor);
    }
    return 0;
  };

  // Calcular proteína basada en la cantidad consumida
  const calculateProtein = (meal) => {
    const quantity = parseFloat(meal.quantity) || 0;
    if (quantity === 0) return 0;

    if (meal.ingredient) {
      const baseAmount = parseFloat(meal.ingredient.amount) || 100;
      const baseProt = parseFloat(meal.ingredient.prot) || 0;
      const factor = quantity / baseAmount;
      return (baseProt * factor).toFixed(1);
    } else if (meal.recipe) {
      const servings = parseFloat(meal.recipe.servings) || 1;
      const totalProt = parseFloat(meal.recipe.total_prot) || 0;
      const factor = quantity / servings;
      return (totalProt * factor).toFixed(1);
    }
    return 0;
  };

  // Calcular carbohidratos basados en la cantidad consumida
  const calculateCarbs = (meal) => {
    const quantity = parseFloat(meal.quantity) || 0;
    if (quantity === 0) return 0;

    if (meal.ingredient) {
      const baseAmount = parseFloat(meal.ingredient.amount) || 100;
      const baseCarb = parseFloat(meal.ingredient.carb) || 0;
      const factor = quantity / baseAmount;
      return (baseCarb * factor).toFixed(1);
    } else if (meal.recipe) {
      const servings = parseFloat(meal.recipe.servings) || 1;
      const totalCarb = parseFloat(meal.recipe.total_carb) || 0;
      const factor = quantity / servings;
      return (totalCarb * factor).toFixed(1);
    }
    return 0;
  };

  // Calcular grasas basadas en la cantidad consumida
  const calculateFat = (meal) => {
    const quantity = parseFloat(meal.quantity) || 0;
    if (quantity === 0) return 0;

    if (meal.ingredient) {
      const baseAmount = parseFloat(meal.ingredient.amount) || 100;
      const baseFat = parseFloat(meal.ingredient.fat) || 0;
      const factor = quantity / baseAmount;
      return (baseFat * factor).toFixed(1);
    } else if (meal.recipe) {
      const servings = parseFloat(meal.recipe.servings) || 1;
      const totalFat = parseFloat(meal.recipe.total_fat) || 0;
      const factor = quantity / servings;
      return (totalFat * factor).toFixed(1);
    }
    return 0;
  };

  const handleDeleteMeal = async (id) => {
    try {
      await mealLogsStore.removeMealLog(id);
      // Recargar meal logs después de eliminar
      await fetchMealLogsForDate();
    } catch (error) {
      console.error('Error deleting meal log:', error);
    }
  };

  watch(() => dateStore.selectedDate, () => {
    fetchMealLogsForDate();
  }, { immediate: true });

  onMounted(() => {
    fetchMealLogsForDate();
  });
  </script>
