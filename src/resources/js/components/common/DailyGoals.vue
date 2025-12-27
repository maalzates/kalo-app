<template>
    <v-card color="deep-purple-accent-4" theme="dark" rounded="xl" class="pa-6">
        <div class="text-center mb-4">
            <div class="text-h4 font-weight-bold">Tus metas diarias</div>
            <div class="text-h6 opacity-70">Objetivos activos basados en tu perfil</div>
        </div>
        <v-row class="text-center" dense>
            <v-col cols="3">
                <div class="text-h5 font-weight-black">
                    {{ user.current_targets.calories }}
                </div>
                <div class="text-caption">Kcal</div>
            </v-col>
            <v-col cols="3">
                <div class="text-h5 font-weight-black">
                    {{ user.current_targets.protein }}g
                </div>
                <div class="text-caption">Proteína</div>
            </v-col>
            <v-col cols="3">
                <div class="text-h5 font-weight-black">
                    {{ user.current_targets.carbs }}g
                </div>
                <div class="text-caption">Carbs</div>
            </v-col>
            <v-col cols="3">
                <div class="text-h5 font-weight-black">
                    {{ user.current_targets.fat }}g
                </div>
                <div class="text-caption">Grasas</div>
            </v-col>
        </v-row>
    </v-card>
</template>

<script setup>
import { useUserStore } from "@/stores/useUserStore";
const userStore = useUserStore();
const user = userStore.authenticatedUser;
</script>

<style scoped>
.opacity-70 { opacity: 0.7; }
.opacity-20 { opacity: 0.2; }
</style>
