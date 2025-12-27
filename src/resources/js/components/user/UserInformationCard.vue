<template>
    <v-card v-if="user" rounded="xl" elevation="2" border>
      <v-list-item class="pa-6">
        <template v-slot:prepend>
          <v-avatar size="70" border>
            <v-img src="https://randomuser.me/api/portraits/men/1.jpg"></v-img>
          </v-avatar>
        </template>
        
        <v-list-item-title class="text-h6 font-weight-bold">
          {{ user.name }}
        </v-list-item-title>
        <v-list-item-subtitle>{{ user.email }}</v-list-item-subtitle>
  
        <template v-slot:append>
          <v-btn icon="mdi-cog" variant="text" color="grey" @click="$emit('open-edit')"></v-btn>
        </template>
      </v-list-item>
  
      <v-divider></v-divider>
  
      <v-card-text class="pa-6">
        <v-row>
          <v-col cols="12" sm="6">
            <v-list density="compact" class="pa-0">
              <v-list-item class="px-0" prepend-icon="mdi-phone" :title="user.country_code + ' ' + user.cellphone" subtitle="Contacto" />
              <v-list-item class="px-0" prepend-icon="mdi-cake-variant" :title="user.birth_date" subtitle="Nacimiento" />
              <v-list-item class="px-0" prepend-icon="mdi-run" :title="user.activity_level" subtitle="Actividad" />
            </v-list>
          </v-col>
  
          <v-col cols="12" sm="6">
            <div class="d-flex flex-wrap gap-2">
              <v-chip prepend-icon="mdi-arrow-up" variant="tonal" color="deep-purple" class="ma-1">
                {{ user.height }} cm
              </v-chip>
              <v-chip prepend-icon="mdi-weight-kilogram" variant="tonal" color="deep-purple" class="ma-1">
                {{ currentWeight }} kg
              </v-chip>
              <v-chip prepend-icon="mdi-gender-male-female" variant="tonal" color="deep-purple" class="ma-1">
                {{ user.gender === 'male' ? 'Hombre' : 'Mujer' }}
              </v-chip>
            </div>
            
            <v-alert
              density="compact"
              color="deep-purple-accent-4"
              theme="dark"
              icon="mdi-target"
              rounded="lg"
              class="mt-4"
            >
              <div class="text-caption">Objetivo</div>
              <div class="font-weight-bold text-uppercase">{{ user.goal_type?.replace('_', ' ') }}</div>
            </v-alert>
          </v-col>
        </v-row>
      </v-card-text>
    </v-card>
  </template>
  
  <script setup>
  import { computed } from 'vue';
  import { useUserStore } from '@/stores/useUserStore';
  
  const userStore = useUserStore();
  const emit = defineEmits(['open-edit']);
  const user = computed(() => userStore.authenticatedUser);
  
  const currentWeight = computed(() => {
    if (!user.value?.biometrics?.length) return '--';
    return user.value.biometrics[user.value.biometrics.length - 1].weight;
  });
  </script>
