<script setup lang="ts">
import AppPage from "~/components/page/app-page.vue";
import {BreadcrumbDTO} from "~/components/breadcrumb/dto/breadcrumb.dto";

</script>

<template>
    <app-page :breadcrumb="new BreadcrumbDTO()" page-title="Início"/>
</template>
