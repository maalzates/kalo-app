import { dailyMeals } from "../data/meals";

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
};

export default mealRepository;
