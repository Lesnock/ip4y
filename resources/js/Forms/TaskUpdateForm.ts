import type { TaskUpdateFormDTO } from "@/types/dto";
import { reactive } from "vue";
import { TaskGateway } from "@/types/gateways";
import { Task } from "@/types";

export type SubmitResponse = {
    error: string|null;
    task: Task|null;
}

export class TaskUpdateForm {
    private _fields: TaskUpdateFormDTO
    private _savedFields: TaskUpdateFormDTO
    private _gateway: TaskGateway

    constructor(project_id: number, gateway: TaskGateway) {
        this._gateway = gateway

        const structure = {
            title: '',
            description: '',
            status: 'pendent',
            project_id: project_id,
            responsible_id: 0,
            due_date: ''
        }

        this._fields = reactive<TaskUpdateFormDTO>({ ...structure })
        this._savedFields = reactive<TaskUpdateFormDTO>({ ...structure })
    }

    data() {
        return this._fields
    }

    saved() {
        return this._savedFields
    }

    fill(data: TaskUpdateFormDTO) {
        for (const field in data) {
            switch (field) {
                case 'title': this._fields['title'] = this._savedFields['title'] = data['title']; break
                case 'description': this._fields['description'] = this._savedFields['description'] = data['description']; break
                case 'status': this._fields['status'] = this._savedFields['status'] = data['status']; break
                case 'project_id': this._fields['project_id'] = this._savedFields['project_id'] = data['project_id']; break
                case 'responsible_id': this._fields['responsible_id'] = this._savedFields['responsible_id'] = data['responsible_id']; break
                case 'due_date': this._fields['due_date'] = this._savedFields['due_date'] = data['due_date']; break
            }
        }
    }

    async patch(id: number, field: keyof TaskUpdateFormDTO): Promise<void|string> {
        const error = await this._gateway.patch(id, { [field]: this._fields[field] })
        this._savedFields[field] = this._fields[field] as any // Updated saved field
        return error
    }
}