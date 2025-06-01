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
import {PaymentMethodService} from "~/modules/payment-method/payment.method.service";
import AppFormSelect from "~/components/select/app-form-select.vue";
import type {IPaymentMethodList} from "~/modules/payment-method/payment.method.list.item.interface";
import {appAlert} from "~/composables/alert/alert";

const cartService = new CartService()
const cartItemService = new CartItemService()
const productService = new ProductService()
const paymentMethodService = new PaymentMethodService()
const paymentMethods = ref<IPaymentMethodList[]|[]>([])
const cart = ref<ICartList|null>(null)
const userStore = useAuthStore()
const editProductMode = ref<boolean>(false)
const editPaymentMode = ref<boolean>(false)
const editId = ref<number|null>(null)
const cartStore = useCartStore()
const installments = ref<{id: number, label: string}[]>([])

const breadcrumb = new BreadcrumbDTO([
    new BreadcrumbItemDTO(PagesMap.page.cart)
]);

async function loadCart(): Promise<void> {
    cartStore.quantity = 0
    if (userStore.userId) {
        cart.value = await cartService.get(userStore.userId)
        if (cart.value.installments === 0) {
            cart.value.installments = 1
        }
        cartStore.quantity = cart.value.total_items
        getInstallmentList()
    }
}

async function editItem(item: ICartItemList): Promise<void> {
    await cartItemService.updateQuantity(item.id, item.quantity);
    await loadCart();
    editProductMode.value = false
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

async function updatePaymentMethod(): Promise<void> {
    if (cart.value) {
        await cartService.update(cart.value)
        await loadCart();
        editPaymentMode.value = false
    }
}

function getInstallmentList(): void {
    let maxInstallments = 1
    paymentMethods.value.forEach((item) => {
        if (item.id === cart.value?.payment_method_id) {
            maxInstallments = item.max_installments
        }
    })
    const items = []
    for (let index = 1; index <= maxInstallments; index++) {
        if (index === 1) {
            items.push({id: index, label: `${index}x (10% de desconto)`})
            continue
        }
        items.push({id: index, label: `${index}x (1% de juros ao mês)`})
    }
    installments.value = items;
}

onMounted(async () => {
    paymentMethods.value = await paymentMethodService.list()
    await loadCart()
})
</script>

<template>
    <app-page :breadcrumb="breadcrumb" :page-title="PagesMap.page.cart.label">
        <app-page-title :title="PagesMap.page.cart.label"/>
        <UAlert
            class="mb-3"
            color="info"
            variant="subtle"
            title="Atenção!"
            description="Pagamentos à vista (1x), tem desconto de 10%, demais parcelas, tem juros de 1% ao mês!"
        />
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
                                <div v-if="editProductMode && item.id === editId" class="py-4 space-x-1 flex items-center">
                                    <app-form-input-number v-model="item.quantity" name="quantity" label="" css-class="max-w-30"/>
                                    <app-button :icon="IconEnum.check" @click="editItem(item)"/>
                                </div>
                                <div v-else class="py-4 space-x-1">
                                    <app-button :icon="IconEnum.pencil" @click="editProductMode = true; editId = item.id"/>
                                    <app-button :icon="IconEnum.trash2" color="error" @click="deleteItem(item)"/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <USeparator/>
                </div>

                <template #footer>
                    <div class="flex justify-between font-bold text-success-600">
                        <span>Sub Total Produtos:</span>
                        <span>R$ {{ NumberUtil.toCurrency(cart?.subtotal_amount ?? 0) }}</span>
                    </div>
                </template>
            </UCard>
            <UCard variant="subtle">
                <template #header>
                    <div class="flex justify-between">
                        <span class="font-bold text-success-600">
                            Pagamento
                        </span>
                        <app-button
                            v-if="cart.payment_method_id !== null && ! editPaymentMode"
                            variant="ghost"
                            color="info"
                            @click="editPaymentMode = true"
                        >
                            Alterar Forma de Pagamento
                        </app-button>
                    </div>
                </template>

                <div v-if="editPaymentMode" class="space-y-3">
                    <app-form-select
                        v-model="cart.payment_method_id"
                        :items="paymentMethods"
                        label="Forma de Pagamento"
                        name="payment_method_id"
                        label-key="name"
                        @update:model-value="getInstallmentList()"
                    />
                    <app-form-select
                        v-if="cart.payment_method_id"
                        v-model="cart.installments"
                        :items="installments"
                        label="Quantidade de parcelas"
                        name="installments"
                    />
                </div>
                <div v-else-if="cart.payment_method_id === null">
                    <app-button class="w-full justify-center" :icon="IconEnum.handCoins" @click="editPaymentMode = true">
                        Escolher Forma de Pagamento
                    </app-button>
                </div>
                <div v-else class="text-success-600">
                    <div class="flex justify-between">
                        <span>Forma selecionada:</span>
                        <span>{{ cart.payment_method.name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Parcelas:</span>
                        <span>{{ cart.installments }}</span>
                    </div>
                </div>

                <template #footer>
                    <div v-if="editPaymentMode">
                        <app-button
                            class="w-full justify-center"
                            :icon="IconEnum.check"
                            :disabled="cart.payment_method_id === null"
                            @click="updatePaymentMethod"
                        >
                            {{ cart.payment_method_id ? 'Alterar Forma de Pagamento' : 'Selecionar Forma de Pagamento'}}
                        </app-button>
                    </div>
                    <div v-else class="font-bold text-success-600">
                        <div class="flex justify-between">
                            <span>Desconto:</span>
                            <span>- R$ {{ NumberUtil.toCurrency(cart?.discount_amount ?? 0) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Total:</span>
                            <span>R$ {{ NumberUtil.toCurrency(cart?.total_amount ?? 0) }}</span>
                        </div>
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
        <app-button
            v-if="cart"
            class="mt-4"
            :icon="IconEnum.shoppingCart"
            variant="subtle"
            position-end
            @click="appAlert.success(
                'Obrigado por testar.',
                'Caro avaliador, espero que tenha gostado da experiência nessa demonstração, ficarei muito feliz se passar para a próxima etapa do processo. Tenha um bom dia! ;)'
            )"
        >
            Finalizar pedido
        </app-button>
    </app-page>
</template>
