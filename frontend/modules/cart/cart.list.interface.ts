import type {IProductList} from "~/modules/products/product.list.item.interface";
import type {IPaymentMethodList} from "~/modules/payment-method/payment.method.list.item.interface";

export interface ICartList {
    id: number
    user_id: number
    payment_method_id: number
    total_items: number
    installments: number
    subtotal_amount: number
    discount_amount: number
    total_amount: number
    items: {
        id: number
        cart_id: number
        product_id: number
        quantity: number
        product: IProductList
    }[],
    payment_method: IPaymentMethodList
}
