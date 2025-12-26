import { defineStore } from "pinia";
import { ref } from "vue";
import recipesRepository from "@/repositories/recipesRepository.js";

export const useRecipesStore = defineStore("recipesStore", () => {
    const recipes = ref([]);

    const fetchRecipes = () => {
        recipes.value = recipesRepository.getRecipes();
    };

    return {
        recipes,
        fetchRecipes,
    };
});
