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

    private _gateway: ProjectGateway

    constructor(gateway: ProjectGateway) {
        this._gateway = gateway

        const structure = {
            title: '',
            description: '',
            due_date: ''
        }

        this._fields = reactive<ProjectUpdateFormDTO>({ ...structure })
    }

    data() {
        return this._fields
    }

    patch(): void {
        this._gateway.patch({ ...this._fields })
    }
}