import BaseService from "~/utils/service/base.service";
import type {ICartList} from "~/modules/cart/cart.list.interface";
import {alertApi} from "~/composables/alert/alert.api";

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
}
