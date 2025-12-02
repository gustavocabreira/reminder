<template>
    <div class="reminder-header">
        <div class="selectors">
            <template v-for="option in dayOptions" :key="option.id">
                <DatePicker
                    v-if="option.icon"
                    v-model:value="selectedDay"
                    type="date"
                    valueType="YYYY-MM-DD"
                    format="DD/MM/YY"
                    placeholder="00/00/00"
                    confirm-text="Aplicar"
                    :max-days-range="[
                        {
                            days: 30,
                        },
                    ]"
                    range
                    confirm
                    :show-icon="false"
                    @confirm="selectDay"
                    @open="openPicker"
                    @close="closePicker"
                >
                    <template #input>
                        <HButton
                            level="secondary"
                            :icon-left="option.icon"
                            :selected="option.id === selectedDay"
                        />
                    </template>
                </DatePicker>
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
import DatePicker from "@huggydigital/huggy-datepicker";

export type Day = "today" | "tomorrow" | "custom";
type DayOption = {
    id: Day;
    label?: string;
    icon?: Icons;
};

type Emits = {
    (e: "selectDay", day: Day): void;
    (e: "showPicker", show: boolean): void;
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

function openPicker() {
    emit("showPicker", true);
}

function closePicker() {
    emit("showPicker", false);
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
