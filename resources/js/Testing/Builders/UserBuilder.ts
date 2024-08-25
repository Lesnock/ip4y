import type { Task, User } from "@/types"

export class UserBuilder {
    private user: User

    private constructor() {
        this.user = {
            id: 1,
            name: 'Test User',
            email: 'test@example.com',
            email_verified_at: '2010-01-01',
        }
    }

    public static aUser() {
        return new UserBuilder;
    }

    public build() {
        return this.user
    }
}