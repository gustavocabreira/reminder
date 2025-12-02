<template>
    <div class="layout">
        <Sidebar />

        <div class="page-container">
            <Navbar />
            <main>
                <router-view />
            </main>
        </div>
    </div>
</template>

<script lang="ts" setup>
import Sidebar from "./components/Sidebar.vue";
import Navbar from "./components/Navbar.vue";
import * as Toast from "@huggydigital/hk-toast";

import { useEchoPublic } from "@laravel/echo-vue";

useEchoPublic("company.1.user.1.reminders", ".reminder.notify", (e: any) => {
    Toast.show({
        title: "Atenção!",
        description: e.title + " acontecerá em " + (new Date(e.scheduled_at)).toLocaleString("pt-BR", {
            year: "numeric",
            month: "2-digit",
            day: "2-digit",
            hour: "2-digit",
            minute: "2-digit",
        }),
        canUndo: false,
        variant: "success",
        closable: false,
        hideIcon: true,
        vertical: "top",
    });
});
</script>

<style scoped>
.layout {
    display: flex;
    height: 100%;
    width: 100%;
}

.page-container {
    flex: 1;
    background-color: var(--neutral-0);
    display: flex;
    flex-direction: column;
    height: 100%;
}
</style>
