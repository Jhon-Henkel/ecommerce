<script setup lang="ts">
import {computed} from "vue";
import AppButton from "~/components/button/app-button.vue";
import {IconEnum} from "~/utils/enum/icon.enum";
import {useTheme} from "~/composables/theme/use.theme";
import type {IServiceList} from "~/utils/interface/service.list.interface";
import {RouteUtil} from "~/utils/route/route.util";

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
    valueKey: {
        type: String,
        default: 'id',
    },
    labelKey: {
        type: String,
        required: true,
    },
    modelValue: {
        type: [Number, null],
        required: true,
    },
    service: {
        type: Object as PropType<IServiceList<any>>,
        required: true,
    },
    orderBy: {
        type: String,
        default: 'id',
    },
    orderByDirection: {
        type: String,
        default: 'desc',
    },
    pageMapNewItem: {
        type: Object as PropType<PageMap>,
        required: false,
        default: undefined
    },
    pageMapEditItem: {
        type: Object as PropType<PageMap>,
        required: false,
        default: undefined
    }
})

const loading = ref(false)
const searchTerm = ref('')
const { currentTheme } = useTheme()
const listItems = ref({data: {}, status: 200})

async function list() {
    loading.value = true
    const items = await props.service.list(1, 20, searchTerm.value, props.orderBy, props.orderByDirection)
    if (props.modelValue != 0 && !items.data.find((item: any) => item.id === props.modelValue)) {
        const result = await props.service.get(props.modelValue)
        items.data.push(result)
    }
    listItems.value = items
    loading.value = false
}

const emit = defineEmits<{
    (e: 'update:modelValue', value: number|null): void
}>()

const model = computed({
    get: () => props.modelValue,
    set: (value) => emit('update:modelValue', value),
})

watch(searchTerm, async () => {
    await list()
})

onMounted(async () => {
    await new Promise(() => setTimeout(async () => {
        await list()
    }, 1000))
})
</script>

<template>
    <UFormField :name="name" :label="label" :required="required" :description="description">
        <div class="flex justify-between space-x-2">
            <USelectMenu
                v-model="model"
                :value-key="valueKey"
                :label-key="labelKey"
                :items="listItems.data"
                class="w-full max-w-full truncate"
                :color="currentTheme.primaryColorRoot"
                :loading="loading"
                :placeholder="`Selecione um(a) ${label}...`"
                :search-input="{
                    placeholder: 'Pesquisar...',
                    loading: loading,
                    loadingIcon: IconEnum.rotateCw,
                }"
                :icon="IconEnum.search"
                :loading-icon="IconEnum.rotateCw"
                :search-term="searchTerm"
                :reset-search-term-on-select="false"
                :reset-search-term-on-blur="false"
                @update:search-term="(value) => searchTerm = value"
            >
                <template #empty>
                    <div class="text-sm text-gray-500">
                        {{ loading ? 'Carregando...' : 'Nenhum resultado encontrado!' }}
                    </div>
                </template>
            </USelectMenu>
            <app-button v-if="pageMapNewItem !== undefined" :icon="IconEnum.plus" variant="soft" @click="RouteUtil.redirect(pageMapNewItem, true)"/>
            <app-button v-if="pageMapEditItem !== undefined" :icon="IconEnum.pencil" variant="soft" @click="RouteUtil.redirect(pageMapEditItem, true)"/>
            <app-button v-if="pageMapNewItem !== undefined || pageMapEditItem !== undefined" :icon="IconEnum.rotateCcw" variant="soft" @click="list"/>
        </div>
    </UFormField>
</template>
