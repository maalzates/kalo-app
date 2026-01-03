<script setup>
  import { computed } from 'vue';
  import { Bar } from 'vue-chartjs';
  // Importamos los elementos específicos para el gráfico de barras
  import { 
    Chart as ChartJS, 
    Title, 
    Tooltip, 
    Legend, 
    BarElement, // <--- Este es el vital para Macros
    CategoryScale, 
    LinearScale 
  } from 'chart.js';
  
  // Registramos los componentes dentro del archivo del hijo
  ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale);
  
  const props = defineProps({
    data: { type: Array, required: true }
  });
  
  const chartData = computed(() => {
    // Ordenamos para asegurar cronología
    const sorted = [...props.data].sort((a, b) => new Date(a.created_at) - new Date(b.created_at));
    
    return {
      labels: sorted.map(i => new Date(i.created_at).toLocaleDateString(undefined, { day: 'numeric', month: 'short' })),
      datasets: [{
        label: 'Calorías',
        backgroundColor: '#D1C4E9',
        data: sorted.map(i => i.kcal),
        borderRadius: 6
      }]
    };
  });
  
  const chartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: { legend: { display: false } }
  };
  </script>
  
  <template>
    <v-card class="pa-6" rounded="xl" border elevation="0">
      <div class="d-flex align-center mb-4">
        <v-icon icon="mdi-fire" color="orange-darken-2" class="mr-2"></v-icon>
        <span class="text-subtitle-1 font-weight-bold">Consumo Calórico (kcal)</span>
      </div>
      <div style="height: 300px;">
        <Bar v-if="props.data && props.data.length > 0" :data="chartData" :options="chartOptions" />
        <v-sheet v-else class="d-flex align-center justify-center fill-height bg-grey-lighten-4 rounded-lg text-grey">
          Sin datos de calorías
        </v-sheet>
      </div>
    </v-card>
  </template>
