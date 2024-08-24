import { Project } from ".";

export interface ProjectGateway {
    add(form: ProjectAddFormDTO): Promise<Project>;
    patch(): Promise<void>;
}
