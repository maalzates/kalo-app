import { createRouter, createWebHistory } from 'vue-router';
import Dashboard from '@/components/menus/Dashboard.vue';
import Ingredients from '@/components/menus/Ingredients.vue';
import Recipes from '@/components/menus/Recipes.vue';
import Profile from '@/components/user/Profile.vue';
import Progress from '@/components/menus/Progress.vue';
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
    {
        path: '/progress', 
        name: 'progress', 
        component: Progress, 
        meta: {requiresAuth: true}
    }
    // Ruta comodín: redirige cualquier URL desconocida al Dashboard
    { 
        path: '/:pathMatch(.*)*', 
        redirect: '/'
    },
]

const router = createRouter({
    history: createWebHistory(),
    routes,
});

/**
 * Router Guard: Protección infranqueable de rutas
 * 
 * Verifica requiresAuth en todos los niveles usando to.matched.some
 * Asegura que el estado del usuario se recupera del localStorage antes de verificar
 * Redirige automáticamente a login si no hay autenticación
 */
router.beforeEach((to, from, next) => {
    // Obtener la instancia del store (se inicializa automáticamente con el token del localStorage)
    const userStore = useUserStore();
    
    // Verificar si la ruta requiere autenticación en todos los niveles
    const requiresAuth = to.matched.some(record => record.meta.requiresAuth);
    
    // Si la ruta requiere autenticación y el usuario NO está logueado
    if (requiresAuth && !userStore.isLoggedIn) {
        // Redirigir al login
        next({ name: 'login' });
        return;
    }
    
    // Si el usuario ya está logueado e intenta acceder a login o register
    if (userStore.isLoggedIn && (to.name === 'login' || to.name === 'register')) {
        // Redirigir al dashboard
        next({ name: 'dashboard' });
        return;
    }
    
    // En cualquier otro caso, permitir el paso
    next();
});

export default router;
