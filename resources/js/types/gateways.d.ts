import { Project } from ".";
import { ProjectAddFormDTO } from "./dto";

export interface ProjectGateway {
    store(form: ProjectAddFormDTO): Promise<{ error: string|null, project: Project|null }>;
    patch(): Promise<void>;
}
