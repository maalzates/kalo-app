<template>
  <v-card v-if="user" rounded="xl" elevation="0" border class="mx-auto bg-grey-lighten-4">
    <v-card-text class="pa-4 pa-md-6">
      <v-row>
        <v-col cols="12" md="4">
          <v-card rounded="lg" class="fill-height border-0 pa-6" elevation="0">
            <div class="d-flex flex-column align-center text-center">
              <v-avatar size="100" color="deep-purple-lighten-4" class="mb-4" border>
                <span class="text-h4 text-deep-purple font-weight-bold">
                  {{ userStore.userInitials }}
                </span>
              </v-avatar>
              
              <h2 class="text-h5 font-weight-bold mb-0 text-grey-darken-4">{{ user.name }}</h2>
              <p class="text-body-2 text-medium-emphasis mb-6">{{ user.email }}</p>
              
              <v-btn 
                block 
                color="deep-purple-accent-4" 
                variant="flat" 
                rounded="lg" 
                prepend-icon="mdi-pencil" 
                class="text-none mb-3"
                @click="isModalOpen = true"
              >
                Editar Perfil
              </v-btn>

              <v-btn 
                block 
                color="error" 
                variant="outlined" 
                rounded="lg" 
                prepend-icon="mdi-logout" 
                class="text-none"
                @click="showLogoutDialog = true"
              >
                Cerrar Sesión
              </v-btn>

              <v-divider class="my-6 w-100"></v-divider>

              <div class="w-100 text-left">
                <div class="d-flex align-center mb-4">
                  <v-avatar size="32" color="deep-purple-lighten-5" class="mr-3">
                    <v-icon color="deep-purple-accent-4" size="small">mdi-phone</v-icon>
                  </v-avatar>
                  <div>
                    <div class="text-caption text-grey">Teléfono</div>
                    <div class="text-body-2 font-weight-bold">{{ user.country_code }} {{ user.phone }}</div>
                  </div>
                </div>

                <div class="d-flex align-center">
                  <v-avatar size="32" color="deep-purple-lighten-5" class="mr-3">
                    <v-icon color="deep-purple-accent-4" size="small">mdi-cake-variant</v-icon>
                  </v-avatar>
                  <div>
                    <div class="text-caption text-grey">Nacimiento</div>
                    <div class="text-body-2 font-weight-bold">{{ user.birth_date }}</div>
                  </div>
                </div>
              </div>
            </div>
          </v-card>
        </v-col>

        <v-col cols="12" md="8">
          <v-row>
            <v-col cols="6" sm="4">
              <v-card rounded="lg" border elevation="0" class="pa-4 text-center fill-height">
                <v-icon color="deep-purple-accent-1" class="mb-2">mdi-human-male-height</v-icon>
                <div class="text-h6 font-weight-black">{{ user.height }} <small class="text-caption">cm</small></div>
                <div class="text-caption text-uppercase font-weight-bold text-grey">Altura</div>
              </v-card>
            </v-col>

            <v-col cols="6" sm="4">
              <v-card rounded="lg" border elevation="0" class="pa-4 text-center fill-height">
                <v-icon color="deep-purple-accent-1" class="mb-2">mdi-weight-kilogram</v-icon>
                <div class="text-h6 font-weight-black">{{ currentWeight }} <small class="text-caption">kg</small></div>
                <div class="text-caption text-uppercase font-weight-bold text-grey">Peso Actual</div>
              </v-card>
            </v-col>

            <v-col cols="12" sm="4">
              <v-card rounded="lg" border elevation="0" class="pa-4 text-center fill-height">
                <v-icon color="deep-purple-accent-1" class="mb-2">mdi-gender-male-female</v-icon>
                <div class="text-h6 font-weight-black">{{ user.gender === 'male' ? 'Hombre' : 'Mujer' }}</div>
                <div class="text-caption text-uppercase font-weight-bold text-grey">Género</div>
              </v-card>
            </v-col>

            <v-col cols="12" sm="6">
              <v-card rounded="lg" variant="tonal" color="deep-purple-accent-4" class="pa-5 fill-height">
                <div class="d-flex align-center mb-2">
                  <v-icon class="mr-2" size="small">mdi-run-fast</v-icon>
                  <span class="text-overline font-weight-bold">Nivel de Actividad</span>
                </div>
                <div class="text-h5 text-capitalize font-weight-bold mb-1">{{ getActivityLabel(user.activity_level) }}</div>
                <div class="text-body-2 opacity-70">Gasto calórico diario basado en tu movimiento.</div>
              </v-card>
            </v-col>

            <v-col cols="12" sm="6">
              <v-card rounded="lg" variant="flat" color="deep-purple-accent-4" theme="dark" class="pa-5 fill-height">
                <div class="d-flex align-center mb-2">
                  <v-icon class="mr-2" size="small">mdi-target</v-icon>
                  <span class="text-overline font-weight-bold text-white">Objetivo Meta</span>
                </div>
                <div class="text-h5 text-uppercase font-weight-black mb-1">
                  {{ user.goal_type?.replace('_', ' ') }}
                </div>
                <div class="text-body-2 opacity-80">
                  Cálculo: <span class="text-capitalize">{{ user.macro_calculation_mode }}</span>
                </div>
              </v-card>
            </v-col>
          </v-row>
        </v-col>
      </v-row>
    </v-card-text>

    <EditUserInfo 
      v-model="isModalOpen" 
      :user-data="user"
      @save="handleSave"
    />

    <v-dialog v-model="showLogoutDialog" max-width="400">
      <v-card class="rounded-xl pa-4">
        <v-card-title class="text-h6 text-center font-weight-bold">¿Cerrar sesión?</v-card-title>
        <v-card-text class="text-center text-grey-darken-1">
          Estás a punto de salir de tu cuenta. ¿Deseas continuar?
        </v-card-text>
        <v-card-actions class="justify-center">
          <v-btn variant="text" rounded="lg" class="text-none" @click="showLogoutDialog = false">Cancelar</v-btn>
          <v-btn color="error" variant="flat" rounded="lg" class="px-6 text-none" @click="confirmLogout">Cerrar Sesión</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-card>
</template>

<script setup>
import { computed, ref } from 'vue';
import { useRouter } from 'vue-router';
import { useUserStore } from '@/stores/useUserStore';
import { useActivityLevel } from '@/composables/useActivityLevel';
import usersRepository from '@/repositories/usersRepository.js';
import EditUserInfo from '@/components/user/EditUserInfo.vue';

const userStore = useUserStore();
const router = useRouter();
const { getActivityLabel } = useActivityLevel();
const user = computed(() => userStore.user);
const isModalOpen = ref(false);
const showLogoutDialog = ref(false);

const currentWeight = computed(() => {
  return user.value.weight;
});

const confirmLogout = () => {
  showLogoutDialog.value = false;
  userStore.logout(router);
};

const handleSave = async (userData) => {
  try {
    if (!user.value?.id) return;
    const updatedUser = await usersRepository.updateUser(user.value.id, userData);
    userStore.setAuth(updatedUser, userStore.token);
  } catch (error) {
    console.error('Error updating user:', error);
  }
};
</script>
