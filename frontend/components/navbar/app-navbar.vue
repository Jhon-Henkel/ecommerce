<script setup>
import {useTheme} from "~/composables/theme/use.theme.js";
import {useAuthStore} from "~/modules/auth/auth.store.js";
import AppNavbarColorModeButton from "~/components/navbar/app-navbar-color-mode-button.vue";
import AppNavbarUser from "~/components/navbar/app-navbar-user.vue";
import AppNavbarSettingsButton from "~/components/navbar/app-navbar-settings-button.vue";
import {RouteUtil} from "~/utils/route/route.util.js";

const {currentTheme} = useTheme()
const store = useAuthStore()
const device = useDevice()
</script>

<template>
    <div class="bg-info-950 top-0 z-50 shadow-md">
        <nav :class="[currentTheme.navbar, 'w-full px-4 py-4 flex items-center justify-between']">
            <NuxtImg
                src="/assets/images/logo.svg"
                alt="Logo"
                :class="[(device.isMobile ? 'ml-10' : 'md:ml-5'), 'h-5 cursor-pointer']"
                @click="RouteUtil.redirect(PagesMap.page.home)"
            />
            <div class="flex items-center space-x-3 md:mr-5">
                <span class="text-white hidden sm:inline">Olá {{ store.name }}</span>
                <app-navbar-color-mode-button/>
                <app-navbar-settings-button/>
                <app-navbar-user/>
            </div>
        </nav>
    </div>
</template>
