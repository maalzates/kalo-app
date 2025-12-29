<template>
  <v-app shadow>
    <v-app-bar color="deep-purple" elevation="0" border="b">
      <v-app-bar-nav-icon @click="drawer = !drawer" />
      <v-app-bar-title class="logo">KALO<span class="logo-light">APP</span></v-app-bar-title>
      <v-spacer class="d-md-none"></v-spacer>
      
      <div class="d-md-none mr-4" @click="$router.push('/profile')">
        <v-avatar size="32" color="deep-purple-lighten-4" class="cursor-pointer">
          <span class="text-caption text-deep-purple font-weight-bold">JD</span>
        </v-avatar>
      </div>
    </v-app-bar>

    <v-navigation-drawer v-model="drawer" color="deep-purple" theme="dark">
      <v-list nav class="mt-4 main-menu">
        <v-list-item prepend-icon="mdi-view-dashboard" title="Tablero" to="/" class="mb-2" />
        <v-list-item prepend-icon="mdi-food-apple" title="Ingredientes" to="/ingredients" class="mb-2" />
        <v-list-item prepend-icon="mdi-chef-hat" title="Recetas" to="/recipes" class="mb-2" />
        <v-list-item prepend-icon="mdi-target" title="Macros" to="/macros" />
        <v-list-item prepend-icon="mdi-login" title="Login" to="/login" />
        <v-list-item prepend-icon="mdi-register" title="Register" to="/register" />
      </v-list>

      <template v-slot:append>
        <v-divider></v-divider>
        <v-list-item
          lines="two"
          title="Juan Dueñas"
          subtitle="Meta: 2,400 kcal"
          class="pa-4 cursor-pointer"
          to="/profile"
        >
          <template v-slot:prepend>
            <v-avatar color="deep-purple-lighten-4" class="mr-n1">
              <span class="text-body-2 text-deep-purple font-weight-bold">JD</span>
            </v-avatar>
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

    <v-main>
      <v-container fluid class="pa-6">
        <router-view />
      </v-container>
    </v-main>
  </v-app>
</template>

<script setup>
import { ref } from 'vue'
const drawer = ref(true)
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
</style>
