import { defineStore } from 'pinia';
import { ref, computed } from 'vue';
import mealRepository from '../repositories/mealRepository';
import { userSettings } from '../data/userStats';

export const useMealStore = defineStore('mealStore', () => {
    // 1. Estado (State) - Usamos ref
    const meals = ref([]);
    const calorieGoal = ref(userSettings.dailyCalorieGoal);

    // 2. Valores derivados (En lugar de Getters) - Usamos computed
    const totalCalories = computed(() => {
        return meals.value.reduce((acc, meal) => acc + meal.calories, 0);
    });

    const remainingCalories = computed(() => {
        return calorieGoal.value - totalCalories.value;
    });

    // 3. Métodos (En lugar de Actions) - Funciones simples
    const fetchMeals = () => {
        meals.value = mealRepository.getDaily();
        console.log('Hello');
    };

    // Retornamos todo para que sea accesible
    return {
        meals,
        calorieGoal,
        totalCalories,
        remainingCalories,
        fetchMeals
    };
});
