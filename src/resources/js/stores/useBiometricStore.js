import { defineStore } from "pinia";
import { ref } from "vue";
import biometricsRepository from "@/repositories/biometricsRepository";

export const useBiometricsStore = defineStore("biometricsStore", () => {
    const biometrics = ref([]);
    const loading = ref(false);
    const error = ref(null);

    const fetchBiometrics = async (filters = {}) => {
        loading.value = true;
        try {
            const response = await biometricsRepository.findAll(filters);
            biometrics.value = response.data || [];
            return response;
        } catch (err) {
            error.value = err;
            throw err;
        } finally {
            loading.value = false;
        }
    };

    const createBiometric = async (data) => {
        try {
            const newRecord = await biometricsRepository.create(data);
            biometrics.value = [newRecord, ...biometrics.value].sort((a, b) => 
                new Date(b.measured_at) - new Date(a.measured_at)
            );
            return newRecord;
        } catch (err) {
            error.value = err;
            throw err;
        }
    };

    return {
        biometrics,
        loading,
        error,
        fetchBiometrics,
        createBiometric
    };
});
