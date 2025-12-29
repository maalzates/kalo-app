import { createRouter, createWebHistory } from 'vue-router';
import Dashboard from '@/components/menus/Dashboard.vue';
import Ingredients from '@/components/menus/Ingredients.vue';
import Recipes from '@/components/menus/Recipes.vue';
import Profile from '@/components/user/Profile.vue';
import MyMacros from '@/components/menus/MyMacros.vue';
import Login from '@/components/Login.vue';
import Register from '@/components/Register.vue';
const routes = [
    { path: '/login', component: Login },
    { path: '/register', component: Register },
    { path: '/', component: Dashboard },
    { path: '/ingredients', component: Ingredients },
    { path: '/recipes', component: Recipes },
    { path: '/profile', component: Profile },
    { path: '/macros', component: MyMacros},
]

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
