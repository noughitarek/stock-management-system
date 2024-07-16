import Webmaster from '@/Layouts/Webmaster';
import { Head } from '@inertiajs/react';
import { PageProps } from '@/types';

export default function Settings({ auth }: PageProps) {
    return (
        <>
            <Head title="Paramétres" />
            
            <Webmaster
                user={auth.user}
                menu={auth.menu}
                breadcrumb={<li className="breadcrumb-item active" aria-current="page">Paramétres</li>}
            >
                <div className="grid grid-cols-12 gap-6">
                </div>
            </Webmaster>
        </>
    )
}