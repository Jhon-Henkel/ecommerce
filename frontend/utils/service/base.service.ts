import {useNotify} from "~/composables/notification/notify";

export default class BaseService {

    protected api
    protected notify

    constructor() {
        const { $api } = useNuxtApp()
        this.api = $api
        this.notify = useNotify()
    }
}
