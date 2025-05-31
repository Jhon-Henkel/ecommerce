export class BreadcrumbItemDTO {
    public label: string
    public icon: string
    public to: string

    constructor(page: PageMap) {
        this.label = page.label
        this.icon = page.icon
        this.to = page.route
    }
}
