<template>
    <v-fab
      v-show="isVisible"
      :icon="icon"
      :color="color"
      location="bottom end"
      size="large"
      elevation="4"
      class="fab-position mb-4 mr-4 d-md-none"
      @click="$emit('click')"
    ></v-fab>
  </template>
  
  <script setup>
  import { ref, onMounted, onUnmounted } from 'vue';
  
  defineProps({
    icon: { type: String, default: 'mdi-plus' },
    color: { type: String, default: 'deep-purple-accent-4' }
  });
  
  defineEmits(['click']);
  
  const isVisible = ref(true);
  let lastScrollPosition = 0;
  
  const handleScroll = () => {
    const currentScrollPosition = window.scrollY || document.documentElement.scrollTop;
    
    // Evitar disparos negativos en mobile
    if (currentScrollPosition < 0) return;
  
    // Si bajamos más de 10px, ocultar. Si subimos, mostrar.
    if (Math.abs(currentScrollPosition - lastScrollPosition) < 10) return;
    
    isVisible.value = currentScrollPosition < lastScrollPosition;
    lastScrollPosition = currentScrollPosition;
  };
  
  onMounted(() => {
    window.addEventListener('scroll', handleScroll);
  });
  
  onUnmounted(() => {
    window.removeEventListener('scroll', handleScroll);
  });
  </script>
  
  <style scoped>
  .fab-position {
    position: fixed;
    bottom: 32px; /* Un poco más de aire para comodidad */
    right: 24px;
    z-index: 99;
    }
  
  /* Efecto de salida hacia abajo */
  .v-enter-active,
  .v-leave-active {
    transition: transform 0.2s ease;
  }
  
  .v-enter-from,
  .v-leave-to {
    transform: translateY(100px);
  }
  </style>
