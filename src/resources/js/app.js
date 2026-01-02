import './bootstrap';
import { createApp } from 'vue';

// Vuetify
import { createVuetify } from 'vuetify';
import * as components from 'vuetify/components';
import * as directives from 'vuetify/directives';
import 'vuetify/styles';
import '@mdi/font/css/materialdesignicons.css';
import { createPinia } from 'pinia';

// Componente Principal
import App from '../App.vue';
import router from '../router/index.js';

const vuetify = createVuetify({
    components,
    directives,
});

const app = createApp(App);

// Dentro de WeightChart.vue o MacrosChart.vue
import { Chart as ChartJS, Title, Tooltip, Legend, LineElement, CategoryScale, LinearScale, PointElement } from 'chart.js'

ChartJS.register(Title, Tooltip, Legend, LineElement, CategoryScale, LinearScale, PointElement)


app.use(vuetify);
app.use(router);
app.use(createPinia());
app.mount('#app');
