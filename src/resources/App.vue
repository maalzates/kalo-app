<template>
  <v-app shadow>
    <v-app-bar color="deep-purple" elevation="0" border="b">
      <v-app-bar-nav-icon class="d-md-none" @click="drawer = !drawer" />
      <v-app-bar-title class="logo">KALO<span class="logo-light">APP</span></v-app-bar-title>
    </v-app-bar>

    <v-navigation-drawer v-model="drawer" color="deep-purple" theme="dark">
      <v-list nav class="mt-4 main-menu">
        <v-list-item prepend-icon="mdi-view-dashboard" title="Diario" to="/" class="mb-2" />
        <v-list-item prepend-icon="mdi-target" title="Objetivos" to="/goals" />
      </v-list>
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

/* Botón Toggle Inteligente */
.btn-toggle {
  position: fixed;
  top: 50%;
  left: 0;
  z-index: 2000;
  border-radius: 0 50% 50% 0;
  transform: translateY(-50%);
  transition: left 0.3s ease; /* Sincronizado con la animación del drawer */
}

/* Cuando el menú está abierto, el botón se desplaza con él */
.btn-toggle.is-open {
  left: 256px; /* Ancho por defecto del drawer de Vuetify */
}

.main-menu :deep(.v-list-item) { min-height: 56px !important; }
.main-menu :deep(.v-list-item-title) { font-size: 1.1rem !important; font-weight: 500; }
.main-menu :deep(.v-icon) { font-size: 1.5rem !important; }
</style>
