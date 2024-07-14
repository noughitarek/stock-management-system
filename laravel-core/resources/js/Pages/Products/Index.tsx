import React, {useState} from 'react';
import Webmaster from '@/Layouts/Webmaster';
import { Head, Link } from '@inertiajs/react';
import { PageProps, Product } from '@/types';
import Page from '@/Components/Page';
import ProductsTable from '@/Components/ProductsTable';
import { Search } from 'lucide-react';
import { Input, Select } from '@headlessui/react';
import PaginationInfo from '@/Components/PaginationInfo';

const Products: React.FC<PageProps<{ products: Product[], from:number, to:number, total:number }>> = ({ auth, products, from, to, total }) => {
    const [searchTerm, setSearchTerm] = useState<string>('');
    const handleSearchChange = (event: React.ChangeEvent<HTMLInputElement>) => {
      setSearchTerm(event.target.value);
    };
    return (
        <>
            <Head title="Products" />
            <Webmaster
                user={auth.user}
                menu={auth.menu}
                breadcrumb={<li className="breadcrumb-item active" aria-current="page">Products</li>}
            >
                <Page title="Products" header={<>
                    <Link className="btn btn-primary" href={route('products.create')}>Créer un produit</Link>
                        <PaginationInfo start={from} end={to} total={total}/>
                        <div className="w-full xl:w-auto flex items-center mt-3 xl:mt-0">
                            <div className="w-56 relative text-slate-500">
                                <Input type="text" className="form-control w-56 box pr-10" placeholder="Rechercher..." value={searchTerm} onChange={handleSearchChange}/>
                                <Search className="w-4 h-4 absolute my-auto inset-y-0 mr-3 right-0"/> 
                            </div>
                        </div>
                </>}>
                    <ProductsTable products={products} searchTerm={searchTerm}/>
                </Page>
            </Webmaster>
        </>
    );
};

export default Products;
