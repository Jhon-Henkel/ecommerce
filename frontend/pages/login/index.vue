<script setup lang="ts">
import type {FormSubmitEvent} from '@nuxt/ui'
import {LoginSchema, type LoginSchemaType} from '~/modules/auth/login/login.schema'
import LoginService from "~/modules/auth/login/login.service";
import {IconEnum} from "~/utils/enum/icon.enum";

useHead({ title: 'Login' })
const service = new LoginService()

const state = reactive<Partial<LoginSchemaType>>(service.makeFormData())
const showPassword = ref(false)
const loading = ref(false)

async function onSubmit(event: FormSubmitEvent<LoginSchemaType>) {
    loading.value = true
    try {
        await service.login(event.data)
        loading.value = false
    } catch {
        loading.value = false
    }
}
</script>

<template>
    <div>
        <div class="min-h-screen w-full flex items-center justify-center bg-cover bg-center bg-no-repeat" :style="{ backgroundImage: `url('/assets/images/login_cover.jpg')` }">
            <div class="w-full max-w-sm p-6 rounded-xl shadow-md bg-white dark:bg-gray-900 text-gray-900 dark:text-white pt-0">
                <div class="w-full h-40 scale-30 mb-4">
                    <NuxtImg src="/assets/images/logo.svg" alt="Logo" class="logo w-full"/>
                </div>
                <UForm :schema="LoginSchema" :state="state" class="space-y-4" @submit="onSubmit">
                    <USeparator/>

                    <UFormField name="email">
                        <UInput v-model="state.email" placeholder="E-mail" :icon="IconEnum.user" class="w-full"/>
                    </UFormField>

                    <UFormField name="password">
                        <UInput
                            v-model="state.password"
                            placeholder="Senha"
                            icon="i-heroicons-lock-closed-16-solid"
                            class="w-full"
                            :type="showPassword ? 'text' : 'password'"
                            :ui="{ trailing: 'pe-1' }"
                        >
                            <template #trailing>
                                <UButton
                                    color="neutral"
                                    variant="link"
                                    size="sm"
                                    :icon="showPassword ? IconEnum.eyeOff : IconEnum.eye"
                                    :aria-label="showPassword ? 'Ocultar senha' : 'Mostrar senha'"
                                    :aria-pressed="showPassword"
                                    aria-controls="password"
                                    @click="showPassword = !showPassword"
                                />
                            </template>
                        </UInput>
                    </UFormField>

                    <USeparator/>

                    <UButton type="submit" trailing-icon="i-ic-outline-login" class="w-full justify-center" :loading="loading">
                        Entrar
                    </UButton>
                </UForm>
            </div>
        </div>
    </div>
</template>

<style scoped>
    .light .logo {
        filter: invert(100%) sepia(53%) saturate(1141%) hue-rotate(127deg) brightness(93%) contrast(87%);
    }
</style>
