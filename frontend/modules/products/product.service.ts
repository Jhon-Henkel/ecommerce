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

    public getImageByProduct(product: IProductList): string {
        switch (product.name) {
            case 'IPhone 16 240 GB':
                return '/assets/images/iphone.webp'
            case 'Ar Condicionado Split 12000 Btu\'s':
                return '/assets/images/ar.webp'
            case 'Parafusadeira 21v':
                return '/assets/images/parafusadeira.webp'
            case 'Playstation 5':
                return '/assets/images/ps5.webp'
            case 'Guitarra Elétrica Gibson':
                return '/assets/images/gibson.webp'
            default:
                return '/assets/images/no-image.jpg'
        }
    }
}
