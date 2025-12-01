import { createApp } from "vue";
import "./bootstrap.js";
import App from "./App.vue";
import router from "./router/index.ts";

import "@huggydigital/hk-global/index.css";

createApp(App).use(router).mount("#app");
