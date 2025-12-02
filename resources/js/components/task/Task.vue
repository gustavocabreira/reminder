<template>
    <li :class="{ task: true, disabled: disabled }">
        <HText decoration="medium" variant="caption-1">
            {{ label }}
        </HText>
        <div>
            <HText decoration="regular" variant="caption-2"> 08:00 </HText>
            <HText decoration="regular" variant="caption-2" class="divider">
                |
            </HText>
            <HText decoration="regular" variant="caption-2" class="origin">
                Joãozinho da Silva
            </HText>
        </div>
    </li>
</template>

<script setup lang="ts">
import { HText } from "@huggydigital/hk-text";

type Props = {
    label: string;
    disabled?: boolean;
};

defineProps<Props>();
</script>

<style scoped>
.task {
    background-color: var(--neutral-10);
    border: 1px solid var(--neutral-40);
    border-radius: 8px;
    padding: 6px 8px 4px 8px;
    list-style: none;
}

.task span {
    color: var(--neutral-130);
}

.task .divider {
    color: var(--neutral-80);
}

.task .origin {
    color: var(--violet-60);
}

.task.disabled {
    background-color: var(--neutral-0);
    border: 1px solid var(--neutral-20);
}

.task.disabled span {
    color: var(--neutral-90);
    text-decoration: line-through;
}
</style>
