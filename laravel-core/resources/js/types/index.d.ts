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
export interface Product{
    id: number;
    designation: string;
    rubrique: Rubrique;
    description: string;
    pictures: string[];
    created_at: Date;
    updated_at: Date;
    created_by: User;
    updated_by: User;
}

export interface Supplier{
    id: number;
    name: string;
    phone: string;
    address: string;
    description: string;
    created_at: Date;
    updated_at: Date;
    created_by: User;
    updated_by: User;
}

export interface Service{
    id: number;
    name: string;
    description: string;
    responsible_name: string;
    responsible_phone: string;
    created_at: Date;
    updated_at: Date;
    created_by: User;
    updated_by: User;
}

export interface InboundProducts{
    id: number;
    product: Product;
    unit_price_excl_tax: number;
    unit_price_net: number;
    qte: number;
    total_amount_excl_tax: number;
    total_amount_net: number;
}
export interface Inbound{
    id: number;
    rubrique: Rubrique;
    commande_note_number: number;
    delivery_note_number: number;
    invoice_number: number;
    supplier: Supplier;
    inbound_products: InboundProducts[];
    created_at: Date;
    updated_at: Date;
    created_by: User;
    updated_by: User;
}

export interface Inbounds{
    rubrique: Rubrique;
    inbounds: Inbound[];
}