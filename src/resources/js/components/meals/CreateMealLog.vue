<template>
  <v-dialog :model-value="modelValue" @update:model-value="$emit('update:modelValue', $event)" max-width="500px">
    <v-card title="Registrar consumo">
      <v-card-text>
        <v-tabs v-model="activeTab" grow color="deep-purple-accent-4" class="mb-4">
          <v-tab value="food">Alimentos</v-tab>
          <v-tab value="recipe">Recetas</v-tab>
        </v-tabs>

        <v-autocomplete
          v-model="selectedItem"
          :label="activeTab === 'food' ? 'Buscar alimento...' : 'Buscar receta...'"
          :items="filteredLibrary"
          item-title="name"
          return-object
          variant="outlined"
          density="compact"
          @update:model-value="onFoodSelected"
        >
          <template v-slot:no-data>
            <v-list-item @click="handleCreateNew">
              <v-list-item-title>
                <v-icon start color="deep-purple">mdi-plus</v-icon>
                Crear nuevo {{ activeTab === 'food' ? 'alimento' : 'receta' }}
              </v-list-item-title>
            </v-list-item>
          </template>
        </v-autocomplete>

        <v-divider class="my-4"></v-divider>

        <div v-if="form.name">
          <p class="text-subtitle-2 mb-2">Has seleccionado: <strong>{{ form.name }}</strong></p>
          <v-row v-if="activeTab === 'food'">
            <v-col cols="7">
              <v-text-field v-model.number="form.base_amount" label="Cantidad" type="number" variant="outlined" density="compact"></v-text-field>
            </v-col>
            <v-col cols="5">
              <v-select v-model="form.base_unit" :items="['g', 'ml', 'unidad']" label="Unidad" variant="outlined" density="compact"></v-select>
            </v-col>
          </v-row>
          
          <v-alert density="compact" color="deep-purple-lighten-5" class="text-caption">
            <div v-if="activeTab === 'food'" class="mb-1 text-grey-darken-1 italic">
              Valores por cada <strong>{{ form.base_amount }}{{ form.base_unit }}</strong>:
            </div>
            Macros: {{ form.calories }} kcal | P: {{ form.protein }}g | C: {{ form.carbs }}g | G: {{ form.fat }}g
          </v-alert>
        </div>
      </v-card-text>

      <v-card-actions class="pa-4">
        <v-spacer></v-spacer>
        <v-btn variant="text" @click="$emit('update:modelValue', false)">Cancelar</v-btn>
        <v-btn color="deep-purple-accent-4" variant="flat" class="px-6" :disabled="!form.name" @click="saveMeal">Guardar</v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script setup>
  import { ref, computed, onMounted, watch } from 'vue';
  import { useMealLogsStore } from '@/stores/useMealLogsStore';
  import { useIngredientsStore } from '@/stores/useIngredientsStore';
  import { useRecipesStore } from '@/stores/useRecipesStore';
  
  defineProps({ modelValue: Boolean });
  const emit = defineEmits(['update:modelValue']);
  
  // Acceso a las 3 stores necesarias
  const mealLogsStore = useMealLogsStore();
  const ingredientsStore = useIngredientsStore();
  const recipesStore = useRecipesStore();
  
  const activeTab = ref('food');
  const selectedItem = ref(null);
  
  const initialState = {
    name: '',
    base_amount: 0,
    base_unit: '',
    calories: 0,
    protein: 0,
    carbs: 0,
    fat: 0
  };
  
  const form = ref({ ...initialState });
  
  // El puente entre el tab activo y la store correspondiente
  const filteredLibrary = computed(() => {
    return activeTab.value === 'food' 
      ? ingredientsStore.ingredients 
      : recipesStore.recipes;
  });
  
  onMounted(() => {
    // Cargamos ambas bibliotecas al abrir el diálogo
    ingredientsStore.fetchIngredients();
    recipesStore.fetchRecipes();
  });
  
  const onFoodSelected = (item) => {
    if (item) {
      console.log(item);
      // Volcamos los datos del ingrediente/receta al formulario de log
      form.value = { ...form.value, ...item };
    }
  };
  
  const handleCreateNew = () => {
    console.log("Abrir flujo de creación para:", activeTab.value);
  };
  
  const saveMeal = () => {
    mealLogsStore.addMealLog({ ...form.value });
    emit('update:modelValue', false);
    
    // Reset completo
    form.value = { ...initialState };
    selectedItem.value = null;
  };

  watch(activeTab, () => {
    form.value = { ...initialState };
    selectedItem.value = null;
  });
  </script>
