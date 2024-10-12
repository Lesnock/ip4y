import { AxiosError } from "axios";

export function isRunningInVitest() {
    return typeof import.meta.env.TEST !== 'undefined';
}

export function delay(ms = 500) {
    if (isRunningInVitest()) {
        ms = 0
    }
    return new Promise(res => {
        setTimeout(res, ms)
    })
}

export function isValidDate(date?: string): boolean {
    if (!date) return false
    const dateObj = new Date(date)
    return !isNaN(dateObj.getTime());
}

export function handleRequestError(error: any): string {
    if (error instanceof AxiosError) {
        // Domain error
        if (error.response?.data.error) {
            return error.response?.data.error
        }

        // Form request error
        if (error.response?.data.errors) {
            const message = []
            for (const field in error.response.data.errors) {
                message.push(error.response.data.errors[field])
            }
            return message.join('<br>')
        }
    }

    return (error as Error).message
}