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
    title: string;
    description: string;
    dueDate: Date,
    tasks: Task[]
};

type Task = {
    title: string;
    description: string;
    status: 'pending' | 'in-progress' | 'completed';
    responsible_id: number | null;
    responsible?: User
}