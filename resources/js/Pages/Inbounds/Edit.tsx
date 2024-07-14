
import React, {useEffect, useState, ChangeEvent} from 'react';
import { PageProps, Supplier, Product, InboundProducts, Inbound, Rubrique } from '@/types';
import { Head, Link } from '@inertiajs/react';
import Page from '@/Components/Page';
import Webmaster from '@/Layouts/Webmaster';
import { Button } from '@headlessui/react';
import Grid from '@/Components/Grid';
import { toast } from 'react-toastify';
import { router, useForm } from '@inertiajs/react'

interface FormData {
    supplier: number;
    rubrique: number;
    commande_note_number: number | null;
    delivery_note_number: number | null;
    invoice_number: number | null;
    products: InboundProducts[];
}

const EditInbound: React.FC<PageProps<{ products: Product[], suppliers: Supplier[], inbound: Inbound }>> = ({ auth, products, inbound, suppliers }) => {
    const { data, setData, put } = useForm<FormData>('rememberKey', {
        rubrique: inbound.rubrique.id,
        supplier: inbound.supplier.id,
        commande_note_number: inbound.commande_note_number ?? null,
        delivery_note_number: inbound.delivery_note_number ?? null,
        invoice_number: inbound.invoice_number ?? null,
        products: inbound.inbound_products,
    });
    const [editing, setEditing] = useState(false)
    
    useEffect(() => {
        if(products.length == 0)
        {
            toast.error('Veuillez créer au moins un produit dans la rubrique sélectionnée.', { className: 'error-toast' });
            router.get(route('inbounds.index'))
            return;
        }
        else
        {
            setData(prevData => ({
                ...prevData,
                products: inbound.inbound_products
            }));
        }
    }, []);

    const handleSubmit = () => {
        setEditing(true);
        put(route('inbounds.update', {inbound: inbound.id}), {
            data: {
                rubrique: data.rubrique,
                supplier: data.supplier,
                commande_note_number: data.commande_note_number,
                delivery_note_number: data.delivery_note_number,
                invoice_number: data.invoice_number,
                products: data.products.map(product => ({
                    product: product.product.id,
                    qte: product.qte,
                    unit_price_excl_tax: product.unit_price_excl_tax,
                    unit_price_net: product.unit_price_net,
                    total_amount_excl_tax: product.total_amount_excl_tax,
                    total_amount_net: product.total_amount_net,
                })),
            },
            onSuccess: () => {
                toast.success('Entrée modifiée avec succès');
                setEditing(false);
                router.get(route('inbounds.index'));
            },
            onError: (error) => {
                toast.error('Erreur lors de la modification de l\'entrée');
                console.error('Error:', error);
                setEditing(false);
            },
        });
        setEditing(false);
    }

    const addProduct = () => {
        const newProductId = data.products.length + 1;
        const newProduct = {
            id: newProductId,
            product: products[0],
            unit_price_excl_tax: 0,
            unit_price_net: 0,
            qte: 0,
            total_amount_excl_tax: 0,
            total_amount_net: 0,
        };
    
        setData(prevData => ({
            ...prevData,
            products: [...prevData.products, newProduct]
        }));
    };

    const removeProduct = (index: number) => {
        const updatedProducts = [...data.products];
        updatedProducts.splice(index, 1);
        setData(prevData => ({
            ...prevData,
            products: updatedProducts
        }));
    };
    const handleChange = (e: React.ChangeEvent<HTMLInputElement | HTMLTextAreaElement | HTMLSelectElement> ,index?: number, fieldName?: keyof InboundProducts) => {
        const { name, value } = e.target;

        if(index !== undefined && fieldName){
            setData(prevData => {
                const updatedProducts = prevData.products ? [...prevData.products] : [];
                if(updatedProducts[index]){
                    let updatedProduct = {
                        ...updatedProducts[index],
                        [fieldName]: value
                    };
                    if (fieldName === "unit_price_excl_tax" || fieldName === "qte") {
                        const unit_price_excl_tax = parseFloat(updatedProduct.unit_price_excl_tax.toString());
                        const qte = parseFloat(updatedProduct.qte.toString());

                        updatedProduct = {
                            ...updatedProduct,
                            unit_price_net: unit_price_excl_tax + unit_price_excl_tax*0.19,
                            total_amount_excl_tax: unit_price_excl_tax*qte,
                            total_amount_net: unit_price_excl_tax*qte*0.19 + unit_price_excl_tax*qte,
                        };
                    }

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
        <Head title="Modifier une entrée" />
        <Webmaster user={auth.user} menu={auth.menu}
            breadcrumb={<>
                <li className="breadcrumb-item" aria-current="page"><Link href={route('inbounds.index')}>Entrées</Link></li>
                <li className="breadcrumb-item" aria-current="page">{inbound.id}</li>
                <li className="breadcrumb-item active" aria-current="page">Modifier</li></>}>
            <Page title="Créer une entrée" header={<></>}>
                <Grid title="Information de l'entrée">
                    <div className="form-inline items-start flex-col xl:flex-row mt-5 pt-5 first:mt-0 first:pt-0">
                        <div className="form-label xl:w-64 xl:!mr-10">
                            <div className="text-left">
                                <div className="flex items-center">
                                    <div className="font-medium">Fournisseur</div>
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
                            <select defaultValue={data.supplier} id="supplier" name="supplier" className="form-control" onChange={handleChange}>
                                <option>Sélectioner le fournisseur</option>
                                {suppliers.map(supplier=>{
                                    return (<option key={supplier.id} value={supplier.id}>{supplier.name}</option>)
                                })}
                            </select>
                            <div className="form-help text-right">Caractère maximum 0/70</div>
                        </div>
                    </div>
                    <div className="form-inline items-start flex-col xl:flex-row mt-5 pt-5 first:mt-0 first:pt-0">
                        <div className="form-label xl:w-64 xl:!mr-10">
                            <div className="text-left">
                                <div className="flex items-center">
                                    <div className="font-medium">Numéro de bon de commande</div>
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
                            <input id="commande_note_number" name="commande_note_number" value={data.commande_note_number !== null ? String(data.commande_note_number) : ''} type="text" className="form-control" placeholder="Numéro de bon de commande" onChange={handleChange}/>
                            <div className="form-help text-right">Caractère maximum 0/70</div>
                        </div>
                    </div>
                    <div className="form-inline items-start flex-col xl:flex-row mt-5 pt-5 first:mt-0 first:pt-0">
                        <div className="form-label xl:w-64 xl:!mr-10">
                            <div className="text-left">
                                <div className="flex items-center">
                                    <div className="font-medium">Numéro de bon de livraison</div>
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
                            <input id="delivery_note_number" name="delivery_note_number" type="text" value={data.delivery_note_number !== null ? String(data.delivery_note_number): ""} className="form-control" placeholder="Numéro de bon de livraison" onChange={handleChange}/>
                            <div className="form-help text-right">Caractère maximum 0/70</div>
                        </div>
                    </div>
                    <div className="form-inline items-start flex-col xl:flex-row mt-5 pt-5 first:mt-0 first:pt-0">
                        <div className="form-label xl:w-64 xl:!mr-10">
                            <div className="text-left">
                                <div className="flex items-center">
                                    <div className="font-medium">Numéro de la facture</div>
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
                            <input id="invoice_number" name="invoice_number" type="text" className="form-control" value={data.invoice_number!==null?String(data.invoice_number):""} placeholder="Numéro de la facture" onChange={handleChange}/>
                            <div className="form-help text-right">Caractère maximum 0/70</div>
                        </div>
                    </div>
                </Grid>
                <Grid title="Information des produits" header={<Button className="btn btn-primary" onClick={addProduct}>+ de produits</Button>}>
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
                                        <select
                                            onChange={(e)=>handleChange(e, index, 'product')}
                                            id={`product-${index}`}
                                            name={`product-${index}`}
                                            className="form-control"
                                            defaultValue={product.product.id}
                                        >
                                            <option value='0'>Sélectioner le produit</option>
                                            {products.map(prod => (
                                                <option key={prod.id} value={prod.id}>{prod.designation}</option>
                                            ))}
                                        </select>
                                        <div className="form-help text-right">Rubrique: {product.product.rubrique.id}</div>
                                    </div>
                                    <div className="w-full mt-3 xl:mt-0 flex-1 mr-2">
                                        <input
                                            onChange={(e)=>handleChange(e, index, 'qte')}
                                            name={`qte-${index}`}
                                            type="number"
                                            className="form-control"
                                            placeholder="Quantité"
                                            value={product.qte}
                                        />
                                        <div className="form-help text-right">La quantité de produit</div>
                                    </div>
                                    <div className="w-full mt-3 xl:mt-0 flex-1 mr-2">
                                        <input
                                            onChange={(e)=>handleChange(e, index, 'unit_price_excl_tax')}
                                            name={`unit_price_excl_tax-${index}`}
                                            type="number"
                                            className="form-control"
                                            placeholder="Prix unitaire / HT"
                                            value={product.unit_price_excl_tax}
                                        />
                                        <div className="form-help text-right">Prix unitaire / HT</div>
                                    </div>
                                    <div className="w-full mt-3 xl:mt-0 flex-1 mr-2">
                                        <input
                                            onChange={(e)=>handleChange(e, index, 'unit_price_net')}
                                            name={`unit_price_net-${index}`}
                                            type="number"
                                            className="form-control"
                                            placeholder="Prix unitaire / TTC"
                                            value={product.unit_price_net}
                                            disabled
                                        />
                                        <div className="form-help text-right">Prix unitaire / TTC</div>
                                    </div>
                                    <div className="w-full mt-3 xl:mt-0 flex-1 mr-2">
                                        <input
                                            onChange={(e)=>handleChange(e, index, 'total_amount_excl_tax')}
                                            name={`total_amount_excl_tax-${index}`}
                                            type="number"
                                            disabled
                                            className="form-control"
                                            placeholder="Montant / HT"
                                            value={product.total_amount_excl_tax}
                                        />
                                        <div className="form-help text-right">Montant / HT</div>
                                    </div>
                                    <div className="w-full mt-3 xl:mt-0 flex-1 mr-2">
                                        <input
                                            onChange={(e)=>handleChange(e, index, 'total_amount_net')}
                                            name={`total_amount_net-${index}`}
                                            type="number"
                                            disabled
                                            className="form-control"
                                            placeholder="Montant / TTC"
                                            value={product.total_amount_net}
                                        />
                                        <div className="form-help text-right">Montant / TTC</div>
                                    </div>
                                    <div>
                                        <Button className="btn btn-primary" onClick={() => removeProduct(index)}>-</Button>
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

export default EditInbound;