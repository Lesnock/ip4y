export interface Form<T, R> {
    validate(): boolean;
    data(): T;
    errors(): { [key in T]: string };
    clearErrors(): void;
    submit(): Promise<R>;
}