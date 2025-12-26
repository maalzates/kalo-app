import { defineStore } from "pinia";
import { ref } from "vue";
import ingredientsRepository from "@/repositories/useIngredientsRepository";

export const useIngredientsStore = defineStore("ingredientsStore", () => {
    const ingredients = ref([]);

    const fetchIngredients = () => {
        ingredients.value = ingredientsRepository.getIngredients();
    };

    return {
        ingredients,
        fetchIngredients,
    };
});
