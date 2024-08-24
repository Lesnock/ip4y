export interface Form<T, R> {
    validate(): boolean;
    data(): T;
    clear(): void;
    errors(): { [key in T]: string };
    clearErrors(): void;
    submit(): Promise<R>;
}