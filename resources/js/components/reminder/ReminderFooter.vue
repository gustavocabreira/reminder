<template>
    <div class="reminder-footer">
        <HButton
            class="create-reminder"
            :disabled="disabled"
            @click="createReminder"
        >
            Criar lembrete
        </HButton>
    </div>
</template>

<script setup lang="ts">
import { HButton } from "@huggydigital/hk-button";

type Props = {
    disabled?: boolean;
};

type Emits = {
    (e: "createReminder"): void;
};

const props = withDefaults(defineProps<Props>(), {
    disabled: false,
});
const emit = defineEmits<Emits>();

function createReminder() {
    emit("createReminder");
}
</script>

<style scoped>
.create-reminder {
    width: 100%;
}
</style>
