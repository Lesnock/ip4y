import type { Task, User } from "@/types"
import { UserBuilder } from "./UserBuilder";

export class TaskBuilder {
    private task: Task

    private constructor() {
        const user = UserBuilder.aUser().build()
        this.task = {
            id: 1,
            title: 'Title',
            description: 'Description',
            responsible_id: user.id,
            responsible: user,
            project_id: 1,
            status: 'pendent',
            due_date: new Date()
        }
    }

    public static aTask() {
        return new TaskBuilder;
    }

    public pendent() {
        this.task.status = 'pendent'
        return this
    }

    public inProgress() {
        this.task.status = 'in-progress'
        return this
    }

    public completed() {
        this.task.status = 'completed'
        return this
    }

    public withResponsible(user: User) {
        this.task.responsible = user
        this.task.responsible_id = user.id
        return this
    }

    public build() {
        return this.task
    }
}