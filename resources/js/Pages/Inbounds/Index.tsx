
import React, {useEffect, useState} from 'react';
import { PageProps, Inbounds, Rubrique } from '@/types';
import { Head, Link } from '@inertiajs/react';
import Page from '@/Components/Page';
import Webmaster from '@/Layouts/Webmaster';
import { Blocks, Bookmark, Calendar, ChevronDown, ChevronLeft, ChevronRight, Contact, Hash, MoreHorizontal, RefreshCw, Search, Settings, Star } from 'lucide-react';
import PaginationInfo from '@/Components/PaginationInfo';
import { Button } from '@headlessui/react';

const InboundsIndex: React.FC<PageProps<{ inbounds: Inbounds[], from:number, to:number, total:number }>> = ({ auth, inbounds, from, to, total }) => {
    const [activeInbound, setActiveInbound] = useState(inbounds.length > 0 ? inbounds[0] : null);
    const handleRubriqueChange = (rubrique: Rubrique) => {
        const filteredInbounds = inbounds.filter(inbound => inbound.rubrique === rubrique);
        setActiveInbound(filteredInbounds.length > 0 ? filteredInbounds[0] : null);
    }

    return (<>
        <Head title="Entrées" />
        <Webmaster
            user={auth.user}
            menu={auth.menu}
            breadcrumb={<li className="breadcrumb-item active" aria-current="page">Entrées</li>}
        >
        <Page title="Entrées" header={<></>}>
            <div className="grid grid-cols-12 gap-6 mt-8">
                <div className="col-span-12 lg:col-span-3 2xl:col-span-2">
                    <h2 className="intro-y text-lg font-medium mr-auto mt-2">Rubriques</h2>
                    <div className="intro-y box bg-primary p-5 mt-6">
                        <div className="dark:border-darkmode-400 text-white">
                            {inbounds.map(inbound=>{
                                if(activeInbound && inbound.rubrique == activeInbound.rubrique)
                                {
                                    return(<Button className="flex items-center px-3 py-2 rounded-md bg-white/10 dark:bg-darkmode-700 font-medium mt-2" >
                                        <Blocks className='w-4 h-4 mr-2'/> {inbound.rubrique.name}
                                    </Button>)
                                }
                                else
                                {
                                    return(<Button onClick={()=>handleRubriqueChange(inbound.rubrique)} className="flex items-center px-3 py-2 mt-2 rounded-md" >
                                        <Blocks className='w-4 h-4 mr-2'/> {inbound.rubrique.name}
                                    </Button>)
                                }
                            })}
                        </div>
                    </div>
                </div>
                <div className="col-span-12 lg:col-span-9 2xl:col-span-10">
                    <div className="intro-y flex flex-col-reverse sm:flex-row items-center">
                        <div className="w-full sm:w-auto relative mr-auto mt-3 sm:mt-0">
                            <Search className="w-4 h-4 absolute my-auto inset-y-0 ml-3 left-0 z-10 text-slate-500"/>
                            <input type="text" className="form-control w-full sm:w-64 box px-10" placeholder="Search mail" />
                            <div className="inbox-filter dropdown absolute inset-y-0 mr-3 right-0 flex items-center" data-tw-placement="bottom-start">
                                <ChevronDown className="dropdown-toggle w-4 h-4 cursor-pointer text-slate-500" role="button" aria-expanded="false" data-tw-toggle="dropdown"/>
                            </div>
                        </div>
                        <div className="w-full sm:w-auto flex">
                        <Link href={route('inbounds.create')} className="btn btn-primary shadow-md mr-2">Créer une entrée</Link>
                        </div>
                    </div>
                    <div className="intro-y col-span-12 overflow-auto lg:overflow-visible">
                        <table className="table table-report -mt-2">
                            <thead>
                                <tr>
                                    <th className="whitespace-nowrap">Date</th>
                                    <th className="whitespace-nowrap">Produits</th>
                                    <th className="whitespace-nowrap">Qte</th>
                                    <th className="whitespace-nowrap">Prix UN/HT</th>
                                    <th className="whitespace-nowrap">Montant HT</th>
                                    <th className="whitespace-nowrap">Prix UN/TTC</th>
                                    <th className="whitespace-nowrap">Montant TTC</th>
                                    <th className="whitespace-nowrap">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            {activeInbound && activeInbound.inbounds.map((inbound, index) => (
                                <tr key={index} className="intro-x">
                                    <td>
                                        <div className="flex items-center">
                                            <Hash className="h-4 w-4 text-gray-500 mr-1" />
                                            <span className="text-sm text-gray-500">{inbound.id}</span>
                                        </div>
                                        <div className="flex items-center mt-1">
                                            <Calendar className="h-4 w-4 text-gray-500 mr-2" />
                                            <span className="text-sm text-gray-500">
                                                {new Date(inbound.created_at).toLocaleString('en-GB', {
                                                    day: '2-digit',
                                                    month: '2-digit',
                                                    year: 'numeric',
                                                    hour: '2-digit',
                                                    minute: '2-digit',
                                                    second: '2-digit'
                                                })}
                                            </span>
                                        </div>
                                        <div className="flex items-center mt-1">
                                            <Contact className="h-4 w-4 text-gray-500 mr-2" />
                                            <span className="text-sm text-gray-500">{inbound.supplier.name}</span>
                                        </div>
                                    </td>
                                    <td>
                                        {inbound.inbound_products.map((product, productIndex)=>{
                                            return (
                                                <div className="flex flex-col space-y-1">
                                                    <span className='font-medium whitespace-nowrap' key={productIndex}>
                                                        {product.product.designation}
                                                    </span>
                                                 </div>
                                            )
                                        }) }
                                    </td>
                                    <td>
                                        {inbound.inbound_products.map((product, productIndex)=>{
                                            return (
                                                <div className="flex flex-col space-y-1">
                                                    <span className='font-medium whitespace-nowrap' key={productIndex}>
                                                        {product.qte}
                                                    </span>
                                                 </div>
                                            )
                                        }) }
                                    </td>
                                    <td>
                                        {inbound.inbound_products.map((product, productIndex)=>{
                                            return (
                                                <div className="flex flex-col space-y-1">
                                                    <span className='font-medium whitespace-nowrap' key={productIndex}>
                                                        <b>{product.unit_price_excl_tax}</b> DZD
                                                    </span>
                                                 </div>
                                            )
                                        }) }
                                    </td>
                                    <td>
                                        {inbound.inbound_products.map((product, productIndex)=>{
                                            return (
                                                <div className="flex flex-col space-y-1">
                                                    <span className='font-medium whitespace-nowrap' key={productIndex}>
                                                        <b>{product.total_amount_excl_tax}</b> DZD
                                                    </span>
                                                 </div>
                                            )
                                        }) }
                                    </td>
                                    <td>
                                        {inbound.inbound_products.map((product, productIndex)=>{
                                            return (
                                                <div className="flex flex-col space-y-1">
                                                    <span className='font-medium whitespace-nowrap' key={productIndex}>
                                                        <b>{product.unit_price_net}</b> DZD
                                                    </span>
                                                 </div>
                                            )
                                        }) }
                                    </td>
                                    <td>
                                        {inbound.inbound_products.map((product, productIndex)=>{
                                            return (
                                                <div className="flex flex-col space-y-1">
                                                    <span className='font-medium whitespace-nowrap' key={productIndex}>
                                                        <b>{product.total_amount_net}</b> DZD
                                                    </span>
                                                 </div>
                                            )
                                        }) }
                                    </td>
                                    <td>
                                        <div className="flex flex-col space-y-1">
                                            <Link href="" className="font-medium text-primary whitespace-nowrap">
                                                Bon de commande 
                                            </Link>
                                         </div>
                                        <div className="flex flex-col space-y-1">
                                            <Link href="" className="font-medium text-primary whitespace-nowrap">
                                                Bon de livraison
                                            </Link>
                                         </div>
                                    </td>
                                </tr>
                            ))}
                        </tbody>
                        </table>
                    </div>
                    <div className="p-5 flex flex-col sm:flex-row items-center text-center sm:text-left text-slate-500">
                        <div>4.41 GB (25%) of 17 GB used Manage</div>
                        <div className="sm:ml-auto mt-2 sm:mt-0">
                            Last account activity: 36 minutes ago
                        </div>
                    </div>
                </div>
            </div>
            </Page>
        </Webmaster>            
    </>
        )
}

export default InboundsIndex;