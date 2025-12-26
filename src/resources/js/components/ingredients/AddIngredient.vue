<template>
    <v-dialog 
      :model-value="modelValue" 
      @update:model-value="$emit('update:modelValue', $event)" 
      max-width="500px"
    >
      <v-card elevation="2" rounded="xl" class="pa-4">
        <v-card-item class="pa-0 mb-4">
          <v-card-title class="text-h6 font-weight-bold">
            {{ isEditing ? 'Editar Ingrediente' : 'Nuevo Ingrediente' }}
          </v-card-title>
          <v-card-subtitle>
            Define los valores nutricionales base
          </v-card-subtitle>
        </v-card-item>
        <v-card-text class="pa-0">
          <v-form ref="formRef">
            <v-text-field
              v-model="form.name"
              label="Nombre del alimento"
              placeholder="Ej: Pechuga de Pollo"
              variant="outlined"
              density="compact"
              class="mb-2"
              rounded="lg"
            ></v-text-field>
  
            <v-row>
              <v-col cols="7">
                <v-text-field
                  v-model.number="form.base_amount"
                  label="Cantidad base"
                  type="number"
                  variant="outlined"
                  density="compact"
                  rounded="lg"
                ></v-text-field>
              </v-col>
              <v-col cols="5">
                <v-select
                  v-model="form.base_unit"
                  :items="['g', 'ml', 'un']"
                  label="Unidad"
                  variant="outlined"
                  density="compact"
                  rounded="lg"
                ></v-select>
              </v-col>
            </v-row>
  
            <v-divider class="my-4"></v-divider>
            <div class="text-subtitle-2 mb-3 text-grey-darken-1">Información Nutricional (por la base)</div>
  
            <v-row>
              <v-col cols="6">
                <v-text-field
                  v-model.number="form.calories"
                  label="Calorías (kcal)"
                  type="number"
                  variant="outlined"
                  density="compact"
                  rounded="lg"
                  prepend-inner-icon="mdi-fire"
                  color="orange-darken-2"
                ></v-text-field>
              </v-col>
              <v-col cols="6">
                <v-text-field
                  v-model.number="form.protein"
                  label="Proteína (g)"
                  type="number"
                  variant="outlined"
                  density="compact"
                  rounded="lg"
                  prepend-inner-icon="mdi-arm-flex"
                  color="deep-purple-accent-4"
                ></v-text-field>
              </v-col>
              <v-col cols="6">
                <v-text-field
                  v-model.number="form.carbs"
                  label="Carbohidratos (g)"
                  type="number"
                  variant="outlined"
                  density="compact"
                  rounded="lg"
                  prepend-inner-icon="mdi-wheat"
                  color="orange-darken-1"
                ></v-text-field>
              </v-col>
              <v-col cols="6">
                <v-text-field
                  v-model.number="form.fat"
                  label="Grasas (g)"
                  type="number"
                  variant="outlined"
                  density="compact"
                  rounded="lg"
                  prepend-inner-icon="mdi-water-outline"
                  color="cyan-darken-1"
                ></v-text-field>
              </v-col>
            </v-row>
          </v-form>
        </v-card-text>
  
        <v-card-actions class="px-0 pt-4">
          <v-spacer></v-spacer>
          <v-btn 
            variant="text" 
            color="grey-darken-1" 
            @click="$emit('update:modelValue', false)"
          >
            Cancelar
          </v-btn>
          <v-btn 
            color="deep-purple-accent-4" 
            variant="flat" 
            class="px-8" 
            rounded="pill"
            @click="handleSubmit"
          >
            {{ isEditing ? 'Guardar Cambios' : 'Crear' }}
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </template>
  
  <script setup>
  import { ref, reactive, watch } from 'vue';
  
  const props = defineProps({
    modelValue: Boolean,
    initialData: Object // Si viene data, es modo edición
  });
  
  const emit = defineEmits(['update:modelValue', 'save']);
  
  const isEditing = ref(false);
  
  const form = reactive({
    name: '',
    base_amount: 100,
    base_unit: 'g',
    calories: 0,
    protein: 0,
    carbs: 0,
    fat: 0
  });
  
  // Resetear o cargar datos al abrir
  watch(() => props.modelValue, (isOpen) => {
    if (isOpen) {
      if (props.initialData) {
        isEditing.value = true;
        Object.assign(form, props.initialData);
      } else {
        isEditing.value = false;
        resetForm();
      }
    }
  });
  
  const resetForm = () => {
    form.name = '';
    form.base_amount = 100;
    form.base_unit = 'g';
    form.calories = 0;
    form.protein = 0;
    form.carbs = 0;
    form.fat = 0;
  };
  
  const handleSubmit = () => {
    // Aquí podrías añadir validaciones básicas
    emit('save', { ...form });
    emit('update:modelValue', false);
  };
  </script>
