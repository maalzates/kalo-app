<template>
  <div class="pa-4 ml-0 ml-md-6">
    <div class="mb-6">
      <div class="text-overline mb-2 ml-1 text-deep-purple-accent-4 font-weight-bold">
        Configuración Vigente
      </div>
      <DailyGoals />
    </div>

    <v-card rounded="xl" elevation="2" border="sm">
      <v-tabs v-model="activeTab" grow color="deep-purple-accent-4">
        <v-tab value="auto" class="font-weight-bold">Automático</v-tab>
        <v-tab value="manual" class="font-weight-bold">Manual</v-tab>
      </v-tabs>

      <v-window v-model="activeTab" class="pa-6">
        <v-window-item value="auto">
          <v-row dense>
            <v-col cols="12" sm="6">
              <v-select v-model="gender" :items="[{title:'Hombre', value:'male'},{title:'Mujer', value:'female'}]" label="Género" variant="outlined" rounded="lg"></v-select>
            </v-col>
            <v-col cols="12" sm="6">
              <v-text-field v-model.number="age" label="Edad" type="number" variant="outlined" rounded="lg"></v-text-field>
            </v-col>
            <v-col cols="6">
              <v-text-field v-model.number="weight" label="Peso (kg)" type="number" variant="outlined" rounded="lg"></v-text-field>
            </v-col>
            <v-col cols="6">
              <v-text-field v-model.number="height" label="Altura (cm)" type="number" variant="outlined" rounded="lg"></v-text-field>
            </v-col>
            <v-col cols="12">
              <v-select v-model="activityLevel" :items="activityFactors" label="Nivel de Actividad" variant="outlined" rounded="lg"></v-select>
            </v-col>
            <v-col cols="12">
              <v-select 
                v-model="goal" 
                :items="[{title:'Perder Peso', value:'loss'}, {title:'Mantener', value:'maintenance'}, {title:'Ganar Músculo', value:'gain'}]" 
                label="Objetivo" 
                variant="outlined" 
                rounded="lg"
              ></v-select>
            </v-col>
          </v-row>

          <v-expand-transition>
            <v-card v-if="calculatedResults" color="deep-purple-lighten-5" flat rounded="xl" class="mt-4 pa-5 border-dashed border-sm border-deep-purple-lighten-3">
              <div class="text-caption font-weight-black text-deep-purple-accent-4 text-uppercase mb-4 text-center" style="letter-spacing: 1px">
                Sugerencia Mifflin-St Jeor
              </div>
              <v-row no-gutters class="text-center align-center">
                <v-col>
                  <div class="text-h6 font-weight-black text-deep-purple-accent-4">{{ calculatedResults.calories }}</div>
                  <div class="text-caption font-weight-bold text-deep-purple-lighten-2">KCAL</div>
                </v-col>
                <v-divider vertical class="mx-2 border-opacity-25" color="deep-purple-accent-4"></v-divider>
                <v-col>
                  <div class="text-h6 font-weight-black text-deep-purple-accent-4">{{ calculatedResults.protein }}g</div>
                  <div class="text-caption font-weight-bold text-deep-purple-lighten-2">PROT</div>
                </v-col>
                <v-col>
                  <div class="text-h6 font-weight-black text-deep-purple-accent-4">{{ calculatedResults.carbs }}g</div>
                  <div class="text-caption font-weight-bold text-deep-purple-lighten-2">CARBS</div>
                </v-col>
                <v-col>
                  <div class="text-h6 font-weight-black text-deep-purple-accent-4">{{ calculatedResults.fat }}g</div>
                  <div class="text-caption font-weight-bold text-deep-purple-lighten-2">GRASA</div>
                </v-col>
              </v-row>
              <v-btn block color="deep-purple-accent-4" rounded="pill" class="mt-6 font-weight-bold" elevation="0" @click="applyMacros(calculatedResults)">
                Actualizar mis metas
              </v-btn>
            </v-card>
          </v-expand-transition>
        </v-window-item>

        <v-window-item value="manual">
          <v-row dense>
            <v-col cols="12">
              <v-text-field v-model.number="manualCalories" label="Calorías totales" variant="outlined" rounded="lg" prepend-inner-icon="mdi-fire" color="deep-purple-accent-4"></v-text-field>
            </v-col>
            <v-col cols="4">
              <v-text-field v-model.number="manualProtein" label="Prot (g)" variant="outlined" rounded="lg"></v-text-field>
            </v-col>
            <v-col cols="4">
              <v-text-field v-model.number="manualCarbs" label="Carbs (g)" variant="outlined" rounded="lg"></v-text-field>
            </v-col>
            <v-col cols="4">
              <v-text-field v-model.number="manualFat" label="Grasa (g)" variant="outlined" rounded="lg"></v-text-field>
            </v-col>
          </v-row>
          <v-btn block color="deep-purple-accent-4" rounded="pill" class="mt-4 font-weight-bold" elevation="0" @click="saveManual">
            Guardar Manualmente
          </v-btn>
        </v-window-item>
      </v-window>
    </v-card>
  </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue';
