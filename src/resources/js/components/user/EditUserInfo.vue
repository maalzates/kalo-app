<template>
    <v-dialog
      :model-value="modelValue"
      @update:model-value="$emit('update:modelValue', $event)"
      fullscreen
      transition="dialog-bottom-transition"
    >
      <v-card class="bg-grey-lighten-4">
        <v-toolbar color="deep-purple-accent-4" dark px-4>
          <v-btn icon @click="$emit('update:modelValue', false)">
            <v-icon>mdi-close</v-icon>
          </v-btn>
          <v-toolbar-title class="font-weight-bold">Editar Perfil</v-toolbar-title>
          <v-spacer></v-spacer>
          <v-btn 
            variant="flat" 
            color="white" 
            class="text-deep-purple font-weight-bold rounded-pill px-6"
            @click="save"
          >
            Guardar
          </v-btn>
        </v-toolbar>
  
        <v-container class="pa-4 mt-2" style="max-width: 600px">
          <div class="text-overline mb-2 ml-2">Datos Personales</div>
          <v-card rounded="xl" class="pa-4 mb-4" border flat>
            <v-text-field
              v-model="form.name"
              label="Nombre completo"
              variant="outlined"
              rounded="lg"
              density="comfortable"
              class="mb-2"
            ></v-text-field>
            
            <v-row dense>
              <v-col cols="4">
                <v-text-field
                  v-model="form.country_code"
                  label="Cod."
                  prefix="+"
                  variant="outlined"
                  rounded="lg"
                  density="comfortable"
                ></v-text-field>
              </v-col>
              <v-col cols="8">
                <v-text-field
                  v-model="form.phone"
                  label="Celular"
                  variant="outlined"
                  rounded="lg"
                  density="comfortable"
                ></v-text-field>
              </v-col>
            </v-row>
          </v-card>
  
          <div class="text-overline mb-2 ml-2">Fisiología</div>
          <v-card rounded="xl" class="pa-4 mb-4" border flat>
            <v-row dense>
              <v-col cols="12">
                <v-select
                  v-model="form.gender"
                  label="Género"
                  :items="[{title: 'Hombre', value: 'male'}, {title: 'Mujer', value: 'female'}]"
                  variant="outlined"
                  rounded="lg"
                  density="comfortable"
                ></v-select>
              </v-col>
              <v-col cols="6">
                <v-text-field
                  v-model.number="form.height"
                  label="Altura (cm)"
                  type="number"
                  variant="outlined"
                  rounded="lg"
                  density="comfortable"
                ></v-text-field>
              </v-col>
              <v-col cols="6">
                <v-text-field
                  v-model="form.birth_date"
                  label="F. Nacimiento"
                  type="date"
                  variant="outlined"
                  rounded="lg"
                  density="comfortable"
                ></v-text-field>
              </v-col>
            </v-row>
          </v-card>
  
          <div class="text-overline mb-2 ml-2">Plan y Actividad</div>
          <v-card rounded="xl" class="pa-4 mb-4" border flat>
            <v-select
              v-model="form.activity_level"
              label="Nivel de Actividad"
              :items="activityLevelOptions"
              variant="outlined"
              rounded="lg"
              density="comfortable"
              class="mb-2"
            ></v-select>
            <v-select
              v-model="form.goal_type"
              label="Objetivo"
              :items="[
                {title: 'Perder Grasa', value: 'cut'},
                {title: 'Mantener', value: 'maintain'},
                {title: 'Ganar Músculo', value: 'grow'}
              ]"
              variant="outlined"
              rounded="lg"
              density="comfortable"
            ></v-select>
          </v-card>

          <div class="text-overline mb-2 ml-2">Seguridad</div>
          <v-card rounded="xl" class="pa-4 mb-4" border flat>
            <v-text-field
              v-model="form.current_password"
              label="Contraseña actual"
              :type="showCurrentPassword ? 'text' : 'password'"
              variant="outlined"
              rounded="lg"
              density="comfortable"
              class="mb-2"
              hint="Solo necesaria si deseas cambiar tu contraseña"
              persistent-hint
            >
              <template v-slot:append-inner>
                <v-icon 
                  :icon="showCurrentPassword ? 'mdi-eye-off' : 'mdi-eye'" 
                  @click="showCurrentPassword = !showCurrentPassword" 
                  size="small" 
                  color="grey"
                ></v-icon>
              </template>
            </v-text-field>
            
            <v-text-field
              v-model="form.new_password"
              label="Nueva contraseña"
              :type="showNewPassword ? 'text' : 'password'"
              variant="outlined"
              rounded="lg"
              density="comfortable"
              class="mb-2"
            >
              <template v-slot:append-inner>
                <v-icon 
                  :icon="showNewPassword ? 'mdi-eye-off' : 'mdi-eye'" 
                  @click="showNewPassword = !showNewPassword" 
                  size="small" 
                  color="grey"
                ></v-icon>
              </template>
            </v-text-field>
            
            <v-text-field
              v-model="form.new_password_confirmation"
              label="Confirmar nueva contraseña"
              :type="showConfirmPassword ? 'text' : 'password'"
              variant="outlined"
              rounded="lg"
              density="comfortable"
            >
              <template v-slot:append-inner>
                <v-icon 
                  :icon="showConfirmPassword ? 'mdi-eye-off' : 'mdi-eye'" 
                  @click="showConfirmPassword = !showConfirmPassword" 
                  size="small" 
                  color="grey"
                ></v-icon>
              </template>
            </v-text-field>
          </v-card>
        </v-container>
      </v-card>
    </v-dialog>
  </template>
  
  <script setup>
  import { reactive, ref, watch } from 'vue';
  import { useActivityLevel } from '@/composables/useActivityLevel';

  const props = defineProps({
    modelValue: Boolean,
    userData: Object
  });

  const emit = defineEmits(['update:modelValue', 'save']);

  const { getActivityOptions } = useActivityLevel();

  // Estado para mostrar/ocultar contraseñas
  const showCurrentPassword = ref(false);
  const showNewPassword = ref(false);
  const showConfirmPassword = ref(false);

  // Opciones de activity level
  const activityLevelOptions = getActivityOptions().map(option => ({
    title: option.label,
    value: option.value
  }));
  
  // Estado local para editar sin afectar la store directamente
  const form = reactive({
    name: '',
    country_code: '',
    phone: '',
    gender: '',
    height: 0,
    birth_date: '',
    activity_level: null,
    goal_type: '',
    current_password: '',
    new_password: '',
    new_password_confirmation: ''
  });
  
  watch(() => props.modelValue, (isOpen) => {
    if (isOpen && props.userData) {
      const userDataCopy = JSON.parse(JSON.stringify(props.userData));
      // No copiar campos de contraseña del usuario (nunca se envían desde el backend)
      Object.assign(form, {
        name: userDataCopy.name || '',
        country_code: userDataCopy.country_code || '',
        phone: userDataCopy.phone || '',
        gender: userDataCopy.gender || '',
        height: userDataCopy.height || 0,
        birth_date: userDataCopy.birth_date || '',
        activity_level: userDataCopy.activity_level || null,
        goal_type: userDataCopy.goal_type || '',
        current_password: '',
        new_password: '',
        new_password_confirmation: ''
      });
    } else {
      // Limpiar campos de contraseña al cerrar
      form.current_password = '';
      form.new_password = '';
      form.new_password_confirmation = '';
    }
  });
  
  const save = () => {
    // Solo incluir campos de contraseña si se está intentando cambiar la contraseña
    const dataToSave = { ...form };
    
    // Si no hay nueva contraseña, eliminar todos los campos de contraseña
    if (!dataToSave.new_password) {
      delete dataToSave.current_password;
      delete dataToSave.new_password;
      delete dataToSave.new_password_confirmation;
    }
    
    emit('save', dataToSave);
    emit('update:modelValue', false);
  };
  </script>
