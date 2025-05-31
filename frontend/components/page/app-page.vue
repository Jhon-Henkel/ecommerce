<script setup lang="ts">
import {BreadcrumbDTO} from "~/components/breadcrumb/dto/breadcrumb.dto";
import AppBreadcrumb from "~/components/breadcrumb/app-breadcrumb.vue";
import AppNavbar from "~/components/navbar/app-navbar.vue";

const props = defineProps({
    pageTitle: {
        type: String,
        required: true,
    },
    breadcrumb: {
        type: BreadcrumbDTO,
        required: true,
    }
})
const device = useDevice()

useHead({ title: props.pageTitle })
</script>

<template>
    <div class="pb-4">
        <ClientOnly>
            <app-navbar/>
            <app-breadcrumb :breadcrumb="breadcrumb"/>
            <div class="flex">
                <main :class="[(device.isMobile ? 'mx-1' : 'mx-5'), 'flex-1 transition-all px-4 overflow-hidden']">
                    <div class="w-full overflow-x-auto">
                        <slot/>
                    </div>
                </main>
            </div>
        </ClientOnly>
    </div>
</template>
