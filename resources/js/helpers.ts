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