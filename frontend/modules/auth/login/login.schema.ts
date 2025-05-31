import { z } from 'zod'

export const LoginSchema = z.object({
    email: z.string().min(1, 'E-mail é obrigatório').email('E-mail inválido'),
    password: z.string().nonempty('Senha é obrigatória').min(4, 'Senha deve ter pelo menos 4 caracteres'),
})

export type LoginSchemaType = z.output<typeof LoginSchema>
