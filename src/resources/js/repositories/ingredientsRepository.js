import { ingredients } from '@/data/Ingredients';

const ingredientsRepository = {
    getIngredients() {
        return ingredients;
    },
};

export default ingredientsRepository;
