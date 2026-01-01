<template>
    <div>
    <v-dialog
        :model-value="modelValue"
        @update:model-value="$emit('update:modelValue', $event)"
        fullscreen
        transition="dialog-bottom-transition"
    >
        <v-card class="bg-grey-lighten-4">
            <v-toolbar color="deep-purple-accent-4" dark px-4>
                <v-btn icon @click="$emit('update:modelValue', false)">
                    <v-icon>mdi-close</v-icon>
                </v-btn>
                <div class="text-body-1 text-sm-h6 font-weight-bold text-wrap line-height-1 pl-2">
                    Registrar Consumo
                </div>
                <v-spacer></v-spacer>
                <v-btn
                    variant="flat"
                    color="white"
                    class="text-deep-purple font-weight-bold rounded-pill px-6"
                    :disabled="!form.name || form.base_amount <= 0"
                    @click="saveMeal"
                >
                    Guardar
                </v-btn>
            </v-toolbar>

            <v-container class="pa-4" style="max-width: 800px">
                <v-card rounded="xl" class="pa-4 mb-4" elevation="1" border="sm">
                    <div class="d-flex align-center justify-space-between mb-4">
                        <div class="d-flex align-center">
                            <v-icon color="deep-purple-accent-4" class="mr-2">mdi-calendar</v-icon>
                            <span class="text-subtitle-2 font-weight-bold text-grey-darken-2">
                                {{ dateStore.fullDate }}
                            </span>
                        </div>
                        <v-chip size="small" color="deep-purple-accent-4" variant="tonal" class="font-weight-bold">
                            LOG DIARIO
                        </v-chip>
                    </div>

                    <v-tabs v-model="activeTab" grow color="deep-purple-accent-4" class="bg-grey-lighten-4 rounded-lg">
                        <v-tab value="food" class="font-weight-bold">Alimentos</v-tab>
                        <v-tab value="recipe" class="font-weight-bold">Recetas</v-tab>
                    </v-tabs>
                </v-card>

                <v-card rounded="xl" class="pa-4 mb-4" elevation="1" border="sm">
                    <div class="text-subtitle-2 mb-2 ml-1 font-weight-bold text-grey-darken-2 uppercase">
                        Seleccionar de la biblioteca
                    </div>
                    <v-autocomplete
                        v-model="selectedItem"
                        :label="activeTab === 'food' ? 'Buscar en mis alimentos...' : 'Buscar en mis recetas...'"
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
                                    Crear {{ activeTab === "food" ? "nuevo alimento" : "nueva receta" }}
                                </v-list-item-title>
                            </v-list-item>
                        </template>
                    </v-autocomplete>
                </v-card>

                <v-expand-transition>
                    <div v-if="form.name">
                        <div class="text-overline mb-2 ml-2 text-deep-purple-accent-4 font-weight-bold">
                            Detalles del registro
                        </div>

                        <v-card rounded="xl" class="pa-5 mb-4" elevation="1" border="sm">
                            <div class="text-h6 font-weight-black mb-4 text-deep-purple-darken-4 text-truncate">
                                {{ form.name }}
                            </div>

                            <v-row dense>
                                <v-col :cols="activeTab === 'food' ? 7 : 12">
                                    <v-text-field
                                        v-model.number="form.base_amount"
                                        :label="activeTab === 'food' ? 'Cantidad consumida' : 'Número de porciones'"
                                        type="number"
                                        variant="outlined"
                                        rounded="lg"
                                        color="deep-purple-accent-4"
                                        hide-details
                                    ></v-text-field>
                                </v-col>

                                <v-col v-if="activeTab === 'food'" cols="5">
                                    <v-select
                                        v-model="form.base_unit"
                                        :items="['g', 'ml', 'unidad']"
                                        label="Unidad"
                                        variant="outlined"
                                        rounded="lg"
                                        color="deep-purple-accent-4"
                                        hide-details
                                    ></v-select>
                                </v-col>
                            </v-row>

                            <v-card variant="tonal" color="deep-purple-accent-4" rounded="lg" class="mt-6 pa-4">
                                <v-row no-gutters class="text-center">
                                    <v-col>
                                        <div class="text-h6 font-weight-black">{{ form.calories }}</div>
                                        <div class="text-caption font-weight-bold uppercase">kcal</div>
                                    </v-col>
                                    <v-divider vertical class="mx-2 opacity-20"></v-divider>
                                    <v-col>
                                        <div class="text-h6 font-weight-black">{{ form.protein }}g</div>
                                        <div class="text-caption font-weight-bold uppercase">Prot</div>
                                    </v-col>
                                    <v-divider vertical class="mx-2 opacity-20"></v-divider>
                                    <v-col>
                                        <div class="text-h6 font-weight-black">{{ form.carbs }}g</div>
                                        <div class="text-caption font-weight-bold uppercase">Carbs</div>
                                    </v-col>
                                    <v-divider vertical class="mx-2 opacity-20"></v-divider>
                                    <v-col>
                                        <div class="text-h6 font-weight-black">{{ form.fat }}g</div>
                                        <div class="text-caption font-weight-bold uppercase">Grasa</div>
                                    </v-col>
                                </v-row>
                            </v-card>
                        </v-card>
                    </div>
                </v-expand-transition>
            </v-container>
        </v-card>
    </v-dialog>

    <!-- Diálogos para crear ingrediente o receta -->
    <AddOrEditIngredient 
        v-model="isCreateIngredientDialogOpen" 
        :initial-data="null"
    />
    <AddOrEditRecipe 
        v-model="isCreateRecipeDialogOpen" 
        :initial-data="null"
    />
    </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from "vue";
