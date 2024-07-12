import React, { ReactNode, useState, useEffect } from 'react';
import { Product } from '@/types';
import Pagination from './Pagination';
import { Blocks, Calendar, CheckSquare, Hash, Trash2, User } from 'lucide-react';
import { Link } from '@inertiajs/react';
import { Button } from '@headlessui/react';

interface PageProps {
    products: Product[];
    searchTerm?: string;
}

const ProductsTable: React.FC<PageProps> = ({ products, searchTerm }) => {
    const [shownProducts, setShownProducts] = useState(products)
    useEffect(() => {
        if (searchTerm) {
          const filteredProducts = products.filter(
            (item) =>
              item.designation.toLowerCase().includes(searchTerm.toLowerCase()) ||
                item.description.toLowerCase().includes(searchTerm.toLowerCase()) ||
                item.created_by.name.toLowerCase().includes(searchTerm.toLowerCase())
          );
          setShownProducts(filteredProducts);
        } else {
            setShownProducts(products);
        }
      }, [products, searchTerm]);
    return (<>
        <div className="intro-y col-span-12 overflow-auto 2xl:overflow-visible">
            <table className="table table-report -mt-2">
                <thead>
                    <tr>
                        <th className="whitespace-nowrap">
                            <input className="form-check-input" type="checkbox" />
                        </th>
                        <th className="whitespace-nowrap">#</th>
                        <th className="text-center whitespace-nowrap">Description</th>
                        <th className="text-center whitespace-nowrap">Rubrique</th>
                        <th className="text-center whitespace-nowrap">Created</th>
                        <th className="text-center whitespace-nowrap">Action</th>
                    </tr>
                </thead>
                <tbody>
                    {
                        shownProducts.map((product)=>{
                            return (<tr className="intro-x" key={product.id}>
                                <td className="w-10">
                                    <input className="form-check-input" type="checkbox" />
                                </td>
                                <td className="!py-3.5">
                                    <div className="flex items-center">
                                        <div className="ml-4">
                                            <div className="flex items-center">
                                                <Hash className="h-4 w-4 text-gray-500 mr-1" />
                                                <span className="text-gray-500">{product.id}</span>
                                            </div>
                                            <div className="flex items-center mt-1">
                                                <Blocks className="h-4 w-4 text-gray-500 mr-1" />
                                                <span className="text-gray-500">{product.designation}</span>
                                            </div>
                                            <div className="flex items-center mt-1">
                                                <Calendar className="h-4 w-4 text-gray-500 mr-1" />
                                                <span className="text-gray-500">{product.updated_at.toLocaleString()}</span>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td className="py-3.5">
                                    <div className="flex items-center">
                                        <div className="ml-4">
                                            {product.description}
                                        </div>
                                    </div>
                                </td>
                                <td className="py-3.5">
                                    <div className="flex items-center">
                                        <div className="ml-4">
                                            <Link href={route('rubriques.edit', {rubrique: product.rubrique.id})} >{product.rubrique.name}</Link>
                                        </div>
                                    </div>
                                </td>
                                <td className="py-3.5">
                                    <div className="flex items-center">
                                        <div className="ml-4">
                                            <div className="flex items-center mt-1">
                                                <User className="h-4 w-4 text-gray-500 mr-1" />
                                                <span className="text-gray-500">{product.created_by ? product.created_by.name : 'Unknown'}</span>
                                            </div>
                                            <div className="flex items-center mt-1">
                                                <Calendar className="h-4 w-4 text-gray-500 mr-1" />
                                                <span className="text-gray-500">{product.updated_at.toLocaleString()}</span>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td className="table-report__action w-56">
                                    <div className="flex justify-center items-center">
                                        <Link className="flex items-center mr-3" href={route('products.edit', { product: product.id })}>
                                            <CheckSquare className="w-4 h-4 mr-1"/> Edit
                                        </Link>
                                        <Button className="flex items-center text-danger">
                                            <Trash2 className="w-4 h-4 mr-1" /> Delete
                                        </Button>
                                    </div>
                                </td>
                            </tr>)
                        })
                    }
                </tbody>
            </table>
        </div>
    </>

    );
};

export default ProductsTable;
