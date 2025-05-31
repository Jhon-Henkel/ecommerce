import {appAlert} from "~/composables/alert/alert";
import {useTheme} from "~/composables/theme/use.theme";

export const alertApi = {
    ask: async (title: string, text: string, iconColor: null|string, confirmColor: null|string, callback: () => Promise<boolean>): Promise<void> => {
        const { $swal } = useNuxtApp()
        const { currentTheme } = useTheme()
        const result = await $swal.fire({
            title: title,
            text: text,
            icon: 'question',
            iconColor: iconColor ?? currentTheme.value.primaryColorHex,
            confirmButtonColor: confirmColor ?? currentTheme.value.primaryColorHex,
            confirmButtonText: 'Sim, continuar',
            cancelButtonText: "Cancelar",
            showCancelButton: true,
        });
        if (result.isConfirmed) {
            try {
                if (await callback()) {
                    await appAlert.success('Sucesso!', 'Ação executada com sucesso!')
                    setTimeout((): void => {
                        $swal.close()
                    }, 1000);
                } else {
                    $swal.close()
                }
            } catch {
                await appAlert.error("Erro", "Ocorreu um erro ao executar essa ação!");
            }
        } else {
            await appAlert.error("Cancelado", "Ação Cancelada");
        }
    },
    askDelete: async (callback: () => Promise<boolean>): Promise<void> => {
        return await alertApi.ask(
            'Deseja realmente deletar esse item?',
            'Uma vez deletado, não é possível recuperar esse item.',
            '#dc2a2a',
            '#dc2a2a',
            callback
        );
    }
}
