<template>
  <v-app>
    <MenuLayout v-if="userStore.isLoggedIn && !shouldHideLayout">
      <router-view />
    </MenuLayout>
    <router-view v-else />
  </v-app>
</template>

<script setup>
import { computed } from 'vue'
import { useRoute } from 'vue-router'
import MenuLayout from '@/layouts/MenuLayout.vue'
import { useUserStore } from '@/stores/useUserStore'

const userStore = useUserStore()
const route = useRoute()

const shouldHideLayout = computed(() => {
  return route.meta.hideLayout === true
})
</script>
