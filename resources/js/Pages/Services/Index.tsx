import React, {useState} from 'react';
import Webmaster from '@/Layouts/Webmaster';
import { Head, Link } from '@inertiajs/react';
import { PageProps, Service } from '@/types';
import Page from '@/Components/Page';
import { Search } from 'lucide-react';
import { Input, Select } from '@headlessui/react';
import PaginationInfo from '@/Components/PaginationInfo';
import ServicesTable from '@/Components/ServicesTable';

const Services: React.FC<PageProps<{ services: Service[], from:number, to:number, total:number }>> = ({ auth, services, from, to, total }) => {
    const [searchTerm, setSearchTerm] = useState<string>('');
    const handleSearchChange = (event: React.ChangeEvent<HTMLInputElement>) => {
      setSearchTerm(event.target.value);
    };
    return (
        <>
            <Head title="Services" />
            <Webmaster
                user={auth.user}
                menu={auth.menu}
                breadcrumb={<li className="breadcrumb-item active" aria-current="page">Services</li>}
            >
                <Page title="Services" header={<>
                    <Link className="btn btn-primary" href={route('services.create')}>Créer un service</Link>
                        <PaginationInfo start={from} end={to} total={total}/>
                        <div className="w-full xl:w-auto flex items-center mt-3 xl:mt-0">
                            <div className="w-56 relative text-slate-500">
                                <Input type="text" className="form-control w-56 box pr-10" placeholder="Rechercher..." value={searchTerm} onChange={handleSearchChange}/>
                                <Search className="w-4 h-4 absolute my-auto inset-y-0 mr-3 right-0"/> 
                            </div>
                        </div>
                </>}>
                    <ServicesTable services={services} searchTerm={searchTerm}/>
                </Page>
            </Webmaster>
        </>
    );
};

export default Services;
