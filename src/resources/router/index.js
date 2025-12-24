import { createRouter, createWebHistory } from 'vue-router';
import Dashboard from '../views/Dashboard.vue';

const routes = [
    { path: '/', component: Dashboard },
    { path: '/goals', component: () => import('../views/Goals.vue') }
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
