import axios from 'axios';

const usersRepository = {
    async getCurrentUser() {
        try {
            const response = await axios.get('/users/me');
            return response.data.data;
        } catch (error) {
            console.error('Error fetching current user:', error);
            throw error;
        }
    },

    async updateUser(userId, userData) {
        try {
            const response = await axios.put(`/users/${userId}`, userData);
            return response.data.data;
        } catch (error) {
            console.error('Error updating user:', error);
            throw error;
        }
    },
};

export default usersRepository;
