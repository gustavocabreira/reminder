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
                    <HTextInput
                        v-model="formData.date"
                        placeholder="00/00/00"
                    />
                </HFormField>
                <HFormField label="Hora">
                    <HTextInput v-model="formData.time" placeholder="00:00" />
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
                    :display-value="
                        (option) => {
                            console.log('option', option);
                            return option.label;
                        }
                    "
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
            <HButton variant="success" @click="save">Salvar</HButton>
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

type FormData = {
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

const emit = defineEmits<Emits>();

const inputTitleRef = ref<InstanceType<typeof HTextInput> | null>(null);
const formData = ref<FormData>(defaultFormData);

const selectedRemindAtOption = computed(() => {
    const found = remindAtOptions.find(
        (option) => option.item.value === formData.value.remindAt
    );

    return found || null;
});

onMounted(() => {
    inputTitleRef.value?.focus();
});

function save() {}

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
