import BaseService from "~/utils/service/base.service";
import type {IProductList} from "~/modules/products/product.list.item.interface";

export class ProductService extends BaseService {
    constructor() {
        super();
    }

    public async list(): Promise<IProductList[]> {
        const data = await this.api.products.list()
        return data.data;
    }
}
