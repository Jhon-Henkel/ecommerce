import type {LoginFormInterface} from "~/modules/auth/login/login.form.interface";
import {useAuthStore} from "~/modules/auth/auth.store";
import {useTheme} from "~/composables/theme/use.theme";
import {RouteUtil} from "~/utils/route/route.util";

export default class  LoginService {
    private store = useAuthStore()

    public async login(data: LoginFormInterface): Promise<void> {
        const { $api } = useNuxtApp()
        const result = await $api.authentication.login(data)
        this.store.login(result.data)
        this.setUserTheme()
        await nextTick()

        const redirect = useRoute().query.redirect
        if (redirect?.length) {
            const redirectPath = decodeURIComponent(redirect as string)
            if (redirectPath !== '/login') {
                RouteUtil.redirect({
                    label: '',
                    icon: '',
                    route: redirectPath,
                })
                return
            }
        }
        RouteUtil.redirect(PagesMap.page.home)
    }

    public makeFormData(): LoginFormInterface {
        return {
            email: 'admin@admin.com',
            password: 'admin',
        }
    }

    protected setUserTheme(): void {
        const { setTheme } = useTheme()
        setTheme('green')
    }
}
