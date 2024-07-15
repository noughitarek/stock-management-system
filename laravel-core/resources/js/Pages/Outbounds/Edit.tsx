
import React, {useEffect, useState, ChangeEvent} from 'react';
import { PageProps, Outbound, Service, OutboundProducts, Rubrique } from '@/types';
import { Head, Link } from '@inertiajs/react';
import Page from '@/Components/Page';
import Webmaster from '@/Layouts/Webmaster';
import { Button } from '@headlessui/react';
import Grid from '@/Components/Grid';
import { toast } from 'react-toastify';
import { router, useForm } from '@inertiajs/react'

interface FormData {
    service: number;
    internal_delivery_note_number: number | null;
    products: OutboundProducts[];
}

const EditOutbound: React.FC<PageProps<{ rubriques: Rubrique[], services: Service[],  outbound: Outbound}>> = ({ auth, rubriques, services, outbound }) => {
    const { data, setData, put } = useForm<FormData>('rememberKey', {
        service: outbound.service.id,
        internal_delivery_note_number: outbound.internal_delivery_note_number,
        products: outbound.outbound_products,
    });
    const [editing, setEditing] = useState(false)
    
    useEffect(() => {
        if (!rubriques || rubriques.length == 0) {
            toast.error('Veuillez créer au moins une rubrique.', { className: 'error-toast' });
            router.get(route('outbounds.index'));
        }
    }, []);

    const handleSubmit = () => {
        setEditing(true);
        put(route('outbounds.update', {outbound: outbound.id}), {
            data: {
                internal_delivery_note_number: data.internal_delivery_note_number,
                service: data.service,
                products: data.products.map(product => ({
                    product: product.product.id,
                    qte: product.qte,
                })),
            },
            onSuccess: () => {
                toast.success('Sortie modifiée avec succès');
                setEditing(false);
                router.get(route('outbounds.index'));
            },
            onError: (error) => {
                toast.error('Erreur lors de la modification de la sortie');
                console.error('Error:', error);
                setEditing(false);
            },
        });
        setEditing(false);
    }

    const addOutbound = () => {
        const newOutboundId = data.products.length + 1;
        const newOutbound = {
            id: newOutboundId,
            rubrique: rubriques[0], 
            product: rubriques[0].products[0],
            qte: 0,
        };
    
        setData(prevData => ({
            ...prevData,
            products: [...prevData.products, newOutbound]
        }));
    };

    const removeOutbound = (index: number) => {
        const updatedProducts = [...data.products];
        updatedProducts.splice(index, 1);
        setData(prevData => ({
            ...prevData,
            products: updatedProducts
        }));
    };
    const handleChange = (e: React.ChangeEvent<HTMLInputElement | HTMLTextAreaElement | HTMLSelectElement> ,index?: number, fieldName?: keyof OutboundProducts) => {
        const { name, value } = e.target;

        if (fieldName === "rubrique" && index !== undefined) {
            const selectedRubrique = rubriques.find((rubrique) => rubrique.id === parseInt(value, 10));
    
            if (selectedRubrique) {
                setData((prevData) => ({
                    ...prevData,
                    products: prevData.products.map((product, idx) => {
                        if (idx === index) {
                            return {
                                ...product,
                                rubrique: selectedRubrique,
                                selectedProduct: selectedRubrique.products.length > 0 ? selectedRubrique.products[0].id : 0
                            };
                        }
                        return product;
                    })
                }));
            }
        } else if(index !== undefined && fieldName){
            setData(prevData => {
                const updatedProducts = prevData.products ? [...prevData.products] : [];
                if(updatedProducts[index]){
                    let updatedProduct = {
                        ...updatedProducts[index],
                        [fieldName]: value
                    };

                    updatedProducts[index] = updatedProduct;
                }

                return { ...prevData, products: updatedProducts };
            });
        }else{
            setData(prevData => ({
                ...prevData,
                [name]: value
            }));
        }
    };

    return (<>
        <Head title="Modifier une sortie" />
        <Webmaster user={auth.user} menu={auth.menu}
            breadcrumb={<>
                <li className="breadcrumb-item" aria-current="page"><Link href={route('outbounds.index')}>Entrées</Link></li>
                <li className="breadcrumb-item" aria-current="page">{outbound.id}</li>
                <li className="breadcrumb-item active" aria-current="page">Modifier</li></>}>
            <Page title="Créer une entrée" header={<></>}>
                <Grid title="Information de l'entrée">
                    <div className="form-inline items-start flex-col xl:flex-row mt-5 pt-5 first:mt-0 first:pt-0">
                        <div className="form-label xl:w-64 xl:!mr-10">
                            <div className="text-left">
                                <div className="flex items-center">
                                    <div className="font-medium">Service</div>
                                    <div className="ml-2 px-2 py-0.5 bg-slate-200 text-slate-600 dark:bg-darkmode-300 dark:text-slate-400 text-xs rounded-md">
                                        Requis
                                    </div>
                                </div>
                                <div className="leading-relaxed text-slate-500 text-xs mt-3">
                                Inclure min. 40 caractères pour le rendre plus attrayant et plus facile à utiliser
                                acheteurs à trouver, comprenant le type de produit, la marque et des informations telles que
                                comme couleur, matériau ou type.
                                </div>
                            </div>
                        </div>
                        <div className="w-full mt-3 xl:mt-0 flex-1">
                            <select defaultValue={data.service} id="service" name="service" className="form-control" onChange={handleChange}>
                                <option>Sélectioner le service</option>
                                {services.map(service=>{
                                    return (<option key={service.id} value={service.id}>{service.name}</option>)
                                })}
                            </select>
                            <div className="form-help text-right">Caractère maximum 0/70</div>
                        </div>
                    </div>
                    <div className="form-inline items-start flex-col xl:flex-row mt-5 pt-5 first:mt-0 first:pt-0">
                        <div className="form-label xl:w-64 xl:!mr-10">
                            <div className="text-left">
                                <div className="flex items-center">
                                    <div className="font-medium">Numéro de bon de sortie</div>
                                    <div className="ml-2 px-2 py-0.5 bg-slate-200 text-slate-600 dark:bg-darkmode-300 dark:text-slate-400 text-xs rounded-md">
                                        Optionnel
                                    </div>
                                </div>
                                <div className="leading-relaxed text-slate-500 text-xs mt-3">
                                Inclure min. 40 caractères pour le rendre plus attrayant et plus facile à utiliser
                                acheteurs à trouver, comprenant le type de produit, la marque et des informations telles que
                                comme couleur, matériau ou type.
                                </div>
                            </div>
                        </div>
                        <div className="w-full mt-3 xl:mt-0 flex-1">
                            <input id="internal_delivery_note_number" name="internal_delivery_note_number" value={data.internal_delivery_note_number !== null ? String(data.internal_delivery_note_number) : ''} type="text" className="form-control" placeholder="Numéro de bon de sortie" onChange={handleChange}/>
                            <div className="form-help text-right">Caractère maximum 0/70</div>
                        </div>
                    </div>
                </Grid>
                <Grid title="Information des produits" header={<Button className="btn btn-primary" onClick={addOutbound}>+ de produits</Button>}>
                        <>
                            {data.products.map((product, index) => (
                                <div key={index} className="form-inline items-start flex-col xl:flex-row mt-5 pt-5 first:mt-0 first:pt-0">
                                    <div className="form-label xl:w-64 xl:!mr-10">
                                        <div className="text-left">
                                            <div className="flex items-center">
                                                <div className="font-medium">Produit</div>
                                                <div className="ml-2 px-2 py-0.5 bg-slate-200 text-slate-600 dark:bg-darkmode-300 dark:text-slate-400 text-xs rounded-md">
                                                    Optionnel
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div className="w-full mt-3 xl:mt-0 flex-1 mr-2">
                                        <select  defaultValue={data.products[index].rubrique.id} onChange={(e)=>handleChange(e, index, 'rubrique')} id={`rubrique-${index}`} name={`rubrique-${index}`} className="form-control">
                                            <option value='0'>Sélectioner la rubrique</option>
                                            {rubriques.map(rubrique => (
                                                <option key={rubrique.id} value={rubrique.id}>{rubrique.name}</option>
                                            ))}
                                        </select>
                                    </div>
                                    <div className="w-full mt-3 xl:mt-0 flex-1 mr-2">
                                        <select
                                            onChange={(e)=>handleChange(e, index, 'product')}
                                            id={`product-${index}`}
                                            name={`product-${index}`}
                                            className="form-control"
                                            defaultValue={data.products[index].id}
                                        >
                                            <option value='0'>Sélectioner le produit</option>
                                            {data.products[index].rubrique.products.map((product) => (
                                                <option key={product.id} value={product.id}>
                                                    {product.designation}
                                                </option>
                                            ))}
                                        </select>
                                        <div className="form-help text-right">Rubrique: {data.products[index].rubrique.name}</div>
                                    </div>
                                    <div className="w-full mt-3 xl:mt-0 flex-1 mr-2">
                                        <input
                                            onChange={(e)=>handleChange(e, index, 'qte')}
                                            name={`qte-${index}`}
                                            type="number"
                                            className="form-control"
                                            placeholder="Quantité"
                                            value={data.products[index].qte}
                                        />
                                        <div className="form-help text-right">La quantité de produit</div>
                                    </div>
                                    <div>
                                        <Button className="btn btn-primary" onClick={() => removeOutbound(index)}>-</Button>
                                    </div>
                                </div>
                            ))}
                        </>
                    </Grid><br/>
                    <Button className="btn btn-primary" disabled={editing} onClick={handleSubmit}>
                        {editing ? 'Enregistrement ...' : 'Enregistrer'}
                    </Button>
                </Page>
            </Webmaster>
        </>
    );
};

export default EditOutbound;