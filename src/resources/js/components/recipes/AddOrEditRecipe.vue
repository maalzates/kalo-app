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
                <div class="text-body-1 text-sm-h6 font-weight-bold text-wrap line-height-1 pl-2">
                  {{ isEditing ? "Editar Receta" : "Nueva Receta" }}
                </div>
                <v-spacer></v-spacer>
                <v-btn
                    variant="flat"
                    color="white"
                    class="text-deep-purple-accent-4 font-weight-bold rounded-pill px-6"
                    @click="saveRecipe"
                    :disabled="!isFormValid || loading"
                    :loading="loading"
                >
                    Guardar
                </v-btn>
            </v-toolbar>
  
            <v-container class="pa-4" style="max-width: 800px">
                <v-card rounded="xl" class="pa-4 mb-4" elevation="1" border="sm">
                    <v-row dense>
                      <v-col cols="8" sm="9">
                        <v-text-field
                            v-model="recipeForm.name"
                            label="Nombre de la receta"
                            placeholder="Ej: Ensalada César Fitness"
                            variant="outlined"
                            hide-details
                            rounded="lg"
                            color="deep-purple-accent-4"
                        ></v-text-field>
                      </v-col>
                      <v-col cols="4" sm="3">
                        <v-text-field
                            v-model.number="recipeForm.servings"
                            label="porciones"
                            density="compact"
                            type="number"
                            variant="outlined"
                            hide-details
                            rounded="lg"
                            color="deep-purple-accent-4"
                            prepend-inner-icon="mdi-account-group"
                        ></v-text-field>
                      </v-col>
                    </v-row>
                </v-card>
  
                <v-card rounded="xl" class="pa-4 mb-4" elevation="1" border="sm">
                    <div class="text-subtitle-2 mb-2 ml-1 font-weight-bold text-grey-darken-2">
                        Añadir Ingredientes
                    </div>
                    <v-autocomplete
                        v-model="selectedIngFromList"
                        :items="ingredientsStore.publicAndPrivateIngredients"
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
                                        {{ ing.unit || "g" }}
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
  import { ref, reactive, computed, watch, onMounted } from "vue";
  import { useIngredientsStore } from "@/stores/useIngredientsStore";
  
  const props = defineProps({
    modelValue: Boolean,
    initialData: Object,
  });
  
  const emit = defineEmits(["update:modelValue", "save"]);
  const ingredientsStore = useIngredientsStore();
  const isEditing = ref(false);
  const selectedIngFromList = ref(null);
  const loading = ref(false);
  
  const recipeForm = reactive({
    id: null,
    name: "",
    servings: 1,
    total_kcal: 0,
    total_prot: 0,
    total_carb: 0,
    total_fat: 0,
    ingredients: [],
  });
  
  onMounted(() => {
    if (ingredientsStore.publicAndPrivateIngredients.length === 0) {
      ingredientsStore.fetchPublicAndPrivateIngredients();
    }
  });
  
  watch(() => props.modelValue, (val) => {
    if (val) {
        if (props.initialData) {
            isEditing.value = true;
            recipeForm.id = props.initialData.id;
            recipeForm.name = props.initialData.name;
            recipeForm.servings = props.initialData.servings || 1;
            // Mapear ingredientes del backend
            recipeForm.ingredients = (props.initialData.ingredients || []).map(ing => {
              const ingredientId = ing.id || ing.ingredient_id;
              const amount = ing.pivot?.amount || ing.amount || 100;
              const unit = ing.pivot?.unit || ing.unit || 'g';
              
              return {
                ingredient_id: ingredientId,
                id: ingredientId, // Asegurar que id también esté presente
                amount: parseFloat(amount) || 100,
                unit: unit,
                name: ing.name,
                base_amount: parseFloat(ing.amount) || parseFloat(ing.base_amount) || 100,
                calories: parseFloat(ing.kcal) || parseFloat(ing.calories) || 0,
                protein: parseFloat(ing.prot) || parseFloat(ing.protein) || 0,
                carbs: parseFloat(ing.carb) || parseFloat(ing.carbs) || 0,
                fat: parseFloat(ing.fat) || 0,
                // Incluir todas las propiedades del ingrediente original
                ...ing
              };
            });
        } else {
            isEditing.value = false;
            recipeForm.id = null;
            recipeForm.name = "";
            recipeForm.servings = 1;
            recipeForm.ingredients = [];
        }
    }
  });
  
  const addIngredientToRecipe = (ing) => {
    if (!ing) return;
    const defaultAmount = (ing.unit === 'un' || ing.unit === 'unidad') ? 1 : 100;
    recipeForm.ingredients.push({
        ingredient_id: ing.id,
        amount: defaultAmount,
        unit: ing.unit || 'g',
        name: ing.name,
        base_amount: ing.amount || 100,
        calories: ing.kcal || 0,
        protein: ing.prot || 0,
        carbs: ing.carb || 0,
        fat: ing.fat || 0,
    });
    selectedIngFromList.value = null;
  };
  
  const removeIngredient = (index) => {
    recipeForm.ingredients.splice(index, 1);
  };
  
  const calculateIngCalories = (ing) => {
    const amount = parseFloat(ing.amount) || 0;
    const baseAmount = parseFloat(ing.base_amount) || parseFloat(ing.amount) || 1;
    const baseCalories = parseFloat(ing.calories) || parseFloat(ing.kcal) || 0;
    if (amount === 0) return 0;
    const factor = amount / baseAmount;
    return Math.round(baseCalories * factor);
  };
  
  const totals = computed(() => {
    return recipeForm.ingredients.reduce(
        (acc, ing) => {
            const amount = parseFloat(ing.amount) || 0;
            const baseAmount = parseFloat(ing.base_amount) || parseFloat(ing.amount) || 1;
            const factor = amount / baseAmount;
  
            acc.calories += (parseFloat(ing.calories) || parseFloat(ing.kcal) || 0) * factor;
            acc.protein += (parseFloat(ing.protein) || parseFloat(ing.prot) || 0) * factor;
            acc.carbs += (parseFloat(ing.carbs) || parseFloat(ing.carb) || 0) * factor;
            acc.fat += (parseFloat(ing.fat) || 0) * factor;
            return acc;
        },
        { calories: 0, protein: 0, carbs: 0, fat: 0 }
    );
  });
  
  const isFormValid = computed(() => {
    // Ahora validamos también que las porciones sean mayores a 0
    return recipeForm.name?.length > 2 && recipeForm.ingredients.length > 0 && recipeForm.servings > 0;
  });
  
  const saveRecipe = async () => {
    loading.value = true;
    try {
      const recipeData = {
        name: recipeForm.name,
        servings: recipeForm.servings,
        total_kcal: Math.round(totals.value.calories),
        total_prot: totals.value.protein.toFixed(2),
        total_carb: totals.value.carbs.toFixed(2),
        total_fat: totals.value.fat.toFixed(2),
        ingredients: recipeForm.ingredients.map(ing => ({
          ingredient_id: ing.ingredient_id || ing.id,
          amount: ing.amount,
          unit: ing.unit || 'g'
        }))
      };
      
      emit("save", recipeData);
      emit("update:modelValue", false);
    } catch (error) {
      console.error('Error saving recipe:', error);
    } finally {
      loading.value = false;
    }
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
