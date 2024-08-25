import type { TaskAddFormDTO } from "@/types/dto";
import { reactive, watch } from "vue";
import { number, object, string, ValidationError } from "yup";
import { TaskGateway } from "@/types/gateways";
import { Task } from "@/types";

export type SubmitResponse = {
    error: string|null;
    task: Task|null;
}

export class TaskAddForm {
    private _fields: TaskAddFormDTO
    private _errors: { [key in keyof TaskAddFormDTO]: string }
    private _schema = object({
        title: string().required(),
        description: string().required(),
        status: string().oneOf(['pendent', 'in-progress', 'completed']).required(),
        project_id: number().required(),
        responsible_id: number().required(),
        due_date: string().required(),
    }) 
    private _project_id: number
    private _gateway: TaskGateway

    constructor(project_id: number, gateway: TaskGateway) {
        this._project_id = project_id
        this._gateway = gateway

        const structure = {
            title: '',
            description: '',
            status: 'pendent',
            project_id: project_id,
            responsible_id: 0,
            due_date: ''
        }

        this._fields = reactive<TaskAddFormDTO>({ ...structure })
        this._errors = reactive<typeof this._errors>({
            title: '',
            description: '',
            status: '',
            project_id: '',
            responsible_id: '',
            due_date: ''
        })

        // Clear errors on type...
        watch(() => this._fields.title, () => this._errors.title = '')
        watch(() => this._fields.description, () => this._errors.description = '')
        watch(() => this._fields.status, () => this._errors.status = '')
        watch(() => this._fields.project_id, () => this._errors.project_id = '')
        watch(() => this._fields.responsible_id, () => this._errors.responsible_id = '')
        watch(() => this._fields.due_date, () => this._errors.due_date = '')
    }

    data() {
        return this._fields
    }

    clear() {
        this._fields.title = ''
        this._fields.description = ''
        this._fields.status = ''
        this._fields.project_id = this._project_id
        this._fields.responsible_id = 0
        this._fields.due_date = ''
    }

    errors() {
        return this._errors
    }

    clearErrors() {
        this._errors.title = ''
        this._errors.description = ''
        this._errors.status = ''
        this._errors.project_id = ''
        this._errors.responsible_id = ''
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
                    this._errors[error.path as keyof TaskAddFormDTO] = error.message
                })
            }
            return false
        }
    }

    submit(): Promise<SubmitResponse> {
        return this._gateway.store({ ...this._fields })
    }
}