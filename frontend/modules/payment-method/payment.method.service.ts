import BaseService from "~/utils/service/base.service";
import type {IPaymentMethodList} from "~/modules/payment-method/payment.method.list.item.interface";

export class PaymentMethodService extends BaseService {
    constructor() {
        super();
    }

    public async list(page: number = 1, perPage: number = 10, search: string = '', orderBy: string = 'id', order: string = 'desc'): Promise<IPaymentMethodList[]> {
        const result = await this.api.paymentMethod.list(page, perPage, search, orderBy, order)
        return result.data;
    }
}
