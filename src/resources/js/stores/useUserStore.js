import { defineStore } from "pinia";
import { ref, computed } from "vue";
import usersRepository from "@/repositories/usersRepository.js";

export const useUserStore = defineStore("userStore", () => {
    // 1. Estado: El token se recupera del localStorage al arrancar
    const token = ref(localStorage.getItem('access_token'));
    const user = ref((() => {
        try {
            const userData = localStorage.getItem('user_data');
            return userData ? JSON.parse(userData) : null;
        } catch (error) {
            return null;
        }
    })());

    // 2. Getters: El estado de login depende de la existencia del token
    const isLoggedIn = computed(() => !!token.value);

    // 3. Acciones: Para gestionar el ciclo de vida de la sesión
    const setAuth = (newUserData, newToken) => {
        user.value = newUserData;
        token.value = newToken;

        // Guardamos en el disco para persistencia al refrescar
        localStorage.setItem('access_token', newToken);
        localStorage.setItem('user_data', JSON.stringify(newUserData));
    };

    /**
     * Logout optimista: Limpia estado primero, redirige inmediatamente, luego invalida token en servidor
     * Patrón de diseño: Prioridad 1 (UI) > Prioridad 2 (Backend)
     */
    const logout = (router) => {
        // PRIORIDAD 1: Limpiar estado de forma SÍNCRONA e INMEDIATA
        // Esto debe ocurrir ANTES de cualquier petición al backend
        // La UI reacciona instantáneamente al cambio de estado reactivo
        user.value = null;
        token.value = null;
        localStorage.removeItem('access_token');
        localStorage.removeItem('user_data');

        // PRIORIDAD 2: Redirigir inmediatamente usando router.push
        // Esto ocurre ANTES de la llamada al backend
        if (router) {
            router.push({ name: 'login' });
        }

        // PRIORIDAD 3: Invalidar token en el servidor (no bloqueante)
        // Esto se hace en segundo plano y NO debe retrasar la redirección
        // No esperamos a que termine esta promesa
        usersRepository.logout().catch(error => {
            // Silenciar errores del backend, el logout local ya se completó
            console.error('Error during backend logout (non-blocking):', error);
        });
    };

    return {
        user,
        token,
        isLoggedIn,
        setAuth,
        logout
    };
});
