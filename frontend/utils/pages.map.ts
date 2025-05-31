import {IconEnum} from "~/utils/enum/icon.enum";

export type PageMap = {
    label: string;
    icon: string;
    route: string;
}

export const PagesMap = {
    page: {
        index: {
            label: 'Início',
            icon: IconEnum.home,
            route: '/',
        },
        auth: {
            login: {
                label: 'Login',
                icon: '',
                route: '/login',
            },
        },
    }
}
