import BaseService from "~/utils/service/base.service";
import type {IProductList} from "~/modules/products/product.list.item.interface";
import {HttpStatusCode} from "axios";
import {useCartStore} from "~/modules/cart/item/cart.item.store";

export class CartItemService extends BaseService {
    constructor() {
        super();
    }

    public async addToCart(item: IProductList, quantity: number): Promise<void> {
        const response = await this.api.cart.item.create({
            product_id: item.id,
            quantity: quantity
        });

        if (response.status === HttpStatusCode.Created) {
            this.notify.success(`${item.name} adicionado ao seu carrinho!`);
            useCartStore().addItem(quantity)
            return
        }
        this.notify.error('Erro ao adicionar item!');
    }
}
