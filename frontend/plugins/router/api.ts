import axios, {type AxiosResponse, HttpStatusCode} from "axios";
import {TimeEnum} from "~/utils/enum/time.enum";
import type {LoginFormInterface} from "~/modules/auth/login/login.form.interface";
import type {NotificationInterface} from "~/composables/notification/notify.interface";
import {useAuthStore} from "~/modules/auth/auth.store";
import {RouteUtil} from "~/utils/route/route.util";
import type {IApiListResponseInterface} from "~/plugins/router/api.list.response.interface";
import type {IProductList} from "~/modules/products/product.list.item.interface";
import type {ICartItemList} from "~/modules/cart/item/cart.item.list.interface";
import type {IApiResponseInterface} from "~/plugins/router/api.get.response.interface";
import type {ICartList} from "~/modules/cart/cart.list.interface";
import type {IPaymentMethodList} from "~/modules/payment-method/payment.method.list.item.interface";

export function createApi(notify: NotificationInterface) {
    const config = useRuntimeConfig()
    const baseApiUrl = config.public.API_URL
    const route = useRoute();
    const store = useAuthStore();

    axios.defaults.timeout = TimeEnum.tenSecondsInMs
    axios.interceptors.response.use(response => {
        return response
    }, async (error) => {
        let notificated = false
        if (error.response) {
            const status = error.response.status
            const loginPage = PagesMap.page.auth.login
            if (status === HttpStatusCode.Unauthorized) {
                notificated = true
                notify.error('Você não está logado!', 'Faça login para continuar!')
                store.logout()
                RouteUtil.redirect(loginPage)
            } else if (status === HttpStatusCode.BadRequest) {
                badRequestError(error)
                return error.response
            } else if (route.fullPath === loginPage.route) {
                notificated = true
                notify.error('Nome de usuário e/ou senha está incorreto', 'Credenciais inválidas!')
            } else if (status === HttpStatusCode.NotFound) {
                notificated = true
            }
        }
        if (error.message.includes("timeout of ")) {
            notificated = true
        }
        if (!notificated) {
            notify.error('Entre em contato com o administrador do sistema!', 'Ocorreu um erro!')
        }
        return Promise.reject(error)
    })

    function badRequestError(error: any): void {
        const loginPage = PagesMap.page.auth.login
        if (route.fullPath === loginPage.route) {
            notify.error('Nome de usuário e/ou senha está incorreto', 'Credenciais inválidas!')
            return
        }

        const rawData = error.response.data?.data
        let parsedData
        try {
            parsedData = JSON.parse(rawData)
        } catch {
            parsedData = rawData
        }

        if (typeof parsedData === 'object' && !Array.isArray(parsedData)) {
            notify.error(formatValidationErrors(parsedData), 'Erro de validação!')
        } else if (Array.isArray(parsedData)) {
            notify.error(parsedData[0], 'Erro de validação!')
        } else {
            notify.error(parsedData ?? 'Erro de requisição!')
        }
    }

    function formatValidationErrors(errors: Record<string, string[]>): string {
        return Object.entries(errors).map(([field, messages]) => {
            if (Array.isArray(messages)) {
                return `• ${field}: ${messages.join(', ')}`
            }
            return `• ${field}: Erro desconhecido`
        }).join('\n')
    }

    function mountApiV1Url(uri: string, id: null | number = null): string {
        const url: string = `${baseApiUrl}v1/${uri}`
        return id ? `${url}/${id}` : url
    }

    function mountApiV1ListUrl(uri: string, page: number, perPage: number, search: string, orderBy: string, order: string): string {
        const url: string = `${baseApiUrl}v1/${uri}`
        return `${url}?page=${page}&search=${search}&order_by=${orderBy}&order_direction=${order}&per_page=${perPage}`
    }

    function makeJsonHeaders(): object {
        return {
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'Authorization': `Bearer ${store.token}`,
            }
        }
    }

    async function baseCreate(uri: string, data: any): Promise<any> {
        const url: string = mountApiV1Url(uri)
        const headers = makeJsonHeaders()
        const result: AxiosResponse = await axios.post(url, data, headers)
        return result.data
    }

    async function baseGet(uri: string, itemId: number): Promise<any> {
        const url: string = mountApiV1Url(uri, itemId)
        const response: AxiosResponse = await axios.get(url, makeJsonHeaders())
        return response.data
    }

    async function baseList(uri: string, page: number = 1, perPage: number = 10, search: string = '', orderBy: string = 'id', order: string = 'asc'): Promise<any> {
        const url: string = mountApiV1ListUrl(uri, page, perPage, search, orderBy, order)
        const response: AxiosResponse = await axios.get(url, makeJsonHeaders())
        return response.data
    }

    async function baseUpdate(uri: string, itemId: number, data: any): Promise<any> {
        const url: string = mountApiV1Url(uri, itemId)
        const result: AxiosResponse = await axios.put(url, data, makeJsonHeaders())
        return result.data
    }

    async function baseDelete(uri: string, itemId: number): Promise<boolean> {
        const url: string = mountApiV1Url(uri, itemId)
        const response: AxiosResponse = await axios.delete(url, makeJsonHeaders())
        return response.data.status === HttpStatusCode.Ok
    }

    return {
        authentication: {
            login: async function (data: LoginFormInterface) {
                const url = `${baseApiUrl}auth/login`
                const headers = {
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    }
                }
                const response: AxiosResponse = await axios.post(url, data, headers)
                return response.data
            },
        },
        products: {
            list: async function (page: number = 1, perPage: number = 10, search: string = '', orderBy: string = 'id', order: string = 'desc'): Promise<IApiListResponseInterface<IProductList>> {
                return await baseList('product', page, perPage, search, orderBy, order)
            },
        },
        paymentMethod: {
            list: async function (page: number = 1, perPage: number = 10, search: string = '', orderBy: string = 'id', order: string = 'desc'): Promise<IApiListResponseInterface<IPaymentMethodList>> {
                return await baseList('payment-method', page, perPage, search, orderBy, order)
            },
        },
        cart: {
            item: {
                create: async function (data: {product_id: number, quantity: number}): Promise<IApiResponseInterface<ICartItemList>> {
                    return await baseCreate('cart/item', data)
                },
                update: async function (data: {quantity: number}, id: number): Promise<IApiResponseInterface<ICartItemList>> {
                    return await baseUpdate('cart/item', id, data)
                },
                delete: async function (id: number): Promise<boolean> {
                    return await baseDelete('cart/item', id)
                },
            },
            get: async function (id: number): Promise<IApiResponseInterface<ICartList>> {
                return await baseGet('cart', id)
            },
            delete: async function (id: number): Promise<boolean> {
                return await baseDelete('cart', id)
            },
            update: async function (data: {payment_method_id: number, installments: number}, id: number): Promise<IApiResponseInterface<ICartList>> {
                return await baseUpdate('cart', id, data)
            },
        }
    }
}
