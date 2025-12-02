<template>
    <HPopover
        side="bottom"
        position="end"
        reposition-on-overflow
        :click-to-close="false"
        :outside-click="false"
    >
        <template #trigger>
            <HButton
                icon-left="calendar"
                level="tertiary"
                class="calendar-button"
                size="lg"
            />
        </template>

        <template #mouseclick>
            <div
                class="popover-container"
                :class="{ 'with-form': showTaskForm }"
            >
                <div class="reminder">
                    <ReminderHeader />

                    <ul class="tasks">
                        <Task
                            v-for="i in 3"
                            :key="i"
                            :label="`Tarefa ${i}`"
                            :disabled="i === 1"
                        />
                    </ul>

                    <ReminderFooter
                        :disabled="showTaskForm"
                        @create-reminder="toggleTaskForm"
                    />
                </div>

                <TaskForm
                    v-if="showTaskForm"
                    @cancel="toggleTaskForm"
                    @save="saveTask"
                />
            </div>
        </template>
    </HPopover>
</template>

<script setup lang="ts">
import { HButton } from "@huggydigital/hk-button";
import { HPopover } from "@huggydigital/hk-popover";
import Task from "../task/Task.vue";
import ReminderHeader from "./ReminderHeader.vue";
import ReminderFooter from "./ReminderFooter.vue";
import TaskForm, { type FormData } from "../task/TaskForm.vue";
import { ref } from "vue";

const showTaskForm = ref<boolean>(false);

function toggleTaskForm() {
    showTaskForm.value = !showTaskForm.value;
}

function saveTask(data: FormData) {
    toggleTaskForm();
}
</script>

<style scoped>
.popover-container {
    background-color: var(--white);
    border: 1px solid var(--neutral-40);
    border-radius: 12px;
    box-shadow: 0 1px 16px rgba(67, 67, 71, 0.08);
    width: 269px;
    height: 400px;
    display: flex;
}

.popover-container.with-form {
    width: 538px;
}

.reminder {
    display: flex;
    flex-direction: column;
    gap: 16px;
    padding: 16px;
    flex: 1;
}

.tasks {
    display: flex;
    flex-direction: column;
    gap: 6px;
    flex: 1;
}
</style>
