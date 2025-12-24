import { defineStore } from 'pinia';
import { ref, computed } from 'vue';
import mealRepository from '../repositories/mealRepository';
import { userSettings } from '../data/userStats';

export const useMealStore = defineStore('mealStore', () => {
    const meals = ref([]);
    const calorieGoal = ref(userSettings.dailyCalorieGoal);

    const totalCalories = computed(() => {
        return meals.value.reduce((acc, meal) => acc + meal.calories, 0);
    });

    const calorieUsagePercentage = computed(() => {
        if (!calorieGoal.value || calorieGoal.value === 0) return 0;
        return (totalCalories.value / calorieGoal.value) * 100;
    });

    const remainingCalories = computed(() => {
        return calorieGoal.value - totalCalories.value;
    });

    const fetchMeals = () => {
        meals.value = mealRepository.getDaily();
    };

    return {
        meals,
        calorieGoal,
        totalCalories,
        calorieUsagePercentage,
        remainingCalories,
        fetchMeals,
    };
});
