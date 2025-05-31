import {BreadcrumbItemDTO} from "~/components/breadcrumb/dto/breadcrumb.item.dto";

export class BreadcrumbDTO {
    public items: Array<BreadcrumbItemDTO>

    constructor(items: Array<BreadcrumbItemDTO>) {
        this.items = []
        this.items.push(new BreadcrumbItemDTO(PagesMap.page.home))
        this.items.push(...items)
    }
}
