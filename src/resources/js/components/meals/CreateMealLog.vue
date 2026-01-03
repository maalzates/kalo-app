<template>
    <v-dialog
        :model-value="modelValue"
        @update:model-value="$emit('update:modelValue', $event)"
        fullscreen
        transition="dialog-bottom-transition"
    >
        <v-card class="bg-grey-lighten-4">
            <v-toolbar color="deep-purple-accent-4" dark flat class="position-sticky top-0 z-index-10">
                <v-btn icon @click="$emit('update:modelValue', false)">
                    <v-icon>mdi-close</v-icon>
                </v-btn>
                <v-toolbar-title class="text-body-1 font-weight-bold">
                    Registrar Consumo
                </v-toolbar-title>
                <v-spacer></v-spacer>
                <v-btn
                    variant="flat"
                    color="white"
                    class="text-deep-purple font-weight-bold rounded-pill px-4"
                    :disabled="!form.name || form.base_amount <= 0"
                    @click="saveMeal"
                >
                    Guardar
                </v-btn>
            </v-toolbar>

            <v-container class="pa-2 pa-sm-4" style="max-width: 800px">
                <v-card rounded="xl" class="pa-2 mb-3" elevation="1">
                    <v-tabs v-model="activeTab" grow color="deep-purple-accent-4" density="comfortable">
                        <v-tab value="food" class="text-caption font-weight-bold">Alimentos</v-tab>
                        <v-tab value="recipe" class="text-caption font-weight-bold">Recetas</v-tab>
                        <v-tab value="ai" class="text-caption font-weight-bold">
                            <v-icon start size="16">mdi-auto-fix</v-icon>IA
                        </v-tab>
                    </v-tabs>
                </v-card>

                <v-window v-model="activeTab">
                    <v-window-item value="ai" class="pb-3">
                        <v-card rounded="xl" class="overflow-hidden" border="sm">
                            <AddFoodPhoto @analysis-finished="onAiAnalysisFinished" />
                        </v-card>
                    </v-window-item>

                    <v-window-item v-for="tab in ['food', 'recipe']" :key="tab" :value="tab">
                        <v-card rounded="xl" class="pa-4 mb-3" border="sm">
                            <v-autocomplete
                                v-model="selectedItem"
                                :label="activeTab === 'food' ? 'Buscar alimento...' : 'Buscar receta...'"
                                :items="filteredLibrary"
                                item-title="name"
                                return-object
                                variant="outlined"
                                rounded="lg"
                                color="deep-purple-accent-4"
                                prepend-inner-icon="mdi-magnify"
                                hide-details
                                @update:model-value="onFoodSelected"
                            >
                                <template v-slot:no-data>
                                    <v-list-item @click="handleCreateNew" class="py-2">
                                        <v-list-item-title class="text-deep-purple font-weight-bold text-body-2">
                                            <v-icon start size="18">mdi-plus-circle</v-icon>
                                            Crear nuevo registro
                                        </v-list-item-title>
                                    </v-list-item>
                                </template>
                            </v-autocomplete>
                        </v-card>
                    </v-window-item>
                </v-window>

                <v-expand-transition>
                    <div v-if="form.name">
                        <div class="text-overline mb-1 ml-2 text-deep-purple-accent-4 font-weight-bold">
                            Detalles del Registro
                        </div>

                        <v-card rounded="xl" class="pa-4 pa-sm-5 mb-4" border="sm">
                            <div class="text-subtitle-1 font-weight-black mb-4 text-deep-purple-darken-4">
                                {{ form.name }}
                            </div>

                            <v-row dense>
                                <v-col :cols="activeTab === 'recipe' ? 12 : 7">
                                    <v-text-field
                                        v-model.number="form.base_amount"
                                        :label="activeTab === 'recipe' ? 'Porciones' : 'Cantidad'"
                                        type="number"
                                        variant="outlined"
                                        rounded="lg"
                                        density="comfortable"
                                        color="deep-purple-accent-4"
                                        hide-details
                                    ></v-text-field>
                                </v-col>
                                <v-col v-if="activeTab !== 'recipe'" cols="5">
                                    <v-select
                                        v-model="form.base_unit"
                                        :items="['g', 'ml', 'unidad']"
                                        label="Unidad"
                                        variant="outlined"
                                        rounded="lg"
                                        density="comfortable"
                                        color="deep-purple-accent-4"
                                        hide-details
                                    ></v-select>
                                </v-col>
                            </v-row>

                            <v-card variant="tonal" color="deep-purple-accent-4" rounded="lg" class="mt-4 pa-3">
                                <v-row no-gutters class="text-center">
                                    <v-col v-for="metric in metrics" :key="metric.label">
                                        <div class="text-subtitle-1 font-weight-black">{{ form[metric.key] }}{{ metric.unit }}</div>
                                        <div class="text-caption font-weight-bold text-uppercase">{{ metric.label }}</div>
                                    </v-col>
                                </v-row>
                            </v-card>
                        </v-card>
                    </div>
                </v-expand-transition>
            </v-container>
        </v-card>

        <AddOrEditIngredient v-model="isCreateIngredientDialogOpen" :initial-data="null" />
        <AddOrEditRecipe v-model="isCreateRecipeDialogOpen" :initial-data="null" />
    </v-dialog>
