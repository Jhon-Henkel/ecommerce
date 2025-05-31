import {BreadcrumbItemDTO} from "~/components/breadcrumb/dto/breadcrumb.item.dto";

export class BreadcrumbDTO {
    public items: Array<BreadcrumbItemDTO>

    constructor(items: Array<BreadcrumbItemDTO>|null = null) {
        this.items = []
        this.items.push(new BreadcrumbItemDTO(PagesMap.page.index))
        if (items !== null) {
            this.items.push(...items)
        }
    }
}