import { useMealLogsStore } from "@/stores/useMealLogsStore";
import { useIngredientsStore } from "@/stores/useIngredientsStore";
import { useRecipesStore } from "@/stores/useRecipesStore";
import { useDateStore } from "@/stores/useDateStore";
import AddOrEditIngredient from "@/components/ingredients/AddOrEditIngredient.vue";
import AddOrEditRecipe from "@/components/recipes/AddOrEditRecipe.vue";

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

const initialState = {
    name: "",
    base_amount: 0,
    base_unit: "",
    calories: 0,
    protein: 0,
    carbs: 0,
    fat: 0,
};

const form = ref({ ...initialState });

const filteredLibrary = computed(() => {
    return activeTab.value === "food"
        ? ingredientsStore.ingredients
        : recipesStore.recipes;
});

onMounted(() => {
    ingredientsStore.fetchIngredients();
    recipesStore.fetchRecipes();
});

const onFoodSelected = (item) => {
    if (item) {
        // Mapear datos del backend al formulario
        const baseAmount = activeTab.value === 'recipe' 
            ? (item.servings || 1) 
            : (item.amount || 100);
        
        const baseUnit = activeTab.value === 'recipe' 
            ? 'serving' 
            : (item.unit || 'g');
        
        // Para ingredientes: calcular valores basados en la cantidad base
        // Para recetas: los valores ya son totales por receta
        let calories = 0, protein = 0, carbs = 0, fat = 0;
        
        if (activeTab.value === 'recipe') {
            calories = item.total_kcal || 0;
            protein = parseFloat(item.total_prot || 0);
            carbs = parseFloat(item.total_carb || 0);
            fat = parseFloat(item.total_fat || 0);
        } else {
            // Para ingredientes, los valores son por base_amount
            const itemBaseAmount = item.amount || 100;
            const itemKcal = item.kcal || 0;
            const itemProt = parseFloat(item.prot || 0);
            const itemCarb = parseFloat(item.carb || 0);
            const itemFat = parseFloat(item.fat || 0);
            
            // Calcular valores para la cantidad base (100g por defecto)
            const factor = baseAmount / itemBaseAmount;
            calories = itemKcal * factor;
            protein = itemProt * factor;
            carbs = itemCarb * factor;
            fat = itemFat * factor;
        }
        
        form.value = {
            ...initialState,
            id: item.id,
            name: item.name,
            base_amount: baseAmount,
            base_unit: baseUnit,
            calories: Math.round(calories),
            protein: protein.toFixed(1),
            carbs: carbs.toFixed(1),
            fat: fat.toFixed(1),
            base_amount_ref: item.amount || item.servings || 100, // Para cálculos
            base_kcal: activeTab.value === 'recipe' ? (item.total_kcal || 0) : (item.kcal || 0),
            base_prot: activeTab.value === 'recipe' ? parseFloat(item.total_prot || 0) : parseFloat(item.prot || 0),
            base_carb: activeTab.value === 'recipe' ? parseFloat(item.total_carb || 0) : parseFloat(item.carb || 0),
            base_fat: activeTab.value === 'recipe' ? parseFloat(item.total_fat || 0) : parseFloat(item.fat || 0),
        };
    }
};

