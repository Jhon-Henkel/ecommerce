<!-- components/Button.vue -->
<script setup>
import {useTheme} from "~/composables/theme/use.theme.js";

defineProps({
    positionEnd: {
        type: Boolean,
        default: false,
    },
    color: {
        type: String,
        required: false,
        default: undefined,
    }
})

const { currentTheme } = useTheme()
</script>

<template>
    <div v-if="positionEnd" class="flex justify-end">
        <!-- eslint-disable vue/no-duplicate-attributes -->
        <UButton v-bind="$attrs" :color="color ?? currentTheme.primaryColorRoot" >
            <slot />
        </UButton>
    </div>
    <!-- eslint-disable vue/no-duplicate-attributes -->
    <UButton v-else v-bind="$attrs" :color="color ?? currentTheme.primaryColorRoot" >
        <slot />
    </UButton>
</template>
