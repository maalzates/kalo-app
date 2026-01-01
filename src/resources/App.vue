<template>
  <v-app shadow>
    <v-app-bar v-if="userStore.isLoggedIn" color="deep-purple" elevation="0" border="b">
      <v-app-bar-nav-icon @click="drawer = !drawer" />
      <v-app-bar-title class="logo">KALO<span class="logo-light">APP</span></v-app-bar-title>
      <v-spacer class="d-md-none"></v-spacer>
      
      <div class="d-md-none mr-4" @click="$router.push('/profile')">
        <v-avatar size="32" color="deep-purple-lighten-4" class="cursor-pointer">
          <span class="text-caption text-deep-purple font-weight-bold">
            {{ userInitials }}
          </span>
        </v-avatar>
      </div>
    </v-app-bar>

    <v-navigation-drawer v-if="userStore.isLoggedIn" v-model="drawer" color="deep-purple" theme="dark">
      <v-list nav class="mt-4 main-menu">
        <v-list-item prepend-icon="mdi-view-dashboard" title="Tablero" to="/" class="mb-2" />
        <v-list-item prepend-icon="mdi-food-apple" title="Ingredientes" to="/ingredients" class="mb-2" />
        <v-list-item prepend-icon="mdi-chef-hat" title="Recetas" to="/recipes" class="mb-2" />
        <v-list-item prepend-icon="mdi-target" title="Macros" to="/macros" class="mb-2" />
        
        <v-divider class="my-2"></v-divider>
        
        <v-list-item prepend-icon="mdi-logout" title="Cerrar Sesión" @click="handleLogout" />
      </v-list>

      <template v-slot:append>
        <v-divider></v-divider>
        <v-list-item
          lines="two"
          :title="userStore.user?.name || 'Usuario'"
          :subtitle="'Meta: ' + (userStore.user?.daily_calories || 0) + ' kcal'"
          class="pa-4 cursor-pointer"
          to="/profile"
        >
          <template v-slot:prepend>
            <v-avatar color="deep-purple-lighten-4" class="mr-n1">
              <span class="text-body-2 text-deep-purple font-weight-bold">
                {{ userInitials }}
              </span>
            </v-avatar>
          </template>
        </v-list-item>
      </template>
    </v-navigation-drawer>

    <v-btn 
      v-if="userStore.isLoggedIn"
      icon 
      color="deep-purple" 
      :class="['d-none d-md-flex btn-toggle', { 'is-open': drawer }]"
      @click="drawer = !drawer"
    >
      <v-icon>{{ drawer ? 'mdi-chevron-left' : 'mdi-chevron-right' }}</v-icon>
    </v-btn>

    <v-main>
      <v-container fluid :class="userStore.isLoggedIn ? 'pa-6' : 'pa-0 fill-height'">
        <router-view />
      </v-container>
    </v-main>
  </v-app>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useRouter } from 'vue-router'
import { useUserStore } from '@/stores/useUserStore'

const router = useRouter()
const userStore = useUserStore()
const drawer = ref(true)

// Iniciales del usuario para los avatares
const userInitials = computed(() => {
  if (!userStore.user?.name) return 'U'
  return userStore.user.name
    .split(' ')
    .map(n => n[0])
    .join('')
    .toUpperCase()
    .substring(0, 2)
})

const handleLogout = () => {
  // El logout maneja todo: limpieza de estado, redirección y llamada al backend
  userStore.logout(router)
}
</script>

<style scoped>
.logo { font-weight: 900; letter-spacing: -1.5px; font-size: 1.5rem; }
.logo-light { font-weight: 300; }
.cursor-pointer { cursor: pointer; }

.btn-toggle {
  position: fixed;
  top: 50%;
  left: 0;
  z-index: 2000;
  border-radius: 0 50% 50% 0;
  transform: translateY(-50%);
  transition: left 0.3s ease;
}

.btn-toggle.is-open {
  left: 256px; 
}

.main-menu :deep(.v-list-item) { min-height: 56px !important; }
.main-menu :deep(.v-list-item-title) { font-size: 1.1rem !important; font-weight: 500; }
.main-menu :deep(.v-icon) { font-size: 1.5rem !important; }

/* Para que la vista de login ocupe todo el espacio si no hay login */
.fill-height {
  height: 100vh;
}
</style>
