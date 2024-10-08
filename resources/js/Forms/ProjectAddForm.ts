import type { ProjectAddFormDTO } from "@/types/dto";
import { reactive, watch } from "vue";
import { object, string, ValidationError } from "yup";
import { ProjectGateway } from "@/types/gateways";
import { Project } from "@/types";

export type SubmitResponse = {
    error: string|null;
    project: Project|null;
}

/**
 * CANDIDATO: Eu escolhi por separar a lógica de formulário do componente em si.
 * Criei essa classe (meio que improvisada) para lidar com essa lógica. Aqui o formulário
 * é preenchido, validado e enviado. O componente vue apenas orquestra esssas ações.
 */
export class ProjectAddForm {
    private _fields: ProjectAddFormDTO

    private _errors: { [key in keyof ProjectAddFormDTO]: string }

    private _schema = object({
        title: string().required(),
        description: string().required(),
        due_date: string().required(),
    }) 

    private _gateway: ProjectGateway

    constructor(gateway: ProjectGateway) {
        this._gateway = gateway

        const structure = {
            title: '',
            description: '',
            due_date: ''
        }

        this._fields = reactive<ProjectAddFormDTO>({ ...structure })
        this._errors = reactive<ProjectAddFormDTO>({ ...structure })

        // Clear errors on type...
        watch(() => this._fields.title, () => this._errors.title = '')
        watch(() => this._fields.description, () => this._errors.description = '')
        watch(() => this._fields.due_date, () => this._errors.due_date = '')
    }

    data() {
        return this._fields
    }

    clear() {
        this._fields.title = ''
        this._fields.description = ''
        this._fields.due_date = ''
    }

    errors() {
        return this._errors
    }

    clearErrors() {
        this._errors.title = ''
        this._errors.description = ''
        this._errors.due_date = ''
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
        return this._gateway.store({ ...this._fields })
    }
}