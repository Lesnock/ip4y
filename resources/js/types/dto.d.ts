export type ProjectAddFormDTO  ={
    title: string;
    description: string;
    due_date: string;
}

export type ProjectUpdateFormDTO = Partial<{
    title: string;
    description: string;
    due_date: string;
}>