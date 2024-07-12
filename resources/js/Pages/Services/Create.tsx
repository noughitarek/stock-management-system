import Grid from "@/Components/Grid";
import Page from "@/Components/Page";
import Webmaster from "@/Layouts/Webmaster";
import { PageProps } from "@/types";
import { useState } from "react";
import { Button } from "@headlessui/react";
import { Head, Link, useForm } from "@inertiajs/react";
import { toast } from 'react-toastify';

const CreateService: React.FC<PageProps> = ( {auth} ) => {
    const [creating, setCreating] = useState(false)
    const { data, setData, post, processing, errors } = useForm({
        name: '',
        responsible_name: '',
        responsible_phone: '',
        description: '',
    });

    const handleChange = (e: React.ChangeEvent<HTMLInputElement | HTMLTextAreaElement | HTMLSelectElement>) => {
        const { name, value } = e.target;
        setData({
            ...data,
            [name]: value
        });
    };

    const handleSubmit = () =>{
        if (!data.name) {
            toast.error('Veuillez remplir tous les champs requis.', { className: 'error-toast' });
            return;
        }
        setCreating(true)

        post(route('services.store'), {
            data: {
                'name': data.name,
                'responsible_name': data.responsible_name,
                'responsible_phone': data.responsible_phone,
                'description': data.description,
            },
            onSuccess: () => {
                toast.success('Service créé avec succès!', { className: 'success-toast' });
                setCreating(false);
            },
            onError: (errors) => {
                console.error(errors);
                toast.error('Erreur lors de la création du service', { className: 'danger-toast' });
                setCreating(false);
            },
        });
    }
    return(<>
        <Head title="Créer un service" />
        <Webmaster user={auth.user} menu={auth.menu}
        breadcrumb=
        {<>
            <li className="breadcrumb-item" aria-current="page"><Link href={route('services.index')}>Services</Link></li>
            <li className="breadcrumb-item active" aria-current="page">Créer</li>
            </>}>
            <Page title="Créer un service" header={<Button className="btn btn-primary" disabled={creating} onClick={handleSubmit}>
                        {creating ? 'Enregistrement ...' : 'Enregistrer'}
                    </Button>}>
            {
                <Grid title="Information du service">
                <form>
                    <div className="form-inline items-start flex-col xl:flex-row mt-5 pt-5 first:mt-0 first:pt-0">
                        <div className="form-label xl:w-64 xl:!mr-10">
                            <div className="text-left">
                                <div className="flex items-center">
                                    <div className="font-medium">Nom du service</div>
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
                            <input onChange={handleChange} id="name" name="name" type="text" className="form-control" placeholder="Nom du service"/>
                            <div className="form-help text-right">Caractère maximum 0/70</div>
                        </div>
                    </div>
                    <div className="form-inline items-start flex-col xl:flex-row mt-5 pt-5 first:mt-0 first:pt-0">
                        <div className="form-label xl:w-64 xl:!mr-10">
                            <div className="text-left">
                                <div className="flex items-center">
                                    <div className="font-medium">Nom du responsable</div>
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
                            <input onChange={handleChange} id="responsible_name" name="responsible_name" type="text" className="form-control" placeholder="Nom du responsable"/>
                            <div className="form-help text-right">Caractère maximum 0/70</div>
                        </div>
                    </div>
                    <div className="form-inline items-start flex-col xl:flex-row mt-5 pt-5 first:mt-0 first:pt-0">
                        <div className="form-label xl:w-64 xl:!mr-10">
                            <div className="text-left">
                                <div className="flex items-center">
                                    <div className="font-medium">Téléphone du responsable</div>
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
                            <input onChange={handleChange} id="responsible_phone" name="responsible_phone" type="text" className="form-control" placeholder="Téléphone du responsable"/>
                            <div className="form-help text-right">Caractère maximum 0/70</div>
                        </div>
                    </div>
                    <div className="form-inline items-start flex-col xl:flex-row mt-5 pt-5 first:mt-0 first:pt-0">
                        <div className="form-label xl:w-64 xl:!mr-10">
                            <div className="text-left">
                                <div className="flex items-center">
                                    <div className="font-medium">Description du service</div>
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
                            <textarea onChange={handleChange} id="description" name="description" className="form-control" placeholder="Description du service"/>
                            <div className="form-help text-right">Caractère maximum 0/255</div>
                        </div>
                    </div><br/>
                    <Button className="btn btn-primary" disabled={creating} onClick={handleSubmit}>
                        {creating ? 'Enregistrement ...' : 'Enregistrer'}
                    </Button>
                </form>
                </Grid>
            }
            </Page>

        </Webmaster>
    </>)
}

export default CreateService;