<script setup lang="ts">
import AppPage from "~/components/page/app-page.vue";
import {BreadcrumbDTO} from "~/components/breadcrumb/dto/breadcrumb.dto";
import AppGrid from "~/components/grid/app-grid.vue";
import AppPageTitle from "~/components/page/app-page-title.vue";
import AppButton from "~/components/button/app-button.vue";
import {useAuthStore} from "~/modules/auth/auth.store";
import {IconEnum} from "~/utils/enum/icon.enum";
import {ProductService} from "~/modules/products/product.service";
import type {IProductList} from "~/modules/products/product.list.item.interface";
import {RouteUtil} from "~/utils/route/route.util";

const store = useAuthStore()
const items = ref<IProductList[]>()
const loading = ref(true)
const productService = new ProductService()

onMounted(async () => {
    loading.value = true
    items.value = await productService.list();
    loading.value = false
})
</script>

<template>
    <app-page :breadcrumb="new BreadcrumbDTO()" page-title="Início">
        <app-page-title title="Confira Nossos Produtos"/>
        <app-grid :cols="5">
            <UCard v-for="item in items" :key="item.id">
                <template #header>
                    <span class="text-lg">
                        {{ item.name }}
                    </span>
                </template>

                <NuxtImg v-if="item.name === 'IPhone 16 240 GB'" src="/assets/images/iphone.webp" alt="IPhone 16" class="w-full rounded-lg" width="320" height="320"/>
                <NuxtImg v-if="item.name === 'Ar Condicionado Split 12000 Btu\'s'" src="/assets/images/ar.webp" alt="Ar Condicionado" class="w-full rounded-lg" width="320" height="320"/>
                <NuxtImg v-if="item.name === 'Parafusadeira 21v'" src="/assets/images/parafusadeira.webp" alt="Parafusadeira" class="w-full rounded-lg" width="320" height="320"/>
                <NuxtImg v-if="item.name === 'Playstation 5'" src="/assets/images/ps5.webp" alt="Playstation 5" class="w-full rounded-lg" width="320" height="320"/>
                <NuxtImg v-if="item.name === 'Guitarra Elétrica Gibson'" src="/assets/images/gibson.webp" alt="Guitarra Elétrica Gibson" class="w-full rounded-lg" width="320" height="320"/>

                <template #footer>
                    <app-button v-if="store.token" :icon="IconEnum.shoppingCart" class="w-full justify-center">
                        Adicionar ao Carrinho
                    </app-button>
                    <app-button v-else :icon="IconEnum.user" class="w-full justify-center" @click="RouteUtil.redirect(PagesMap.page.auth.login)">
                        Fazer Login
                    </app-button>
                </template>
            </UCard>
        </app-grid>
    </app-page>
</template>
