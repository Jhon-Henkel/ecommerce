export interface NotificationInterface {
    success: (message: string, title?: string) => void
    error: (message: string, title?: string) => void
}