watch(() => form.value.base_amount, (newAmount) => {
    if (form.value.id && form.value.base_amount_ref) {
        const factor = newAmount / form.value.base_amount_ref;
        form.value.calories = Math.round(form.value.base_kcal * factor);
        form.value.protein = (form.value.base_prot * factor).toFixed(1);
        form.value.carbs = (form.value.base_carb * factor).toFixed(1);
        form.value.fat = (form.value.base_fat * factor).toFixed(1);
    }
});

const handleCreateNew = () => {
    if (activeTab.value === 'food') {
        isCreateIngredientDialogOpen.value = true;
    } else {
        isCreateRecipeDialogOpen.value = true;
    }
};

// Watch para detectar cuando se cierra el diálogo de ingrediente y recargar la lista
watch(() => isCreateIngredientDialogOpen.value, async (isOpen) => {
    if (!isOpen) {
        // Cuando se cierra el diálogo, recargar la lista de ingredientes
        await ingredientsStore.fetchIngredients();
    }
});

// Watch para detectar cuando se cierra el diálogo de receta y recargar la lista
watch(() => isCreateRecipeDialogOpen.value, async (isOpen) => {
    if (!isOpen) {
        // Cuando se cierra el diálogo, recargar la lista de recetas
        await recipesStore.fetchRecipes();
    }
});

const saveMeal = async () => {
    try {
        const selectedDate = dateStore.selectedDate instanceof Date 
            ? dateStore.selectedDate.toISOString().split('T')[0]
            : dateStore.selectedDate || new Date().toISOString().split('T')[0];
            
        // Construir el objeto mealData solo con el campo correspondiente
        const mealData = {
            quantity: form.value.base_amount.toString(),
            unit: form.value.base_unit || (activeTab.value === 'recipe' ? 'serving' : 'g'),
            logged_at: selectedDate
        };
        
        if (activeTab.value === 'food') {
            mealData.ingredient_id = form.value.id;
        } else {
            mealData.recipe_id = form.value.id;
        }
        
        await mealLogsStore.addMealLog(mealData);
        
        // Recargar los meal logs para la fecha seleccionada
        await mealLogsStore.fetchMealLogs({
            date_from: selectedDate,
            date_to: selectedDate
        });
        
        emit("update:modelValue", false);
        form.value = { ...initialState };
        selectedItem.value = null;
    } catch (error) {
        console.error('Error saving meal log:', error);
    }
};

watch(activeTab, () => {
    form.value = { ...initialState };
    selectedItem.value = null;
});

watch(() => props.modelValue, (isOpen) => {
    if (!isOpen) {
        // Resetear formulario al cerrar
        form.value = { ...initialState };
        selectedItem.value = null;
        activeTab.value = "food";
    }
});
</script>

<style scoped>
.uppercase {
    text-transform: uppercase;
    letter-spacing: 1px;
    font-size: 0.65rem !important;
}

:deep(input::-webkit-outer-spin-button),
:deep(input::-webkit-inner-spin-button) {
    -webkit-appearance: none;
    margin: 0;
}
:deep(input[type="number"]) {
    -moz-appearance: textfield;
}
</style>
