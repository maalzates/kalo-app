<template>
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
          v-for="meal in mealLogsStore.mealLogs"
          :key="meal.id"
          class="mb-2 border-b-sm"
        >
          <template v-slot:prepend>
            <v-avatar color="grey-lighten-4" size="48">
              <v-icon color="deep-purple">mdi-food-apple</v-icon>
            </v-avatar>
          </template>
  
          <v-list-item-title class="text-subtitle-1 font-weight-bold text-wrap">
            {{ meal.name }}
          </v-list-item-title>
  
          <v-list-item-subtitle class="text-caption">
            <span class="text-deep-purple font-weight-medium">
              {{ meal.calories }} kcal
            </span>
            <span class="text-grey ml-2">
              P: {{ meal.protein }}g • C: {{ meal.carbs }}g • G: {{ meal.fat }}g
            </span>
          </v-list-item-subtitle>
  
          <template v-slot:append>
            <v-btn
              icon="mdi-delete-outline"
              variant="text"
              color="red"
              @click="mealLogsStore.removeMealLog(meal.id)"
            ></v-btn>
          </template>
        </v-list-item>
      </v-list>
  
      <div
        v-if="mealLogsStore.mealLogs.length === 0"
        class="pa-8 text-center text-grey"
      >
        <v-icon size="48" class="mb-2">mdi-clipboard-text-outline</v-icon>
        <p>Aún no has registrado nada hoy</p>
      </div>
    </v-card>
    <CreateMealLog v-model="isCreateMealLogDialogOpen" />
  </template>
  
  <script setup>
  import { useMealLogsStore } from "@/stores/useMealLogsStore";
  import { useDateStore } from "@/stores/useDateStore";
  import { ref, onMounted } from 'vue';
  import CreateMealLog from '@/components/meals/CreateMealLog.vue';
  const mealLogsStore = useMealLogsStore();
  const dateStore = useDateStore();

  const isCreateMealLogDialogOpen = ref(false);

  const openCreateMealLogDialog = () => {
    isCreateMealLogDialogOpen.value = true;
  };

  onMounted(async () => {
   await mealLogsStore.fetchMealLogs();
  });
  </script>
