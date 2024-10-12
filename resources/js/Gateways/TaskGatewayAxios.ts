import { handleRequestError } from "@/helpers";
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
            const task = res.data.task
            task.due_date = new Date(res.data.task.due_date)
            return { error: null, task }
        } catch (error) {
            return { error: handleRequestError(error), task: null }
        }
    }

    async update(id: number, form: TaskUpdateFormDTO): Promise<void|string> {
        try {
            await this.client.put(`/tasks/${id}`, form)
        } catch (error) {
            return handleRequestError(error)
        }
    }

    async delete(id: number): Promise<void|string> {
         try {
            await this.client.delete(`/tasks/${id}`)
        } catch (error) {
            return handleRequestError(error)
        }
    }
}