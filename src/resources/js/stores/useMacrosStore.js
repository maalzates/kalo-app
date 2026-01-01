import { defineStore } from "pinia";
import { ref, computed } from "vue";
import macrosRepository from "@/repositories/macrosRepository.js";

export const useMacrosStore = defineStore("macrosStore", () => {
    const macros = ref([]);
    const loading = ref(false);
    const error = ref(null);

    /**
     * Normaliza una fecha para comparar solo día/mes/año (sin hora)
     */
    const normalizeDate = (date) => {
        const d = date instanceof Date ? date : new Date(date);
        return new Date(d.getFullYear(), d.getMonth(), d.getDate());
    };

    /**
     * Busca el macro más cercano hacia atrás basado en la fecha
     * Si no hay un macro para la fecha exacta, retorna el más reciente anterior
     * Compara solo día/mes/año, ignorando la hora
     */
    const findClosestMacroBeforeDate = (targetDate) => {
        if (!targetDate || macros.value.length === 0) {
            return null;
        }

        // Normalizar la fecha objetivo (solo día/mes/año)
        const target = normalizeDate(targetDate);
        const targetTime = target.getTime();

        // Filtrar macros que sean anteriores o iguales a la fecha objetivo
        const validMacros = macros.value.filter(macro => {
            if (!macro.created_at) return false;
            const macroDate = normalizeDate(macro.created_at);
            const macroTime = macroDate.getTime();
            return macroTime <= targetTime;
        });

        if (validMacros.length === 0) {
            return null;
        }

        // Ordenar por fecha descendente y tomar el más reciente
        const sorted = validMacros.sort((a, b) => {
            const dateA = normalizeDate(a.created_at);
            const dateB = normalizeDate(b.created_at);
            return dateB.getTime() - dateA.getTime(); // Más reciente primero
        });

        return sorted[0];
    };

    /**
     * Obtiene el macro actual basado en la fecha proporcionada
     * Si no existe uno para esa fecha, busca el más cercano hacia atrás
     */
    const getMacroForDate = (targetDate) => {
        return findClosestMacroBeforeDate(targetDate);
    };

    /**
     * Obtiene la fecha del macro más antiguo del usuario
     * Retorna null si no hay macros
     */
    const getOldestMacroDate = () => {
        if (macros.value.length === 0) {
            return null;
        }

        // Ordenar por fecha ascendente y tomar el más antiguo
        const sorted = [...macros.value].sort((a, b) => {
            const dateA = normalizeDate(a.created_at);
            const dateB = normalizeDate(b.created_at);
            return dateA.getTime() - dateB.getTime(); // Más antiguo primero
        });

        return normalizeDate(sorted[0].created_at);
    };

    const fetchMacros = async (filters = {}) => {
        loading.value = true;
        error.value = null;
        try {
            const response = await macrosRepository.findAll(filters);
            // El backend retorna { data: [...], meta: {...} }
            macros.value = response.data || [];
        } catch (err) {
            error.value = err;
            console.error("Error fetching macros:", err);
        } finally {
            loading.value = false;
        }
    };

    const createMacro = async (macroData) => {
        loading.value = true;
        error.value = null;
        try {
            const newMacro = await macrosRepository.create(macroData);
            macros.value.push(newMacro);
            // Ordenar por fecha descendente
            macros.value.sort((a, b) => {
                const dateA = new Date(a.created_at);
                const dateB = new Date(b.created_at);
                return dateB - dateA;
            });
            return newMacro;
        } catch (err) {
            error.value = err;
            console.error("Error creating macro:", err);
            throw err;
        } finally {
            loading.value = false;
        }
    };

    const updateMacro = async (id, macroData) => {
        loading.value = true;
        error.value = null;
        try {
            const updated = await macrosRepository.update(id, macroData);
            const index = macros.value.findIndex(m => m.id === id);
            if (index !== -1) {
                macros.value[index] = { ...macros.value[index], ...updated };
            }
            return updated;
        } catch (err) {
            error.value = err;
            console.error("Error updating macro:", err);
            throw err;
        } finally {
            loading.value = false;
        }
    };

    const deleteMacro = async (id) => {
        loading.value = true;
        error.value = null;
        try {
            await macrosRepository.delete(id);
            macros.value = macros.value.filter(m => m.id !== id);
        } catch (err) {
            error.value = err;
            console.error("Error deleting macro:", err);
            throw err;
        } finally {
            loading.value = false;
        }
    };

    return {
        macros,
        loading,
        error,
        fetchMacros,
        createMacro,
        updateMacro,
        deleteMacro,
        getMacroForDate,
        findClosestMacroBeforeDate,
        getOldestMacroDate,
    };
});

