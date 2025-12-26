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
          <v-toolbar-title>{{ isEditing ? 'Editar Receta' : 'Nueva Receta' }}</v-toolbar-title>
          <v-spacer></v-spacer>
          <v-btn variant="text" @click="saveRecipe" :disabled="!isFormValid">
            Guardar
          </v-btn>
        </v-toolbar>
  
        <v-container class="pa-4">
          <v-card rounded="xl" class="pa-4 mb-4" elevation="1">
            <v-text-field
              v-model="recipeForm.name"
              label="Nombre de la receta"
              placeholder="Ej: Ensalada César Fitness"
              variant="outlined"
              hide-details
              rounded="lg"
            ></v-text-field>
          </v-card>
  
          <v-card rounded="xl" class="pa-4 mb-4" elevation="1">
            <div class="text-subtitle-2 mb-2 ml-1">Añadir Ingredientes</div>
            <v-autocomplete
              v-model="selectedIngFromList"
              :items="ingredientsStore.ingredients"
              item-title="name"
              return-object
              label="Buscar en mis ingredientes..."
              variant="solo-filled"
              flat
              rounded="lg"
              prepend-inner-icon="mdi-magnify"
              @update:model-value="addIngredientToRecipe"
            ></v-autocomplete>
          </v-card>
  
          <div class="text-overline mb-2 ml-2">Ingredientes en la receta</div>
          <v-row dense>
            <v-col v-for="(ing, index) in recipeForm.ingredients" :key="ing.id" cols="12">
              <v-card rounded="lg" class="pa-3" border flat>
                <div class="d-flex align-center justify-space-between">
                  <div style="flex: 1">
                    <div class="text-body-1 font-weight-bold">{{ ing.name }}</div>
                    <div class="text-caption text-grey">{{ calculateIngCalories(ing) }} kcal</div>
                  </div>
                  
                  <div class="d-flex align-center" style="max-width: 150px">
                    <v-text-field
                      v-model.number="ing.amount"
                      type="number"
                      density="compact"
                      variant="underlined"
                      suffix="g"
                      hide-details
                      class="mr-4"
                    ></v-text-field>
                    <v-btn icon="mdi-delete-outline" variant="text" color="error" size="small" @click="removeIngredient(index)"></v-btn>
                  </div>
                </div>
              </v-card>
            </v-col>
          </v-row>
  
          <v-card v-if="recipeForm.ingredients.length > 0" color="deep-purple-accent-4" theme="dark" rounded="xl" class="pa-4 mt-6">
            <div class="text-center mb-2">Totales de la Receta</div>
            <v-row class="text-center" dense>
              <v-col cols="3">
                <div class="text-h6">{{ totals.calories }}</div>
                <div class="text-caption">Kcal</div>
              </v-col>
              <v-col cols="3">
                <div class="text-h6">{{ totals.protein }}g</div>
                <div class="text-caption">Prot</div>
              </v-col>
              <v-col cols="3">
                <div class="text-h6">{{ totals.carbs }}g</div>
                <div class="text-caption">Carbs</div>
              </v-col>
              <v-col cols="3">
                <div class="text-h6">{{ totals.fat }}g</div>
                <div class="text-caption">Grasas</div>
              </v-col>
            </v-row>
          </v-card>
        </v-container>
      </v-card>
    </v-dialog>
  </template>
  
  <script setup>
  import { ref, reactive, computed, watch } from 'vue';
  import { useIngredientsStore } from '@/stores/useIngredientsStore';
  
  const props = defineProps({
    modelValue: Boolean,
    initialData: Object
  });
  
  const emit = defineEmits(['update:modelValue', 'save']);
  const ingredientsStore = useIngredientsStore();
  const isEditing = ref(false);
  const selectedIngFromList = ref(null);
  
  const recipeForm = reactive({
    id: null,
    name: '',
    ingredients: []
  });
  
  // Lógica de carga de datos
  watch(() => props.modelValue, (val) => {
    if (val) {
      if (props.initialData) {
        isEditing.value = true;
        Object.assign(recipeForm, JSON.parse(JSON.stringify(props.initialData)));
      } else {
        isEditing.value = false;
        recipeForm.id = Date.now();
        recipeForm.name = '';
        recipeForm.ingredients = [];
      }
    }
  });
  
  const addIngredientToRecipe = (ing) => {
    if (!ing) return;
    // Añadimos una copia con una cantidad por defecto de 100g
    recipeForm.ingredients.push({
      ...ing,
      amount: 100 // Cantidad inicial por defecto
    });
    selectedIngFromList.value = null; // Limpiar buscador
  };
  
  const removeIngredient = (index) => {
    recipeForm.ingredients.splice(index, 1);
  };
  
  // Función para calcular calorías proporcionales al peso ingresado
  const calculateIngCalories = (ing) => {
    return ((ing.calories * ing.amount) / ing.base_amount).toFixed(0);
  };
  
  const totals = computed(() => {
    return recipeForm.ingredients.reduce((acc, ing) => {
      const factor = ing.amount / ing.base_amount;
      acc.calories += ing.calories * factor;
      acc.protein += ing.protein * factor;
      acc.carbs += ing.carbs * factor;
      acc.fat += ing.fat * factor;
      return acc;
    }, { calories: 0, protein: 0, carbs: 0, fat: 0 });
  });
  
  const isFormValid = computed(() => {
    return recipeForm.name.length > 2 && recipeForm.ingredients.length > 0;
  });
  
  const saveRecipe = () => {
    emit('save', { ...recipeForm, ...totals.value });
    emit('update:modelValue', false);
  };
  </script>
