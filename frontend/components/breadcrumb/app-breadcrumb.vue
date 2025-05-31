<script setup lang="ts">
import {BreadcrumbDTO} from "~/components/breadcrumb/dto/breadcrumb.dto";
import {useTheme} from "~/composables/theme/use.theme";

const props = defineProps({
    breadcrumb: {
        type: BreadcrumbDTO,
        required: true
    }
})
const { currentTheme } = useTheme()
const device = useDevice()
const items = ref(props.breadcrumb?.items)

onMounted(() => {
    if (device.isMobile) {
        items.value = [items.value[0], items.value[items.value.length - 1]]
    }
})
</script>

<template>
    <UBreadcrumb :items="items" :class="[(device.isMobile ? 'ml-1' : 'ml-5'), 'p-4']" :ui="{ link: currentTheme.pageTitle}"/>
</template>
