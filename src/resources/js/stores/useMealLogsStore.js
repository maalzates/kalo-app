import { defineStore } from "pinia";
import { ref, computed } from "vue";
import mealLogsRepository from "../repositories/mealLogsRepository";

export const useMealLogsStore = defineStore("mealLogsStore", () => {
    const mealLogs = ref([]);
    const loading = ref(false);
    const error = ref(null);
    const isAnalyzing = ref(null); // Nuevo estado para la cámara
    const analysisResult = ref(null); // Resultado temporal de la IA

    // Metas de macros (se pueden obtener del usuario o de macros)
    const calorieGoal = ref(2000);
    const proteinGoal = ref(150);
    const carbsGoal = ref(200);
    const fatGoal = ref(65);

    // Totales consumidos
    const totalProtein = computed(() => {
        return mealLogs.value?.reduce((acc, m) => {
            // Ajustar según la estructura de datos del backend
            const protein = m.protein || m.prot || 0;
            return acc + parseFloat(protein);
        }, 0) || 0;
    });

    const totalCarbs = computed(() => {
        return mealLogs.value?.reduce((acc, m) => {
            const carbs = m.carbs || m.carb || 0;
            return acc + parseFloat(carbs);
        }, 0) || 0;
    });

    const totalFat = computed(() => {
        return mealLogs.value?.reduce((acc, m) => {
            const fat = m.fat || 0;
            return acc + parseFloat(fat);
        }, 0) || 0;
    });

    const totalCalories = computed(() => {
        return mealLogs.value?.reduce((acc, m) => {
            const calories = m.calories || m.kcal || 0;
            return acc + parseFloat(calories);
        }, 0) || 0;
    });

    const proteinUsagePercentage = computed(() => {
        if (!proteinGoal.value || proteinGoal.value === 0) return 0;
        return (totalProtein.value / proteinGoal.value) * 100;
    });

    const carbsUsagePercentage = computed(() => {
        if (!carbsGoal.value || carbsGoal.value === 0) return 0;
        return (totalCarbs.value / carbsGoal.value) * 100;
    });

    const fatUsagePercentage = computed(() => {
        if (!fatGoal.value || fatGoal.value === 0) return 0;
        return (totalFat.value / fatGoal.value) * 100;
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

    const fetchMealLogs = async (filters = {}) => {
        loading.value = true;
        error.value = null;
        try {
            const response = await mealLogsRepository.getAll(filters);
            // Manejar respuesta paginada o directa
            if (response && Array.isArray(response)) {
                mealLogs.value = response;
            } else if (response?.data && Array.isArray(response.data)) {
                mealLogs.value = response.data;
            } else {
                mealLogs.value = [];
            }
        } catch (err) {
            error.value = err.response?.data?.message || 'Error al cargar registros de comida';
            console.error('Error fetching meal logs:', err);
            mealLogs.value = [];
        } finally {
            loading.value = false;
        }
    };

    const addMealLog = async (mealData) => {
        loading.value = true;
        error.value = null;
        try {
            const newMealLog = await mealLogsRepository.create(mealData);
            mealLogs.value.push(newMealLog);
            return newMealLog;
        } catch (err) {
            error.value = err.response?.data?.message || 'Error al crear registro de comida';
            throw err;
        } finally {
            loading.value = false;
        }
    };

    const addMealLogFromAI = async (mealData) => {
        loading.value = true;
        error.value = null;
        try {
            const newMealLog = await mealLogsRepository.createFromAI(mealData);
            mealLogs.value.push(newMealLog);
            return newMealLog;
        } catch (err) {
            error.value = err.response?.data?.message || 'Error al crear registro de comida desde IA';
            throw err;
        } finally {
            loading.value = false;
        }
    };

    const updateMealLog = async (id, mealData) => {
        loading.value = true;
        error.value = null;
        try {
            const updatedMealLog = await mealLogsRepository.update(id, mealData);
            const index = mealLogs.value.findIndex(m => m.id === id);
            if (index !== -1) {
                mealLogs.value[index] = updatedMealLog;
            }
            return updatedMealLog;
        } catch (err) {
            error.value = err.response?.data?.message || 'Error al actualizar registro de comida';
            throw err;
        } finally {
            loading.value = false;
        }
    };

    const removeMealLog = async (id) => {
        loading.value = true;
        error.value = null;
        try {
            await mealLogsRepository.delete(id);
            mealLogs.value = mealLogs.value.filter(m => m.id !== id);
            return true;
        } catch (err) {
            error.value = err.response?.data?.message || 'Error al eliminar registro de comida';
            throw err;
        } finally {
            loading.value = false;
        }
    };

    const analyzeMealImage = async (imageBlob) => {
        isAnalyzing.value = true;
        error.value = null;
        try {
            const result = await mealLogsRepository.analyzeFoodImage(imageBlob);
            analysisResult.value = result;
            return result;
        } catch (err) {
            error.value = err.response?.data?.message || 'Error al analizar la imagen';
            throw err;
        } finally {
            isAnalyzing.value = false;
        }
    };

    return {
        mealLogs,
        loading,
        error,
        isAnalyzing,
        calorieGoal,
        proteinGoal,
        carbsGoal,
        fatGoal,
        totalCalories,
        totalProtein,
        totalCarbs,
        totalFat,
        calorieUsagePercentage,
        proteinUsagePercentage,
        carbsUsagePercentage,
        fatUsagePercentage,
        remainingCalories,
        calorieColor,
        analysisResult,
        fetchMealLogs,
        addMealLog,
        addMealLogFromAI,
        updateMealLog,
        analyzeMealImage,
        removeMealLog,
    };
});
