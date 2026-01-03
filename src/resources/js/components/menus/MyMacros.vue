<template>
  <div class="pa-4 ml-0 ml-md-6">
    <v-row>
      <v-col cols="12" lg="7">
        <div class="mb-6">
          <div class="text-overline mb-2 ml-1 text-deep-purple-accent-4 font-weight-bold">
            Configuración Vigente
          </div>
          <DailyGoals />
        </div>

        <div class="text-overline mb-2 ml-1 text-grey-darken-1 font-weight-bold">
          Actualizar Objetivos
        </div>
        <UpdateMyMacros />
      </v-col>

      <v-col cols="12" lg="5">
        <div class="text-overline mb-2 ml-1 text-grey-darken-1 font-weight-bold">
          Evolución
        </div>
        <MyMacrosList />
      </v-col>
    </v-row>
  </div>
</template>

<script setup>
import DailyGoals from '@/components/common/DailyGoals.vue';
import UpdateMyMacros from '@/components/macros/UpdateMyMacros.vue';
import MyMacrosList from '@/components/macros/MyMacrosList.vue';
import { onMounted } from 'vue';
import { useMacrosStore } from '@/stores/useMacrosStore';

const macrosStore = useMacrosStore();

onMounted(async () => {
  await macrosStore.fetchMacros();
});
</script>
