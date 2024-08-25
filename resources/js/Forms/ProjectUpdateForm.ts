import type { ProjectUpdateFormDTO } from "@/types/dto";
import { reactive } from "vue";
import { ProjectGateway } from "@/types/gateways";
import { Project } from "@/types";

export type SubmitResponse = {
    error: string|null;
    project: Project|null;
}

export class ProjectUpdateForm {
    private _fields: ProjectUpdateFormDTO
    private _savedFields: ProjectUpdateFormDTO
    private _gateway: ProjectGateway

    constructor(gateway: ProjectGateway) {
        this._gateway = gateway

        const structure = {
            title: '',
            description: '',
            due_date: ''
        }

        this._fields = reactive<ProjectUpdateFormDTO>({ ...structure })
        this._savedFields = reactive<ProjectUpdateFormDTO>({ ...structure })
    }

    data() {
        return this._fields
    }

    saved() {
        return this._savedFields
    }

    fill(data: ProjectUpdateFormDTO) {
        for (const field in data) {
            const fieldName = field as keyof ProjectUpdateFormDTO
            this._fields[fieldName] = data[fieldName]
            this._savedFields[fieldName] = data[fieldName]
        }
    }

    async patch(id: number, field: keyof ProjectUpdateFormDTO): Promise<void|string> {
        const error = await this._gateway.patch(id, { [field]: this._fields[field] })
        this._savedFields[field] = this._fields[field] // Updated saved field
        return error
    }
}