export const NumberUtil = {
    toCurrency: (value: number|string): string => {
        value = Number(value);
        return `${value.toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`
    }
}
