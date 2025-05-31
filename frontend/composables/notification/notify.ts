import type {NotificationInterface} from "~/composables/notification/notify.interface";

export function useNotify(): NotificationInterface {
    const toast = useToast()

    return {
        success(message: string, title: string = 'Sucesso!') {
            toast.add({
                title: title,
                description: message,
                color: 'success',
                icon: 'material-symbols-check-circle-outline-sharp'
            })
        },
        error(message: string, title: string = 'Algo não saiu como o esperado!') {
            toast.add({
                title: title,
                description: message,
                color: 'error',
                icon: 'material-symbols-error-circle-rounded-outline-sharp'
            })
        }
    }
}
