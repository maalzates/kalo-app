<script setup>
import { ref, computed } from "vue";
import { Bar } from "vue-chartjs";
import { useStatsCalculator } from "@/composables/useStatsCalculator";
import {
    Chart as ChartJS,
    Title,
    Tooltip,
    Legend,
    BarElement,
    CategoryScale,
    LinearScale,
    PointElement,
    LineElement,
    LineController,
} from "chart.js";

ChartJS.register(
    Title,
    Tooltip,
    Legend,
    BarElement,
    CategoryScale,
    LinearScale,
    PointElement,
    LineElement,
    LineController
);

const props = defineProps({
    macroGoals: { type: Array, default: () => [] },
    mealLogs: { type: Array, default: () => [] },
});

const { aggregateLogsByDay, getTargetForDate } = useStatsCalculator();
const selectedMetric = ref("kcal");

const metrics = [
    { title: "Calorías", value: "kcal", color: "#9575CD" },
    { title: "Proteína", value: "protein", color: "#FF8A80" },
    { title: "Carbos", value: "carbs", color: "#81C784" },
    { title: "Grasas", value: "fat", color: "#FFD54F" },
];

const chartData = computed(() => {
    const dailyData = aggregateLogsByDay(props.mealLogs);
    const sortedDates = Object.keys(dailyData).sort();

    if (sortedDates.length === 0) return null;

    const currentMetricObj = metrics.find(
        (m) => m.value === selectedMetric.value
    );

    const labels = sortedDates.map((d) => {
        const [year, month, day] = d.split("-");
        return new Date(year, month - 1, day).toLocaleDateString(undefined, {
            day: "numeric",
            month: "short",
        });
    });
    const consumoData = sortedDates.map(
        (date) => dailyData[date][selectedMetric.value]
    );
    const objetivoData = sortedDates.map((date) =>
        getTargetForDate(date, props.macroGoals, selectedMetric.value)
    );

    const finalData = {
        labels: labels,
        datasets: [
            {
                label: "Consumo Real",
                type: "bar",
                backgroundColor: currentMetricObj.color,
                data: consumoData,
                borderRadius: 6,
                order: 2,
            },
            {
                label: "Objetivo",
                type: "line",
                borderColor: "#546E7A",
                borderWidth: 2,
                borderDash: [5, 5],
                pointRadius: 3,
                data: objetivoData,
                order: 1,
                spanGaps: true,
            },
        ],
    };

    console.log(
        "🎨 OBJETO FINAL COMPLETO:",
        JSON.parse(JSON.stringify(finalData))
    );

    return finalData;
});

const chartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: { position: "bottom" },
        tooltip: { mode: "index", intersect: false, callbacks: {
            label: function (context) {
                let label = context.dataset.label || "";
                if (label) {
                    label += ": ";
                }
                // ✅ Formatear sin comas, con 2 decimales
                label += context.parsed.y.toFixed(2);
                return label;
            },
        } },
        
    },
    scales: {
        y: { beginAtZero: true, grid: { color: "#F0F0F0" } },
        x: { grid: { display: false } },
    },
};
</script>

<template>
    <v-card class="pa-6" rounded="xl" border elevation="0">
        <div class="d-flex justify-space-between align-center mb-6">
            <span class="text-subtitle-1 font-weight-bold"
                >Llegaste a tus requerimientos?</span
            >
            <v-select
                v-model="selectedMetric"
                :items="metrics"
                item-title="title"
                item-value="value"
                density="compact"
                variant="outlined"
                hide-details
                rounded="lg"
                style="max-width: 140px"
            ></v-select>
        </div>

        <div style="height: 350px">
            <Bar
                v-if="chartData"
                :data="chartData"
                :options="chartOptions"
                :key="`chart-${selectedMetric}`"
            />
            <v-sheet
                v-else
                class="d-flex align-center justify-center fill-height bg-grey-lighten-5 rounded-xl border"
            >
                <span class="text-grey text-caption"
                    >Sin datos para graficar</span
                >
            </v-sheet>
        </div>
    </v-card>
</template>
