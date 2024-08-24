import type { Form } from "@/types/form";
import type { ProjectAddFormDTO } from "@/types/dto";
import { reactive, watch } from "vue";
import { object, string, ValidationError } from "yup";
import { ProjectGateway } from "@/types/gateways";
import { Project } from "@/types";

export type SubmitResponse = {
    error: string|null;
    project: Project|null;
}

export class ProjectAddForm implements Form<ProjectAddFormDTO, SubmitResponse> {
    private _fields: ProjectAddFormDTO

    private _errors: { [key in keyof ProjectAddFormDTO]: string }

    private _schema = object({
        title: string().required(),
        description: string().required(),
        dueDate: string().required(),
    }) 

    private _gateway: ProjectGateway

    constructor(gateway: ProjectGateway) {
        this._gateway = gateway

        const structure = {
            title: '',
            description: '',
            dueDate: ''
        }

        this._fields = reactive<ProjectAddFormDTO>({ ...structure })
        this._errors = reactive<ProjectAddFormDTO>({ ...structure })

        // Clear errors on type...
        watch(() => this._fields.title, () => this._errors.title = '')
        watch(() => this._fields.description, () => this._errors.description = '')
        watch(() => this._fields.dueDate, () => this._errors.dueDate = '')
    }

    data() {
        return this._fields
    }

    errors() {
        return this._errors
    }

    clearErrors() {
        this._errors.title = ''
        this._errors.description = ''
        this._errors.dueDate = ''
    }

    validate(): boolean {
        this.clearErrors()
        try {
            this._schema.validateSync(this._fields, { abortEarly: false })
            return true
        } catch (err) {
            if (err instanceof ValidationError) {
                err.inner.forEach(error => {
                    this._errors[error.path as keyof ProjectAddFormDTO] = error.message
                })
            }
            return false
        }
    }

    submit(): Promise<SubmitResponse> {
        return this._gateway.store(this._fields)
    }
}