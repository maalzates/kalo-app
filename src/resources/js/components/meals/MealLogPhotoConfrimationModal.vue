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
          <v-card rounded="xl" class="pa-4 mb-4" border="sm">
            <div class="text-overline text-deep-purple-accent-4 font-weight-bold mb-1">Resultado de la IA</div>
            <v-text-field v-model="localData.name" label="Nombre del plato/alimento" variant="outlined" rounded="lg"
              color="deep-purple-accent-4" density="comfortable"></v-text-field>
  
            <v-row v-if="localData.type === 'recipe'" dense>
              <v-col cols="6">
                <v-text-field v-model.number="localData.servings" label="Porciones que rinde" type="number"
                  variant="outlined" rounded="lg" color="deep-purple-accent-4" density="comfortable"
                  prepend-inner-icon="mdi-account-group"></v-text-field>
              </v-col>
              <v-col cols="6">
                <v-text-field v-model.number="consumedQuantity" label="Porciones consumidas" type="number"
                  variant="outlined" rounded="lg" color="deep-purple-accent-4" density="comfortable"
                  prepend-inner-icon="mdi-food"></v-text-field>
              </v-col>
            </v-row>
  
            <v-row v-if="localData.type === 'ingredient'" dense>
              <v-col cols="6">
                <v-text-field v-model.number="localData.amount" label="Cantidad base" type="number" variant="outlined"
                  rounded="lg" color="deep-purple-accent-4" density="comfortable"></v-text-field>
              </v-col>
              <v-col cols="3">
                <v-select v-model="localData.unit" :items="['g', 'ml', 'un']" label="Unidad base" variant="outlined"
                  rounded="lg" color="deep-purple-accent-4" density="comfortable"></v-select>
              </v-col>
              <v-col cols="3">
                <v-text-field v-model.number="consumedQuantity" label="Cantidad consumida" type="number"
                  variant="outlined" rounded="lg" color="deep-purple-accent-4" density="comfortable"></v-text-field>
              </v-col>
            </v-row>
          </v-card>
  
          <template v-if="localData.type === 'recipe'">
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
  
          <div class="text-overline mb-1 ml-2 text-deep-purple-accent-4 font-weight-bold">Totales Calculados</div>
          <v-card rounded="xl" class="pa-4 bg-deep-purple-accent-4 text-white" elevation="4">
            <v-row no-gutters class="text-center">
              <v-col>
                <div class="text-h5 font-weight-black">{{ totals.kcal }}</div>
                <div class="text-caption font-weight-bold uppercase">kcal</div>
              </v-col>
              <v-divider vertical class="mx-2 border-opacity-25"></v-divider>
              <v-col>
                <div class="text-h6 font-weight-black">{{ totals.prot }}g</div>
                <div class="text-caption font-weight-bold uppercase">Prot</div>
              </v-col>
              <v-divider vertical class="mx-2 border-opacity-25"></v-divider>
              <v-col>
                <div class="text-h6 font-weight-black">{{ totals.carb }}g</div>
                <div class="text-caption font-weight-bold uppercase">Carbs</div>
              </v-col>
              <v-divider vertical class="mx-2 border-opacity-25"></v-divider>
              <v-col>
                <div class="text-h6 font-weight-black">{{ totals.fat }}g</div>
                <div class="text-caption font-weight-bold uppercase">Grasa</div>
              </v-col>
            </v-row>
          </v-card>
        </v-container>
      </v-card>
    </v-dialog>
  </template>
  
  <script setup>
  import { ref, computed, watch } from 'vue';
  import { useMealLogsStore } from '@/stores/useMealLogsStore';
  import { useDateStore } from '@/stores/useDateStore';
  
  const props = defineProps({
    modelValue: Boolean,
    analysisData: Object // El JSON que devuelve la IA
  });
  
  const emit = defineEmits(['update:modelValue', 'confirmed']);
  
  const mealLogsStore = useMealLogsStore();
  const dateStore = useDateStore();
  const isSaving = ref(false);
  const consumedQuantity = ref(0);
  
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
      // Inicializar cantidad consumida con los valores base
      if (newVal.type === 'ingredient') {
        consumedQuantity.value = newVal.amount || 0;
      } else if (newVal.type === 'recipe') {
        consumedQuantity.value = newVal.servings || 1;
      }
    }
  }, { immediate: true });

  // Validar que el formulario esté completo
  const isFormValid = computed(() => {
    if (!localData.value.name || !localData.value.name.trim()) return false;
    if (consumedQuantity.value <= 0) return false;
    if (localData.value.type === 'ingredient' && !localData.value.amount) return false;
    if (localData.value.type === 'recipe' && !localData.value.servings) return false;
    return true;
  });
  
  // Totales computados: si es receta suma ingredientes, si no usa los del objeto base
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
      emit('confirmed', localData.value);
      emit('update:modelValue', false);
    } catch (error) {
      console.error('Error guardando meal log desde IA:', error);
      // El error ya está manejado en el store
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