</template>

<script setup>
import { ref, computed, onMounted, watch } from "vue";
import { useMealLogsStore } from "@/stores/useMealLogsStore";
import { useIngredientsStore } from "@/stores/useIngredientsStore";
import { useRecipesStore } from "@/stores/useRecipesStore";
import { useDateStore } from "@/stores/useDateStore";

import AddOrEditIngredient from "@/components/ingredients/AddOrEditIngredient.vue";
import AddOrEditRecipe from "@/components/recipes/AddOrEditRecipe.vue";
import AddFoodPhoto from "@/components/meals/AddFoodPhoto.vue";

const props = defineProps({ modelValue: Boolean });
const emit = defineEmits(["update:modelValue"]);

const mealLogsStore = useMealLogsStore();
const dateStore = useDateStore();
const ingredientsStore = useIngredientsStore();
const recipesStore = useRecipesStore();

const activeTab = ref("food");
const selectedItem = ref(null);
const isCreateIngredientDialogOpen = ref(false);
const isCreateRecipeDialogOpen = ref(false);

const metrics = [
    { label: 'kcal', key: 'calories', unit: '' },
    { label: 'Prot', key: 'protein', unit: 'g' },
    { label: 'Carb', key: 'carbs', unit: 'g' },
    { label: 'Grasa', key: 'fat', unit: 'g' }
];

const initialState = {
    id: null, name: "", base_amount: 0, base_unit: "", calories: 0,
    protein: 0, carbs: 0, fat: 0, base_amount_ref: 100,
    base_kcal: 0, base_prot: 0, base_carb: 0, base_fat: 0
};

const form = ref({ ...initialState });

const filteredLibrary = computed(() => {
    return activeTab.value === "food"
        ? ingredientsStore.publicAndPrivateIngredients
        : recipesStore.recipesForMealLog;
});

const onAiAnalysisFinished = (aiResult) => {
    form.value = {
        ...initialState,
        name: aiResult.detected_name || "Comida detectada",
        base_amount: aiResult.amount || 100,
        base_unit: 'g',
        calories: Math.round(aiResult.kcal || 0),
        protein: parseFloat(aiResult.prot || 0).toFixed(1),
        carbs: parseFloat(aiResult.carb || 0).toFixed(1),
        fat: parseFloat(aiResult.fat || 0).toFixed(1),
        base_amount_ref: aiResult.amount || 100,
        base_kcal: aiResult.kcal || 0,
        base_prot: aiResult.prot || 0,
        base_carb: aiResult.carb || 0,
        base_fat: aiResult.fat || 0,
    };
};

const onFoodSelected = (item) => {
    if (!item) return;
    const isRecipe = activeTab.value === 'recipe';
    const amount = isRecipe ? (item.servings || 1) : (item.amount || 100);
    
    form.value = {
        ...initialState,
        id: item.id, name: item.name, base_amount: amount,
        base_unit: isRecipe ? 'serving' : (item.unit || 'g'),
        calories: Math.round(isRecipe ? item.total_kcal : item.kcal),
        protein: parseFloat(isRecipe ? item.total_prot : item.prot).toFixed(1),
        carbs: parseFloat(isRecipe ? item.total_carb : item.carb).toFixed(1),
        fat: parseFloat(isRecipe ? item.total_fat : item.fat).toFixed(1),
        base_amount_ref: amount,
        base_kcal: isRecipe ? item.total_kcal : item.kcal,
        base_prot: isRecipe ? item.total_prot : item.prot,
        base_carb: isRecipe ? item.total_carb : item.carb,
        base_fat: isRecipe ? item.total_fat : item.fat,
    };
};

watch(() => form.value.base_amount, (newVal) => {
    if (form.value.base_amount_ref > 0) {
        const factor = newVal / form.value.base_amount_ref;
        form.value.calories = Math.round(form.value.base_kcal * factor);
        form.value.protein = (form.value.base_prot * factor).toFixed(1);
        form.value.carbs = (form.value.base_carb * factor).toFixed(1);
        form.value.fat = (form.value.base_fat * factor).toFixed(1);
    }
});

const saveMeal = async () => {
    try {
        const date = dateStore.selectedDate.toISOString().split('T')[0];
        const mealData = {
            quantity: form.value.base_amount.toString(),
            unit: form.value.base_unit || 'g',
            logged_at: date,
            ...(activeTab.value === 'food' ? { ingredient_id: form.value.id } : { recipe_id: form.value.id })
        };
        await mealLogsStore.addMealLog(mealData);
        await mealLogsStore.fetchMealLogs({ date_from: date, date_to: date });
        emit("update:modelValue", false);
    } catch (e) { console.error(e); }
};

watch(activeTab, () => {
    form.value = { ...initialState };
    selectedItem.value = null;
});

onMounted(() => {
    ingredientsStore.fetchPublicAndPrivateIngredients();
    recipesStore.fetchRecipesForMealLog();
});
</script>

<style scoped>
.z-index-10 { z-index: 10; }
.line-height-1 { line-height: 1.2; }
:deep(.v-tab) { text-transform: none !important; letter-spacing: 0; }
</style>
