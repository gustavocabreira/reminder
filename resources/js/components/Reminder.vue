<template>
    <HPopover side="bottom" position="end" reposition-on-overflow>
        <template #trigger>
            <HButton
                icon-left="calendar"
                level="tertiary"
                class="calendar-button"
                size="lg"
            />
        </template>

        <template #mouseclick>
            <div class="reminder">
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
            </div>
        </template>
    </HPopover>
</template>

<script setup lang="ts">
import { HButton } from "@huggydigital/hk-button";
import type { Icons } from "@huggydigital/hk-icons";
import { HPopover } from "@huggydigital/hk-popover";
import { computed, ref } from "vue";

type Day = "today" | "tomorrow" | "custom";
type DayOption = {
    id: Day;
    label?: string;
    icon?: Icons;
};

const dayOptions: DayOption[] = [
    { id: "today", label: "Hoje" },
    { id: "tomorrow", label: "Amanhã" },
    { id: "custom", icon: "calendar" },
];

const selectedDay = ref<Day>("today");

function selectDay(day: Day) {
    selectedDay.value = day;
}
</script>

<style scoped>
.reminder {
    background-color: var(--white);
    border: 1px solid var(--neutral-40);
    padding: 16px;
    border-radius: 12px;
    box-shadow: 0 1px 16px rgba(67, 67, 71, 0.08);
}

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
