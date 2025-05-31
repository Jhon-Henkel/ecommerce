<script setup lang="ts">
import { computed } from 'vue'

const props = defineProps({
    cols: {
        type: Number,
        default: 2,
    },
})

const gridClass = computed(() => {
    if (window.innerWidth < 640) {
        return getGridClass(1)
    }
    return getGridClass(props.cols)
})

function getGridClass(colCount: number): string {
    const colsMap: Record<number, string> = {
        1: 'grid-cols-1',
        2: 'grid-cols-2',
        3: 'grid-cols-3',
        4: 'grid-cols-4',
        5: 'grid-cols-5',
        6: 'grid-cols-6',
        7: 'grid-cols-7',
        8: 'grid-cols-8',
        9: 'grid-cols-9',
        10: 'grid-cols-10',
        11: 'grid-cols-11',
        12: 'grid-cols-12',
    }
    const safeCol = Math.min(Math.max(1, colCount), 12)
    return colsMap[safeCol] || 'grid-cols-2'
}
</script>

<template>
    <div class="grid" :class="[gridClass, 'gap-4']">
        <slot/>
    </div>
</template>
