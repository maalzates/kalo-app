<template>
    <v-container
        fluid
        class="fill-height bg-grey-lighten-5 pa-0 justify-center"
    >
        <v-card
            width="100%"
            max-width="500"
            flat
            class="pa-10 rounded-xl border-sm bg-white"
        >
            <div class="text-center mb-8">
                <h1
                    class="text-h4 font-weight-bold text-deep-purple-darken-4 mb-2"
                >
                    Register
                </h1>
            </div>

            <div class="d-flex justify-center mb-6">
                <v-btn
                    variant="outlined"
                    border="sm"
                    rounded="lg"
                    class="py-7 px-10 d-flex align-center justify-center"
                    @click="$router.push('/login')"
                >
                    <v-img
                        src="https://www.gstatic.com/images/branding/product/2x/googleg_48dp.png"
                        width="22"
                        height="22"
                        contain
                    ></v-img>
                </v-btn>
            </div>

            <v-divider class="mb-8 mt-2">
                <span class="text-caption text-grey">or</span>
            </v-divider>

            <v-form @submit.prevent="handleRegister">
                <div
                    class="text-caption font-weight-bold text-grey-darken-1 mb-1 ml-1"
                >
                    Email address
                </div>
                <v-text-field
                    v-model="email"
                    variant="outlined"
                    density="comfortable"
                    rounded="lg"
                    color="deep-purple-accent-4"
                    class="mb-3"
                    placeholder="email@example.com"
                    persistent-placeholder
                ></v-text-field>

                <div
                    class="text-caption font-weight-bold text-grey-darken-1 mb-1 ml-1"
                >
                    Password
                </div>
                <v-text-field
                    v-model="password"
                    :type="showPassword ? 'text' : 'password'"
                    variant="outlined"
                    density="comfortable"
                    rounded="lg"
                    color="deep-purple-accent-4"
                    placeholder="••••••••"
                    persistent-placeholder
                    class="mb-6"
                >
                    <template v-slot:append-inner>
                        <v-icon
                            :icon="showPassword ? 'mdi-eye-off' : 'mdi-eye'"
                            @click="showPassword = !showPassword"
                            size="small"
                            color="grey"
                        ></v-icon>
                    </template>
                </v-text-field>

                <v-btn
                    block
                    color="deep-purple-accent-2"
                    size="x-large"
                    rounded="lg"
                    class="font-weight-bold text-none mb-6"
                    elevation="0"
                    type="submit"
                >
                    Register
                </v-btn>
            </v-form>

            <div class="text-center">
                <div class="text-body-1 text-grey-darken-3 mb-6">
                    Already have an account?
                    <v-btn
                        variant="text"
                        color="deep-purple-accent-4"
                        class="text-none font-weight-black px-1"
                        @click="$router.push('/login')"
                    >
                        Log in
                    </v-btn>
                </div>

                <p class="text-caption text-grey-darken-1 px-4 line-height-1">
                    By continuing you agree with our
                    <a
                        href="#"
                        class="text-grey-darken-3 font-weight-bold text-decoration-underline"
                        >Terms of Service</a
                    >
                    and confirm that you have read our
                    <a
                        href="#"
                        class="text-grey-darken-3 font-weight-bold text-decoration-underline"
                        >Privacy Policy</a
                    >
                </p>
            </div>
        </v-card>
    </v-container>
</template>

<script setup>
import { ref } from "vue";

const email = ref("");
const password = ref("");
const showPassword = ref(false);

const handleRegister = () => console.log("Registering user...");
const registerWithGoogle = () => console.log("Google Register...");
const goToLogin = () => console.log("Navigating to Login...");
</script>

<style scoped>
.border-sm {
    border: 1px solid #e0e0e0 !important;
}
:deep(.v-field__label) {
    display: none;
}
.line-height-1 {
    line-height: 1.4;
}
a {
    text-decoration: none;
}
</style>
