
import React, {useEffect, useState, ChangeEvent} from 'react';
import { PageProps, Supplier, Product, InboundProducts, Inbound } from '@/types';
import { Head, Link } from '@inertiajs/react';
import Page from '@/Components/Page';
import Webmaster from '@/Layouts/Webmaster';
import { Button } from '@headlessui/react';
import Grid from '@/Components/Grid';
import { toast } from 'react-toastify';
import { router, useForm } from '@inertiajs/react'

interface FormData {
    supplier: number;
    commande_note_number: string;
    delivery_note_number: string;
    invoice_number: string;
    products: InboundProducts[];
}

const CreateInbound: React.FC<PageProps<{ products: Product[], suppliers: Supplier[], from:number, to:number, total:number }>> = ({ auth, products, suppliers, from, to, total }) => {


    const { data, setData, post, processing, errors } = useForm<FormData>({
        supplier: 0,
        commande_note_number: '',
        delivery_note_number: '',
        invoice_number: '',
        products: [],
    });

    const [creating, setCreating] = useState(false)
    const [inboundedProducts, setInboundedProducts] = useState<InboundProducts[]>([]);
    
    useEffect(() => {
        if(products.length == 0)
        {
            toast.error('Veuillez créer au moins un produit dans la rubrique sélectionnée.', { className: 'error-toast' });
            router.get(route('inbounds.index'))
            return;
        }
        else
        {
            setInboundedProducts([
                {
                    id: 1,
                    product: products[0],
                    unit_price_excl_tax: 0,
                    unit_price_net: 0,
                    qte: 0,
                    total_amount_excl_tax: 0,
                    total_amount_net: 0,
                }
            ]);
        }
    }, []);

    useEffect(() => {
        const updatedProducts = inboundedProducts.map(product => {
            const qte = product.qte
            const unit_price_excl_tax = product.unit_price_excl_tax

            const unit_price_net = unit_price_excl_tax+unit_price_excl_tax*19/100
            const total_amount_excl_tax = qte * unit_price_excl_tax;
            const total_amount_net = total_amount_excl_tax+total_amount_excl_tax*19/100;
            
            return {
                ...product,
                unit_price_net: unit_price_net,
                total_amount_excl_tax: total_amount_excl_tax,
                total_amount_net: total_amount_net
            };
        });
    
        setInboundedProducts(updatedProducts);
    }, [products]);

    const handleSubmit = () => {
        setCreating(true);
        post(route('inbounds.store'), {
            onSuccess: () => {
                toast.success('Entrée créée avec succès');
                setCreating(false);
                router.get(route('inbounds.index'));
            },
            onError: (error) => {
                toast.error('Erreur lors de la création de l\'entrée');
                console.error('Error:', error);
                setCreating(false);
            },
            data: {
                supplier: data.supplier,
                commande_note_number: data.commande_note_number,
                delivery_note_number: data.delivery_note_number,
                invoice_number: data.invoice_number,
                products: inboundedProducts.map(product => ({
                    product_id: product.product.id,
                    qte: product.qte,
                    unit_price_excl_tax: product.unit_price_excl_tax,
                    unit_price_net: product.unit_price_net,
                    total_amount_excl_tax: product.total_amount_excl_tax,
                    total_amount_net: product.total_amount_net,
                })),
            },
        });
    };

    const addProduct = () => {
        const newProductId = inboundedProducts.length + 1;
        setInboundedProducts([
            ...inboundedProducts,
            {
                id: newProductId,
                product: products[0],
                unit_price_excl_tax: 0,
                unit_price_net: 0,
                qte: 0,
                total_amount_excl_tax: 0,
                total_amount_net: 0,
            }
        ]);
    };

    const removeProduct = (index: number) => {
        const updatedProducts = [...inboundedProducts];
        updatedProducts.splice(index, 1);
        setInboundedProducts(updatedProducts);
    };

    const handleInputChange = (index: number, fieldName: keyof Inbound, e: ChangeEvent<HTMLInputElement | HTMLSelectElement>) => {
        const { value } = e.target;
    
        const updatedProducts = [...inboundedProducts];
        updatedProducts[index] = {
            ...updatedProducts[index],
            [fieldName]: value
        };
        setInboundedProducts(updatedProducts);
    
        setData(prevData => ({
            ...prevData,
            products: updatedProducts,
        }));
    };
    return (<>
        <Head title="Créer une entrée" />
        <Webmaster user={auth.user} menu={auth.menu}
            breadcrumb={<>
                <li className="breadcrumb-item" aria-current="page"><Link href={route('inbounds.index')}>Entrées</Link></li>
                <li className="breadcrumb-item active" aria-current="page">Créer</li></>}>
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
                            <select id="supplier" name="supplier" className="form-control" onChange={(e) => handleInputChange(0, 'supplier', e)}>
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
                            <input id="commande_note_number" name="commande_note_number" type="text" className="form-control" placeholder="Numéro de bon de commande" onChange={(e) => handleInputChange(0, 'commande_note_number', e)}/>
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
                            <input id="delivery_note_number" name="delivery_note_number" type="text" className="form-control" placeholder="Numéro de bon de livraison" onChange={(e) => handleInputChange(0, 'delivery_note_number', e)}/>
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
                            <input id="invoice_number" name="invoice_number" type="text" className="form-control" placeholder="Numéro de la facture" onChange={(e) => handleInputChange(0, 'invoice_number', e)}/>
                            <div className="form-help text-right">Caractère maximum 0/70</div>
                        </div>
                    </div>
                </Grid>
                <Grid title="Information des produits" header={<Button className="btn btn-primary" onClick={addProduct}>+ de produits</Button>}>
                        <>
                            {inboundedProducts.map((product, index) => (
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
                                            id={`product-${index}`}
                                            name={`product-${index}`}
                                            className="form-control"
                                            value={product.product.id}
                                            onChange={(e) => handleInputChange(index, 'product', e)}
                                        >
                                            <option>Sélectioner le produit</option>
                                            {products.map(prod => (
                                                <option key={prod.id} value={prod.id}>{prod.designation}</option>
                                            ))}
                                        </select>
                                        <div className="form-help text-right">Caractère maximum 0/70</div>
                                    </div>
                                    <div className="w-full mt-3 xl:mt-0 flex-1 mr-2">
                                        <input
                                            name={`qte-${index}`}
                                            type="number"
                                            className="form-control"
                                            placeholder="Quantité"
                                            value={product.qte}
                                            onChange={(e) => handleInputChange(index, 'qte', e)}
                                        />
                                        <div className="form-help text-right">La quantité de produit</div>
                                    </div>
                                    <div className="w-full mt-3 xl:mt-0 flex-1 mr-2">
                                        <input
                                            name={`unit_price_excl_tax-${index}`}
                                            type="number"
                                            className="form-control"
                                            placeholder="Prix unitaire / HT"
                                            value={product.unit_price_excl_tax}
                                            onChange={(e) => handleInputChange(index, 'unit_price_excl_tax', e)}
                                        />
                                        <div className="form-help text-right">Prix unitaire / HT</div>
                                    </div>
                                    <div className="w-full mt-3 xl:mt-0 flex-1 mr-2">
                                        <input
                                            name={`unit_price_net-${index}`}
                                            type="number"
                                            className="form-control"
                                            placeholder="Prix unitaire / TTC"
                                            value={product.unit_price_net}
                                            disabled
                                            onChange={(e) => handleInputChange(index, 'unit_price_net', e)}
                                        />
                                        <div className="form-help text-right">Prix unitaire / TTC</div>
                                    </div>
                                    <div className="w-full mt-3 xl:mt-0 flex-1 mr-2">
                                        <input
                                            name={`montant_excl_tax-${index}`}
                                            type="number"
                                            disabled
                                            className="form-control"
                                            placeholder="Montant / HT"
                                            value={product.total_amount_excl_tax}
                                            onChange={(e) => handleInputChange(index, 'total_amount_excl_tax', e)}
                                        />
                                        <div className="form-help text-right">Montant / HT</div>
                                    </div>
                                    <div className="w-full mt-3 xl:mt-0 flex-1 mr-2">
                                        <input
                                            name={`total_amount_net-${index}`}
                                            type="number"
                                            disabled
                                            className="form-control"
                                            placeholder="Montant / TTC"
                                            value={product.total_amount_net}
                                            onChange={(e) => handleInputChange(index, 'total_amount_net', e)}
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
                    <Button className="btn btn-primary" disabled={creating} onClick={handleSubmit}>
                        {creating ? 'Enregistrement ...' : 'Enregistrer'}
                    </Button>
                </Page>
            </Webmaster>
        </>
    );
};

export default CreateInbound;