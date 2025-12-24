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

    const calorieColor = computed(() => {
        const pct = calorieUsagePercentage.value;
    
        switch (true) {
            case pct < 60:
                return 'orange';
            case pct >= 60 && pct < 80:
                return 'amber';
            case pct >= 80 && pct < 90:
                return 'green';
            case pct >= 90 && pct <= 100:
                return 'deep-purple';
            default:
                return 'red';
        }
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
        calorieColor,
        fetchMeals,
    };
});
