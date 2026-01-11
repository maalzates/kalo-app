<template>
  <v-card class="pa-3" rounded="lg" elevation="0" border style="width: fit-content; max-width: 100%;">
    <div v-if="heatmapData.length > 0" class="d-flex align-center flex-wrap" style="gap: 10px;">
      <!-- Título -->
      <div class="d-flex align-center flex-shrink-0">
        <v-icon icon="mdi-calendar-check" color="primary" size="small" class="mr-1"></v-icon>
        <span class="text-body-2 font-weight-medium text-medium-emphasis">Adherencia</span>
      </div>

      <!-- Heatmap inline -->
      <div class="d-flex flex-wrap align-center" style="gap: 3px;">
        <div
          v-for="day in heatmapData"
          :key="day.date"
          class="heatmap-cell"
          :style="{ backgroundColor: getColor(day.score) }"
        >
          <v-tooltip activator="parent" location="top">
            <span class="text-caption">{{ formatDate(day.date) }}: {{ Math.round(day.score) }}%</span>
          </v-tooltip>
        </div>
      </div>

      <!-- Stats inline -->
      <div class="d-flex align-center flex-shrink-0">
        <span class="text-body-2">
          <span class="font-weight-bold text-primary">{{ averageAdherence }}%</span>
          <span class="text-medium-emphasis mx-1">·</span>
          <span class="text-medium-emphasis">{{ heatmapData.length }}d</span>
        </span>
      </div>
    </div>

    <div v-else class="text-body-2 text-medium-emphasis text-center py-2">
      Sin datos
    </div>
  </v-card>
</template>

<script setup>
import { computed, ref } from 'vue';
import { useStatsCalculator } from '@/composables/useStatsCalculator';

const props = defineProps({
  mealLogs: { type: Array, default: () => [] },
  macroGoals: { type: Array, default: () => [] }
});

const { calculateAdherence } = useStatsCalculator();
const hoveredDay = ref(null);

const adherenceScores = computed(() => calculateAdherence(props.mealLogs, props.macroGoals));

const heatmapData = computed(() => {
  return Object.keys(adherenceScores.value)
    .sort()
    .map(date => ({
      date,
      score: adherenceScores.value[date]
    }));
});

const averageAdherence = computed(() => {
  if (heatmapData.value.length === 0) return 0;
  const sum = heatmapData.value.reduce((acc, day) => acc + day.score, 0);
  return Math.round(sum / heatmapData.value.length);
});

const getColor = (score) => {
  if (score >= 90) return '#558B2F'; // Verde oscuro
  if (score >= 75) return '#7CB342'; // Verde medio
  if (score >= 60) return '#9CCC65'; // Verde claro
  if (score >= 40) return '#C5E1A5'; // Verde muy claro
  return '#EEEEEE'; // Gris (sin datos o muy bajo)
};

const formatDate = (dateStr) => {
  const [year, month, day] = dateStr.split('-');
  return new Date(year, month - 1, day).toLocaleDateString('es-ES', {
    day: 'numeric',
    month: 'short',
    year: 'numeric'
  });
};
</script>

<style scoped>
.heatmap-cell {
  width: 12px;
  height: 12px;
  border-radius: 3px;
  cursor: pointer;
  flex-shrink: 0;
  transition: transform 0.15s ease;
}

.heatmap-cell:hover {
  transform: scale(1.4);
}
</style>
