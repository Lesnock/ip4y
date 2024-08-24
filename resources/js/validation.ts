import { Schema, ValidationError } from "yup";

type ErrorsObject = { [key: string]: string }

export function validate(schema: Schema, form: object) {
    const errors: ErrorsObject = {}

    try {
        schema.validateSync(form, { abortEarly: false })
        return { status: true, errors: null }
    } catch (err) {
        if (err instanceof ValidationError) {
            err.inner.forEach(error => {
                errors[error.path as string] = error.message
            })
        }
        return { status: false, errors }
    }
}