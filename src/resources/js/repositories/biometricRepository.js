import axios from 'axios';

const biometricsRepository = {
    async findAll(filters = {}) {
        try {
            // Convertimos los filtros en query params
            const response = await axios.get('/biometrics', { params: filters });
            return response.data; // Retorna { data: [...], meta: {...} }
        } catch (error) {
            console.error('Error fetching biometrics:', error);
            throw error;
        }
    },

    async create(data) {
        try {
            const response = await axios.post('/biometrics', data);
            return response.data.data;
        } catch (error) {
            console.error('Error creating biometric:', error);
            throw error;
        }
    },

    async update(id, data) {
        try {
            const response = await axios.put(`/biometrics/${id}`, data);
            return response.data.data;
        } catch (error) {
            console.error('Error updating biometric:', error);
            throw error;
        }
    },

    async delete(id) {
        try {
            await axios.delete(`/biometrics/${id}`);
            return true;
        } catch (error) {
            console.error('Error deleting biometric:', error);
            throw error;
        }
    }
};

export default biometricsRepository;
