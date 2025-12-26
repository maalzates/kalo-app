import { mealLogs } from "../data/mealLogs";
import { foodLibrary } from '../data/foodLibrary';

const mealLogsRepository = {
    getMealLogs() {
        return mealLogs;
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

    getFoodLibrary() {
        return foodLibrary;
    },
};

export default mealLogsRepository;
