import BaseService from "~/utils/service/base.service";
import type {ICartList} from "~/modules/cart/cart.list.interface";
import {alertApi} from "~/composables/alert/alert.api";
import {HttpStatusCode} from "axios";

export class CartService extends BaseService {
    constructor() {
        super();
    }

    public async get(id: number): Promise<ICartList> {
        const data = await this.api.cart.get(id);
        return data.data;
    }

    public async delete(id: number): Promise<void> {
        await alertApi.askDelete(async (): Promise<boolean> => {
            return await this.api.cart.delete(id)
        })
    }

    public async update(cart: ICartList): Promise<void> {
        const response = await this.api.cart.update({
            payment_method_id: cart.payment_method_id, installments: cart.installments
        }, cart.id)

        if (response.status === HttpStatusCode.Ok) {
            this.notify.success('Carrinho atualizado com sucesso!');
            return
        }
        this.notify.error('Erro ao atualizar carrinho!');
    }
}
