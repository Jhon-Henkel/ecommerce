<script setup lang="ts">
import {BreadcrumbDTO} from "~/components/breadcrumb/dto/breadcrumb.dto";
import AppPage from "~/components/page/app-page.vue";
import {BreadcrumbItemDTO} from "~/components/breadcrumb/dto/breadcrumb.item.dto";
import AppPageTitle from "~/components/page/app-page-title.vue";
import AppGrid from "~/components/grid/app-grid.vue";
import {CartService} from "~/modules/cart/cart.service";
import type {ICartList} from "~/modules/cart/cart.list.interface";
import {useAuthStore} from "~/modules/auth/auth.store";
import {NumberUtil} from "~/utils/number/number.util";
import {ProductService} from "~/modules/products/product.service";
import AppButton from "~/components/button/app-button.vue";
import {IconEnum} from "~/utils/enum/icon.enum";
import AppFormInputNumber from "~/components/input/app-form-input-number.vue";
import type {ICartItemList} from "~/modules/cart/item/cart.item.list.interface";
import {CartItemService} from "~/modules/cart/item/cart.item.service";
import {useCartStore} from "~/modules/cart/item/cart.item.store";
import {RouteUtil} from "~/utils/route/route.util";

const cartService = new CartService()
const cartItemService = new CartItemService()
const productService = new ProductService()
const cart = ref<ICartList|null>(null)
const userStore = useAuthStore()
const editMode = ref<boolean>(false)
const editId = ref<number|null>(null)
const cartStore = useCartStore()

const breadcrumb = new BreadcrumbDTO([
    new BreadcrumbItemDTO(PagesMap.page.cart)
]);

async function loadCart(): Promise<void> {
    cartStore.quantity = 0
    if (userStore.userId) {
        cart.value = await cartService.get(userStore.userId)
        cartStore.quantity = cart.value.total_items
    }
}

async function editItem(item: ICartItemList): Promise<void> {
    await cartItemService.updateQuantity(item.id, item.quantity);
    await loadCart();
    editMode.value = false
    editId.value = null
}

async function deleteItem(item: ICartItemList): Promise<void> {
    await cartItemService.delete(item.id)
    await loadCart();
}

async function deleteCart(): Promise<void> {
    if (cart.value) {
        await cartService.delete(cart.value.id)
        await loadCart();
    }
}

onMounted(async () => {
    await loadCart()
})
</script>

<template>
    <app-page :breadcrumb="breadcrumb" :page-title="PagesMap.page.cart.label">
        <app-page-title :title="PagesMap.page.cart.label"/>
        <app-grid v-if="cart">
            <UCard variant="subtle">
                <template #header>
                    <div class="flex justify-between">
                        <span class="font-bold text-success-600">
                            Produtos
                        </span>
                        <app-button variant="ghost" color="error" :disabled="(cart?.total_items ?? 0) <= 0" @click="deleteCart">
                            Esvaziar Carrinho
                        </app-button>
                    </div>
                </template>

                <div v-for="item in cart.items" :key="item.id" class="mb-3">
                    <USeparator/>
                    <div class="flex w-full">
                        <div class="w-15 h-15">
                            <NuxtImg :src="productService.getImageByProduct(item.product)" alt="Imagem Produto" class="w-full rounded-lg"/>
                        </div>
                        <div class="ms-5 flex flex-col">
                            <span>{{ item.product.name }}</span>
                            <span>{{item.quantity}} x R$ {{ NumberUtil.toCurrency(item.product.price) }}</span>
                        </div>
                        <div class="ml-auto">
                            <div class="flex items-center justify-center">
                                <div v-if="editMode && item.id === editId" class="py-4 space-x-1 flex items-center">
                                    <app-form-input-number v-model="item.quantity" name="quantity" label="" css-class="max-w-30"/>
                                    <app-button :icon="IconEnum.check" @click="editItem(item)"/>
                                </div>
                                <div v-else class="py-4 space-x-1">
                                    <app-button :icon="IconEnum.pencil" @click="editMode = true; editId = item.id"/>
                                    <app-button :icon="IconEnum.trash2" color="error" @click="deleteItem(item)"/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <USeparator/>
                </div>
                <template #footer>
                    <div class="flex justify-between font-bold text-success-600">
                        <span>Sub Total:</span>
                        <span>R$ {{ NumberUtil.toCurrency(cart?.subtotal_amount ?? 0) }}</span>
                    </div>
                </template>
            </UCard>
            <UCard variant="subtle">
                <template #header>
                    <div class="flex justify-between">
                        <span class="font-bold text-success-600">
                            Forma de Pagamento
                        </span>
                        <app-button variant="ghost" color="info">
                            Alterar Forma de Pagamento
                        </app-button>
                    </div>
                </template>
            </UCard>
        </app-grid>
        <app-grid v-else :cols="1">
            <div class="text-center">
                <span class="font-bold text-success-600">
                    Poxa, seu carrinho está vaio! :(
                </span>
            </div>
            <div class="text-center">
                <app-button :icon="IconEnum.shoppingCart" variant="subtle" @click="RouteUtil.redirect(PagesMap.page.index)">
                    Ir as Compras
                </app-button>
            </div>
        </app-grid>
    </app-page>
</template>
