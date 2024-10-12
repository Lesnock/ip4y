import { Schema, ValidationError, setLocale } from "yup";
import { pt } from 'yup-locale-pt'

setLocale(pt)

export function validate<T>(schema: Schema, form: T) {
    try {
        schema.validateSync(form, { abortEarly: false })
        return { status: true, errors: {} }
    } catch (err) {
        const errors = {} as { [key: string]: string }
        if (err instanceof ValidationError) {
            err.inner.forEach(error => {
                errors[error.path as string] = error.message
            })
        }
        return { status: false, errors }
    }
}