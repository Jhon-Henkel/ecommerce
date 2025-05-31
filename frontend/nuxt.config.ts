// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
    compatibilityDate: '2024-11-01',
    devtools: {
        enabled: process.env.NODE_ENV !== 'production'
    },
    modules: [
        '@nuxt/eslint',
        '@nuxt/icon',
        '@nuxt/fonts',
        '@nuxt/image',
        '@nuxt/test-utils',
        '@nuxt/ui',
        '@nuxt/scripts',
        '@pinia/nuxt',
        '@nuxtjs/device'
    ],
    css: ['~/public/assets/css/main.css'],
    app: {
        pageTransition: {
            name: "page",
            mode: "out-in"
        },
        head: {
            link: [
                {
                    rel: 'icon',
                    type: 'image/x-icon',
                    href: '/favicon.png'
                }
            ]
        }
    },
    runtimeConfig: {
        public: {
            API_URL: process.env.API_URL,
            port: process.env.PORT,
        }
    }
})
