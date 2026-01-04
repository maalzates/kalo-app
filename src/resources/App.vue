<template>
  <v-app>
    <MenuLayout v-if="userStore.isLoggedIn">
      <router-view />
    </MenuLayout>
    <router-view v-else />
  </v-app>
</template>

<script setup>
import MenuLayout from '@/layouts/MenuLayout.vue'
import { useUserStore } from '@/stores/useUserStore'

const userStore = useUserStore()
</script>
