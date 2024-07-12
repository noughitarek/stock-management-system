import { useState } from 'react';
import { Button } from '@headlessui/react';
import { Head, Link, useForm } from '@inertiajs/react';
import { toast } from 'react-toastify';
import Grid from '@/Components/Grid';
import Page from '@/Components/Page';
import Webmaster from '@/Layouts/Webmaster';
import { PageProps, Supplier } from '@/types';

const EditSupplier: React.FC<PageProps<{ supplier: Supplier }>> = ({ auth, supplier }) => {
    const { data, setData, put, processing, errors } = useForm({
        name: supplier.name || '',
        phone: supplier.phone || '',
        address: supplier.address || '',
        description: supplier.description || '',
    });

    const [creating, setCreating] = useState(false);

    const handleChange = (e: React.ChangeEvent<HTMLInputElement | HTMLTextAreaElement | HTMLSelectElement>) => {
        const { name, value } = e.target;
        setData((prevData) => ({
            ...prevData,
            [name]: value,
        }));
    };

    const handleSubmit = () => {
        if (!data.name) {
            toast.error('Veuillez remplir tous les champs requis.', { className: 'error-toast' });
            return;
        }
        setCreating(true);

        put(route('suppliers.update', { supplier: supplier.id }), {
            data: {
                name: data.name,
                phone: data.phone,
                address: data.address,
                description: data.description,
            },
            onSuccess: () => {
                toast.success('Fournisseur modifié avec succès!', { className: 'success-toast' });
                setCreating(false);
            },
            onError: (errors) => {
                console.error(errors);
                toast.error('Erreur lors de la modification du fournisseur', { className: 'danger-toast' });
                setCreating(false);
            },
        });
    };

    return (
        <>
            <Head title="Modifier un fournisseur" />
            <Webmaster user={auth.user} menu={auth.menu} breadcrumb={
                <>
                    <li className="breadcrumb-item" aria-current="page">
                        <Link href={route('suppliers.index')}>Fournisseurs</Link>
                    </li>
                    <li className="breadcrumb-item" aria-current="page">
                        {supplier.id}
                    </li>
                    <li className="breadcrumb-item active" aria-current="page">Modifier</li>
                </>
            }>
                <Page title="Modifier une supplier" header={
                    <Button className="btn btn-primary" disabled={creating} onClick={handleSubmit}>
                        {creating ? 'Enregistrement ...' : 'Enregistrer'}
                    </Button>
                }>
                    <Grid title="Information de la supplier">
                        <form>
                            <div className="form-inline items-start flex-col xl:flex-row mt-5 pt-5 first:mt-0 first:pt-0">
                                <div className="form-label xl:w-64 xl:!mr-10">
                                    <div className="text-left">
                                        <div className="flex items-center">
                                            <div className="font-medium">Nom du fournisseur</div>
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
                                    <input onChange={handleChange} id="name" name="name" type="text" className="form-control" value={supplier.name} placeholder="Nom de supplier"/>
                                    <div className="form-help text-right">Caractère maximum 0/70</div>
                                </div>
                            </div>
                            <div className="form-inline items-start flex-col xl:flex-row mt-5 pt-5 first:mt-0 first:pt-0">
                                <div className="form-label xl:w-64 xl:!mr-10">
                                    <div className="text-left">
                                        <div className="flex items-center">
                                            <div className="font-medium">Téléphone du fournisseur</div>
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
                                    <input onChange={handleChange} id="phone" name="phone" type="text" className="form-control" value={supplier.phone} placeholder="Téléphone du fournisseur"/>
                                    <div className="form-help text-right">Caractère maximum 0/70</div>
                                </div>
                            </div>
                            <div className="form-inline items-start flex-col xl:flex-row mt-5 pt-5 first:mt-0 first:pt-0">
                                <div className="form-label xl:w-64 xl:!mr-10">
                                    <div className="text-left">
                                        <div className="flex items-center">
                                            <div className="font-medium">Adresse du fournisseur</div>
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
                                    <input onChange={handleChange} id="address" name="address" type="text" className="form-control" value={supplier.address} placeholder="Adresse du fournisseur"/>
                                    <div className="form-help text-right">Caractère maximum 0/70</div>
                                </div>
                            </div>
                            <div className="form-inline items-start flex-col xl:flex-row mt-5 pt-5 first:mt-0 first:pt-0">
                                <div className="form-label xl:w-64 xl:!mr-10">
                                    <div className="text-left">
                                        <div className="flex items-center">
                                            <div className="font-medium">Description du fournisseur</div>
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
                                    <textarea onChange={handleChange} id="description" name="description" className="form-control" value={supplier.description} placeholder="Description du fournisseur"/>
                                    <div className="form-help text-right">Caractère maximum 0/255</div>
                                </div>
                            </div><br/>
                            <Button className="btn btn-primary" disabled={creating} onClick={handleSubmit}>
                                {creating ? 'Enregistrement ...' : 'Enregistrer'}
                            </Button>
                        </form>
                    </Grid>
                </Page>
            </Webmaster>
        </>
    );
};

export default EditSupplier;