import DailyGoals from '@/components/common/DailyGoals.vue';
import { useMacroCalculator } from '@/composables/useMacroCalculator';
import { useMacrosStore } from '@/stores/useMacrosStore';

const activeTab = ref('auto');
const macrosStore = useMacrosStore();

const { 
  gender, weight, height, age, activityLevel, goal, 
  activityFactors, calculatedResults 
} = useMacroCalculator();

const manualCalories = ref(0);
const manualProtein = ref(0);
const manualCarbs = ref(0);
const manualFat = ref(0);

// Cargar macro existente si hay uno
const loadExistingMacro = () => {
  if (macrosStore.macros.length > 0) {
    // El backend solo permite un macro por usuario, así que tomamos el primero
    const existingMacro = macrosStore.macros[0];
    manualCalories.value = existingMacro.kcal || 0;
    manualProtein.value = parseFloat(existingMacro.prot) || 0;
    manualCarbs.value = parseFloat(existingMacro.carb) || 0;
    manualFat.value = parseFloat(existingMacro.fat) || 0;
  }
};

const applyMacros = async (data) => {
  try {
    const macroData = {
      kcal: data.calories,
      prot: data.protein.toString(),
      carb: data.carbs.toString(),
      fat: data.fat.toString(),
    };

    if (macrosStore.macros.length > 0) {
      // Si ya existe un macro, actualizarlo
      const existingMacro = macrosStore.macros[0];
      await macrosStore.updateMacro(existingMacro.id, macroData);
    } else {
      // Si no existe, crear uno nuevo
      await macrosStore.createMacro(macroData);
    }
    
    // Actualizar los valores manuales para que se reflejen en el formulario
    manualCalories.value = data.calories;
    manualProtein.value = data.protein;
    manualCarbs.value = data.carbs;
    manualFat.value = data.fat;
  } catch (error) {
    console.error("Error applying macros:", error);
  }
};

const saveManual = async () => {
  if (manualCalories.value <= 0 || manualProtein.value < 0 || manualCarbs.value < 0 || manualFat.value < 0) {
    console.error("Valores inválidos");
    return;
  }

  try {
    const macroData = {
      kcal: manualCalories.value,
      prot: manualProtein.value.toString(),
      carb: manualCarbs.value.toString(),
      fat: manualFat.value.toString(),
    };

    if (macrosStore.macros.length > 0) {
      // Si ya existe un macro, actualizarlo
      const existingMacro = macrosStore.macros[0];
      await macrosStore.updateMacro(existingMacro.id, macroData);
    } else {
      // Si no existe, crear uno nuevo
      await macrosStore.createMacro(macroData);
    }
  } catch (error) {
    console.error("Error saving manual macros:", error);
  }
};

onMounted(async () => {
  await macrosStore.fetchMacros();
  loadExistingMacro();
});
</script>
