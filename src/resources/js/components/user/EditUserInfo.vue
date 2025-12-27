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
                  v-model="form.cellphone"
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
              :items="['Sedentario', 'Ligero', 'Moderado', 'Muy Activo']"
              variant="outlined"
              rounded="lg"
              density="comfortable"
              class="mb-2"
            ></v-select>
            <v-select
              v-model="form.goal_type"
              label="Objetivo"
              :items="[
                {title: 'Perder Grasa', value: 'fat_loss'},
                {title: 'Mantener', value: 'maintenance'},
                {title: 'Ganar Músculo', value: 'muscle_gain'}
              ]"
              variant="outlined"
              rounded="lg"
              density="comfortable"
            ></v-select>
          </v-card>
        </v-container>
      </v-card>
    </v-dialog>
  </template>
  
  <script setup>
  import { reactive, watch } from 'vue';
  
  const props = defineProps({
    modelValue: Boolean,
    userData: Object
  });
  
  const emit = defineEmits(['update:modelValue', 'save']);
  
  // Estado local para editar sin afectar la store directamente
  const form = reactive({
    name: '',
    country_code: '',
    cellphone: '',
    gender: '',
    height: 0,
    birth_date: '',
    activity_level: '',
    goal_type: ''
  });
  
  watch(() => props.modelValue, (isOpen) => {
    if (isOpen && props.userData) {
      Object.assign(form, JSON.parse(JSON.stringify(props.userData)));
    }
  });
  
  const save = () => {
    emit('save', { ...form });
    emit('update:modelValue', false);
  };
  </script>
