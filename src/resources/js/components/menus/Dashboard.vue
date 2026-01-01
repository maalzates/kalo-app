<template>
    <v-row class="ml-0 ml-md-6">
        <v-col cols="12">
            <DateSelector />
        </v-col>
        <v-col cols="12" md="6">
            <DailyMacrosSummary />
        </v-col>

        <v-col cols="12" md="6">
            <DailyMealLogs />
        </v-col>
        <MobileFloatingButton 
            icon="mdi-plus" 
            @click="openCreateMealLogDialog" 
        />
        <CreateMealLog v-model="isCreateMealLogDialogOpen" />
    </v-row>
</template>

<script setup>
import { ref } from "vue";
import DailyMacrosSummary from "@/components/dashboard/DailyMacrosSummary.vue";
import DailyMealLogs from "@/components/dashboard/DailyMealLogs.vue";
import DateSelector from "@/components/dashboard/DateSelector.vue";
import CreateMealLog from "@/components/meals/CreateMealLog.vue";
import MobileFloatingButton from "@/components/common/MobileFloatingButton.vue";

const isCreateMealLogDialogOpen = ref(false);

const openCreateMealLogDialog = () => {
    isCreateMealLogDialogOpen.value = true;
};
</script>
