<script setup lang="ts">
import {RouteUtil} from "~/utils/route/route.util";

const userRoute = PagesMap.page.home
const items = ref([
    {
        label: userRoute.label,
        icon: userRoute.icon,
        onSelect() {
            RouteUtil.redirect(userRoute)
        }
    }
])
</script>

<template>
    <UDropdownMenu :items="items" :content="{align: 'start'}" :ui="{content: 'w-48'}" size="xl">
    </UDropdownMenu>
</template>
