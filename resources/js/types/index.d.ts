export interface User {
    id: number;
    name: string;
    email: string;
    email_verified_at: string;
}

export type PageProps<T extends Record<string, unknown> = Record<string, unknown>> = T & {
    auth: {
        user: User;
    };
};

type Project = {
    id: number;
    title: string;
    description: string;
    due_date: Date,
    tasks: Task[]
};

type Task = {
    id: number;
    title: string;
    description: string;
    status: 'pendent' | 'in-progress' | 'completed';
    project_id: number;
    responsible_id: number;
    responsible?: User;
    due_date: Date,
}

type VueEmitter<T> = (event: T, ...args: any[]) => void