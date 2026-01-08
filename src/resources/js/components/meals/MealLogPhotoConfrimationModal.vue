<template>
    <v-dialog :model-value="modelValue" @update:model-value="$emit('update:modelValue', $event)" fullscreen
      transition="dialog-bottom-transition">
      <v-card class="bg-grey-lighten-4">
        <v-toolbar color="deep-purple-accent-4" flat class="position-sticky top-0 z-index-10">
          <v-btn icon @click="$emit('update:modelValue', false)">
            <v-icon>mdi-close</v-icon>
          </v-btn>
          <v-toolbar-title class="text-body-1 font-weight-bold">
            Confirmar Análisis
          </v-toolbar-title>
          <v-spacer></v-spacer>
          <v-btn
            variant="flat"
            color="white"
            class="text-deep-purple font-weight-bold rounded-pill px-6"
            :loading="isSaving"
            :disabled="isSaving || !isFormValid"
            @click="handleSave"
          >
            Confirmar
          </v-btn>
        </v-toolbar>

        <v-container class="pa-4" style="max-width: 800px">
          <!-- Food Name and Quantity Section -->
          <v-card rounded="xl" class="pa-4 mb-4" border="sm">
            <div class="d-flex align-center justify-space-between mb-3">
              <div class="text-overline text-deep-purple-accent-4 font-weight-bold">Resultado de la IA</div>
              <v-btn
                v-if="!editMode"
                variant="text"
                color="deep-purple-accent-4"
                density="compact"
                prepend-icon="mdi-pencil"
                class="text-caption font-weight-bold"
                @click="editMode = true"
              >
                Editar detalles
              </v-btn>
            </div>

            <template v-if="!editMode">
              <!-- Display Mode -->
              <div class="text-h6 font-weight-bold mb-3">{{ localData.name }}</div>
              <v-row dense>
                <v-col cols="6">
                  <div class="text-caption text-grey-darken-1">Cantidad base</div>
                  <div class="text-subtitle-1 font-weight-bold">
                    {{ localData.type === 'recipe' ? localData.servings + ' porciones' : localData.amount + ' ' + localData.unit }}
                  </div>
                </v-col>
                <v-col cols="6">
                  <div class="text-caption text-grey-darken-1">Cantidad consumida</div>
                  <div class="text-subtitle-1 font-weight-bold text-deep-purple-accent-4">
                    {{ consumedQuantity }} {{ localData.type === 'recipe' ? 'porciones' : localData.unit }}
                  </div>
                </v-col>
              </v-row>
            </template>

            <template v-else>
              <!-- Edit Mode -->
              <v-text-field v-model="localData.name" label="Nombre del plato/alimento" variant="outlined" rounded="lg"
                color="deep-purple-accent-4" density="comfortable" class="mb-3"></v-text-field>

              <v-row v-if="localData.type === 'recipe'" dense>
                <v-col cols="12">
                  <v-text-field v-model.number="consumedQuantity" label="Porciones consumidas" type="number"
                    variant="outlined" rounded="lg" color="deep-purple-accent-4" density="comfortable"
                    prepend-inner-icon="mdi-food" hint="Edita la cantidad de porciones que consumiste"
                    persistent-hint></v-text-field>
                </v-col>
              </v-row>

              <v-row v-if="localData.type === 'ingredient'" dense>
                <v-col cols="12">
                  <v-text-field
                    v-model.number="consumedQuantity"
                    :label="`Cantidad consumida (${localData.unit})`"
                    type="number"
                    variant="outlined"
                    rounded="lg"
                    color="deep-purple-accent-4"
                    density="comfortable"
                    prepend-inner-icon="mdi-food"
                    :hint="`Edita la cantidad en ${localData.unit} que consumiste`"
                    persistent-hint
                  ></v-text-field>
                </v-col>
              </v-row>

              <v-btn
                variant="text"
                color="deep-purple-accent-4"
                density="compact"
                prepend-icon="mdi-check"
                class="text-caption font-weight-bold mt-2"
                @click="editMode = false"
              >
                Guardar cambios
              </v-btn>
            </template>
          </v-card>
  
          <!-- Ingredients Section (for recipes only, editable) -->
          <template v-if="localData.type === 'recipe' && editMode">
            <div class="d-flex align-center justify-space-between mb-2 px-2">
              <div class="text-overline text-grey-darken-1 font-weight-bold">Desglose de ingredientes</div>
              <v-btn variant="text" color="deep-purple-accent-4" density="compact" prepend-icon="mdi-plus" @click="addIngredient">
                Agregar
              </v-btn>
            </div>

            <v-card v-for="(ing, index) in localData.ingredients" :key="index" rounded="xl" class="pa-4 mb-3" border="sm">
              <div class="d-flex align-center mb-3">
                <v-text-field v-model="ing.name" label="Ingrediente" variant="underlined" hide-details
                  density="compact" class="font-weight-bold"></v-text-field>
                <v-btn icon="mdi-delete-outline" variant="text" color="red-lighten-1" size="small" @click="removeIngredient(index)"></v-btn>
              </div>

              <v-row dense>
                <v-col cols="4">
                  <v-text-field v-model.number="ing.amount" label="Cant." type="number" variant="outlined" rounded="lg"
                    density="compact" hide-details></v-text-field>
                </v-col>
                <v-col cols="3">
                  <v-select v-model="ing.unit" :items="['g', 'ml', 'un']" variant="outlined" rounded="lg"
                    density="compact" hide-details></v-select>
                </v-col>
                <v-col cols="5">
                  <v-text-field v-model.number="ing.kcal" label="Kcal" type="number" variant="outlined" rounded="lg"
                    density="compact" hide-details color="orange-darken-2"></v-text-field>
                </v-col>
              </v-row>

              <v-row dense class="mt-2 text-center">
                <v-col v-for="m in ['prot', 'carb', 'fat']" :key="m" cols="4">
                  <v-text-field v-model.number="ing[m]" :label="m.toUpperCase()" type="number" variant="outlined" rounded="lg"
                    density="compact" hide-details color="deep-purple-lighten-3"></v-text-field>
                </v-col>
              </v-row>
            </v-card>
          </template>

          <!-- Macros Display Section -->
          <div class="text-overline mb-2 ml-2 text-deep-purple-accent-4 font-weight-bold">
            Información Nutricional {{ localData.type === 'ingredient' ? '(por ' + localData.amount + ' ' + localData.unit + ')' : '(total)' }}
          </div>
          <v-card rounded="xl" class="pa-6" elevation="2">
            <!-- Calories Display -->
            <div class="text-center mb-6">
              <v-progress-circular
                :model-value="100"
                :size="140"
                :width="12"
                color="deep-purple-accent-4"
              >
                <div class="text-center">
                  <span class="text-h4 font-weight-bold">{{ consumedTotals.kcal }}</span>
                  <div class="text-caption text-uppercase text-grey-darken-1">kcal totales</div>
                </div>
              </v-progress-circular>
            </div>

            <v-divider class="mb-4"></v-divider>

            <!-- Macros Progress Bars -->
            <div>
              <div class="d-flex justify-space-between align-center mb-2">
                <div class="d-flex align-center ga-2">
                  <v-icon size="20" color="deep-purple">mdi-food-drumstick</v-icon>
                  <span class="text-subtitle-2 font-weight-bold">Proteína</span>
                </div>
                <span class="text-body-2 font-weight-bold text-deep-purple">
                  {{ consumedTotals.prot }}g
                </span>
              </div>
              <v-progress-linear
                :model-value="100"
                color="deep-purple"
                height="10"
                rounded
                class="mb-4"
              ></v-progress-linear>

              <div class="d-flex justify-space-between align-center mb-2">
                <div class="d-flex align-center ga-2">
                  <v-icon size="20" color="orange-darken-1">mdi-bread-slice</v-icon>
                  <span class="text-subtitle-2 font-weight-bold">Carbohidratos</span>
                </div>
                <span class="text-body-2 font-weight-bold text-orange-darken-1">
                  {{ consumedTotals.carb }}g
                </span>
              </div>
              <v-progress-linear
                :model-value="100"
                color="orange-darken-1"
                height="10"
                rounded
                class="mb-4"
              ></v-progress-linear>

              <div class="d-flex justify-space-between align-center mb-2">
                <div class="d-flex align-center ga-2">
                  <v-icon size="20" color="cyan-darken-1">mdi-oil</v-icon>
                  <span class="text-subtitle-2 font-weight-bold">Grasas</span>
                </div>
                <span class="text-body-2 font-weight-bold text-cyan-darken-1">
                  {{ consumedTotals.fat }}g
                </span>
              </div>
              <v-progress-linear
                :model-value="100"
                color="cyan-darken-1"
                height="10"
                rounded
              ></v-progress-linear>
            </div>
          </v-card>
        </v-container>
      </v-card>
    </v-dialog>
  </template>
  
  <script setup>
  import { ref, computed, watch } from 'vue';
  import { useMealLogsStore } from '@/stores/useMealLogsStore';
  import { useDateStore } from '@/stores/useDateStore';
  import { useToast } from 'vue-toastification';

  const props = defineProps({
    modelValue: Boolean,
    analysisData: Object // El JSON que devuelve la IA
  });

  const emit = defineEmits(['update:modelValue', 'confirmed', 'cancelled']);

  const toast = useToast();
  const mealLogsStore = useMealLogsStore();
  const dateStore = useDateStore();
  const isSaving = ref(false);
  const consumedQuantity = ref(0);
  const editMode = ref(false);

  // Clonamos la data para que sea editable localmente sin mutar el prop
  const localData = ref({
    type: 'ingredient',
    name: '',
    servings: 1,
    amount: 0,
    unit: 'g',
    kcal: 0,
    prot: 0,
    carb: 0,
    fat: 0,
    ingredients: []
  });

  watch(() => props.analysisData, (newVal) => {
    if (newVal) {
      localData.value = JSON.parse(JSON.stringify(newVal));
      editMode.value = false;
      // Inicializar cantidad consumida con los valores base
      if (newVal.type === 'ingredient') {
        consumedQuantity.value = newVal.amount || 0;
      } else if (newVal.type === 'recipe') {
        consumedQuantity.value = newVal.servings || 1;
      }
    }
  }, { immediate: true });

  // Emitir evento cuando el usuario cierra el modal sin confirmar
  watch(() => props.modelValue, (isModalCurrentlyOpen, wasModalPreviouslyOpen) => {
    const userClosedModalWithoutConfirming = wasModalPreviouslyOpen === true && isModalCurrentlyOpen === false;

    if (userClosedModalWithoutConfirming) {
      // Notificar al padre que el usuario canceló/cerró sin confirmar
      emit('cancelled');
    }
  });

  // Validar que el formulario esté completo
  const isFormValid = computed(() => {
    if (!localData.value.name || !localData.value.name.trim()) return false;
    if (consumedQuantity.value <= 0) return false;
    if (localData.value.type === 'ingredient' && !localData.value.amount) return false;
    if (localData.value.type === 'recipe' && !localData.value.servings) return false;
    return true;
  });
  
  // Totales base: si es receta suma ingredientes, si no usa los del objeto base
  const totals = computed(() => {
    if (localData.value.type === 'ingredient') {
      return {
        kcal: Math.round(localData.value.kcal || 0),
        prot: parseFloat(localData.value.prot || 0).toFixed(1),
        carb: parseFloat(localData.value.carb || 0).toFixed(1),
        fat: parseFloat(localData.value.fat || 0).toFixed(1)
      };
    }

    return localData.value.ingredients.reduce((acc, ing) => {
      acc.kcal += Number(ing.kcal || 0);
      acc.prot = (Number(acc.prot) + Number(ing.prot || 0)).toFixed(1);
      acc.carb = (Number(acc.carb) + Number(ing.carb || 0)).toFixed(1);
      acc.fat = (Number(acc.fat) + Number(ing.fat || 0)).toFixed(1);
      return acc;
    }, { kcal: 0, prot: 0, carb: 0, fat: 0 });
  });

  // Totales consumidos: calcula basado en la cantidad consumida vs base
  const consumedTotals = computed(() => {
    const baseAmount = localData.value.type === 'ingredient' ? (localData.value.amount || 1) : (localData.value.servings || 1);
    const consumed = consumedQuantity.value || 0;
    const factor = baseAmount > 0 ? consumed / baseAmount : 0;

    return {
      kcal: Math.round(totals.value.kcal * factor),
      prot: (parseFloat(totals.value.prot) * factor).toFixed(1),
      carb: (parseFloat(totals.value.carb) * factor).toFixed(1),
      fat: (parseFloat(totals.value.fat) * factor).toFixed(1)
    };
  });
  
  const addIngredient = () => {
    localData.value.ingredients.push({
      name: 'Nuevo ingrediente',
      amount: 100,
      unit: 'g',
      kcal: 0,
      prot: 0,
      carb: 0,
      fat: 0
    });
  };
  
  const removeIngredient = (index) => {
    localData.value.ingredients.splice(index, 1);
  };
  
  const handleSave = async () => {
    if (!isFormValid.value) return;

    isSaving.value = true;
    try {
      if (localData.value.type === 'recipe') {
        await savePhotoRecipe();
      } else {
        await savePhotoIngredient();
      }
      toast.success('Análisis confirmado y consumo registrado exitosamente');
      emit('confirmed', localData.value);
      emit('update:modelValue', false);
    } catch (error) {
      console.error('Error guardando meal log desde IA:', error);
      toast.error('Error al guardar el análisis. Intenta de nuevo.');
    } finally {
      isSaving.value = false;
    }
  };
  
  const savePhotoIngredient = async () => {
    const date = dateStore.selectedDate instanceof Date
      ? dateStore.selectedDate.toISOString().split('T')[0]
      : dateStore.selectedDate;
    
    const mealData = {
      quantity: consumedQuantity.value.toString(),
      unit: localData.value.unit || 'g',
      ai_name: localData.value.name,
      ai_data: {
        type: 'ingredient',
        name: localData.value.name,
        amount: parseFloat(localData.value.amount) || 100,
        unit: localData.value.unit || 'g',
        kcal: parseFloat(localData.value.kcal) || 0,
        prot: parseFloat(localData.value.prot) || 0,
        carb: parseFloat(localData.value.carb) || 0,
        fat: parseFloat(localData.value.fat) || 0,
      },
      logged_at: date,
    };
    
    await mealLogsStore.addMealLogFromAI(mealData);
    // Recargar meal logs para la fecha actual
    await mealLogsStore.fetchMealLogs({ date_from: date, date_to: date });
  };
  
  const savePhotoRecipe = async () => {
    const date = dateStore.selectedDate instanceof Date
      ? dateStore.selectedDate.toISOString().split('T')[0]
      : dateStore.selectedDate;
    
    const mealData = {
      quantity: consumedQuantity.value.toString(),
      unit: 'serving',
      ai_name: localData.value.name,
      ai_data: {
        type: 'recipe',
        name: localData.value.name,
        servings: parseFloat(localData.value.servings) || 1,
        kcal: totals.value.kcal,
        prot: parseFloat(totals.value.prot) || 0,
        carb: parseFloat(totals.value.carb) || 0,
        fat: parseFloat(totals.value.fat) || 0,
        ingredients: localData.value.ingredients || [],
      },
      logged_at: date,
    };
    
    await mealLogsStore.addMealLogFromAI(mealData);
    // Recargar meal logs para la fecha actual
    await mealLogsStore.fetchMealLogs({ date_from: date, date_to: date });
  };
  </script>
  
  <style scoped>
  .z-index-10 { z-index: 10; }
  .uppercase { text-transform: uppercase; letter-spacing: 1px; }
  </style>
