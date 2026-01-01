import { createRouter, createWebHistory } from 'vue-router';
import Dashboard from '@/components/menus/Dashboard.vue';
import Ingredients from '@/components/menus/Ingredients.vue';
import Recipes from '@/components/menus/Recipes.vue';
import Profile from '@/components/user/Profile.vue';
import MyMacros from '@/components/menus/MyMacros.vue';
import Login from '@/components/Login.vue';
import Register from '@/components/Register.vue';
import { useUserStore } from '../js/stores/useUserStore';

const routes = [
    { 
        path: '/login', 
        name: 'login', // <--- IMPORTANTE
        component: Login 
    },
    { 
        path: '/register', 
        name: 'register', // <--- IMPORTANTE
        component: Register 
    },
    { 
        path: '/', 
        name: 'dashboard', // <--- IMPORTANTE
        component: Dashboard, 
        meta: {requiresAuth: true} 
    },
    { 
        path: '/ingredients', 
        name: 'ingredients', 
        component: Ingredients, 
        meta: {requiresAuth: true} 
    },
    { 
        path: '/recipes', 
        name: 'recipes', 
        component: Recipes, 
        meta: {requiresAuth: true} 
    },
    { 
        path: '/profile', 
        name: 'profile', 
        component: Profile, 
        meta: {requiresAuth: true} 
    },
    { 
        path: '/macros', 
        name: 'macros', 
        component: MyMacros, 
        meta: {requiresAuth: true}
    },
]

const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach((to, from, next) => {
    const userStore = useUserStore()
    
    // Si la ruta requiere auth y el usuario NO está logueado
    if (to.matched.some(record => record.meta.requiresAuth) && !userStore.isLoggedIn) {
      next({ name: 'login' })
    } 
    // Si el usuario ya está logueado e intenta ir al login o register
    else if (userStore.isLoggedIn && (to.name === 'login' || to.name === 'register')) {
      next({ name: 'dashboard' })
    }
    // En cualquier otro caso, permitir el paso
    else {
      next()
    }
  })

export default router;
