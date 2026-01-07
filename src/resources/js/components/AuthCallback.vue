<script setup>
    import { onMounted, ref } from "vue";
    import { useRouter, useRoute } from "vue-router";
    import { useUserStore } from "@/stores/useUserStore";

    const router = useRouter();
    const route = useRoute();
    const userStore = useUserStore();

    const loading = ref(true);
    const errorMessage = ref("");

    onMounted(() => {
        handleOAuthCallback();
    });

    const handleOAuthCallback = () => {
        try {
            const { token, user, error } = route.query;

            if (error) {
                errorMessage.value = error;
                setTimeout(() => {
                    router.push('/login');
                }, 3000);
                return;
            }

            if (!token || !user) {
                errorMessage.value = "Authentication failed. Missing credentials.";
                setTimeout(() => {
                    router.push('/login');
                }, 3000);
                return;
            }

            // Parse user data from JSON string
            const userData = JSON.parse(user);

            // Store auth data
            userStore.setAuth(userData, token);

            // Redirect to dashboard
            router.push({ name: 'dashboard' });
        } catch (error) {
            console.error("OAuth callback error:", error);
            errorMessage.value = "An error occurred during authentication.";
            setTimeout(() => {
                router.push('/login');
            }, 3000);
        } finally {
            loading.value = false;
        }
    };
    </script>

    <template>
        <v-container fluid class="fill-height bg-grey-lighten-5 pa-0 justify-center align-center">
            <v-card width="100%" max-width="400" flat class="pa-10 rounded-xl border-sm bg-white text-center">
                <div v-if="loading">
                    <v-progress-circular indeterminate color="deep-purple-accent-4" size="48" class="mb-4"></v-progress-circular>
                    <h2 class="text-h6 text-grey-darken-2 mb-2">Authenticating...</h2>
                    <p class="text-body-2 text-grey">Please wait while we complete your login.</p>
                </div>

                <div v-else-if="errorMessage">
                    <v-icon icon="mdi-alert-circle" color="error" size="48" class="mb-4"></v-icon>
                    <h2 class="text-h6 text-error mb-2">Authentication Failed</h2>
                    <p class="text-body-2 text-grey">{{ errorMessage }}</p>
                    <p class="text-caption text-grey mt-4">Redirecting to login...</p>
                </div>

                <div v-else>
                    <v-icon icon="mdi-check-circle" color="success" size="48" class="mb-4"></v-icon>
                    <h2 class="text-h6 text-success mb-2">Success!</h2>
                    <p class="text-body-2 text-grey">Redirecting to dashboard...</p>
                </div>
            </v-card>
        </v-container>
    </template>
