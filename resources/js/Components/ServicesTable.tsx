import React, { ReactNode, useState, useEffect } from 'react';
import { Service } from '@/types';
import Pagination from './Pagination';
import { Blocks, BookUser, Calendar, CheckSquare, Hash, Phone, Trash2, User } from 'lucide-react';
import { Link, router, useForm } from '@inertiajs/react';
import { Button } from '@headlessui/react';
import DeleteModal from './DeleteModal';
import { toast } from 'react-toastify';

interface PageProps {
    services: Service[];
    searchTerm?: string;
}

const ServicesTable: React.FC<PageProps> = ({ services, searchTerm }) => {
    const [showDeleteModal, setShowDeleteModal] = useState(false);
    const [deleting, setDeleting] = useState(false);
    const { data, setData, delete: deleteEntry } = useForm<{ service: number }>({ service: 0 });

    const [shownServices, setShownServices] = useState(services)
    useEffect(() => {
        if (searchTerm) {
          const filteredServices = services.filter(
            (item) =>
                (item.name && item.name.toLowerCase().includes(searchTerm.toLowerCase())) ||
                (item.description && item.description.toLowerCase().includes(searchTerm.toLowerCase())) ||
                (item.responsible_name && item.responsible_name.toLowerCase().includes(searchTerm.toLowerCase())) ||
                (item.responsible_phone && item.responsible_phone.toLowerCase().includes(searchTerm.toLowerCase())) ||
                (item.created_by && item.created_by.name.toLowerCase().includes(searchTerm.toLowerCase()))
          );
          setShownServices(filteredServices);
        } else {
            setShownServices(services);
        }
      }, [services, searchTerm]);
    const handleDeleteClick = (event: React.MouseEvent<HTMLButtonElement>, serviceID: number) => {
        event.preventDefault();
        setData({ service: serviceID });
        setShowDeleteModal(true);
    };

    const handleDeleteConfirm = () => {
        setDeleting(true);

        deleteEntry(route('services.destroy', {service: data.service}), {
            data: {service: data.service},
            onSuccess: () => {
                toast.success('Service supprimé avec succès');
                router.get(route('services.index'));
            },
            onError: (error) => {
                toast.error('Erreur lors de la suppression du service');
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
                        <th className="text-center whitespace-nowrap">Responsable</th>
                        <th className="text-center whitespace-nowrap">Description</th>
                        <th className="text-center whitespace-nowrap">Created</th>
                        <th className="text-center whitespace-nowrap">Action</th>
                    </tr>
                </thead>
                <tbody>
                    {
                        shownServices.map((service)=>{
                            return (<tr className="intro-x" key={service.id}>
                                <td className="w-10">
                                    <input className="form-check-input" type="checkbox" />
                                </td>
                                <td className="!py-3.5">
                                    <div className="flex items-center">
                                        <div className="ml-4">
                                            <div className="flex items-center">
                                                <Hash className="h-4 w-4 text-gray-500 mr-1" />
                                                <span className="text-gray-500">{service.id}</span>
                                            </div>
                                            <div className="flex items-center mt-1">
                                                <Blocks className="h-4 w-4 text-gray-500 mr-1" />
                                                <span className="text-gray-500">{service.name}</span>
                                            </div>
                                            <div className="flex items-center mt-1">
                                                <Calendar className="h-4 w-4 text-gray-500 mr-1" />
                                                <span className="text-gray-500">{service.updated_at.toLocaleString()}</span>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td className="!py-3.5">
                                    <div className="flex items-center">
                                        <div className="ml-4">
                                            <div className="flex items-center mt-1">
                                                <BookUser className="h-4 w-4 text-gray-500 mr-1" />
                                                <span className="text-gray-500">{service.responsible_name}</span>
                                            </div>
                                            <div className="flex items-center mt-1">
                                                <Phone className="h-4 w-4 text-gray-500 mr-1" />
                                                <span className="text-gray-500">{service.responsible_phone}</span>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td className="py-3.5">
                                    <div className="flex items-center">
                                        <div className="ml-4">
                                            {service.description}
                                        </div>
                                    </div>
                                </td>
                                <td className="py-3.5">
                                    <div className="flex items-center">
                                        <div className="ml-4">
                                            <div className="flex items-center mt-1">
                                                <User className="h-4 w-4 text-gray-500 mr-1" />
                                                <span className="text-gray-500">{service.created_by ? service.created_by.name : 'Unknown'}</span>
                                            </div>
                                            <div className="flex items-center mt-1">
                                                <Calendar className="h-4 w-4 text-gray-500 mr-1" />
                                                <span className="text-gray-500">{service.updated_at.toLocaleString()}</span>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td className="table-report__action w-56">
                                    <div className="flex justify-center items-center">
                                        <Link className="flex items-center mr-3" href={route('rubriques.edit', { rubrique: service.id })}>
                                            <CheckSquare className="w-4 h-4 mr-1"/> Modifier
                                        </Link>
                                        <Button className="flex items-center text-danger" onClick={(event) => handleDeleteClick(event, service.id)}>
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

export default ServicesTable;
