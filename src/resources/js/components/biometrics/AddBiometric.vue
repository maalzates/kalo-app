<template>
  <v-card rounded="xl" elevation="2" border="sm">
    <v-card-text class="pa-6">
      <v-row dense>
        <v-col cols="12" sm="6">
          <v-text-field
            v-model.number="weight"
            label="Peso"
            type="number"
            step="0.1"
            suffix="kg"
            variant="outlined"
            rounded="lg"
            color="deep-purple-accent-4"
            prepend-inner-icon="mdi-weight-kilogram"
            :rules="[rules.required, rules.positive]"
          ></v-text-field>
        </v-col>

        <v-col cols="12" sm="6">
          <v-text-field
            v-model.number="fatPercentage"
            label="Grasa Corporal"
            type="number"
            step="0.1"
            suffix="%"
            variant="outlined"
            rounded="lg"
            color="deep-purple-accent-4"
            prepend-inner-icon="mdi-percent"
            hint="Opcional"
            persistent-hint
          ></v-text-field>
        </v-col>

        <v-col cols="12" sm="6">
          <v-text-field
            v-model.number="cleanMass"
            label="Masa Magra"
            type="number"
            step="0.1"
            suffix="kg"
            variant="outlined"
            rounded="lg"
            color="deep-purple-accent-4"
            prepend-inner-icon="mdi-human"
            hint="Opcional"
            persistent-hint
          ></v-text-field>
        </v-col>

        <v-col cols="12" sm="6">
          <v-text-field
            v-model.number="waistCircumference"
            label="Circunferencia Cintura"
            type="number"
            step="0.1"
            suffix="cm"
            variant="outlined"
            rounded="lg"
            color="deep-purple-accent-4"
            prepend-inner-icon="mdi-tape-measure"
            hint="Opcional"
            persistent-hint
          ></v-text-field>
        </v-col>
      </v-row>

      <v-btn
        block
        color="deep-purple-accent-4"
        rounded="pill"
        class="mt-4 font-weight-bold"
        elevation="0"
        :loading="loading"
        :disabled="!isValid"
        @click="saveBiometric"
      >
        Guardar Medición
      </v-btn>
    </v-card-text>
  </v-card>
</template>

<script setup>
import { ref, computed } from 'vue';
import { useBiometricsStore } from '@/stores/useBiometricsStore';
import { useToast } from 'vue-toastification';

const biometricsStore = useBiometricsStore();
const toast = useToast();

const weight = ref(null);
const fatPercentage = ref(null);
const cleanMass = ref(null);
const waistCircumference = ref(null);
const loading = ref(false);

const rules = {
  required: value => !!value || 'Campo requerido',
  positive: value => value > 0 || 'Debe ser mayor a 0'
};

const isValid = computed(() => {
  return weight.value && weight.value > 0;
});

const saveBiometric = async () => {
  if (!isValid.value) {
    toast.error('Por favor completa los campos requeridos');
    return;
  }

  loading.value = true;
  try {
    const data = {
      weight: weight.value.toString(),
      measured_at: new Date().toISOString().split('T')[0]
    };

    if (fatPercentage.value && fatPercentage.value > 0) {
      data.fat_percentage = fatPercentage.value.toString();
    }

    if (cleanMass.value && cleanMass.value > 0) {
      data.clean_mass = cleanMass.value.toString();
    }

    if (waistCircumference.value && waistCircumference.value > 0) {
      data.waist_circumference = waistCircumference.value.toString();
    }

    await biometricsStore.createBiometric(data);
    toast.success('Medición guardada correctamente');

    // Reset form
    weight.value = null;
    fatPercentage.value = null;
    cleanMass.value = null;
    waistCircumference.value = null;
  } catch (error) {
    console.error('Error saving biometric:', error);
    toast.error('Error al guardar la medición');
  } finally {
    loading.value = false;
  }
};
</script>
