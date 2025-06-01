import BaseService from "~/utils/service/base.service";
import type {IProductList} from "~/modules/products/product.list.item.interface";
import {HttpStatusCode} from "axios";
import {useCartStore} from "~/modules/cart/item/cart.item.store";
import {alertApi} from "~/composables/alert/alert.api";

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

    public async updateQuantity(id: number, quantity: number): Promise<void> {
        const response = await this.api.cart.item.update({quantity: quantity}, id)

        if (response.status === HttpStatusCode.Ok) {
            this.notify.success('Quantidade atualizada com sucesso!');
            return
        }
        this.notify.error('Erro ao atualizar quantidade!');
    }

    public async delete(id: number): Promise<void> {
        await alertApi.askDelete(async (): Promise<boolean> => {
            return await this.api.cart.item.delete(id)
        })
    }
}
