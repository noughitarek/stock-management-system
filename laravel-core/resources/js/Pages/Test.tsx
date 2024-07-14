import Webmaster from '@/Layouts/Webmaster';
import { Head } from '@inertiajs/react';
import { PageProps } from '@/types';
import { MenuItem } from '@/types/MenuItem';

export default function Dashboard({ auth }: PageProps) {
    return (
        <>
            <Head title="Dashboard" />
            
            <Webmaster
                user={auth.user}
                menu={auth.menu}
                header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>}
            >
                <div>Test</div>
            </Webmaster>
        </>
    )
}