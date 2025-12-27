<template>
    <v-container maxWidth="800px">
        <UserInformationCard class="mb-2"/>
      <v-row>
        <v-col cols="12" md="6">
          <v-card elevation="2" rounded="xl" class="pa-4 h-100">
            <v-card-title class="d-flex align-center">
              <v-icon start color="deep-purple">mdi-account-details</v-icon>
              Datos Biométricos
            </v-card-title>
            <v-card-text>
              <v-row dense>
                <v-col cols="6"><v-text-field label="Edad" variant="outlined" density="compact" rounded="lg"></v-text-field></v-col>
                <v-col cols="6"><v-select label="Sexo" :items="['Hombre', 'Mujer']" variant="outlined" density="compact" rounded="lg"></v-select></v-col>
                <v-col cols="6"><v-text-field label="Peso (kg)" variant="outlined" density="compact" rounded="lg"></v-text-field></v-col>
                <v-col cols="6"><v-text-field label="Altura (cm)" variant="outlined" density="compact" rounded="lg"></v-text-field></v-col>
              </v-row>
            </v-card-text>
          </v-card>
        </v-col>
  
        <v-col cols="12" md="6">
          <v-card elevation="2" rounded="xl" class="pa-4 h-100">
            <v-card-title class="d-flex align-center">
              <v-icon start color="orange-darken-2">mdi-run-fast</v-icon>
              Estilo de Vida
            </v-card-title>
            <v-card-text>
              <v-select
                label="Nivel de Actividad"
                :items="['Sedentario', 'Ligero', 'Moderado', 'Muy Activo']"
                variant="outlined"
                density="compact"
                rounded="lg"
                class="mb-2"
              ></v-select>
              <v-select
                label="Objetivo"
                :items="['Perder Peso', 'Mantener', 'Ganar Músculo']"
                variant="outlined"
                density="compact"
                rounded="lg"
              ></v-select>
            </v-card-text>
          </v-card>
        </v-col>
  
        <v-col cols="12">
          <v-card color="deep-purple-accent-4" theme="dark" rounded="xl" class="pa-6">
            <div class="d-flex justify-space-between align-center">
              <div>
                <div class="text-h6">Tus Metas Diarias</div>
                <div class="text-body-2 opacity-80">Calculadas automáticamente según tus datos</div>
              </div>
              <v-btn color="white" variant="flat" rounded="pill" class="text-deep-purple font-weight-bold">
                Recalcular
              </v-btn>
            </div>
            
            <v-row class="mt-4 text-center">
              <v-col cols="3">
                <div class="text-h5 font-weight-black">2,450</div>
                <div class="text-caption">Kcal</div>
              </v-col>
              <v-col cols="3">
                <div class="text-h5 font-weight-black">180g</div>
                <div class="text-caption">Proteína</div>
              </v-col>
              <v-col cols="3">
                <div class="text-h5 font-weight-black">250g</div>
                <div class="text-caption">Carbs</div>
              </v-col>
              <v-col cols="3">
                <div class="text-h5 font-weight-black">70g</div>
                <div class="text-caption">Grasas</div>
              </v-col>
            </v-row>
          </v-card>
        </v-col>
      </v-row>
    </v-container>
    
  </template>

  <script setup>
    import UserInformationCard from '@/components/user/UserInformationCard.vue';

  </script>
