import {useAuthStore} from "~/modules/auth/auth.store";
import {RouteUtil} from "~/utils/route/route.util";

export default defineNuxtRouteMiddleware(async (to) => {
    if (import.meta.server) {
        return
    }

    const store = useAuthStore()
    const authPage = PagesMap.page.auth

    const publicRoutes = [
        authPage.login.route,
    ]

    if (publicRoutes.includes(to.path.slice(1))) {
        return
    }

    await nextTick()
    const isSessionExpired: boolean = store.expiresIn != null && store.expiresIn < Math.floor(Date.now() / 1000)

    if (store.token == null || isSessionExpired) {
        return RouteUtil.redirect({
            label: authPage.login.label,
            icon: authPage.login.icon,
            route: 'login' + '?redirect=' + to.path,
        })
    }
})
