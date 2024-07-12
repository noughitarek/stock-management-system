import { MenuItem } from './MenuItem'

export interface User {
    id: number;
    name: string;
    email: string;
    email_verified_at: string;
}

export type PageProps<T extends Record<string, unknown> = Record<string, unknown>> = T & {
    auth: {
        user: User;
        menu: MenuItem[];
    };
};

export interface User{
    id: number;
    name: string;
    role: string;
}

export interface Rubrique{
    id: number;
    name: string;
    description: string;
    created_at: Date;
    updated_at: Date;
    created_by: User;
    updated_by: User;
}