<script setup lang="ts">
import {useTheme} from "~/composables/theme/use.theme";

defineProps({
    title: {
        type: String,
        required: true
    }
})

const { currentTheme } = useTheme()
</script>

<template>
    <div class="flex justify-between mb-8">
        <span :class="[currentTheme.pageTitle, 'text-2xl font-bold']">
            {{ title }}
        </span>
        <slot/>
    </div>
</template>
