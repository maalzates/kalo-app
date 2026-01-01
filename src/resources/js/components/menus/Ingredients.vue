<template>
    <v-card elevation="2" rounded="xl" class="pa-4 ml-0 ml-md-6">
        <v-card-item class="px-0 pt-0">
            <div class="d-flex align-center justify-space-between mb-4">
                <div>
                    <v-card-title class="text-h6 font-weight-bold">
                        Mis Ingredientes
                    </v-card-title>
                    <v-card-subtitle>
                        Gestiona los alimentos base de tu dieta
                    </v-card-subtitle>
                </div>

                <v-btn
                    color="deep-purple-accent-4"
                    prepend-icon="mdi-plus"
                    rounded="pill"
                    variant="flat"
                    class="d-none d-md-flex new-button"
                    @click="openDialog(null, false)"
                >
                    Nuevo
                </v-btn>
            </div>

            <v-text-field
                v-model="search"
                prepend-inner-icon="mdi-magnify"
                label="Buscar ingrediente..."
                variant="outlined"
                density="compact"
                hide-details
                rounded="lg"
                class="mb-4"
            ></v-text-field>
        </v-card-item>

        <v-divider class="mb-2"></v-divider>

        <v-list class="pa-0">
            <template
                v-for="(item, index) in filteredIngredients"
                :key="item.id"
            >
                <v-list-item class="px-0 py-3">
                    <template v-slot:prepend>
                        <v-avatar color="deep-purple-lighten-5" size="48">
                            <v-icon color="deep-purple-accent-4"
                                >mdi-food-apple</v-icon
                            >
                        </v-avatar>
                    </template>

                    <v-list-item-title class="font-weight-bold text-subtitle-1 d-flex align-center ga-2">
                        <span>{{ item.name }}</span>
                        <v-chip 
                            size="x-small" 
                            :color="item.user_id ? 'blue' : 'green'"
                            variant="tonal"
                            class="font-weight-bold"
                        >
                            {{ item.user_id ? 'Privado' : 'Público' }}
                        </v-chip>
                    </v-list-item-title>

                    <v-list-item-subtitle class="text-caption">
                        {{ item.amount || 100 }}{{ item.unit || 'g' }} •
                        {{ item.kcal || 0 }} kcal | P: {{ item.prot || 0 }}g | C:
                        {{ item.carb || 0 }}g | G: {{ item.fat || 0 }}g
                    </v-list-item-subtitle>

                    <template v-slot:append>
                        <!-- Solo mostrar menú de acciones para ingredientes privados (del usuario) -->
                        <v-menu v-if="item.user_id" location="bottom end">
                            <template v-slot:activator="{ props }">
                                <v-btn
                                    icon="mdi-dots-vertical"
                                    variant="text"
                                    color="grey-darken-1"
                                    v-bind="props"
                                ></v-btn>
                            </template>
                            <v-list density="compact" rounded="lg">
                                <v-list-item @click="openDialog(item, true)">
                                    <template v-slot:prepend>
                                        <v-icon size="small">mdi-pencil</v-icon>
                                    </template>
                                    <v-list-item-title
                                        >Editar</v-list-item-title
                                    >
                                </v-list-item>
                                <v-list-item
                                    @click="openDeleteConfirm(item)"
                                    color="error"
                                >
                                    <template v-slot:prepend>
                                        <v-icon size="small">mdi-delete</v-icon>
                                    </template>
                                    <v-list-item-title
                                        >Eliminar</v-list-item-title
                                    >
                                </v-list-item>
                            </v-list>
                        </v-menu>
                    </template>
                </v-list-item>
                <v-divider
                    v-if="index < filteredIngredients.length - 1"
                    inset
                ></v-divider>
            </template>
        </v-list>

        <v-alert
            v-if="filteredIngredients.length === 0"
            type="info"
            variant="tonal"
            text="No se encontraron ingredientes con ese nombre."
            class="mt-4 rounded-lg"
        ></v-alert>
    </v-card>
    <AddOrEditIngredient
        v-model="isAddOrEditDialogOpen"
        :initialData="selectedIngredient"
    />
    <MobileFloatingButton icon="mdi-plus" @click="openDialog(null, false)" />
    <ConfirmDeleteDialog
        v-model="isDeleteDialogOpen"
        :itemName="selectedIngredient?.name || ''"
        @confirm="confirmDelete"
    />
</template>

<script setup>
import { ref, computed, onMounted } from "vue";
import { useIngredientsStore } from "@/stores/useIngredientsStore";
import AddOrEditIngredient from "@/components/ingredients/AddOrEditIngredient.vue";
import MobileFloatingButton from "@/components/common/MobileFloatingButton.vue";
import ConfirmDeleteDialog from "@/components/common/ConfirmDeleteDialog.vue";

const ingredientsStore = useIngredientsStore();
const search = ref("");
const isAddOrEditDialogOpen = ref(false);
const isDeleteDialogOpen = ref(false);
const selectedIngredient = ref(null);

const filteredIngredients = computed(() => {
    return ingredientsStore.ingredients.filter((ing) =>
        ing.name.toLowerCase().includes(search.value.toLowerCase())
    );
});

const openDialog = (item, isEditing = false) => {
    selectedIngredient.value = isEditing ? { ...item } : null;
    isAddOrEditDialogOpen.value = true;
};

const openDeleteConfirm = (item) => {
    selectedIngredient.value = item;
    isDeleteDialogOpen.value = true;
};

const confirmDelete = async () => {
    if (selectedIngredient.value) {
        try {
            await ingredientsStore.deleteIngredient(selectedIngredient.value.id);
            isDeleteDialogOpen.value = false;
            selectedIngredient.value = null;
        } catch (error) {
            console.error('Error deleting ingredient:', error);
        }
    }
};

onMounted(() => {
    ingredientsStore.fetchIngredients();
});
</script>

<style scoped>
.new-button {
    font-family: 'Inter', 'Roboto', sans-serif;
    font-weight: 600;
    letter-spacing: 0.5px;
    text-transform: none;
}
</style>
