import { dailyMeals } from '../data/meals';

const mealRepository = {
    // Esta función devuelve el array de comidas que definimos en el archivo .js
    getDaily() {
        return dailyMeals;
    }
};

export default mealRepository;
