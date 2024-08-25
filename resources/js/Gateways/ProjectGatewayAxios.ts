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
            return { 
                error: null, 
                project: {
                    ...res.data.project,
                    due_date: new Date(res.data.project.due_date)
                }
            }
        } catch (error) {
            if (error instanceof AxiosError) {
                // Domain error
                if (error.response?.data.error) {
                    return { error: error.response?.data.error, project: null }
                }

                // Form request error
                if (error.response?.data.errors) {
                    const message = []
                    for (const field in error.response.data.errors) {
                        message.push(error.response.data.errors[field])
                    }
                    return { error: message.join('<br>'), project: null }
                }
            }

            return { error: (error as Error).message, project: null }
        }
    }

    async patch(id: number, form: ProjectUpdateFormDTO): Promise<void|string> {
        try {
            await this.client.patch(`/projects/${id}`, form)
        } catch (error) {
            if (error instanceof AxiosError) {
                // Domain error
                if (error.response?.data.error) {
                    return error.response?.data.error
                }

                // Form request error
                if (error.response?.data.errors) {
                    const message = []
                    for (const field in error.response.data.errors) {
                        message.push(error.response.data.errors[field])
                    }
                    return message.join('<br>')
                }
            }

            return (error as Error).message
        }
    }

    async delete(id: number): Promise<void|string> {
         try {
            await this.client.delete(`/projects/${id}`)
        } catch (error) {
            if (error instanceof AxiosError) {
                if (error.response?.data.error) {
                    return error.response?.data.error
                }
            }
            return (error as Error).message
        }
    }
}