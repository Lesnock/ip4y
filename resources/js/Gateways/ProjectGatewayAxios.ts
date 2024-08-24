import { Project } from "@/types";
import { ProjectAddFormDTO } from "@/types/dto";
import { ProjectGateway } from "@/types/gateways";
import axios, { Axios, AxiosError } from "axios";

export class ProjectGatewayAxios implements ProjectGateway {
    client: Axios

    constructor() {
        this.client = axios.create()
    }

    async store(form: ProjectAddFormDTO): Promise<{ error: string|null, project: Project|null }> {
        try {
            const res = await this.client.post('/projects', form)
            return { error: null, project: res.data.project }
        } catch (error) {
            if (error instanceof AxiosError) {
                const message = error.response?.data?.error ?? error.message
                return { error: message, project: null }
            }
            return { error: (error as Error).message, project: null }
        }
    }

    patch(): Promise<void> {
        throw new Error("Method not implemented.");
    }
}