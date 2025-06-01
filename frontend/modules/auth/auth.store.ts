import { defineStore } from 'pinia'
import {RouteUtil} from "~/utils/route/route.util";
import {useCartStore} from "~/modules/cart/item/cart.item.store";

const expirationSessionTimeInSeconds: number = 14400

export const useAuthStore = defineStore('auth', {
    state: () => ({
        token: null as string|null,
        userId: null as number|null,
        expiresIn: null as number|null,
    }),
    persist: true,
    actions: {
        login(data: {token: string, user_id: number|null}): void {
            this.token = data.token
            this.userId = data.user_id
            this.expiresIn = Math.floor(Date.now() / 1000) + expirationSessionTimeInSeconds
        },
        logout(): void {
            this.token = null
            this.expiresIn = null
            useCartStore().quantity = 0
            RouteUtil.redirect(PagesMap.page.index)
        }
    }
})
