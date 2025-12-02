<template>
    <div class="reminder-header">
        <div class="selectors">
            <template v-for="option in dayOptions" :key="option.id">
                <HButton
                    v-if="option.icon"
                    level="secondary"
                    :icon-left="option.icon"
                    :selected="option.id === selectedDay"
                    @click="selectDay(option.id)"
                />
                <HButton
                    v-else
                    level="secondary"
                    :selected="option.id === selectedDay"
                    @click="selectDay(option.id)"
                >
                    {{ option.label }}
                </HButton>
            </template>
        </div>

        <HButton level="tertiary" icon-left="help" size="sm" />
    </div>
</template>

<script setup lang="ts">
import { HButton } from "@huggydigital/hk-button";
import type { Icons } from "@huggydigital/hk-icons";
import { ref } from "vue";

type Day = "today" | "tomorrow" | "custom";
type DayOption = {
    id: Day;
    label?: string;
    icon?: Icons;
};

type Emits = {
    (e: "selectDay", day: Day): void;
};

const emit = defineEmits<Emits>();

const dayOptions: DayOption[] = [
    { id: "today", label: "Hoje" },
    { id: "tomorrow", label: "Amanhã" },
    { id: "custom", icon: "calendar" },
];

const selectedDay = ref<Day>("today");

function selectDay(day: Day) {
    selectedDay.value = day;
    emit("selectDay", day);
}
</script>

<style scoped>
.reminder-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.selectors {
    display: flex;
    gap: 4px;
}
</style>
