import { defineStore } from 'pinia'
import {RouteUtil} from "~/utils/route/route.util";

const expirationSessionTimeInSeconds: number = 14400

export const useAuthStore = defineStore('auth', {
    state: () => ({
        token: null as string|null,
        expiresIn: null as number|null,
    }),
    persist: true,
    actions: {
        login(data: {token: string}): void {
            this.token = data.token
            this.expiresIn = Math.floor(Date.now() / 1000) + expirationSessionTimeInSeconds
        },
        logout(): void {
            this.token = null
            this.expiresIn = null
            RouteUtil.redirect(PagesMap.page.index)
        }
    }
})
