<script setup>
    import { ref } from "vue";
    import { useRouter } from "vue-router";
    import { useUserStore } from "@/stores/useUserStore";
    import axios from "axios";
    
    const router = useRouter();
    const userStore = useUserStore();
    
    const email = ref("");
    const password = ref("");
    const showPassword = ref(false);
    const loading = ref(false);
    const errorMessage = ref("");
    
    const handleLogin = async () => {
        loading.value = true;
        errorMessage.value = "";
        
        try {
            const response = await axios.post("/login", {
                email: email.value,
                password: password.value,
            });
    
            const { access_token, user } = response.data.data;
            
            // Asegurar que el token se guarde correctamente
            userStore.setAuth(user, access_token);
    
            // Redirigir al dashboard usando el nombre de la ruta
            // El router guard manejará la validación automáticamente
            router.push({ name: 'dashboard' });
        } catch (error) {
            console.error("Login error:", error);
            errorMessage.value = error.response?.data?.message || "Invalid credentials";
        } finally {
            loading.value = false;
        }
    };
    
    const loginWithGoogle = () => console.log("Google Login (Pending)...");
    </script>
    
    <template>
        <v-container fluid class="fill-height bg-grey-lighten-5 pa-0 justify-center">
            <v-card width="100%" max-width="500" flat class="pa-10 rounded-xl border-sm bg-white">
                <div class="text-center mb-8">
                    <h1 class="text-h4 font-weight-bold text-grey-darken-4 mb-2">Log in</h1>
                </div>
    
                <div class="d-flex justify-center mb-6">
                    <v-btn variant="outlined" border="sm" rounded="lg" class="py-7 px-10 d-flex align-center justify-center" @click="loginWithGoogle">
                        <v-img src="https://www.gstatic.com/images/branding/product/2x/googleg_48dp.png" width="22" height="22" contain></v-img>
                    </v-btn>
                </div>
    
                <v-divider class="mb-8 mt-2">
                    <span class="text-caption text-grey">or</span>
                </v-divider>
    
                <v-alert v-if="errorMessage" type="error" variant="tonal" density="compact" class="mb-4 rounded-lg">
                    {{ errorMessage }}
                </v-alert>
    
                <v-form @submit.prevent="handleLogin">
                    <div class="text-caption font-weight-bold text-grey-darken-1 mb-1 ml-1">Email address</div>
                    <v-text-field v-model="email" variant="outlined" density="comfortable" rounded="lg" color="deep-purple-accent-4" class="mb-3" persistent-placeholder type="email" required :disabled="loading"></v-text-field>
    
                    <div class="text-caption font-weight-bold text-grey-darken-1 mb-1 ml-1">Password</div>
                    <v-text-field v-model="password" :type="showPassword ? 'text' : 'password'" variant="outlined" density="comfortable" rounded="lg" color="deep-purple-accent-4" persistent-placeholder :disabled="loading" required>
                        <template v-slot:append-inner>
                            <v-icon :icon="showPassword ? 'mdi-eye-off' : 'mdi-eye'" @click="showPassword = !showPassword" size="small" color="grey"></v-icon>
                        </template>
                    </v-text-field>
    
                    <v-btn variant="text" size="small" color="deep-purple-accent-4" class="text-none font-weight-black px-1 mb-6 mt-n2">Forgot password?</v-btn>
    
                    <v-btn block color="deep-purple-accent-2" size="x-large" rounded="lg" class="font-weight-bold text-none mb-6" elevation="0" type="submit" :loading="loading">
                        Log in
                    </v-btn>
                </v-form>
    
                <div class="text-center">
                    <v-btn variant="text" color="deep-purple-accent-4" class="text-none font-weight-bold mb-4">Can't Access Your Account?</v-btn>
                    <div class="text-body-2 text-grey-darken-1">
                        Don't have an account?
                        <v-btn variant="text" color="deep-purple-accent-4" class="text-none font-weight-black px-1" @click="$router.push('/register')">Sign Up</v-btn>
                    </div>
                </div>
            </v-card>
        </v-container>
    </template>
