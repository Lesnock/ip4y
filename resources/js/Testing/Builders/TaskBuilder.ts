import type { Task, User } from "@/types"

export class TaskBuilder {
    private task: Task

    private constructor() {
        this.task = {
            title: 'Title',
            description: 'Description',
            responsible_id: null,
            status: 'pending'
        }
    }

    public static aTask() {
        return new TaskBuilder;
    }

    public pending() {
        this.task.status = 'pending'
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