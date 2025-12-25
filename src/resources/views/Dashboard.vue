<template>
  <v-row>
    <v-col cols="12" md="6">
      <DailySummary class="ml-0 ml-md-6"/>
    </v-col>

    <v-col cols="12" md="6">
      <TodayMeals />
    </v-col>
  </v-row>
</template>

<script setup>
import { onMounted } from "vue";
import { useMealStore } from "../js/stores/useMealStore";

import DailySummary from "../js/components/dashboard/DailySummary.vue";
import TodayMeals from "../js/components/dashboard/TodayMeals.vue";

const mealStore = useMealStore();

onMounted(() => {
  mealStore.fetchMeals();
});
</script>
