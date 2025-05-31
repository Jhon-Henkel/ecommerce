// plugins/plugins.ts
import {createApi} from "~/plugins/router/api";
import {useNotify} from "~/composables/notification/notify";

export default defineNuxtPlugin(() => {
    const api = createApi(useNotify())

    return {
        provide: {
            api
        }
    }
})
