<script setup>
import {IconEnum} from "~/utils/enum/icon.enum.js";

const colorMode = useColorMode()

const isDark = computed({
    get() {
        return colorMode.value === 'dark'
    },
    set(_isDark) {
        colorMode.preference = _isDark ? 'dark' : 'light'
    }
})
const icon = computed(() => {
    return isDark.value ? IconEnum.moon : IconEnum.sun
})
</script>

<template>
    <ClientOnly v-if="!colorMode?.forced">
        <UButton :icon="icon" color="neutral" variant="ghost" class="text-white cursor-pointer" @click="isDark = !isDark"/>
        <template #fallback>
            <div class="size-8"/>
        </template>
    </ClientOnly>
</template>
