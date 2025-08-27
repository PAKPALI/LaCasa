import { createApp } from 'vue';
import App from '../components/Index.vue';
import router from '../router'; // Import du routeur

// Charger Bootstrap CSS
// import 'bootstrap/dist/css/bootstrap.min.css';
// Charger Bootstrap JS (si tu veux l'utiliser)
// import 'bootstrap/dist/js/bootstrap.bundle.min.js';

const app = createApp(App)
app.use(router)
app.mount('#app')