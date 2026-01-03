<template>
  <v-card elevation="2" rounded="xl" class="pa-4 ml-0 ml-md-6">
    <v-card-item class="px-0 pt-0">
      <div class="d-flex align-center justify-space-between mb-4">
        <div>
          <v-card-title class="text-h6 font-weight-bold">Inventario & Cocina</v-card-title>
          <v-card-subtitle>Gestiona tus alimentos y preparaciones</v-card-subtitle>
        </div>
        <v-btn color="deep-purple-accent-4" prepend-icon="mdi-plus" rounded="pill" variant="flat" class="d-none d-md-flex" @click="openDialog(null, false)">
          Nuevo {{ activeTab === 'ing' ? 'Ingrediente' : 'Receta' }}
        </v-btn>
      </div>

      <v-text-field v-model="search" prepend-inner-icon="mdi-magnify" :label="activeTab === 'ing' ? 'Buscar ingrediente...' : 'Buscar receta...'" variant="outlined" density="compact" hide-details rounded="lg"></v-text-field>
    </v-card-item>

    <v-tabs v-model="activeTab" color="deep-purple-accent-4" grow>
      <v-tab value="ing">Ingredientes</v-tab>
      <v-tab value="rec">Recetas</v-tab>
    </v-tabs>

    <v-window v-model="activeTab" class="mt-4">
      <v-window-item value="ing">
        <FoodList 
          :items="paginatedIngredients" 
          :is-recipe="false"
          :page="ingPage"
          :total-pages="Math.ceil(filteredIngredients.length / itemsPerPage)"
          @update:page="(val) => ingPage = val"
          @edit="(item) => openDialog(item, true)"
          @delete="openDeleteConfirm"
        />
      </v-window-item>

      <v-window-item value="rec">
        <FoodList 
          :items="paginatedRecipes" 
          :is-recipe="true"
          :page="recPage"
          :total-pages="Math.ceil(filteredRecipes.length / itemsPerPage)"
          :expanded-id="expandedRecipeId"
          @update:page="(val) => recPage = val"
          @click-item="(item) => toggleExpand(item.id)"
          @edit="(item) => openDialog(item, true)"
          @delete="openDeleteConfirm"
        >
          <template #expansion="{ item }">
            <div class="bg-grey-lighten-5 pa-4 pt-0">
              <div class="d-flex align-center mb-3">
                <v-divider></v-divider>
                <span class="text-overline px-3 text-grey-darken-1 font-weight-bold">Composición</span>
                <v-divider></v-divider>
              </div>
              <v-row dense>
                <v-col v-for="(ing, idx) in item.ingredients" :key="ing.id || idx" cols="12">
                  <v-sheet border rounded="lg" class="pa-3 d-flex align-center justify-space-between mb-1">
                    <div class="d-flex align-center">
                      <v-icon size="small" color="orange-darken-2" class="mr-3">mdi-circle-small</v-icon>
                      <div>
                        <div class="text-body-2 font-weight-bold">{{ ing.name || ing.ingredient?.name }}</div>
                        <div class="text-caption text-grey">Cant: {{ ing.pivot?.amount || ing.amount }}{{ ing.pivot?.unit || ing.unit || 'g' }}</div>
                      </div>
                    </div>
                    <div class="text-right">
                      <div class="text-body-2 font-weight-medium text-deep-purple">{{ ing.kcal || 0 }} kcal</div>
                      <div class="text-caption text-grey">{{ ing.prot || 0 }}g P • {{ ing.carb || 0 }}g C</div>
                    </div>
                  </v-sheet>
                </v-col>
              </v-row>
            </div>
          </template>
        </FoodList>
      </v-window-item>
    </v-window>
  </v-card>

  <AddOrEditIngredient v-model="ingDialog" :initialData="selectedItem" />
  <AddOrEditRecipe v-model="recDialog" :initialData="selectedItem" @save="handleSaveRecipe" />
  
  <ConfirmDeleteDialog 
    v-model="deleteDialog" 
    :itemName="selectedItem?.name || ''" 
    @confirm="confirmDelete" 
  />

  <MobileFloatingButton icon="mdi-plus" @click="openDialog(null, false)" />
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue';
import { useIngredientsStore } from "@/stores/useIngredientsStore";
import { useRecipesStore } from "@/stores/useRecipesStore";

// Componentes originales
import AddOrEditIngredient from "@/components/ingredients/AddOrEditIngredient.vue";
import AddOrEditRecipe from "@/components/recipes/AddOrEditRecipe.vue";
import MobileFloatingButton from "@/components/common/MobileFloatingButton.vue";
import ConfirmDeleteDialog from "@/components/common/ConfirmDeleteDialog.vue";
import FoodList from "@/components/foods/FoodList.vue";

const ingStore = useIngredientsStore();
const recStore = useRecipesStore();

const activeTab = ref('ing');
const search = ref("");
const expandedRecipeId = ref(null);
const itemsPerPage = 8;
const ingPage = ref(1);
const recPage = ref(1);

const selectedItem = ref(null);
const ingDialog = ref(false);
const recDialog = ref(false);
const deleteDialog = ref(false);

// Reiniciar páginas al buscar para evitar quedarse en una página vacía
watch(search, () => {
  ingPage.value = 1;
  recPage.value = 1;
});

const filteredIngredients = computed(() => ingStore.ingredients.filter(i => i.name.toLowerCase().includes(search.value.toLowerCase())));
const filteredRecipes = computed(() => recStore.recipes.filter(r => r.name.toLowerCase().includes(search.value.toLowerCase())));

const paginatedIngredients = computed(() => filteredIngredients.value.slice((ingPage.value - 1) * itemsPerPage, ingPage.value * itemsPerPage));
const paginatedRecipes = computed(() => filteredRecipes.value.slice((recPage.value - 1) * itemsPerPage, recPage.value * itemsPerPage));

const toggleExpand = (id) => expandedRecipeId.value = expandedRecipeId.value === id ? null : id;

const openDialog = (item, isEditing = false) => {
  selectedItem.value = isEditing ? { ...item } : null;
  if (activeTab.value === 'ing') ingDialog.value = true;
  else recDialog.value = true;
};

const openDeleteConfirm = (item) => {
  selectedItem.value = item;
  deleteDialog.value = true;
};

const confirmDelete = async () => {
  if (activeTab.value === 'ing') await ingStore.deleteIngredient(selectedItem.value.id);
  else await recStore.deleteRecipe(selectedItem.value.id);
  deleteDialog.value = false;
};

const handleSaveRecipe = async (data) => {
  if (selectedItem.value) await recStore.updateRecipe(selectedItem.value.id, data);
  else await recStore.createRecipe(data);
  recDialog.value = false;
};

onMounted(() => {
  ingStore.fetchIngredients();
  recStore.fetchRecipes();
});
</script>
