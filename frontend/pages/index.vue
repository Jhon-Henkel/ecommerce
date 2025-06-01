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
import {NumberUtil} from "~/utils/number/number.util";

const store = useAuthStore()
const items = ref<IProductList[]>()
const loading = ref(true)
const productService = new ProductService()

async function addToCart(item: IProductList) {

}

onMounted(async () => {
    loading.value = true
    items.value = await productService.list();
    loading.value = false
})
</script>

<template>
    <app-page :breadcrumb="new BreadcrumbDTO()" page-title="Início">
        <div class="flex justify-between">
            <app-page-title title="Confira Nossos Produtos"/>
            <app-page-title title="Tudo com 10% de desconto à vista"/>
        </div>
        <app-grid :cols="3">
            <UCard v-for="item in items" :key="item.id">
                <NuxtImg :src="productService.getImageByProduct(item)" alt="Imagem Produto" class="w-full rounded-lg" width="320" height="320"/>
                <template #footer>
                    <div class="flex justify-between text-lg mb-2">
                        <span>
                            {{ item.name }}
                        </span>
                        <span>
                            R$ {{ NumberUtil.toCurrency(item.price) }}
                        </span>
                    </div>
                    <app-button v-if="store.token" :icon="IconEnum.shoppingCart" class="w-full justify-center" @click="addToCart(item)">
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
