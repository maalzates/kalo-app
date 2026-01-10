import { createRouter, createWebHistory } from 'vue-router';
import Dashboard from '@/components/menus/Dashboard.vue';
import Ingredients from '@/components/menus/Ingredients.vue';
import Recipes from '@/components/menus/Recipes.vue';
import Profile from '@/components/user/Profile.vue';
import MyMacros from '@/components/menus/MyMacros.vue';
import MyBiometrics from '@/components/menus/MyBiometrics.vue';
import FoodManager from '@/components/menus/FoodManager.vue';
import Progress from '@/components/menus/Progress.vue';
import Login from '@/components/Login.vue';
import Register from '@/components/Register.vue';
import AuthCallback from '@/components/AuthCallback.vue';
import Onboarding from '@/pages/Onboarding.vue';
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
        path: '/auth/callback',
        name: 'auth-callback',
        component: AuthCallback
    },
    {
        path: '/onboarding',
        name: 'onboarding',
        component: Onboarding,
        meta: { requiresAuth: true, skipProfileCheck: true, hideLayout: true }
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
        path: '/biometrics',
        name: 'biometrics',
        component: MyBiometrics,
        meta: {requiresAuth: true}
    },
    {
        path: '/progress',
        name: 'progress',
        component: Progress,
        meta: {requiresAuth: true}
    },
    // Ruta comodín: redirige cualquier URL desconocida al Dashboard
    { 
        path: '/:pathMatch(.*)*', 
        redirect: '/'
    },
    {
        path: '/foods',
        name: 'Foods',
        component: FoodManager, // Verifica la ruta de tu archivo
        meta: { 
          requiresAuth: true,
          title: 'Gestion de alimentos' 
        }
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
 * Verifica perfil completo y redirige a onboarding si es necesario
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

    // Verificar si el perfil está completo (solo para usuarios autenticados y rutas que no skip el check)
    const skipProfileCheck = to.matched.some(record => record.meta.skipProfileCheck);
    if (userStore.isLoggedIn && !skipProfileCheck && to.name !== 'onboarding') {
        const user = userStore.user;
        const profileIncomplete = !user?.weight ||
                                  !user?.height ||
                                  !user?.birth_date ||
                                  !user?.gender ||
                                  !user?.activity_level ||
                                  !user?.goal_type;

        if (profileIncomplete) {
            next({ name: 'onboarding' });
            return;
        }
    }

    // En cualquier otro caso, permitir el paso
    next();
});

export default router;
