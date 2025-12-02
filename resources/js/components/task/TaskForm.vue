<template>
    <div class="form-container">
        <div class="form-header">
            <HText variant="subtitle-2" decoration="medium">
                Criar lembrete
            </HText>
        </div>

        <HForm class="form">
            <HFormField label="Título">
                <HTextInput
                    v-model="formData.title"
                    ref="inputTitleRef"
                    placeholder="Ex: Ligar para Pedro"
                />
            </HFormField>

            <div style="display: flex; gap: 8px">
                <HFormField label="Data">
                    <DatePicker
                        v-model:value="formData.date"
                        type="date"
                        valueType="YYYY-MM-DD"
                        format="DD/MM/YY"
                        placeholder="00/00/00"
                        confirm-text="Aplicar"
                        confirm
                        :show-icon="true"
                        @confirm="confirmDate"
                        @open="openPicker"
                        @close="closePicker"
                    ></DatePicker>
                </HFormField>
                <HFormField label="Hora">
                    <DatePicker
                        v-model:value="formData.time"
                        type="time"
                        valueType="HH:mm:ss"
                        format="HH:mm"
                        placeholder="00:00"
                        confirm-text="Aplicar"
                        :time-labels="timeLabels"
                        confirm
                        :show-icon="true"
                        @confirm="confirmTime"
                        @open="openPicker"
                        @close="closePicker"
                    ></DatePicker>
                </HFormField>
            </div>

            <HFormField label="Vincular chat/contato">
                <HSelect
                    v-model="formData.contact"
                    placeholder="Selecionar"
                    fill
                ></HSelect>
            </HFormField>

            <HFormField label="Lembrar em">
                <HSelect
                    :selected="selectedRemindAtOption"
                    :display-value="(option) => option.label"
                    @select="(option) => (formData.remindAt = option.value)"
                    placeholder="Selecionar"
                    fill
                >
                    <template #default>
                        <HSelectList
                            :options="remindAtOptions"
                            :comparable="(a, b) => a.item.value === b.value"
                        />
                    </template>
                </HSelect>
            </HFormField>
        </HForm>

        <div class="form-footer">
            <HButton level="tertiary" @click="cancel">Cancelar</HButton>
            <HButton variant="success" :disabled="saveDisabled" @click="save">
                Salvar
            </HButton>
        </div>
    </div>
</template>

<script setup lang="ts">
import { HButton } from "@huggydigital/hk-button";
import { HForm, HFormField } from "@huggydigital/hk-form";
import { HSelect, HSelectList } from "@huggydigital/hk-select-v2";
import { HText } from "@huggydigital/hk-text";
import { HTextInput } from "@huggydigital/hk-text-input";
import { computed, onMounted, ref } from "vue";
import DatePicker from "@huggydigital/huggy-datepicker";

export type FormData = {
    title: string;
    date: string;
    time: string;
    contact: string;
    remindAt: number;
};

type Props = {
    task?: any;
};

type Emits = {
    (e: "save", data: FormData): void;
    (e: "cancel"): void;
    (e: "showPicker", show: boolean): void;
};

const defaultFormData: FormData = {
    title: "",
    date: "",
    time: "",
    contact: "",
    remindAt: 0,
};

const remindAtOptions = [
    {
        label: "Na hora do evento",
        item: {
            label: "Na hora do evento",
            value: 0,
        },
    },
    {
        label: "1min antes",
        item: {
            label: "1min antes",
            value: 1,
        },
    },
    {
        label: "10min antes",
        item: {
            label: "10min antes",
            value: 10,
        },
    },
    {
        label: "30min antes",
        item: {
            label: "30min antes",
            value: 30,
        },
    },
    {
        label: "1h antes",
        item: {
            label: "1h antes",
            value: 60,
        },
    },
];

const timeLabels = {
    hour: "Hora",
    minute: "Minuto",
};

const emit = defineEmits<Emits>();

const inputTitleRef = ref<InstanceType<typeof HTextInput> | null>(null);
const formData = ref<FormData>(defaultFormData);

const selectedRemindAtOption = computed(() => {
    const found = remindAtOptions.find(
        (option) => option.item.value === formData.value.remindAt
    );

    return found || null;
});

const saveDisabled = computed(() => {
    return (
        formData.value.title.trim() === "" ||
        formData.value.date === "" ||
        formData.value.time === "" ||
        formData.value.contact === "" ||
        formData.value.remindAt === undefined
    );
});

onMounted(() => {
    inputTitleRef.value?.focus();
});

function confirmDate(selectedDate: string) {
    formData.value.date = selectedDate;
}

function confirmTime(selectedTime: string) {
    formData.value.time = selectedTime;
}

function openPicker() {
    emit("showPicker", true);
}

function closePicker() {
    emit("showPicker", false);
}

function save() {
    if (saveDisabled.value) return;

    emit("save", formData.value);
}

function cancel() {
    formData.value = defaultFormData;
    emit("cancel");
}
</script>

<style scoped>
.form-container {
    display: flex;
    flex-direction: column;
    gap: 8px;
    border-left: 1px solid var(--neutral-40);
    padding: 16px;
    flex: 1;
}

.form {
    gap: 8px;
    flex: 1;
}

.form-footer {
    display: flex;
    justify-content: space-between;
}
</style>
