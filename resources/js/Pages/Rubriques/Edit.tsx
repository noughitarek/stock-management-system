import { useState } from 'react';
import { Button } from '@headlessui/react';
import { Head, Link, useForm } from '@inertiajs/react';
import { toast } from 'react-toastify';
import Grid from '@/Components/Grid';
import Page from '@/Components/Page';
import Webmaster from '@/Layouts/Webmaster';
import { PageProps, Rubrique } from '@/types';

const EditRubrique: React.FC<PageProps<{ rubrique: Rubrique }>> = ({ auth, rubrique }) => {
    const { data, setData, put, processing, errors } = useForm({
        name: rubrique.name || '',
        description: rubrique.description || '',
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

        put(route('rubriques.update', { rubrique: rubrique.id }), {
            data: {
                name: data.name,
                description: data.description,
            },
            onSuccess: () => {
                toast.success('Rubrique créée avec succès!', { className: 'success-toast' });
                setCreating(false);
            },
            onError: (errors) => {
                console.error(errors);
                toast.error('Erreur lors de la création de la rubrique', { className: 'danger-toast' });
                setCreating(false);
            },
        });
    };

    return (
        <>
            <Head title="Créer une rubrique" />
            <Webmaster user={auth.user} menu={auth.menu} breadcrumb={
                <>
                    <li className="breadcrumb-item" aria-current="page">
                        <Link href={route('rubriques.index')}>Rubriques</Link>
                    </li>
                    <li className="breadcrumb-item" aria-current="page">
                        {rubrique.id}
                    </li>
                    <li className="breadcrumb-item active" aria-current="page">Modifier</li>
                </>
            }>
                <Page title="Modifier une rubrique" header={
                    <Button className="btn btn-primary" disabled={creating} onClick={handleSubmit}>
                        {creating ? 'Enregistrement ...' : 'Enregistrer'}
                    </Button>
                }>
                    <Grid title="Information de la rubrique">
                        <form>
                            <div className="form-inline items-start flex-col xl:flex-row mt-5 pt-5 first:mt-0 first:pt-0">
                                <div className="form-label xl:w-64 xl:!mr-10">
                                    <div className="text-left">
                                        <div className="flex items-center">
                                            <div className="font-medium">Nom de la rubrique</div>
                                            <div className="ml-2 px-2 py-0.5 bg-slate-200 text-slate-600 dark:bg-darkmode-300 dark:text-slate-400 text-xs rounded-md">
                                                Requis
                                            </div>
                                        </div>
                                        <div className="leading-relaxed text-slate-500 text-xs mt-3">
                                            Inclure min. 40 caractères pour le rendre plus attrayant et plus facile à utiliser acheteurs à trouver, comprenant le type de produit, la marque et des informations telles que comme couleur, matériau ou type.
                                        </div>
                                    </div>
                                </div>
                                <div className="w-full mt-3 xl:mt-0 flex-1">
                                    <input onChange={handleChange} id="name" name="name" type="text" className="form-control" placeholder="Nom de rubrique" value={data.name} />
                                    <div className="form-help text-right">Caractère maximum 0/70</div>
                                </div>
                            </div>
                            <div className="form-inline items-start flex-col xl:flex-row mt-5 pt-5 first:mt-0 first:pt-0">
                                <div className="form-label xl:w-64 xl:!mr-10">
                                    <div className="text-left">
                                        <div className="flex items-center">
                                            <div className="font-medium">Description de la rubrique</div>
                                            <div className="ml-2 px-2 py-0.5 bg-slate-200 text-slate-600 dark:bg-darkmode-300 dark:text-slate-400 text-xs rounded-md">
                                            Optionnel
                                            </div>
                                        </div>
                                        <div className="leading-relaxed text-slate-500 text-xs mt-3">
                                            Inclure min. 40 caractères pour le rendre plus attrayant et plus facile à utiliser acheteurs à trouver, comprenant le type de produit, la marque et des informations telles que comme couleur, matériau ou type.
                                        </div>
                                    </div>
                                </div>
                                <div className="w-full mt-3 xl:mt-0 flex-1">
                                    <textarea onChange={handleChange} id="description" name="description" className="form-control" value={data.description} placeholder="Description de la rubrique" />
                                    <div className="form-help text-right">Caractère maximum 0/255</div>
                                </div>
                            </div><br />
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

export default EditRubrique;