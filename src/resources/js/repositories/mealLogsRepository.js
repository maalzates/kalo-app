import { mealLogs } from "@/data/MealLogs";
import axios from 'axios';

const mealLogsRepository = {
    async getMealLogs() {
        try{
            const response = await axios.get(`/api/meal-logs`);
            return response;
        }catch (error) {
            console.error(error);
        }finally{
            console.log(response ?? error);
        }
    },

    deleteMealLog(id) {
        const index = mealLogs.findIndex((m) => m.id === id);

        if (index !== -1) {
            mealLogs.splice(index, 1);
        }
        return [...mealLogs];
    },

    storeMealLog(meal) {
        const newMeal = {
            id: Math.random().toString(36).substring(2, 15),
            ...meal
        };
        mealLogs.push(newMeal);
        return [...mealLogs];
    },
};

export default mealLogsRepository;
