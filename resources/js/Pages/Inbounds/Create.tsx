
import React, {useEffect, useState} from 'react';
import { PageProps, Inbounds, Supplier, Product, InboundProducts } from '@/types';
import { Head, Link } from '@inertiajs/react';
import Page from '@/Components/Page';
import Webmaster from '@/Layouts/Webmaster';
import { Button } from '@headlessui/react';
import Grid from '@/Components/Grid';

const CreateInbound: React.FC<PageProps<{ products: Product[], suppliers: Supplier[], from:number, to:number, total:number }>> = ({ auth, products, suppliers, from, to, total }) => {


    const [inboundedProducts, setInboundedProducts] = useState<InboundProducts[]>([
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
    }, [inboundedProducts]);
    const removeProduct = (index: number) => {
        const updatedProducts = [...inboundedProducts];
        updatedProducts.splice(index, 1);
        setInboundedProducts(updatedProducts);
    };
    const handleInputChange = (index: number, fieldName: keyof InboundProducts, value: string | number) => {
        const updatedProducts = [...inboundedProducts];
        updatedProducts[index] = {
            ...updatedProducts[index],
            [fieldName]: value
        };
        setInboundedProducts(updatedProducts);
    };
    return (<>
        <Head title="Entrées" />
        <Webmaster user={auth.user} menu={auth.menu}
            breadcrumb={<>
                <li className="breadcrumb-item" aria-current="page"><Link href={route('inbounds.index')}>Entrées</Link></li>
                <li className="breadcrumb-item active" aria-current="page">Créer</li></>}>
            <Page title="Entrées" header={<></>}>
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
                            <select id="supplier" name="supplier" className="form-control">
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
                            <input id="commande_note_number" name="commande_note_number" type="text" className="form-control" placeholder="Numéro de bon de commande"/>
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
                            <input id="delivery_note_number" name="delivery_note_number" type="text" className="form-control" placeholder="Numéro de bon de livraison"/>
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
                            <input id="invoice_number" name="invoice_number" type="text" className="form-control" placeholder="Numéro de la facture"/>
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
                                            onChange={(e) => handleInputChange(index, 'product', e.target.value)}
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
                                            onChange={(e) => handleInputChange(index, 'qte', parseInt(e.target.value))}
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
                                            onChange={(e) => handleInputChange(index, 'unit_price_excl_tax', parseFloat(e.target.value))}
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
                                            onChange={(e) => handleInputChange(index, 'unit_price_net', parseFloat(e.target.value))}
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
                                            onChange={(e) => handleInputChange(index, 'total_amount_excl_tax', parseFloat(e.target.value))}
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
                                            onChange={(e) => handleInputChange(index, 'total_amount_net', parseFloat(e.target.value))}
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
                    <Button className="btn btn-primary">Create</Button>
                </Page>
            </Webmaster>
        </>
    );
};

export default CreateInbound;