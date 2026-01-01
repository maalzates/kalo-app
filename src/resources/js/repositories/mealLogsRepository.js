import axios from 'axios';

const mealLogsRepository = {
    async getAll(filters = {}) {
        try {
            const params = new URLSearchParams();
            if (filters.date_from) params.append('date_from', filters.date_from);
            if (filters.date_to) params.append('date_to', filters.date_to);
            if (filters.type) params.append('type', filters.type);
            if (filters.page) params.append('page', filters.page);
            if (filters.per_page) params.append('per_page', filters.per_page);

            const response = await axios.get(`/meal-logs?${params.toString()}`);
            return response.data.data;
        } catch (error) {
            console.error('Error fetching meal logs:', error);
            throw error;
        }
    },

    async getById(id) {
        try {
            const response = await axios.get(`/meal-logs/${id}`);
            return response.data.data;
        } catch (error) {
            console.error('Error fetching meal log:', error);
            throw error;
        }
    },

    async create(mealLogData) {
        try {
            const response = await axios.post('/meal-logs', mealLogData);
            return response.data.data;
        } catch (error) {
            console.error('Error creating meal log:', error);
            throw error;
        }
    },

    async update(id, mealLogData) {
        try {
            const response = await axios.put(`/meal-logs/${id}`, mealLogData);
            return response.data.data;
        } catch (error) {
            console.error('Error updating meal log:', error);
            throw error;
        }
    },

    async delete(id) {
        try {
            await axios.delete(`/meal-logs/${id}`);
            return true;
        } catch (error) {
            console.error('Error deleting meal log:', error);
            throw error;
        }
    },
};

export default mealLogsRepository;
