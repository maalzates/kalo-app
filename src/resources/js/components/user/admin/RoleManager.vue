<template>
    <v-card elevation="2" rounded="xl" border="error" class="pa-4 border-dashed">
      <v-card-title class="text-error font-weight-bold d-flex align-center">
        <v-icon start>mdi-shield-lock</v-icon>
        Panel de Administración
      </v-card-title>
      <v-card-subtitle>Gestión de permisos y roles del sistema</v-card-subtitle>
      
      <v-card-text>
        <v-list bg-color="transparent">
          <v-list-item class="px-0">
            <template v-slot:prepend> <v-icon>mdi-database-edit</v-icon> </template>
            <v-list-item-title>Editar Base Global</v-list-item-title>
            <template v-slot:append> <v-switch color="error" hide-details></v-switch> </template>
          </v-list-item>
          
          <v-list-item class="px-0">
            <template v-slot:prepend> <v-icon>mdi-account-group</v-icon> </template>
            <v-list-item-title>Gestionar Usuarios</v-list-item-title>
            <template v-slot:append> <v-switch color="error" hide-details></v-switch> </template>
          </v-list-item>
        </v-list>
      </v-card-text>
    </v-card>
  </template>
