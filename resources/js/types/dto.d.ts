export type ProjectAddFormDTO = {
    title: string;
    description: string;
    due_date: string;
}

export type ProjectUpdateFormDTO = {
    title: string;
    description: string;
    due_date: string;
}

export type TaskAddFormDTO = {
    title: string;
    description: string;
    status: string;
    project_id: number;
    responsible_id: number;
    due_date: string;
}

export type TaskUpdateFormDTO = Partial<TaskAddFormDTO>