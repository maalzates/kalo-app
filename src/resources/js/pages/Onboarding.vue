<template>
  <v-container class="fill-height bg-grey-lighten-4" fluid>
    <v-row justify="center" align="center">
      <v-col cols="12" sm="10" md="8" lg="6" xl="5">
        <!-- Paso 1: Pantalla de Bienvenida -->
        <v-card v-if="currentStep === 0" rounded="xl" class="pa-8 text-center" elevation="4">
          <v-icon size="100" color="deep-purple-accent-4" class="mb-6">mdi-account-heart</v-icon>
          <h1 class="text-h4 font-weight-bold mb-4">¡Bienvenido a Kalo App!</h1>
          <p class="text-body-1 text-grey-darken-1 mb-6">
            Para comenzar, necesitamos conocer algunos datos básicos sobre ti.
            Esto nos permitirá calcular tus macros personalizados y ayudarte a alcanzar tus objetivos.
          </p>
          <v-btn
            color="deep-purple-accent-4"
            variant="flat"
            size="large"
            rounded="pill"
            class="font-weight-bold px-8"
            @click="currentStep = 1"
          >
            Comenzar
          </v-btn>
        </v-card>

        <!-- Paso 2: Formulario de Datos -->
        <v-card v-if="currentStep === 1" rounded="xl" class="pa-6" elevation="4">
          <v-card-title class="text-h5 font-weight-bold mb-4 px-0">
            Cuéntanos sobre ti
          </v-card-title>

          <v-form ref="formRef" v-model="isFormValid">
            <v-row dense>
              <!-- Peso -->
              <v-col cols="12" sm="6">
                <v-text-field
                  v-model.number="formData.weight"
                  label="Peso"
                  type="number"
                  suffix="kg"
                  variant="outlined"
                  rounded="lg"
                  color="deep-purple-accent-4"
                  prepend-inner-icon="mdi-weight-kilogram"
                  :rules="[rules.required, rules.positive]"
                ></v-text-field>
              </v-col>

              <!-- Altura -->
              <v-col cols="12" sm="6">
                <v-text-field
                  v-model.number="formData.height"
                  label="Altura"
                  type="number"
                  suffix="cm"
                  variant="outlined"
                  rounded="lg"
                  color="deep-purple-accent-4"
                  prepend-inner-icon="mdi-human-male-height"
                  :rules="[rules.required, rules.positive]"
                ></v-text-field>
              </v-col>

              <!-- Fecha de Nacimiento -->
              <v-col cols="12" sm="6">
                <v-text-field
                  v-model="formData.birth_date"
                  label="Fecha de Nacimiento"
                  type="date"
                  variant="outlined"
                  rounded="lg"
                  color="deep-purple-accent-4"
                  prepend-inner-icon="mdi-calendar"
                  :rules="[rules.required]"
                ></v-text-field>
              </v-col>

              <!-- Género -->
              <v-col cols="12" sm="6">
                <v-select
                  v-model="formData.gender"
                  :items="genderOptions"
                  item-title="label"
                  item-value="value"
                  label="Género"
                  variant="outlined"
                  rounded="lg"
                  color="deep-purple-accent-4"
                  prepend-inner-icon="mdi-gender-male-female"
                  :rules="[rules.required]"
                ></v-select>
              </v-col>

              <!-- Nivel de Actividad -->
              <v-col cols="12">
                <v-select
                  v-model="formData.activity_level"
                  :items="activityLevels"
                  item-title="label"
                  item-value="value"
                  label="Nivel de Actividad"
                  variant="outlined"
                  rounded="lg"
                  color="deep-purple-accent-4"
                  prepend-inner-icon="mdi-run"
                  :rules="[rules.required]"
                >
                  <template #item="{ props, item }">
                    <v-list-item v-bind="props">
                      <v-list-item-subtitle class="text-caption text-wrap">
                        {{ item.raw.description }}
                      </v-list-item-subtitle>
                    </v-list-item>
                  </template>
                </v-select>
              </v-col>

              <!-- Objetivo -->
              <v-col cols="12">
                <v-select
                  v-model="formData.goal_type"
                  :items="goalOptions"
                  item-title="label"
                  item-value="value"
                  label="Objetivo"
                  variant="outlined"
                  rounded="lg"
                  color="deep-purple-accent-4"
                  prepend-inner-icon="mdi-target"
                  :rules="[rules.required]"
                >
                  <template #item="{ props, item }">
                    <v-list-item v-bind="props">
                      <v-list-item-subtitle class="text-caption">
                        {{ item.raw.description }}
                      </v-list-item-subtitle>
                    </v-list-item>
                  </template>
                </v-select>
              </v-col>
            </v-row>

            <v-btn
              color="deep-purple-accent-4"
              variant="flat"
              size="large"
              rounded="pill"
              class="font-weight-bold mt-4"
              block
              :disabled="!isFormValid || loading"
              :loading="loading"
              @click="saveProfile"
            >
              Completar Configuración
            </v-btn>
          </v-form>
        </v-card>

      </v-col>
    </v-row>
  </v-container>
</template>

<script setup>
import { ref, reactive } from 'vue';
import { useRouter } from 'vue-router';
import { useUserStore } from '@/stores/useUserStore';
import { useActivityLevel } from '@/composables/useActivityLevel';
import { useToast } from 'vue-toastification';

const router = useRouter();
const userStore = useUserStore();
const toast = useToast();
const { getActivityOptions } = useActivityLevel();

const currentStep = ref(0);
const isFormValid = ref(false);
const loading = ref(false);
const formRef = ref(null);

const formData = reactive({
  weight: null,
  height: null,
  birth_date: '',
  gender: '',
  activity_level: null,
  goal_type: ''
});

const genderOptions = [
  { label: 'Masculino', value: 'male' },
  { label: 'Femenino', value: 'female' }
];

const activityLevels = getActivityOptions();

const goalOptions = [
  {
    label: 'Perder Peso',
    value: 'cut',
    description: 'Déficit calórico de 500 kcal'
  },
  {
    label: 'Mantener Peso',
    value: 'maintain',
    description: 'Mantener peso actual'
  },
  {
    label: 'Ganar Músculo',
    value: 'grow',
    description: 'Superávit calórico de 300 kcal'
  }
];

const rules = {
  required: value => !!value || 'Campo requerido',
  positive: value => value > 0 || 'Debe ser mayor a 0'
};

const saveProfile = async () => {
  loading.value = true;
  try {
    await userStore.updateProfile(formData);
    toast.success('¡Configuración completada! Bienvenido a Kalo App');
    router.push('/');
  } catch (error) {
    console.error(error);
    toast.error('Error al guardar el perfil. Intenta de nuevo.');
  } finally {
    loading.value = false;
  }
};
</script>

<style scoped>
.fill-height {
  min-height: 100vh;
}
</style>
