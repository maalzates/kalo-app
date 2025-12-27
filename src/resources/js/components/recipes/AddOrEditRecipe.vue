<template>
  <v-dialog
      :model-value="modelValue"
      @update:model-value="$emit('update:modelValue', $event)"
      fullscreen
      transition="dialog-bottom-transition"
  >
      <v-card class="bg-grey-lighten-4">
          <v-toolbar color="deep-purple-accent-4" dark>
              <v-btn icon @click="$emit('update:modelValue', false)">
                  <v-icon>mdi-close</v-icon>
              </v-btn>
              <v-toolbar-title class="font-weight-bold">
                  {{ isEditing ? "Editar Receta" : "Nueva Receta" }}
              </v-toolbar-title>
              <v-spacer></v-spacer>
              <v-btn
                  variant="flat"
                  color="white"
                  class="text-deep-purple-accent-4 font-weight-bold rounded-pill px-6"
                  @click="saveRecipe"
                  :disabled="!isFormValid"
              >
                  Guardar
              </v-btn>
          </v-toolbar>

          <v-container class="pa-4" style="max-width: 800px">
              <v-card rounded="xl" class="pa-4 mb-4" elevation="1" border="sm">
                  <v-text-field
                      v-model="recipeForm.name"
                      label="Nombre de la receta"
                      placeholder="Ej: Ensalada César Fitness"
                      variant="outlined"
                      hide-details
                      rounded="lg"
                      color="deep-purple-accent-4"
                  ></v-text-field>
              </v-card>

              <v-card rounded="xl" class="pa-4 mb-4" elevation="1" border="sm">
                  <div class="text-subtitle-2 mb-2 ml-1 font-weight-bold text-grey-darken-2">
                      Añadir Ingredientes
                  </div>
                  <v-autocomplete
                      v-model="selectedIngFromList"
                      :items="ingredientsStore.ingredients"
                      item-title="name"
                      return-object
                      label="Buscar en mis ingredientes..."
                      variant="solo-filled"
                      flat
                      rounded="lg"
                      bg-color="grey-lighten-4"
                      prepend-inner-icon="mdi-magnify"
                      no-data-text="No tienes ingredientes creados"
                      @update:model-value="addIngredientToRecipe"
                  ></v-autocomplete>
              </v-card>

              <div class="text-overline mb-2 ml-2 text-deep-purple-accent-4 font-weight-bold">
                  Ingredientes en la receta
              </div>

              <v-row dense>
                  <v-col v-for="(ing, index) in recipeForm.ingredients" :key="ing.id" cols="12">
                      <v-card rounded="xl" class="pa-3 mb-1" border flat>
                          <div class="d-flex align-center">
                              <div class="flex-grow-1">
                                  <div class="text-body-1 font-weight-bold text-truncate" style="max-width: 160px">
                                      {{ ing.name }}
                                  </div>
                                  <div class="text-caption text-deep-purple-accent-4 font-weight-bold">
                                      {{ calculateIngCalories(ing) }} kcal
                                  </div>
                              </div>

                              <div class="d-flex align-center bg-grey-lighten-4 rounded-pill px-3 py-1" style="min-width: 140px">
                                  <v-text-field
                                      v-model.number="ing.amount"
                                      type="number"
                                      density="compact"
                                      variant="plain"
                                      hide-details
                                      class="centered-input"
                                      style="width: 50px"
                                  ></v-text-field>

                                  <span class="text-caption font-weight-black text-grey-darken-1 mr-2">
                                      {{ ing.unit || ing.base_unit || "g" }}
                                  </span>

                                  <v-divider vertical class="mx-1 my-2" thickness="2"></v-divider>

                                  <v-btn
                                      icon="mdi-close-circle"
                                      variant="text"
                                      color="grey-lighten-1"
                                      size="small"
                                      @click="removeIngredient(index)"
                                  ></v-btn>
                              </div>
                          </div>
                      </v-card>
                  </v-col>
              </v-row>

              <v-expand-transition>
                  <v-card v-if="recipeForm.ingredients.length > 0" color="deep-purple-accent-4" theme="dark" rounded="xl" class="pa-5 mt-6 elevation-4">
                      <div class="text-center text-overline mb-4 opacity-80">Totales Estimados</div>
                      <v-row class="text-center" dense>
                          <v-col cols="3">
                              <div class="text-h5 font-weight-black">{{ Math.round(totals.calories) }}</div>
                              <div class="text-caption font-weight-medium">Kcal</div>
                          </v-col>
                          <v-col cols="3">
                              <div class="text-h5 font-weight-black">{{ totals.protein.toFixed(1) }}g</div>
                              <div class="text-caption font-weight-medium">Prot</div>
                          </v-col>
                          <v-col cols="3">
                              <div class="text-h5 font-weight-black">{{ totals.carbs.toFixed(1) }}g</div>
                              <div class="text-caption font-weight-medium">Carbs</div>
                          </v-col>
                          <v-col cols="3">
                              <div class="text-h5 font-weight-black">{{ totals.fat.toFixed(1) }}g</div>
                              <div class="text-caption font-weight-medium">Grasas</div>
                          </v-col>
                      </v-row>
                  </v-card>
              </v-expand-transition>
          </v-container>
      </v-card>
  </v-dialog>
