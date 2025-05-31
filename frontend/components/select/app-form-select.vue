<script setup lang="ts">
import {computed} from "vue";
import {IconEnum} from "~/utils/enum/icon.enum";
import {useTheme} from "~/composables/theme/use.theme";

const props = defineProps({
    name: {
        type: String,
        required: true,
    },
    label: {
        type: String,
        required: true,
    },
    required: {
        type: Boolean,
        default: false,
    },
    description: {
        type: String,
        default: '',
    },
    modelValue: {
        type: [Number, String],
        required: true,
    },
    items: {
        type: Array<{id: string|number, label: string}>,
        required: true,
    },
    disabled: {
        type: Boolean,
        default: false,
    }
})

const { currentTheme } = useTheme()

const emit = defineEmits<{
    (e: 'update:modelValue', value: number|string): void
}>()

const model = computed({
    get: () => props.modelValue,
    set: (value) => emit('update:modelValue', value),
})
</script>

<template>
    <UFormField :name="name" :label="label" :required="required" :description="description">
        <USelectMenu
            v-model="model"
            value-key="id"
            label-key="label"
            :items="items"
            class="w-full max-w-full truncate"
            :color="currentTheme.primaryColorRoot"
            :placeholder="`Selecione um(a) ${label}...`"
            :search-input="{
                placeholder: 'Pesquisar...',
            }"
            :icon="IconEnum.search"
            :disabled="disabled"
        >
            <template #empty>
                <div class="text-sm text-gray-500">
                    Nenhum resultado encontrado!
                </div>
            </template>
        </USelectMenu>
    </UFormField>
</template>
