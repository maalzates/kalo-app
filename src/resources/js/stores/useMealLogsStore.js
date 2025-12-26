import { defineStore } from "pinia";
import { ref, computed } from "vue";
import mealLogsRepository from "../repositories/mealLogsRepository";
import { userSettings } from "../data/userStats";

export const useMealLogsStore = defineStore("mealLogsStore", () => {
    const mealLogs = ref([]);
    const calorieGoal = ref(userSettings.dailyCalorieGoal);

    // 1. Metas de macros (vienen de userSettings)
    const proteinGoal = ref(userSettings.dailyProteinGoal);
    const carbsGoal = ref(userSettings.dailyCarbsGoal);
    const fatGoal = ref(userSettings.dailyFatGoal);

    // 2. Totales consumidos
    const totalProtein = computed(
        () => mealLogs.value?.reduce((acc, m) => acc + m.protein, 0) || 0
    );
    const totalCarbs = computed(
        () => mealLogs.value?.reduce((acc, m) => acc + m.carbs, 0) || 0
    );
    const totalFat = computed(
        () => mealLogs.value?.reduce((acc, m) => acc + m.fat, 0) || 0
    );

    const proteinUsagePercentage = computed(
        () => (totalProtein.value / proteinGoal.value) * 100 || 0
    );
    const carbsUsagePercentage = computed(
        () => (totalCarbs.value / carbsGoal.value) * 100 || 0
    );
    const fatUsagePercentage = computed(
        () => (totalFat.value / fatGoal.value) * 100 || 0
    );

    const totalCalories = computed(() => {
        return mealLogs.value.reduce((acc, meal) => acc + meal.calories, 0);
    });

    const calorieUsagePercentage = computed(() => {
        if (!calorieGoal.value || calorieGoal.value === 0) return 0;
        return (totalCalories.value / calorieGoal.value) * 100;
    });

    const remainingCalories = computed(() => {
        return calorieGoal.value - totalCalories.value;
    });

    const calorieColor = computed(() => {
        const pct = calorieUsagePercentage.value;

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

    const fetchMealLogs = () => {
        mealLogs.value = mealLogsRepository.getMealLogs();
    };

    const addMealLog = (mealData) => {
        const updatedMealLogs = mealLogsRepository.storeMealLog(mealData);
        mealLogs.value = updatedMealLogs;
    };

    const removeMealLog = (id) => {
        mealLogs.value = mealLogsRepository.deleteMealLog(id);
    };

    return {
        mealLogs,
        calorieGoal,
        totalCalories,
        calorieUsagePercentage,
        remainingCalories,
        calorieColor,
        proteinGoal,
        carbsGoal,
        fatGoal,
        totalProtein,
        totalCarbs,
        totalFat,
        proteinUsagePercentage,
        carbsUsagePercentage,
        fatUsagePercentage,
        fetchMealLogs,
        removeMealLog,
        addMealLog,
    };
});
