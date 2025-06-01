export interface IPaymentMethodList {
    id: number
    name: string
    fee_percent: number
    discount_percent: number
    max_discount_installments: number
    max_installments: number
}
