import { recipes } from '@/data/Recipes';

const recipesRepository = {
    getRecipes() {
        return recipes;
    },
};

export default recipesRepository;
