<template>
    <v-row>
        <v-col cols="12" md="6">
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

                        <div class="text-caption text-uppercase">
                            kcal restantes
                        </div>
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
            </v-card>
        </v-col>
    </v-row>
</template>

<script setup>
import { onMounted } from "vue";
import { useMealStore } from "../js/stores/useMealStore";

const mealStore = useMealStore();

onMounted(() => {
    mealStore.fetchMeals();
});
</script>
