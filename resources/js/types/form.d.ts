export interface Form<T> {
    validate(): boolean;
    data(): T;
    errors(): { [key in T]: string };
    clearErrors(): void;
}