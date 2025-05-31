<script setup>
    import { TimeEnum } from "~/utils/enum/time.enum.js";
    import { ptBR } from "date-fns/locale";

    const toastConfig = {
        position: "top-right",
        duration: TimeEnum.fiveSecondsInMs,
        swipeThreshold: 100
    }
</script>

<template>
    <UApp :toaster="toastConfig" :locale="ptBR">
        <nuxt-page/>
    </UApp>
</template>

<style>
.page-enter-active,
.page-leave-active {
    transition: all 0.5s ease;
}

.page-enter-from,
.page-leave-to {
    opacity: 0;
    filter: blur(1rem);
}
</style>
