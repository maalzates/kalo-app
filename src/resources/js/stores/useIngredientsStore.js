import { defineStore } from "pinia";
import { ref } from "vue";
import ingredientsRepository from "@/repositories/ingredientsRepository.js";

export const useIngredientsStore = defineStore("ingredientsStore", () => {
    const ingredients = ref([]);
    const ingredientsForMealLog = ref([]); // Variable separada para CreateMealLog (incluye públicos)
    const loading = ref(false);
    const error = ref(null);

    const fetchIngredients = async (filters = {}) => {
        loading.value = true;
        error.value = null;
        try {
            const response = await ingredientsRepository.getAll(filters);
            // Manejar respuesta paginada o directa
            if (response && Array.isArray(response)) {
                ingredients.value = response;
            } else if (response?.data && Array.isArray(response.data)) {
                ingredients.value = response.data;
            } else {
                ingredients.value = [];
            }
        } catch (err) {
            error.value = err.response?.data?.message || 'Error al cargar ingredientes';
            console.error('Error fetching ingredients:', err);
            ingredients.value = [];
        } finally {
            loading.value = false;
        }
    };

    const fetchIngredientsForMealLog = async (filters = {}) => {
        loading.value = true;
        error.value = null;
        try {
            // Siempre incluir elementos públicos para MealLogs
            const response = await ingredientsRepository.getAll({ ...filters, include_public: true });
            // Manejar respuesta paginada o directa
            if (response && Array.isArray(response)) {
                ingredientsForMealLog.value = response;
            } else if (response?.data && Array.isArray(response.data)) {
                ingredientsForMealLog.value = response.data;
            } else {
                ingredientsForMealLog.value = [];
            }
        } catch (err) {
            error.value = err.response?.data?.message || 'Error al cargar ingredientes';
            console.error('Error fetching ingredients for meal log:', err);
            ingredientsForMealLog.value = [];
        } finally {
            loading.value = false;
        }
    };

    const createIngredient = async (ingredientData) => {
        loading.value = true;
        error.value = null;
        try {
            const newIngredient = await ingredientsRepository.create(ingredientData);
            ingredients.value.push(newIngredient);
            // También agregar a ingredientsForMealLog si es del usuario actual
            ingredientsForMealLog.value.push(newIngredient);
            return newIngredient;
        } catch (err) {
            error.value = err.response?.data?.message || 'Error al crear ingrediente';
            throw err;
        } finally {
            loading.value = false;
        }
    };

    const updateIngredient = async (id, ingredientData) => {
        loading.value = true;
        error.value = null;
        try {
            const updatedIngredient = await ingredientsRepository.update(id, ingredientData);
            const index = ingredients.value.findIndex(ing => ing.id === id);
            if (index !== -1) {
                ingredients.value[index] = updatedIngredient;
            }
            return updatedIngredient;
        } catch (err) {
            error.value = err.response?.data?.message || 'Error al actualizar ingrediente';
            throw err;
        } finally {
            loading.value = false;
        }
    };

    const deleteIngredient = async (id) => {
        loading.value = true;
        error.value = null;
        try {
            await ingredientsRepository.delete(id);
            ingredients.value = ingredients.value.filter(ing => ing.id !== id);
            return true;
        } catch (err) {
            error.value = err.response?.data?.message || 'Error al eliminar ingrediente';
            throw err;
        } finally {
            loading.value = false;
        }
    };

    return {
        ingredients,
        ingredientsForMealLog,
        loading,
        error,
        fetchIngredients,
        fetchIngredientsForMealLog,
        createIngredient,
        updateIngredient,
        deleteIngredient,
    };
});
