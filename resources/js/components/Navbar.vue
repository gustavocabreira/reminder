<template>
    <nav class="navbar">
        <HText decoration="medium" variant="headline-3">Página inicial</HText>

        <Reminder />
    </nav>
</template>

<script setup lang="ts">
import { HButton } from "@huggydigital/hk-button";
import { HText } from "@huggydigital/hk-text";
import Reminder from "./reminder/Reminder.vue";
</script>

<style scoped>
.navbar {
    height: 64px;
    width: 100%;
    background-color: var(--white);
    border-bottom: 1px solid var(--neutral-40);
    padding: 16px 24px;

    display: flex;
    justify-content: space-between;
    align-items: center;
}

.calendar-button {
    width: 36px;
    height: 32px;
}
</style>
