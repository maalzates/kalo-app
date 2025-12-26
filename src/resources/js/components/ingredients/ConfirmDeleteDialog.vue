<template>
    <v-dialog 
      :model-value="modelValue" 
      @update:model-value="$emit('update:modelValue', $event)" 
      max-width="400px"
    >
      <v-card elevation="2" rounded="xl" class="pa-4 text-center">
        <v-card-item class="pa-0">
          <v-icon color="error" size="64" class="mb-4">mdi-alert-circle-outline</v-icon>
          <v-card-title class="text-h6 font-weight-bold">
            ¿Confirmar eliminación?
          </v-card-title>
        </v-card-item>
  
        <v-card-text class="pa-0 mt-2 text-body-1">
          ¿Estás seguro de que deseas eliminar <strong>{{ itemName }}</strong> de tu lista? 
          Esta acción no se puede deshacer.
        </v-card-text>
  
        <v-card-actions class="px-0 pt-6">
          <v-btn 
            variant="text" 
            color="grey-darken-1" 
            block 
            @click="$emit('update:modelValue', false)"
            class="mb-2"
          >
            Cancelar
          </v-btn>
          <v-btn 
            color="error" 
            variant="flat" 
            block 
            rounded="pill"
            @click="$emit('confirm')"
          >
            Eliminar
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </template>
  
  <script setup>
  defineProps({
    modelValue: Boolean,
    itemName: String
  });
  
  defineEmits(['update:modelValue', 'confirm']);
  </script>
