<template>
    <template v-if="userStore.isLoggedIn">
      <v-app-bar color="deep-purple" elevation="0" border="b">
        <v-app-bar-nav-icon @click="drawer = !drawer" />
        <v-app-bar-title class="logo">KALO<span class="logo-light">APP</span></v-app-bar-title>
        <v-spacer></v-spacer>
        
        <div class="d-md-none mr-4" @click="$router.push('/profile')">
          <v-avatar size="32" color="deep-purple-lighten-4" class="cursor-pointer">
            <span class="text-caption text-deep-purple font-weight-bold">{{ userStore.userInitials }}</span>
          </v-avatar>
        </div>
      </v-app-bar>
  
      <v-navigation-drawer v-model="drawer" color="deep-purple" theme="dark">
        <v-list nav class="mt-4 main-menu">
          <v-list-item prepend-icon="mdi-view-dashboard" title="Tablero" to="/" />
          <v-list-item prepend-icon="mdi-food-apple" title="Ingredientes" to="/ingredients" />
          <v-list-item prepend-icon="mdi-chef-hat" title="Recetas" to="/recipes" />
          <v-list-item prepend-icon="mdi-target" title="Macros" to="/macros" />
        </v-list>
  
        <template v-slot:append>
          <v-divider></v-divider>
          <v-list-item class="pa-4">
            <template v-slot:prepend>
              <v-avatar color="deep-purple-lighten-4" class="mr-2">
                <span class="text-body-2 text-deep-purple font-weight-bold">{{ userStore.userInitials }}</span>
              </v-avatar>
            </template>
  
            <v-list-item-title class="font-weight-bold">{{ userStore.user?.name }}</v-list-item-title>
            <v-list-item-subtitle>Meta: {{ userStore.user?.macros?.[0]?.kcal || 0 }} kcal</v-list-item-subtitle>
  
            <template v-slot:append>
              <v-menu location="top end" transition="slide-y-reverse-transition">
                <template v-slot:activator="{ props }">
                  <v-btn icon="mdi-dots-vertical" variant="text" v-bind="props"></v-btn>
                </template>
  
                <v-list density="compact" class="rounded-lg">
                  <v-list-item prepend-icon="mdi-account" title="Perfil" to="/profile" />
                  <v-divider></v-divider>
                  <v-list-item 
                    prepend-icon="mdi-logout" 
                    title="Cerrar Sesión" 
                    color="error" 
                    @click="showLogoutDialog = true" 
                  />
                </v-list>
              </v-menu>
            </template>
          </v-list-item>
        </template>
      </v-navigation-drawer>
  
      <v-btn 
        icon 
        color="deep-purple" 
        :class="['d-none d-md-flex btn-toggle', { 'is-open': drawer }]"
        @click="drawer = !drawer"
      >
        <v-icon>{{ drawer ? 'mdi-chevron-left' : 'mdi-chevron-right' }}</v-icon>
      </v-btn>
    </template>
  
    <v-main>
      <v-container fluid :class="userStore.isLoggedIn ? 'pa-6' : 'pa-0 fill-height'">
        <slot />
      </v-container>
    </v-main>
  
    <v-dialog v-model="showLogoutDialog" max-width="400">
      <v-card class="rounded-xl pa-4">
        <v-card-title class="text-h6 text-center">¿Cerrar sesión?</v-card-title>
        <v-card-text class="text-center">
          Estás a punto de salir de tu cuenta. ¿Deseas continuar?
        </v-card-text>
        <v-card-actions class="justify-center">
          <v-btn variant="text" rounded="lg" @click="showLogoutDialog = false">Cancelar</v-btn>
          <v-btn color="error" variant="flat" rounded="lg" class="px-6" @click="confirmLogout">Cerrar Sesión</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </template>
  
  <script setup>
  import { ref } from 'vue'
  import { useRouter } from 'vue-router'
  import { useUserStore } from '@/stores/useUserStore'
  
  const userStore = useUserStore()
  const router = useRouter()
  const drawer = ref(true)
  const showLogoutDialog = ref(false)
  
  const confirmLogout = () => {
    showLogoutDialog.value = false
    userStore.logout(router)
  }
  </script>
  
  <style scoped>
  .logo { font-weight: 900; letter-spacing: -1.5px; font-size: 1.5rem; }
  .logo-light { font-weight: 300; }
  .cursor-pointer { cursor: pointer; }
  .btn-toggle {
    position: fixed; top: 50%; left: 0; z-index: 2000;
    border-radius: 0 50% 50% 0; transform: translateY(-50%);
    transition: left 0.3s ease;
  }
  .btn-toggle.is-open { left: 256px; }
  .main-menu :deep(.v-list-item) { min-height: 50px !important; margin-bottom: 4px; }
  .fill-height { height: 100vh; }
  </style>
