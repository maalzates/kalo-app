import axios from 'axios';

const macrosRepository = {
    async findAll(filters = {}) {
        try {
            const response = await axios.get('/macros', { params: filters });
            return response.data.data;
        } catch (error) {
            console.error('Error fetching macros:', error);
            throw error;
        }
    },

    async findById(id) {
        try {
            const response = await axios.get(`/macros/${id}`);
            return response.data.data;
        } catch (error) {
            console.error('Error fetching macro:', error);
            throw error;
        }
    },

    async create(macroData) {
        try {
            const response = await axios.post('/macros', macroData);
            return response.data.data;
        } catch (error) {
            console.error('Error creating macro:', error);
            throw error;
        }
    },

    async update(id, macroData) {
        try {
            const response = await axios.put(`/macros/${id}`, macroData);
            return response.data.data;
        } catch (error) {
            console.error('Error updating macro:', error);
            throw error;
        }
    },

    async delete(id) {
        try {
            await axios.delete(`/macros/${id}`);
            return true;
        } catch (error) {
            console.error('Error deleting macro:', error);
            throw error;
        }
    },
};

export default macrosRepository;

