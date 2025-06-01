<script setup>
import {useTheme} from "~/composables/theme/use.theme.js";
import {useAuthStore} from "~/modules/auth/auth.store.js";
import AppNavbarColorModeButton from "~/components/navbar/app-navbar-color-mode-button.vue";
import {RouteUtil} from "~/utils/route/route.util.js";
import AppButton from "~/components/button/app-button.vue";
import {IconEnum} from "~/utils/enum/icon.enum.js";
import {useCartStore} from "~/modules/cart/item/cart.item.store.js";

const {currentTheme} = useTheme()
const store = useAuthStore()
const device = useDevice()
</script>

<template>
    <div class="bg-info-950 top-0 z-50 shadow-md">
        <nav :class="[currentTheme.navbar, 'w-full px-3 py-3 flex items-center justify-between']">
            <NuxtImg
                src="/assets/images/logo.svg"
                alt="Logo"
                :class="[(device.isMobile ? 'ml-10' : 'md:ml-5'), 'h-5 cursor-pointer']"
                @click="RouteUtil.redirect(PagesMap.page.index)"
            />
            <div class="flex items-center space-x-3 md:mr-5">
                <UChip  v-if="store.token" :text="useCartStore().quantity" size="3xl" color="neutral">
                    <app-button :icon="IconEnum.shoppingCart" @click="RouteUtil.redirect(PagesMap.page.cart)">
                        Carrinho
                    </app-button>
                </UChip>
                <app-button v-else :icon="IconEnum.user" @click="RouteUtil.redirect(PagesMap.page.auth.login)">
                    Fazer Login
                </app-button>
                <app-navbar-color-mode-button/>
                <app-button v-if="store.token" :icon="IconEnum.logOut" class="hover:bg-error text-white" variant="ghost" color="neutral" @click="store.logout()">
                    Logout
                </app-button>
            </div>
        </nav>
    </div>
</template>
