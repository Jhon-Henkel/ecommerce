<script setup>
import {useTheme} from "~/composables/theme/use.theme.js";
import {useAuthStore} from "~/modules/auth/auth.store.js";
import AppNavbarColorModeButton from "~/components/navbar/app-navbar-color-mode-button.vue";
import AppNavbarSettingsButton from "~/components/navbar/app-navbar-settings-button.vue";
import {RouteUtil} from "~/utils/route/route.util.js";
import AppButton from "~/components/button/app-button.vue";
import {IconEnum} from "~/utils/enum/icon.enum.js";

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
                <span v-if="store.token" class="text-white hidden sm:inline">Olá Usuário</span>
                <app-button v-else :icon="IconEnum.user" @click="RouteUtil.redirect(PagesMap.page.auth.login)">
                    Fazer Login
                </app-button>
                <app-navbar-color-mode-button/>
                <app-navbar-settings-button/>
                <app-button :icon="IconEnum.logOut" class="hover:bg-error text-white" variant="ghost" color="neutral" @click="store.logout()">
                    Logout
                </app-button>
            </div>
        </nav>
    </div>
</template>
