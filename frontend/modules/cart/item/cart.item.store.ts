export const useCartStore = defineStore('cart', {
    state: () => ({
        quantity: 0,
    }),
    persist: true,
    actions: {
        addItem(quantity: number): void {
            this.quantity = this.quantity + quantity
        }
    }
})
