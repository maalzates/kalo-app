import { dailyMeals } from "../data/meals";
import { foodLibrary } from '../data/foodLibrary';

const mealRepository = {
    getDaily() {
        return dailyMeals;
    },

    delete(id) {
        const index = dailyMeals.findIndex((m) => m.id === id);

        if (index !== -1) {
            dailyMeals.splice(index, 1);
        }
        return [...dailyMeals];
    },

    save(meal) {
        const newMeal = {
            id: Math.random().toString(36).substring(2, 15),
            ...meal
        };
        dailyMeals.push(newMeal);
        return [...dailyMeals];
    },

    getFoodLibrary() {
        return foodLibrary;
    },
};

export default mealRepository;
