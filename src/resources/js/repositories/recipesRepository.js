import axios from 'axios';

const recipesRepository = {
    async getAll(filters = {}) {
        try {
            const params = new URLSearchParams();
            if (filters.search) params.append('search', filters.search);
            if (filters.page) params.append('page', filters.page);
            if (filters.per_page) params.append('per_page', filters.per_page);

            const response = await axios.get(`/recipes?${params.toString()}`);
            return response.data.data;
        } catch (error) {
            console.error('Error fetching recipes:', error);
            throw error;
        }
    },

    async getById(id) {
        try {
            const response = await axios.get(`/recipes/${id}`);
            return response.data.data;
        } catch (error) {
            console.error('Error fetching recipe:', error);
            throw error;
        }
    },

    async create(recipeData) {
        try {
            const response = await axios.post('/recipes', recipeData);
            return response.data.data;
        } catch (error) {
            console.error('Error creating recipe:', error);
            throw error;
        }
    },

    async update(id, recipeData) {
        try {
            const response = await axios.put(`/recipes/${id}`, recipeData);
            return response.data.data;
        } catch (error) {
            console.error('Error updating recipe:', error);
            throw error;
        }
    },

    async delete(id) {
        try {
            await axios.delete(`/recipes/${id}`);
            return true;
        } catch (error) {
            console.error('Error deleting recipe:', error);
            throw error;
        }
    },
};

export default recipesRepository;
