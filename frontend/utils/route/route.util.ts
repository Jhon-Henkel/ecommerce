import type {PageMap} from "~/utils/pages.map";

export const RouteUtil = {
    redirect: function(page: PageMap, targetBlank: boolean = false) {
        if (targetBlank) {
            window.open(page.route, '_blank')
            return
        }
        return navigateTo(page.route)
    }
}
