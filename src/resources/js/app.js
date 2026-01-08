import './bootstrap';
import { createApp } from 'vue';

// Vuetify
import { createVuetify } from 'vuetify';
import * as components from 'vuetify/components';
import * as directives from 'vuetify/directives';
import 'vuetify/styles';
import '@mdi/font/css/materialdesignicons.css';
import { createPinia } from 'pinia';

// Toast Notifications
import Toast from 'vue-toastification';
import 'vue-toastification/dist/index.css';

// Componente Principal
import App from '../App.vue';
import router from '../router/index.js';

const vuetify = createVuetify({
    components,
    directives,
});

// Toast configuration
const toastOptions = {
    position: 'top-right',
    timeout: 3000,
    closeOnClick: true,
    pauseOnFocusLoss: true,
    pauseOnHover: true,
    draggable: true,
    draggablePercent: 0.6,
    showCloseButtonOnHover: false,
    hideProgressBar: false,
    closeButton: 'button',
    icon: true,
    rtl: false,
    maxToasts: 5,
    newestOnTop: true
};

const app = createApp(App);

// Chart.js setup
import { Chart as ChartJS, Title, Tooltip, Legend, LineElement, CategoryScale, LinearScale, PointElement } from 'chart.js'

ChartJS.register(Title, Tooltip, Legend, LineElement, CategoryScale, LinearScale, PointElement)

app.use(vuetify);
app.use(router);
app.use(createPinia());
app.use(Toast, toastOptions);
app.mount('#app');
