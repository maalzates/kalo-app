<template>
    <v-card 
      v-if="user"
      color="deep-purple-lighten-5" 
      rounded="xl" 
      variant="flat"
    >
      <v-row no-gutters align="center">
        <v-col 
          cols="12" 
          sm="4" 
          class="pa-4 border-b-sm border-sm-0 border-e-sm-border-deep-purple-lighten-4"
        >
          <div class="d-flex align-center justify-center justify-sm-start">
            <v-icon 
              icon="mdi-flag-checkered" 
              color="deep-purple-accent-4" 
              class="mr-3 opacity-80"
            ></v-icon>
            <div>
              <div class="text-overline font-weight-black text-deep-purple-accent-4 line-height-1">
                Metas Diarias
              </div>
              <div class="text-caption text-deep-purple-lighten-1 text-truncate">
                Basado en tu perfil
              </div>
            </div>
          </div>
        </v-col>
  
        <v-col cols="12" sm="8" class="pa-2">
          <v-row no-gutters justify="space-around" class="text-center align-center">
            <v-col>
              <div class="text-h6 font-weight-black text-deep-purple-accent-4">
                {{ calories }}
              </div>
              <div class="text-caption font-weight-bold text-deep-purple-lighten-2 text-uppercase">
                kcal
              </div>
            </v-col>
  
            <v-divider vertical inset color="deep-purple-accent-4" class="border-opacity-25 mx-1"></v-divider>
  
            <v-col v-for="macro in macros" :key="macro.label">
    <div class="text-h6 font-weight-black text-deep-purple-accent-4 mb-1">
        {{ macro.value }}<small class="text-caption ml-1 font-weight-medium">g</small>
    </div>
    
    <div class="text-caption font-weight-bold text-deep-purple-lighten-2 text-uppercase">
        {{ macro.label }}
    </div>
</v-col>
          </v-row>
        </v-col>
      </v-row>
    </v-card>
  </template>
  
  <script setup>
  import { computed, watch } from 'vue';
  import { useUserStore } from "@/stores/useUserStore";
  import { useMacrosStore } from "@/stores/useMacrosStore";
  import { useDateStore } from "@/stores/useDateStore";
  
  const userStore = useUserStore();
  const macrosStore = useMacrosStore();
  const dateStore = useDateStore();
  
  const user = computed(() => userStore.user);
  
  // Obtener el macro para la fecha seleccionada (busca el más cercano hacia atrás)
  const macroForDate = computed(() => {
    return macrosStore.getMacroForDate(dateStore.selectedDate);
  });
  
  // Calorías del macro encontrado para la fecha
  const calories = computed(() => {
    return macroForDate.value?.kcal || 0;
  });
  
  // Macros del macro encontrado para la fecha
  const macros = computed(() => [
    { label: 'Prot', value: parseFloat(macroForDate.value?.prot) || 0 },
    { label: 'Carb', value: parseFloat(macroForDate.value?.carb) || 0 },
    { label: 'Grasa', value: parseFloat(macroForDate.value?.fat) || 0 },
  ]);
  
  // Cargar macros cuando cambie la fecha
  const loadMacrosForDate = async () => {
    if (macrosStore.macros.length === 0) {
      await macrosStore.fetchMacros();
    }
  };
  
  watch(() => dateStore.selectedDate, () => {
    loadMacrosForDate();
  }, { immediate: true });
  </script>
