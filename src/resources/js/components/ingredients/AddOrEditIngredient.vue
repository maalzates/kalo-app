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
        <div class="text-body-1 text-sm-h6 font-weight-bold text-wrap line-height-1 pl-2">

          {{ isEditing ? "Editar Ingrediente" : "Nuevo Ingrediente" }}
        </div>
        <v-spacer></v-spacer>
        <v-btn
          variant="flat"
          color="white"
          class="text-deep-purple-accent-4 font-weight-bold rounded-pill px-6"
          @click="handleSubmit"
          :disabled="!isFormValid"
        >
          Guardar
        </v-btn>
      </v-toolbar>

      <v-container class="pa-4" style="max-width: 800px">
        <v-card rounded="xl" class="pa-5 mb-4" elevation="1" border="sm">
          <div class="text-subtitle-2 mb-3 ml-1 font-weight-bold text-grey-darken-2 uppercase">
            Información General
          </div>
          <v-text-field
            v-model="form.name"
            label="Nombre del alimento"
            placeholder="Ej: Pechuga de Pollo"
            variant="outlined"
            rounded="lg"
            color="deep-purple-accent-4"
            prepend-inner-icon="mdi-food-apple-outline"
          ></v-text-field>
        </v-card>

        <v-card rounded="xl" class="pa-5 mb-4" elevation="1" border="sm">
          <div class="text-subtitle-2 mb-3 ml-1 font-weight-bold text-grey-darken-2 uppercase">
            Porción de Referencia
          </div>
          <v-row dense>
            <v-col cols="7">
              <v-text-field
                v-model.number="form.base_amount"
                label="Cantidad"
                type="number"
                variant="outlined"
                rounded="lg"
                color="deep-purple-accent-4"
                suffix=""
              ></v-text-field>
            </v-col>
            <v-col cols="5">
              <v-select
                v-model="form.base_unit"
                :items="['g', 'ml', 'un']"
                label="Unidad"
                variant="outlined"
                rounded="lg"
                color="deep-purple-accent-4"
              ></v-select>
            </v-col>
          </v-row>
          <div class="text-caption text-grey-darken-1 ml-1">
            * Indica la cantidad sobre la cual registrarás los nutrientes (ej: por cada 100g).
          </div>
        </v-card>

        <div class="text-overline mb-2 ml-2 text-deep-purple-accent-4 font-weight-bold">
          Información Nutricional
        </div>

        <v-card rounded="xl" class="pa-5 mb-4" elevation="1" border="sm">
          <v-row dense>
            <v-col cols="12" sm="6">
              <v-text-field
                v-model.number="form.calories"
                label="Calorías (kcal)"
                type="number"
                variant="outlined"
                rounded="lg"
                prepend-inner-icon="mdi-fire"
                color="deep-purple-accent-4"
              ></v-text-field>
            </v-col>
            <v-col cols="12" sm="6">
              <v-text-field
                v-model.number="form.protein"
                label="Proteína (g)"
                type="number"
                variant="outlined"
                rounded="lg"
                prepend-inner-icon="mdi-arm-flex"
                color="deep-purple-accent-4"
              ></v-text-field>
            </v-col>
            <v-col cols="12" sm="6">
              <v-text-field
                v-model.number="form.carbs"
                label="Carbohidratos (g)"
                type="number"
                variant="outlined"
                rounded="lg"
                prepend-inner-icon="mdi-wheat"
                color="deep-purple-accent-4"
              ></v-text-field>
            </v-col>
            <v-col cols="12" sm="6">
              <v-text-field
                v-model.number="form.fat"
                label="Grasas (g)"
                type="number"
                variant="outlined"
                rounded="lg"
                prepend-inner-icon="mdi-water-outline"
                color="deep-purple-accent-4"
              ></v-text-field>
            </v-col>
          </v-row>
        </v-card>

        <v-card variant="tonal" color="deep-purple-accent-4" rounded="xl" class="pa-4 text-center mt-4">
          <div class="text-body-2 font-weight-medium">
            Los valores ingresados se guardarán como referencia para tu base de datos personal.
          </div>
        </v-card>
      </v-container>
    </v-card>
  </v-dialog>
</template>

<script setup>
import { ref, reactive, watch, computed } from 'vue';

const props = defineProps({
  modelValue: Boolean,
  initialData: Object
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

// Validación: El nombre debe existir y la base no puede ser 0
const isFormValid = computed(() => {
  return form.name?.trim().length >= 2 && form.base_amount > 0;
});

watch(() => props.modelValue, (isOpen) => {
  if (isOpen) {
    if (props.initialData) {
      isEditing.value = true;
      Object.assign(form, JSON.parse(JSON.stringify(props.initialData)));
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
  emit('save', { ...form });
  emit('update:modelValue', false);
};
</script>

<style scoped>
/* Consistencia en los inputs de número */
:deep(input::-webkit-outer-spin-button),
:deep(input::-webkit-inner-spin-button) {
  -webkit-appearance: none;
  margin: 0;
}
:deep(input[type="number"]) {
  -moz-appearance: textfield;
}

.uppercase {
  text-transform: uppercase;
  letter-spacing: 1px;
  font-size: 0.7rem !important;
}
</style>
