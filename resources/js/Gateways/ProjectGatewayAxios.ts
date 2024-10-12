import { handleRequestError } from "@/helpers";
import { Project } from "@/types";
import { ProjectAddFormDTO, ProjectUpdateFormDTO } from "@/types/dto";
import { ProjectGateway } from "@/types/gateways";
import axios, { Axios, AxiosError } from "axios";

export class ProjectGatewayAxios implements ProjectGateway {
    client: Axios

    constructor() {
        this.client = axios.create()
    }

    async store(form: ProjectAddFormDTO): Promise<{ error: string | null, project: Project | null }> {
        try {
            const res = await this.client.post('/projects', form)
            const project = res.data.project
            project.due_date = new Date(project.due_date)
            return { error: null, project }
        } catch (error) {
            return { error: handleRequestError(error), project: null }
        }
    }

    async update(id: number, form: ProjectUpdateFormDTO): Promise<void|string> {
        try {
            await this.client.put(`/projects/${id}`, form)
        } catch (error) {
            return handleRequestError(error)
        }
    }

    async delete(id: number): Promise<void|string> {
         try {
            await this.client.delete(`/projects/${id}`)
        } catch (error) {
            return handleRequestError(error)
        }
    }
}