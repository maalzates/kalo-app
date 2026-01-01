import { defineStore } from "pinia";
import { ref } from "vue";
import recipesRepository from "@/repositories/recipesRepository.js";

export const useRecipesStore = defineStore("recipesStore", () => {
    const recipes = ref([]);
    const recipesForMealLog = ref([]); // Variable separada para CreateMealLog (incluye públicos)
    const loading = ref(false);
    const error = ref(null);

    const fetchRecipes = async (filters = {}) => {
        loading.value = true;
        error.value = null;
        try {
            const response = await recipesRepository.getAll(filters);
            // Manejar respuesta paginada o directa
            if (response && Array.isArray(response)) {
                recipes.value = response;
            } else if (response?.data && Array.isArray(response.data)) {
                recipes.value = response.data;
            } else {
                recipes.value = [];
            }
        } catch (err) {
            error.value = err.response?.data?.message || 'Error al cargar recetas';
            console.error('Error fetching recipes:', err);
            recipes.value = [];
        } finally {
            loading.value = false;
        }
    };

    const fetchRecipesForMealLog = async (filters = {}) => {
        loading.value = true;
        error.value = null;
        try {
            // Siempre incluir elementos públicos para MealLogs
            const response = await recipesRepository.getAll({ ...filters, include_public: true });
            // Manejar respuesta paginada o directa
            if (response && Array.isArray(response)) {
                recipesForMealLog.value = response;
            } else if (response?.data && Array.isArray(response.data)) {
                recipesForMealLog.value = response.data;
            } else {
                recipesForMealLog.value = [];
            }
        } catch (err) {
            error.value = err.response?.data?.message || 'Error al cargar recetas';
            console.error('Error fetching recipes for meal log:', err);
            recipesForMealLog.value = [];
        } finally {
            loading.value = false;
        }
    };

    const createRecipe = async (recipeData) => {
        loading.value = true;
        error.value = null;
        try {
            const newRecipe = await recipesRepository.create(recipeData);
            recipes.value.push(newRecipe);
            // También agregar a recipesForMealLog si es del usuario actual
            recipesForMealLog.value.push(newRecipe);
            return newRecipe;
        } catch (err) {
            error.value = err.response?.data?.message || 'Error al crear receta';
            throw err;
        } finally {
            loading.value = false;
        }
    };

    const updateRecipe = async (id, recipeData) => {
        loading.value = true;
        error.value = null;
        try {
            const updatedRecipe = await recipesRepository.update(id, recipeData);
            const index = recipes.value.findIndex(rec => rec.id === id);
            if (index !== -1) {
                recipes.value[index] = updatedRecipe;
            }
            return updatedRecipe;
        } catch (err) {
            error.value = err.response?.data?.message || 'Error al actualizar receta';
            throw err;
        } finally {
            loading.value = false;
        }
    };

    const deleteRecipe = async (id) => {
        loading.value = true;
        error.value = null;
        try {
            await recipesRepository.delete(id);
            recipes.value = recipes.value.filter(rec => rec.id !== id);
            return true;
        } catch (err) {
            error.value = err.response?.data?.message || 'Error al eliminar receta';
            throw err;
        } finally {
            loading.value = false;
        }
    };

    return {
        recipes,
        recipesForMealLog,
        loading,
        error,
        fetchRecipes,
        fetchRecipesForMealLog,
        createRecipe,
        updateRecipe,
        deleteRecipe,
    };
});
