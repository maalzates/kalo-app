<script setup>
import { ref, computed } from "vue";
import { Line } from "vue-chartjs";
import { useStatsCalculator } from "@/composables/useStatsCalculator";
import {
    Chart as ChartJS,
    Title,
    Tooltip,
    Legend,
    CategoryScale,
    LinearScale,
    PointElement,
    LineElement,
    Filler
} from "chart.js";

ChartJS.register(
    Title,
    Tooltip,
    Legend,
    CategoryScale,
    LinearScale,
    PointElement,
    LineElement,
    Filler
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

const hexToRgba = (hex, alpha = 0.2) => {
    const r = parseInt(hex.slice(1, 3), 16);
    const g = parseInt(hex.slice(3, 5), 16);
    const b = parseInt(hex.slice(5, 7), 16);
    return `rgba(${r}, ${g}, ${b}, ${alpha})`;
};

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

    return {
        labels: labels,
        datasets: [
            {
                label: "Consumo Real",
                borderColor: currentMetricObj.color,
                backgroundColor: hexToRgba(currentMetricObj.color, 0.3),
                data: consumoData,
                fill: true,
                tension: 0.4,
                pointRadius: 5,
                pointHoverRadius: 7,
                pointBackgroundColor: currentMetricObj.color,
                pointBorderColor: "#fff",
                pointBorderWidth: 2,
                borderWidth: 3,
            },
            {
                label: "Objetivo",
                borderColor: "#37474F",
                backgroundColor: "rgba(55, 71, 79, 0.1)",
                borderWidth: 3,
                borderDash: [8, 4],
                pointRadius: 5,
                pointHoverRadius: 7,
                pointBackgroundColor: "#37474F",
                pointBorderColor: "#fff",
                pointBorderWidth: 2,
                data: objetivoData,
                fill: false,
                spanGaps: true,
                tension: 0.1,
            },
        ],
    };
});

const chartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: {
            position: "bottom",
            labels: {
                usePointStyle: true,
                padding: 15,
                font: { size: 12, weight: '500' }
            }
        },
        tooltip: {
            mode: "index",
            intersect: false,
            backgroundColor: 'rgba(0, 0, 0, 0.8)',
            titleFont: { size: 13, weight: 'bold' },
            bodyFont: { size: 12 },
            padding: 12,
            callbacks: {
                label: function (context) {
                    let label = context.dataset.label || "";
                    if (label) {
                        label += ": ";
                    }
                    label += context.parsed.y.toFixed(1);
                    return label;
                },
                afterBody: function(context) {
                    const consumo = context[0].parsed.y;
                    const objetivo = context[1]?.parsed.y;
                    if (objetivo) {
                        const diff = consumo - objetivo;
                        const percentage = ((diff / objetivo) * 100).toFixed(1);
                        const symbol = diff >= 0 ? '+' : '';
                        return `Diferencia: ${symbol}${diff.toFixed(1)} (${symbol}${percentage}%)`;
                    }
                    return '';
                }
            }
        }
    },
    scales: {
        y: {
            beginAtZero: true,
            grid: { color: "rgba(0, 0, 0, 0.05)" },
            ticks: {
                font: { size: 11 },
                callback: function(value) {
                    return value.toFixed(0);
                }
            }
        },
        x: {
            grid: { display: false },
            ticks: { font: { size: 11 } }
        }
    }
};
</script>

<template>
    <v-card class="pa-6" rounded="xl" border elevation="0">
        <div class="d-flex flex-column flex-sm-row justify-space-between align-start align-sm-center mb-6 ga-3">
            <div class="d-flex align-center">
                <v-icon icon="mdi-chart-line-variant" color="primary" class="mr-2"></v-icon>
                <span class="text-subtitle-1 font-weight-bold">Llegaste a tus requerimientos?</span>
            </div>

            <v-chip-group
                v-model="selectedMetric"
                mandatory
                selected-class="text-primary font-weight-bold"
                density="compact"
            >
                <v-chip
                    v-for="metric in metrics"
                    :key="metric.value"
                    :value="metric.value"
                    variant="tonal"
                    size="small"
                    :color="selectedMetric === metric.value ? 'primary' : 'default'"
                >
                    {{ metric.title }}
                </v-chip>
            </v-chip-group>
        </div>

        <div style="height: 320px">
            <Line
                v-if="chartData"
                :data="chartData"
                :options="chartOptions"
                :key="`chart-${selectedMetric}`"
            />
            <v-sheet
                v-else
                class="d-flex align-center justify-center fill-height bg-grey-lighten-4 rounded-lg"
            >
                <div class="text-center text-grey">
                    <v-icon size="48" color="grey-lighten-1">mdi-chart-line-variant</v-icon>
                    <p class="text-caption mt-2">Sin datos para graficar</p>
                </div>
            </v-sheet>
        </div>
    </v-card>
</template>
