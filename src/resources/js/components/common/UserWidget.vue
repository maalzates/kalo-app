<template>
    <div :class="['user-widget d-flex align-center cursor-pointer', customClass]" @click="$router.push('/profile')">
      <v-avatar size="40" color="deep-purple-lighten-4" class="elevation-1">
        <span class="text-deep-purple font-weight-bold">JD</span>
      </v-avatar>
      
      <div v-if="showName" class="ml-3 text-left">
        <div class="text-subtitle-2 font-weight-bold leading-tight">Juan Dueñas</div>
        <div class="text-caption text-grey-lighten-1">Meta: 2,400 kcal</div>
      </div>
    </div>
  </template>
  
  <script setup>
  defineProps({
    showName: { type: Boolean, default: true },
    customClass: String
  });
  </script>
  
  <style scoped>
  .leading-tight { line-height: 1.2; }
  .cursor-pointer { cursor: pointer; }
  </style>
