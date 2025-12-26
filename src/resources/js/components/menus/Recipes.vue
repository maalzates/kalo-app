<template>
    <v-card elevation="2" rounded="xl" class="pa-4">
      <v-card-item class="px-0 pt-0">
        <div class="d-flex align-center justify-space-between mb-4">
          <div>
            <v-card-title class="text-h6 font-weight-bold">Mis Recetas</v-card-title>
            <v-card-subtitle>Toca una receta para ver sus ingredientes</v-card-subtitle>
          </div>
          <v-btn
            color="deep-purple-accent-4"
            prepend-icon="mdi-plus"
            rounded="pill"
            variant="flat"
            class="d-none d-md-flex"
            @click="openDialog(null, false)"
          >
            Nueva Receta
          </v-btn>
        </div>
  
        <v-text-field
          v-model="search"
          prepend-inner-icon="mdi-magnify"
          label="Buscar receta..."
          variant="outlined"
          density="compact"
          hide-details
          rounded="lg"
          class="mb-4"
        ></v-text-field>
      </v-card-item>
  
      <v-divider class="mb-2"></v-divider>
  
      <v-list class="pa-0">
        <template v-for="(recipe, index) in filteredRecipes" :key="recipe.id">
          <v-list-item 
            class="px-0 py-3" 
            @click="toggleExpand(recipe.id)"
            :append-icon="expandedId === recipe.id ? 'mdi-chevron-up' : 'mdi-chevron-down'"
          >
            <template v-slot:prepend>
              <v-avatar color="orange-lighten-5" size="48">
                <v-icon color="orange-darken-2">mdi-silverware-fork-knife</v-icon>
              </v-avatar>
            </template>
  
            <v-list-item-title class="font-weight-bold text-subtitle-1">
              {{ recipe.name }}
            </v-list-item-title>
            
            <v-list-item-subtitle class="text-caption">
              {{ recipe.calories }} kcal | P: {{ recipe.protein }}g | C: {{ recipe.carbs }}g | F: {{ recipe.fat }}g
            </v-list-item-subtitle>
  
            <template v-slot:append>
              <v-menu location="bottom end">
                <template v-slot:activator="{ props }">
                  <v-btn icon="mdi-dots-vertical" variant="text" color="grey-darken-1" v-bind="props" @click.stop></v-btn>
                </template>
                <v-list density="compact" rounded="lg">
                  <v-list-item @click.stop="openDialog(recipe, true)">
                    <template v-slot:prepend><v-icon size="small">mdi-pencil</v-icon></template>
                    <v-list-item-title>Editar</v-list-item-title>
                  </v-list-item>
                  <v-list-item @click.stop="openDeleteConfirm(recipe)" color="error">
                    <template v-slot:prepend><v-icon size="small">mdi-delete</v-icon></template>
                    <v-list-item-title>Eliminar</v-list-item-title>
                  </v-list-item>
                </v-list>
              </v-menu>
            </template>
          </v-list-item>
  
          <v-expand-transition>
            <div v-if="expandedId === recipe.id" class="bg-grey-lighten-5 pa-4 pt-0">
              <div class="d-flex align-center mb-3">
                <v-divider></v-divider>
                <span class="text-overline px-3 text-grey-darken-1 font-weight-bold">Composición</span>
                <v-divider></v-divider>
              </div>
              <v-row dense>
                <v-col v-for="ing in recipe.ingredients" :key="ing.id" cols="12">
                  <v-sheet border rounded="lg" class="pa-3 d-flex align-center justify-space-between">
                    <div class="d-flex align-center">
                      <v-icon size="small" color="orange-darken-2" class="mr-3">mdi-circle-small</v-icon>
                      <div>
                        <div class="text-body-2 font-weight-bold">{{ ing.name }}</div>
                        <div class="text-caption text-grey">Cant: {{ ing.amount }}{{ ing.unit }}</div>
                      </div>
                    </div>
                    <div class="text-right">
                      <div class="text-body-2 font-weight-medium text-deep-purple">
                        {{ ing.calories }} <span class="text-caption">kcal</span>
                      </div>
                      <div class="text-caption text-grey">
                        {{ ing.protein }}g P • {{ ing.carbs }}g C
                      </div>
                    </div>
                  </v-sheet>
                </v-col>
              </v-row>
            </div>
          </v-expand-transition>
          <v-divider v-if="index < filteredRecipes.length - 1" inset></v-divider>
        </template>
      </v-list>
    </v-card>
  
    <AddOrEditRecipe
      v-model="isAddOrEditDialogOpen"
      :initialData="selectedRecipe"
      @save="handleSaveRecipe"
    />
  
    <ConfirmDeleteDialog
      v-model="isDeleteDialogOpen"
      :itemName="selectedRecipe?.name"
      @confirm="handleConfirmDelete"
    />
  
    <MobileFloatingButton icon="mdi-plus" @click="openDialog(null, false)" />
  </template>
  
  <script setup>
  import { ref, computed } from 'vue';
  import { useRecipesStore } from '@/stores/useRecipesStore';
  import AddOrEditRecipe from '@/components/recipes/AddOrEditRecipe.vue';
  import MobileFloatingButton from '@/components/common/MobileFloatingButton.vue';
  import ConfirmDeleteDialog from '@/components/common/ConfirmDeleteDialog.vue';
  
  const recipesStore = useRecipesStore();
  const search = ref('');
  const expandedId = ref(null);
  const isAddOrEditDialogOpen = ref(false);
  const isDeleteDialogOpen = ref(false);
  const selectedRecipe = ref(null);
  
  const filteredRecipes = computed(() => {
    return recipesStore.recipes.filter(r => 
      r.name.toLowerCase().includes(search.value.toLowerCase())
    );
  });
  
  const toggleExpand = (id) => {
    expandedId.value = expandedId.value === id ? null : id;
  };
  
  // Abrir Modal para Crear o Editar
  const openDialog = (item, isEditing = false) => {
    selectedRecipe.value = isEditing ? { ...item } : null;
    isAddOrEditDialogOpen.value = true;
  };
  
  // Abrir Modal de Confirmación de Borrado
  const openDeleteConfirm = (item) => {
    selectedRecipe.value = item;
    isDeleteDialogOpen.value = true;
  };
  
  // Manejadores de eventos (Simulación de backend)
  const handleSaveRecipe = (recipeData) => {
    if (selectedRecipe.value) {
      console.log("Actualizando receta:", recipeData);
      // recipesStore.updateRecipe(recipeData);
    } else {
      console.log("Creando nueva receta:", recipeData);
      // recipesStore.addRecipe(recipeData);
    }
    isAddOrEditDialogOpen.value = false;
  };
  
  const handleConfirmDelete = () => {
    console.log("Eliminando receta ID:", selectedRecipe.value?.id);
    // recipesStore.deleteRecipe(selectedRecipe.value.id);
    isDeleteDialogOpen.value = false;
  };
  </script>
