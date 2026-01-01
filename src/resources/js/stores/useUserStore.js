import { defineStore } from "pinia";
import { ref, computed } from "vue";

export const useUserStore = defineStore("userStore", () => {
    // 1. Estado: El token se recupera del localStorage al arrancar
    const token = ref(localStorage.getItem('access_token'));
    const user = ref(JSON.parse(localStorage.getItem('user_data')));

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

    const logout = async () => {
        try {
            // Llamar al endpoint de logout del backend
            await window.axios.post('/logout');
        } catch (error) {
            console.error('Error during logout:', error);
        } finally {
            // Limpiar estado local independientemente del resultado del backend
            user.value = null;
            token.value = null;
            localStorage.removeItem('access_token');
            localStorage.removeItem('user_data');
            
            // Forzar redirección al login después de limpiar el estado
            // Usar window.location para forzar una recarga completa y limpiar cualquier estado residual
            window.location.href = '/login';
        }
    };

    return {
        user,
        token,
        isLoggedIn,
        setAuth,
        logout
    };
});
