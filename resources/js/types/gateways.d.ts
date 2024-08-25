import { Project } from ".";
import { ProjectAddFormDTO, ProjectUpdateFormDTO } from "./dto";

export interface ProjectGateway {
    store(form: ProjectAddFormDTO): Promise<{ error: string|null, project: Project|null }>;
    patch(form: ProjectUpdateFormDTO): Promise<void|string>;
    delete(id: number): Promise<void|string>;
}
