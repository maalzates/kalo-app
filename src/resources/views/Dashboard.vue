<template>
    <v-row class="ml-0 ml-md-6">
        <v-col cols="12">
            <DateSelector />
        </v-col>
        <v-col cols="12" md="6">
            <DailySummary />
        </v-col>

        <v-col cols="12" md="6">
            <TodayMeals />
        </v-col>
        <v-col cols="12">
            <v-btn
                icon="mdi-plus"
                color="deep-purple"
                variant="tonal"
                size="small"
                @click="openAddMealDialog"
                class="d-md-none"
                ></v-btn
            >
        </v-col>
        <AddMealDialog v-model="isAddMealDialogOpen" />
    </v-row>
</template>

<script setup>
import { onMounted } from "vue";
import { useMealStore } from "@/stores/useMealStore";

import DailySummary from "@/components/dashboard/DailySummary.vue";
import TodayMeals from "@/components/dashboard/TodayMeals.vue";
import DateSelector from "@/components/dashboard/DateSelector.vue";
import { ref } from "vue";
import AddMealDialog from "@/components/meals/AddMealDialog.vue";

const isAddMealDialogOpen = ref(false);

const openAddMealDialog = () => {
    isAddMealDialogOpen.value = true;
};

const mealStore = useMealStore();

onMounted(() => {
    mealStore.fetchMeals();
});
</script>
