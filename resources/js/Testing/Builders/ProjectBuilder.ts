import type { Project, Task } from "@/types"

export class ProjectBuilder {
    private project: Project

    private constructor() {
        this.project = {
            id: 1,
            title: 'Title',
            description: 'Description',
            dueDate: new Date(),
            tasks: []
        }
    }

    public static aProject() {
        return new ProjectBuilder;
    }

    public withTask(task: Task) {
        this.project.tasks.push(task)
        return this
    }

    public build() {
        return this.project
    }
}