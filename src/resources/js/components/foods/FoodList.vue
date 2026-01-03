<template>
  <div>
    <div class="d-flex align-center justify-space-between mb-4 bg-grey-lighten-4 pa-2 rounded-lg">
      <div class="text-caption font-weight-bold ml-2 text-grey-darken-1">
        Página {{ props.page }} de {{ props.totalPages || 1 }}
      </div>
      <v-pagination
        :model-value="props.page"
        :length="props.totalPages || 1"
        :disabled="props.totalPages <= 1"
        total-visible="3"
        density="compact"
        active-color="deep-purple-accent-4"
        size="small"
        variant="flat"
        @update:model-value="handlePageChange"
      ></v-pagination>
    </div>

    <v-list class="pa-0">
      <template v-for="(item, index) in props.items" :key="item.id">
        <v-list-item 
          class="px-0 py-3" 
          @click="emit('click-item', item)"
        >
          <template v-slot:prepend>
            <v-avatar :color="props.isRecipe ? 'orange-lighten-5' : 'deep-purple-lighten-5'" size="48">
              <v-icon :color="props.isRecipe ? 'orange-darken-2' : 'deep-purple-accent-4'">
                {{ props.isRecipe ? 'mdi-silverware-fork-knife' : 'mdi-food-apple' }}
              </v-icon>
            </v-avatar>
          </template>

          <v-list-item-title class="font-weight-bold text-subtitle-1 d-flex align-center ga-2">
            <span>{{ item.name }}</span>
            <v-chip size="x-small" :color="item.user_id ? 'blue' : 'green'" variant="tonal" class="font-weight-bold">
              {{ item.user_id ? 'Privado' : 'Público' }}
            </v-chip>
          </v-list-item-title>

          <v-list-item-subtitle class="text-caption">
            <template v-if="props.isRecipe">
              {{ item.total_kcal || 0 }} kcal | P: {{ item.total_prot || 0 }}g | C: {{ item.total_carb || 0 }}g | F: {{ item.total_fat || 0 }}g
            </template>
            <template v-else>
              {{ item.amount || 100 }}{{ item.unit || 'g' }} • {{ item.kcal || 0 }} kcal | P: {{ item.prot || 0 }}g | C: {{ item.carb || 0 }}g | G: {{ item.fat || 0 }}g
            </template>
          </v-list-item-subtitle>

          <template v-slot:append>
            <div class="d-flex align-center">
              <v-icon v-if="props.isRecipe" :icon="props.expandedId === item.id ? 'mdi-chevron-up' : 'mdi-chevron-down'" class="mr-2" color="grey"></v-icon>
              
              <v-menu v-if="item.user_id" location="bottom end">
                <template v-slot:activator="{ props: menuProps }">
                  <v-btn icon="mdi-dots-vertical" variant="text" v-bind="menuProps" @click.stop></v-btn>
                </template>
                <v-list density="compact" rounded="lg">
                  <v-list-item @click.stop="emit('edit', item)">
                    <template v-slot:prepend><v-icon size="small">mdi-pencil</v-icon></template>
                    <v-list-item-title>Editar</v-list-item-title>
                  </v-list-item>
                  <v-list-item @click.stop="emit('delete', item)" color="error">
                    <template v-slot:prepend><v-icon size="small">mdi-delete</v-icon></template>
                    <v-list-item-title>Eliminar</v-list-item-title>
                  </v-list-item>
                </v-list>
              </v-menu>
            </div>
          </template>
        </v-list-item>

        <v-expand-transition v-if="props.isRecipe">
          <div v-if="props.expandedId === item.id">
            <slot name="expansion" :item="item"></slot>
          </div>
        </v-expand-transition>

        <v-divider v-if="index < props.items.length - 1" inset></v-divider>
      </template>
    </v-list>

    <v-alert
      v-if="props.items.length === 0"
      type="info"
      variant="tonal"
      text="No hay elementos para mostrar en esta página."
      class="mt-4 rounded-lg"
    ></v-alert>
  </div>
</template>

<script setup>
// Props explícitas
const props = defineProps({
  items: { type: Array, required: true },
  isRecipe: { type: Boolean, default: false },
  totalPages: { type: Number, default: 1 },
  page: { type: Number, required: true },
  expandedId: { type: [Number, String, null], default: null }
});

// Emits explícitos
const emit = defineEmits(['update:page', 'edit', 'delete', 'click-item']);

// Manejo de cambio de página avisando al padre
const handlePageChange = (newPage) => {
  emit('update:page', newPage);
};
</script>
