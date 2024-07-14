import React, { ReactNode, useState, useEffect } from 'react';
import { Supplier } from '@/types';
import Pagination from './Pagination';
import { Blocks, Calendar, CheckSquare, Hash, MapPin, Phone, Trash2, User } from 'lucide-react';
import { Link, router, useForm } from '@inertiajs/react';
import { Button } from '@headlessui/react';
import DeleteModal from './DeleteModal';
import { toast } from 'react-toastify';

interface PageProps {
    suppliers: Supplier[];
    searchTerm?: string;
}

const SuppliersTable: React.FC<PageProps> = ({ suppliers, searchTerm }) => {
    const [showDeleteModal, setShowDeleteModal] = useState(false);
    const [deleting, setDeleting] = useState(false);
    const { data, setData, delete: deleteEntry } = useForm<{ supplier: number }>({ supplier: 0 });

    const [shownSuppliers, setShownSuppliers] = useState(suppliers)
    useEffect(() => {
        if (searchTerm) {
          const filteredSuppliers = suppliers.filter(
            (item) =>
                (item.name && item.name.toLowerCase().includes(searchTerm.toLowerCase())) ||
                (item.description && item.description.toLowerCase().includes(searchTerm.toLowerCase())) ||
                (item.created_by && item.created_by.name.toLowerCase().includes(searchTerm.toLowerCase()))
          );
          setShownSuppliers(filteredSuppliers);
        } else {
          setShownSuppliers(suppliers);
        }
      }, [suppliers, searchTerm]);
      const handleDeleteClick = (event: React.MouseEvent<HTMLButtonElement>, supplierID: number) => {
        event.preventDefault();
        setData({ supplier: supplierID });
        setShowDeleteModal(true);
    };

    const handleDeleteConfirm = () => {
        setDeleting(true);

        deleteEntry(route('suppliers.destroy', {supplier: data.supplier}), {
            data: {supplier: data.supplier},
            onSuccess: () => {
                toast.success('Fournisseur supprimé avec succès');
                router.get(route('suppliers.index'));
            },
            onError: (error) => {
                toast.error('Erreur lors de la suppression du fournisseur');
                setDeleting(false);
                console.error('Error:', error);
            },
        });
        setShowDeleteModal(false);
    };

    const handleDeleteCancel = () => {
        setShowDeleteModal(false);
    };
    return (<>
        <div className="intro-y col-span-12 overflow-auto 2xl:overflow-visible">
            <table className="table table-report -mt-2">
                <thead>
                    <tr>
                        <th className="whitespace-nowrap">
                            <input className="form-check-input" type="checkbox" />
                        </th>
                        <th className="whitespace-nowrap">#</th>
                        <th className="text-center whitespace-nowrap">Contact</th>
                        <th className="text-center whitespace-nowrap">Description</th>
                        <th className="text-center whitespace-nowrap">Created</th>
                        <th className="text-center whitespace-nowrap">Action</th>
                    </tr>
                </thead>
                <tbody>
                    {
                        shownSuppliers.map((supplier)=>{
                            return (<tr className="intro-x" key={supplier.id}>
                                <td className="w-10">
                                    <input className="form-check-input" type="checkbox" />
                                </td>
                                <td className="!py-3.5">
                                    <div className="flex items-center">
                                        <div className="ml-4">
                                            <div className="flex items-center">
                                                <Hash className="h-4 w-4 text-gray-500 mr-1" />
                                                <span className="text-gray-500">{supplier.id}</span>
                                            </div>
                                            <div className="flex items-center mt-1">
                                                <Blocks className="h-4 w-4 text-gray-500 mr-1" />
                                                <span className="text-gray-500">{supplier.name}</span>
                                            </div>
                                            <div className="flex items-center mt-1">
                                                <Calendar className="h-4 w-4 text-gray-500 mr-1" />
                                                <span className="text-gray-500">{supplier.updated_at.toLocaleString()}</span>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td className="!py-3.5">
                                    <div className="flex items-center">
                                        <div className="ml-4">
                                            <div className="flex items-center mt-1">
                                                <Phone className="h-4 w-4 text-gray-500 mr-1" />
                                                <span className="text-gray-500">{supplier.phone}</span>
                                            </div>
                                            <div className="flex items-center">
                                                <MapPin className="h-4 w-4 text-gray-500 mr-1" />
                                                <span className="text-gray-500">{supplier.address}</span>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td className="py-3.5">
                                    <div className="flex items-center">
                                        <div className="ml-4">
                                            {supplier.description}
                                        </div>
                                    </div>
                                </td>
                                <td className="py-3.5">
                                    <div className="flex items-center">
                                        <div className="ml-4">
                                            <div className="flex items-center mt-1">
                                                <User className="h-4 w-4 text-gray-500 mr-1" />
                                                <span className="text-gray-500">{supplier.created_by ? supplier.created_by.name : 'Unknown'}</span>
                                            </div>
                                            <div className="flex items-center mt-1">
                                                <Calendar className="h-4 w-4 text-gray-500 mr-1" />
                                                <span className="text-gray-500">{supplier.updated_at.toLocaleString()}</span>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td className="table-report__action w-56">
                                    <div className="flex justify-center items-center">
                                        <Link className="flex items-center mr-3" href={route('suppliers.edit', { supplier: supplier.id })}>
                                            <CheckSquare className="w-4 h-4 mr-1"/> Modifier
                                        </Link>
                                        <Button className="flex items-center text-danger" onClick={(event) => handleDeleteClick(event, supplier.id)}>
                                            <Trash2 className="w-4 h-4 mr-1" /> Supprimer
                                        </Button>
                                    </div>
                                </td>
                            </tr>)
                        })
                    }
                </tbody>
            </table>
            <DeleteModal showDeleteModal={showDeleteModal} handleDeleteCancel={handleDeleteCancel} handleDeleteConfirm={handleDeleteConfirm} deleting={deleting}/>
        </div>
    </>

    );
};

export default SuppliersTable;
