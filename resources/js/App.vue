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
