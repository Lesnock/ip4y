import { Project, Task } from ".";
import { ProjectAddFormDTO, ProjectUpdateFormDTO, TaskAddFormDTO, TaskUpdateFormDTO } from "./dto";

export interface ProjectGateway {
    store(form: ProjectAddFormDTO): Promise<{ error: string|null, project: Project|null }>;
    update(id: number, form: ProjectUpdateFormDTO): Promise<void|string>;
    delete(id: number): Promise<void|string>;
}

export interface TaskGateway {
    store(form: TaskAddFormDTO): Promise<{ error: string|null, task: Task|null }>;
    update(id: number, form: TaskUpdateFormDTO): Promise<void|string>;
    delete(id: number): Promise<void|string>;
}
