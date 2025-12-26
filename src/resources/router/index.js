import { createRouter, createWebHistory } from 'vue-router';
import Dashboard from '@/components/menus/Dashboard.vue';
import Ingredients from '@/components/menus/Ingredients.vue';

const routes = [
    { path: '/', component: Dashboard },
    { path: '/ingredients', component: Ingredients },
    { path: '/goals', component: () => import('../js/components/menus/Goals.vue') }
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
