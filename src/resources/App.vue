<template>
  <v-app>
    <MenuLayout>
      <router-view />
    </MenuLayout>
  </v-app>
</template>

<script setup>
import MenuLayout from '@/layouts/MenuLayout.vue'
</script>
