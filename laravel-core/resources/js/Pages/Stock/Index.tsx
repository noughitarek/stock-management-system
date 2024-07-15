
import React, {useRef, useState} from 'react';
import { PageProps, Outbounds, Rubrique, Product } from '@/types';
import { Head, Link, router, useForm } from '@inertiajs/react';
import Page from '@/Components/Page';
import Webmaster from '@/Layouts/Webmaster';
import { Blocks, Bookmark, Box, Calendar, CheckSquare, ChevronDown, ChevronLeft, ChevronRight, Contact, Hash, MoreHorizontal, RefreshCw, Search, Settings, Star, Trash2 } from 'lucide-react';
import PaginationInfo from '@/Components/PaginationInfo';
import { Button } from '@headlessui/react';
import Modal from '@/Components/Modal';
import { toast } from 'react-toastify';
import DeleteModal from '@/Components/DeleteModal';

interface StockRow{
    information: Product;
    inbounds: number;
    outbounds: number;
    stock: number;
}
interface Stock{
    rubrique: Rubrique;
    products: StockRow[];
}
const Stock: React.FC<PageProps<{ stock: Stock[] }>> = ({ auth, stock }) => {
    const [activeStock, setActiveStock] = useState<Stock | null>(stock.length > 0 ? stock[0] : null);

    return (<>
        <Head title="Sorties" />
        <Webmaster
            user={auth.user}
            menu={auth.menu}
            breadcrumb={<li className="breadcrumb-item active" aria-current="page">Sorties</li>}
        >
        <Page title="Sorties" header={<></>}>
            <div className="grid grid-cols-12 gap-6 mt-8">
                <div className="col-span-12 lg:col-span-3 2xl:col-span-2">
                    <h2 className="intro-y text-lg font-medium mr-auto mt-2">Rubriques</h2>
                    <div className="intro-y box bg-primary p-5 mt-6">
                        <div className="dark:border-darkmode-400 text-white">
                            {stock.map(st=>{
                                const isActive = activeStock && st === activeStock;
                                return (
                                    <Button
                                        key={st.rubrique.id}
                                        onClick={() => setActiveStock(st)}
                                        className={`flex items-center px-3 py-2 rounded-md mt-2 ${isActive ? 'bg-white/10 dark:bg-darkmode-700 font-medium' : ''}`}
                                    >
                                        <Blocks className='w-4 h-4 mr-2' />
                                        {st.rubrique.name}
                                    </Button>
                                );
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
                        </div>
                    </div>
                    <div className="intro-y col-span-12 overflow-auto lg:overflow-visible">
                        <table className="table table-report -mt-2">
                            <thead>
                                <tr>
                                    <th className="whitespace-nowrap">Date</th>
                                    <th className="whitespace-nowrap">Produits</th>
                                    <th className="whitespace-nowrap">Entrées</th>
                                    <th className="whitespace-nowrap">Sorties</th>
                                    <th className="whitespace-nowrap">Stock</th>
                                    <th className="whitespace-nowrap">Docs</th>
                                </tr>
                            </thead>
                            <tbody>
                                {activeStock?.products.map((product, index)=>(
                                <tr key={index} className="intro-x">
                                    <td>
                                        <div className="flex items-center">
                                            <Hash className="h-4 w-4 text-gray-500 mr-1" />
                                            <span className="text-sm text-gray-500">{product.information.id}</span>
                                        </div>
                                        <div className="flex items-center mt-1">
                                            <Calendar className="h-4 w-4 text-gray-500 mr-2" />
                                            <span className="text-sm text-gray-500">
                                                {new Date(product.information.created_at).toLocaleString('en-GB', {
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
                                            <Blocks className="h-4 w-4 text-gray-500 mr-2" />
                                            <span className="text-sm text-gray-500">{product.information.rubrique.name}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div className="flex items-center mt-1">
                                            <Box className="h-4 w-4 text-gray-500 mr-2" />
                                            <span className="text-sm text-gray-500">{product.information.designation}</span>
                                        </div>
                                        <div className="text-sm text-gray-600">{product.information.description}</div>
                                    </td>
                                    <td>
                                        {product.inbounds}
                                    </td>
                                    <td>
                                        {product.outbounds}
                                    </td>
                                    <td>
                                        {product.stock}
                                    </td>
                                    <td>
                                        <div className="flex flex-col space-y-1">
                                            <Link href="" className="font-medium text-primary whitespace-nowrap">
                                            Fich de stock
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

export default Stock;