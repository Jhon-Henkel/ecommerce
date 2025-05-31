import { ref, computed } from 'vue'

type ThemeName = 'green'
export type PrimaryColor = "success" | "neutral" | "error" | "primary" | "secondary" | "info" | "warning" | undefined

const defaultTheme: ThemeName = 'green'
const theme = ref<ThemeName>(defaultTheme)

interface IThemeConfig {
    primaryColor: string
    primaryColorRoot: PrimaryColor
    primaryColorHex: string
    pageTitle: string
    navbar: string
    bgHover300: string
    sidebar: string
    tableHeader: string
    tableBody: string
    table: string
    sidebarDivider: string
    sidebarHover: string
}

const themes: Record<ThemeName, IThemeConfig> = {
    green: {
        primaryColor: 'green-800',
        primaryColorRoot: 'success',
        primaryColorHex: '#00C16A',
        pageTitle: 'dark:text-green-600 light:text-green-600',
        navbar: 'bg-green-500/[.6]',
        bgHover300: 'hover:bg-green-300',
        sidebar: 'dark:bg-neutral-800 dark:text-green-700 light:bg-green-100 light:text-green-800',
        table: 'rounded-lg overflow-auto border border-green-300 dark:border-neutral-700',
        tableHeader: 'px-4 py-4 light:bg-green-100 light:text-green-800 dark:bg-neutral-800 dark:text-green-700 border border-green-300 dark:border-neutral-700',
        tableBody: 'text-xs text-center odd:bg-white even:bg-green-50 dark:odd:bg-neutral-900 dark:even:bg-neutral-800 hover:bg-green-100 dark:hover:bg-neutral-700',
        sidebarDivider: 'text-green-900 dark:text-green-500',
        sidebarHover: 'hover:bg-green-800 hover:text-white dark:hover:bg-green-800 dark:hover:text-white',
    },
}

function setTheme(newTheme: ThemeName) {
    theme.value = newTheme
    if (import.meta.client) {
        localStorage.setItem('theme', newTheme)
    }
}

const currentTheme = computed(() => themes[theme.value])

if (import.meta.client) {
    const savedTheme = localStorage.getItem('theme') as ThemeName | null
    if (savedTheme && themes[savedTheme]) {
        theme.value = savedTheme
    }
}

export function useTheme() {
    return {
        theme,
        currentTheme,
        setTheme
    }
}
