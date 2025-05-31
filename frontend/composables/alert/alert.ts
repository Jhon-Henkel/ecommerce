import {useTheme} from "~/composables/theme/use.theme";

export const appAlert = {
    success: async (title: string, text: string): Promise<void> => {
        const { $swal } = useNuxtApp()
        const { currentTheme } = useTheme()
        await $swal.fire({
            title: title,
            text: text,
            icon: 'success',
            confirmButtonText: 'Ok',
            confirmButtonColor: currentTheme.value.primaryColorHex,
        });
    },
    error: async (title: string, text: string): Promise<void> => {
        const { $swal } = useNuxtApp()
        const { currentTheme } = useTheme()
        await $swal.fire({
            title: title,
            text: text,
            icon: 'error',
            confirmButtonText: 'Ok',
            confirmButtonColor: currentTheme.value.primaryColorHex,
        });
    }
}
