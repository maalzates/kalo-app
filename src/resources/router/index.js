import { createRouter, createWebHistory } from 'vue-router';
import Dashboard from '@/components/Dashboard.vue';
import IngredientsList from '@/components/ingredients/IngredientsList.vue';

const routes = [
    { path: '/', component: Dashboard },
    { path: '/ingredients', component: IngredientsList },
    { path: '/goals', component: () => import('../js/components/Goals.vue') }
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
