import type { Project } from "@/types"

export class ProjectBuilder {
    private project: Project

    private constructor() {
        this.project = {
            title: 'Title',
            description: 'Description',
            dueDate: new Date(),
        }
    }

    public static aProject() {
        return new ProjectBuilder;
    }

    public build() {
        return this.project
    }
}