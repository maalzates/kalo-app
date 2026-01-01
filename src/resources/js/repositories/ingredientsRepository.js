import axios from 'axios';

const ingredientsRepository = {
    async getAll(filters = {}) {
        try {
            const params = new URLSearchParams();
            if (filters.search) params.append('search', filters.search);
            if (filters.unit) params.append('unit', filters.unit);
            if (filters.page) params.append('page', filters.page);
            if (filters.per_page) params.append('per_page', filters.per_page);

            const response = await axios.get(`/ingredients?${params.toString()}`);
            return response.data.data;
        } catch (error) {
            console.error('Error fetching ingredients:', error);
            throw error;
        }
    },

    async getById(id) {
        try {
            const response = await axios.get(`/ingredients/${id}`);
            return response.data.data;
        } catch (error) {
            console.error('Error fetching ingredient:', error);
            throw error;
        }
    },

    async create(ingredientData) {
        try {
            const response = await axios.post('/ingredients', ingredientData);
            return response.data.data;
        } catch (error) {
            console.error('Error creating ingredient:', error);
            throw error;
        }
    },

    async update(id, ingredientData) {
        try {
            const response = await axios.put(`/ingredients/${id}`, ingredientData);
            return response.data.data;
        } catch (error) {
            console.error('Error updating ingredient:', error);
            throw error;
        }
    },

    async delete(id) {
        try {
            await axios.delete(`/ingredients/${id}`);
            return true;
        } catch (error) {
            console.error('Error deleting ingredient:', error);
            throw error;
        }
    },
};

export default ingredientsRepository;
