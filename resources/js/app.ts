import { createApp } from "vue";
import "./bootstrap.js";
import App from "./App.vue";
import router from "./router/index.ts";
import { configureEcho } from "@laravel/echo-vue";

import '@huggydigital/huggy-datepicker/index.css'
import "@huggydigital/hk-global/index.css";

const app = createApp(App);

console.log('env', import.meta.env.VITE_REVERB_APP_KEY);

configureEcho({
    broadcaster: "reverb",
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST,
    wsPort: import.meta.env.VITE_REVERB_PORT,
    wssPort: import.meta.env.VITE_REVERB_PORT,
    forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? 'https') === 'https',
    enabledTransports: ['ws', 'wss'],
});

app.use(router).mount('#app');