</template>

<script setup>
import { ref, reactive, computed, watch } from "vue";
import { useIngredientsStore } from "@/stores/useIngredientsStore";

const props = defineProps({
  modelValue: Boolean,
  initialData: Object,
});

const emit = defineEmits(["update:modelValue", "save"]);
const ingredientsStore = useIngredientsStore();
const isEditing = ref(false);
const selectedIngFromList = ref(null);

const recipeForm = reactive({
  id: null,
  name: "",
  ingredients: [],
});

watch(() => props.modelValue, (val) => {
  if (val) {
      if (props.initialData) {
          isEditing.value = true;
          recipeForm.id = props.initialData.id;
          recipeForm.name = props.initialData.name;
          recipeForm.ingredients = JSON.parse(JSON.stringify(props.initialData.ingredients || []));
      } else {
          isEditing.value = false;
          recipeForm.id = Date.now();
          recipeForm.name = "";
          recipeForm.ingredients = [];
      }
  }
});

const addIngredientToRecipe = (ing) => {
  if (!ing) return;
  
  // Si la unidad es 'unidad' o similar, empezamos con 1, si es 'g' con 100
  const defaultAmount = (ing.unit === 'unidad' || ing.base_unit === 'unidad') ? 1 : 100;

  recipeForm.ingredients.push({
      ...JSON.parse(JSON.stringify(ing)), // Copia profunda para evitar reactividad cruzada
      amount: defaultAmount,
  });
  selectedIngFromList.value = null;
};

const removeIngredient = (index) => {
  recipeForm.ingredients.splice(index, 1);
};

const calculateIngCalories = (ing) => {
  // Aseguramos que existan valores y convertimos a número
  const amount = parseFloat(ing.amount) || 0;
  const baseAmount = parseFloat(ing.base_amount) || 1;
  const baseCalories = parseFloat(ing.calories) || 0;

  if (amount === 0) return 0;

  const factor = amount / baseAmount;
  return Math.round(baseCalories * factor);
};

const totals = computed(() => {
  return recipeForm.ingredients.reduce(
      (acc, ing) => {
          const amount = parseFloat(ing.amount) || 0;
          const baseAmount = parseFloat(ing.base_amount) || 1;
          const factor = amount / baseAmount;

          acc.calories += (parseFloat(ing.calories) || 0) * factor;
          acc.protein += (parseFloat(ing.protein) || 0) * factor;
          acc.carbs += (parseFloat(ing.carbs) || 0) * factor;
          acc.fat += (parseFloat(ing.fat) || 0) * factor;
          return acc;
      },
      { calories: 0, protein: 0, carbs: 0, fat: 0 }
  );
});

const isFormValid = computed(() => {
  return recipeForm.name?.length > 2 && recipeForm.ingredients.length > 0;
});

const saveRecipe = () => {
  emit("save", { ...recipeForm, ...totals.value });
  emit("update:modelValue", false);
};
</script>

<style scoped>
:deep(.centered-input input) {
  text-align: center;
  font-weight: 800;
  padding: 0;
  color: #311b92;
}

:deep(input::-webkit-outer-spin-button),
:deep(input::-webkit-inner-spin-button) {
  -webkit-appearance: none;
  margin: 0;
}

:deep(input[type="number"]) {
  -moz-appearance: textfield;
}
</style>
