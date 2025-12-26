<template>
    <v-dialog :model-value="modelValue" @update:model-value="$emit('update:modelValue', $event)" max-width="500px">
      <v-card title="Registrar alimento de hoy" subtitle="Selecciona un alimento de la biblioteca o ingresa uno nuevo">
        <v-card-text>
          <v-autocomplete
            label="Buscar alimento"
            :items="ingredientsStore.ingredients"
            item-title="name"
            return-object
            variant="outlined"
            @update:model-value="onFoodSelected"
            class="mb-4"
            density="compact"
          ></v-autocomplete>
  
          <v-divider class="mb-6"></v-divider>
  
          <v-text-field v-model="form.name" label="Nombre" variant="outlined" density="compact"></v-text-field>
          
          <v-row>
            <v-col cols="7">
              <v-text-field v-model.number="form.amount" label="Cantidad" type="number" variant="outlined" density="compact"></v-text-field>
            </v-col>
            <v-col cols="5">
              <v-select v-model="form.unit" :items="['g', 'ml']" label="Unidad" variant="outlined" density="compact"></v-select>
            </v-col>
          </v-row>
          
          <v-row>
            <v-col cols="6">
              <v-text-field v-model.number="form.calories" label="Calorías" type="number" variant="outlined" density="compact" suffix="kcal"></v-text-field>
            </v-col>
            <v-col cols="6">
              <v-text-field v-model.number="form.protein" label="Proteína" type="number" variant="outlined" density="compact" suffix="g"></v-text-field>
            </v-col>
            <v-col cols="6">
              <v-text-field v-model.number="form.carbs" label="Carbohidratos" type="number" variant="outlined" density="compact" suffix="g"></v-text-field>
            </v-col>
            <v-col cols="6">
              <v-text-field v-model.number="form.fat" label="Grasas" type="number" variant="outlined" density="compact" suffix="g"></v-text-field>
            </v-col>
          </v-row>
        </v-card-text>
  
        <v-card-actions class="pa-4">
          <v-spacer></v-spacer>
          <v-btn variant="text" @click="$emit('update:modelValue', false)">Cancelar</v-btn>
          <v-btn color="deep-purple-accent-4" variant="flat" class="px-6" @click="saveMealLog">Guardar</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </template>
  
  <script setup>
  import { ref, onMounted } from 'vue';
  import { useMealLogsStore } from '@/stores/useMealLogsStore';
  import { useIngredientsStore } from '@/stores/useIngredientsStore';
  
  defineProps({ modelValue: Boolean });
  const emit = defineEmits(['update:modelValue']);
  
  const mealLogsStore = useMealLogsStore();
  const ingredientsStore = useIngredientsStore();

  const initialState = {
    name: '',
    amount: 100,
    unit: 'g',
    calories: 0,
    protein: 0,
    carbs: 0,
    fat: 0
  };
  
  const form = ref({ ...initialState });

  onMounted(() => {
    mealLogsStore.fetchMealLogs();
    ingredientsStore.fetchIngredients();
  });
  
  const onFoodSelected = (food) => {
    if (food) {
      // Sobrescribimos el form con los datos del alimento, manteniendo cantidad y unidad si el alimento no los trae
      form.value = { ...form.value, ...food };
    }
  };
  
  const saveMealLog = () => {
    mealLogsStore.addMealLog({ ...form.value });
    emit('update:modelValue', false);
    form.value = { ...initialState };
  };
  </script>
