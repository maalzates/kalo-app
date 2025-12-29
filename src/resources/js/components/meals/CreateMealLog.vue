<template>
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
                <div
                    class="text-body-1 text-sm-h6 font-weight-bold text-wrap line-height-1 pl-2"
                >
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
                <v-card
                    rounded="xl"
                    class="pa-4 mb-4"
                    elevation="1"
                    border="sm"
                >
                    <div class="d-flex align-center justify-space-between mb-4">
                        <div class="d-flex align-center">
                            <v-icon color="deep-purple-accent-4" class="mr-2"
                                >mdi-calendar</v-icon
                            >
                            <span
                                class="text-subtitle-2 font-weight-bold text-grey-darken-2"
                                >{{ dateStore.fullDate }}</span
                            >
                        </div>
                        <v-chip
                            size="small"
                            color="deep-purple-accent-4"
                            variant="tonal"
                            class="font-weight-bold"
                        >
                            LOG DIARIO
                        </v-chip>
                    </div>

                    <v-tabs
                        v-model="activeTab"
                        grow
                        color="deep-purple-accent-4"
                        class="bg-grey-lighten-4 rounded-lg"
                    >
                        <v-tab value="food" class="font-weight-bold"
                            >Alimentos</v-tab
                        >
                        <v-tab value="recipe" class="font-weight-bold"
                            >Recetas</v-tab
                        >
                    </v-tabs>
                </v-card>

                <v-card
                    rounded="xl"
                    class="pa-4 mb-4"
                    elevation="1"
                    border="sm"
                >
                    <div
                        class="text-subtitle-2 mb-2 ml-1 font-weight-bold text-grey-darken-2 uppercase"
                    >
                        Seleccionar de la biblioteca
                    </div>
                    <v-autocomplete
                        v-model="selectedItem"
                        :label="
                            activeTab === 'food'
                                ? 'Buscar en mis alimentos...'
                                : 'Buscar en mis recetas...'
                        "
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
                                <v-list-item-title
                                    class="text-deep-purple font-weight-bold text-body-2"
                                >
                                    <v-icon start size="18"
                                        >mdi-plus-circle</v-icon
                                    >
                                    Crear
                                    {{
                                        activeTab === "food"
                                            ? "nuevo alimento"
                                            : "nueva receta"
                                    }}
                                </v-list-item-title>
                            </v-list-item>
                        </template>
                    </v-autocomplete>
                </v-card>

                <v-expand-transition>
                    <div v-if="form.name">
                        <div
                            class="text-overline mb-2 ml-2 text-deep-purple-accent-4 font-weight-bold"
                        >
                            Detalles del registro
                        </div>

                        <v-card
                            rounded="xl"
                            class="pa-5 mb-4"
                            elevation="1"
                            border="sm"
                        >
                            <div
                                class="text-h6 font-weight-black mb-4 text-deep-purple-darken-4 text-truncate"
                            >
                                {{ form.name }}
                            </div>

                            <v-row dense>
                                <v-col cols="7">
                                    <v-text-field
                                        v-model.number="form.base_amount"
                                        label="Cantidad consumida"
                                        type="number"
                                        variant="outlined"
                                        rounded="lg"
                                        color="deep-purple-accent-4"
                                        hide-details
                                    ></v-text-field>
                                </v-col>
                                <v-col cols="5">
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

                            <v-card
                                variant="tonal"
                                color="deep-purple-accent-4"
                                rounded="lg"
                                class="mt-6 pa-4"
                            >
                                <v-row no-gutters class="text-center">
                                    <v-col>
                                        <div class="text-h6 font-weight-black">
                                            {{ form.calories }}
                                        </div>
                                        <div
                                            class="text-caption font-weight-bold uppercase"
                                        >
                                            kcal
                                        </div>
                                    </v-col>
                                    <v-divider
                                        vertical
                                        class="mx-2 opacity-20"
                                    ></v-divider>
                                    <v-col>
                                        <div class="text-h6 font-weight-black">
                                            {{ form.protein }}g
                                        </div>
                                        <div
                                            class="text-caption font-weight-bold uppercase"
                                        >
                                            Prot
                                        </div>
                                    </v-col>
                                    <v-divider
                                        vertical
                                        class="mx-2 opacity-20"
                                    ></v-divider>
                                    <v-col>
                                        <div class="text-h6 font-weight-black">
                                            {{ form.carbs }}g
                                        </div>
                                        <div
                                            class="text-caption font-weight-bold uppercase"
                                        >
                                            Carbs
                                        </div>
                                    </v-col>
                                    <v-divider
                                        vertical
                                        class="mx-2 opacity-20"
                                    ></v-divider>
                                    <v-col>
                                        <div class="text-h6 font-weight-black">
                                            {{ form.fat }}g
                                        </div>
                                        <div
                                            class="text-caption font-weight-bold uppercase"
                                        >
                                            Grasa
                                        </div>
                                    </v-col>
                                </v-row>
                            </v-card>
                        </v-card>
                    </div>
                </v-expand-transition>
            </v-container>
        </v-card>
    </v-dialog>
</template>

<script setup>
import { ref, computed, onMounted, watch } from "vue";
import { useMealLogsStore } from "@/stores/useMealLogsStore";
import { useIngredientsStore } from "@/stores/useIngredientsStore";
import { useRecipesStore } from "@/stores/useRecipesStore";
import { useDateStore } from "@/stores/useDateStore";

defineProps({ modelValue: Boolean });
const emit = defineEmits(["update:modelValue"]);

const mealLogsStore = useMealLogsStore();
const dateStore = useDateStore();
const ingredientsStore = useIngredientsStore();
const recipesStore = useRecipesStore();

const activeTab = ref("food");
const selectedItem = ref(null);

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
        // Volcamos los datos conservando la reactividad
        form.value = { ...initialState, ...JSON.parse(JSON.stringify(item)) };
        // Ajuste opcional: Si es un alimento nuevo poner 100 por defecto
        if (form.value.base_amount === 0) form.value.base_amount = 100;
    }
};

const handleCreateNew = () => {
    console.log("Abrir flujo de creación para:", activeTab.value);
};

const saveMeal = () => {
    mealLogsStore.addMealLog({ ...form.value });
    emit("update:modelValue", false);

    // Reset completo
    form.value = { ...initialState };
    selectedItem.value = null;
};

watch(activeTab, () => {
    form.value = { ...initialState };
    selectedItem.value = null;
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
