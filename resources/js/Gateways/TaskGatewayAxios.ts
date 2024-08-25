import { Task } from "@/types";
import { TaskAddFormDTO, TaskUpdateFormDTO } from "@/types/dto";
import { TaskGateway } from "@/types/gateways";
import axios, { Axios, AxiosError } from "axios";

export class TaskGatewayAxios implements TaskGateway {
    client: Axios

    constructor() {
        this.client = axios.create()
    }

    async store(form: TaskAddFormDTO): Promise<{ error: string | null, task: Task | null }> {
        try {
            const res = await this.client.post('/tasks', form)
            return { 
                error: null, 
                task: {
                    ...res.data.task,
                    due_date: new Date(res.data.task.due_date)
                }
            }
        } catch (error) {
            if (error instanceof AxiosError) {
                // Domain error
                if (error.response?.data.error) {
                    return { error: error.response?.data.error, task: null }
                }

                // Form request error
                if (error.response?.data.errors) {
                    const message = []
                    for (const field in error.response.data.errors) {
                        message.push(error.response.data.errors[field])
                    }
                    return { error: message.join('<br>'), task: null }
                }
            }

            return { error: (error as Error).message, task: null }
        }
    }

    async patch(id: number, form: TaskUpdateFormDTO): Promise<void|string> {
        try {
            await this.client.patch(`/tasks/${id}`, form)
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
            await this.client.delete(`/tasks/${id}`)
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