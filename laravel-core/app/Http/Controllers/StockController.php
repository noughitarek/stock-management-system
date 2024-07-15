<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Product;
use App\Models\Rubrique;
use Illuminate\Http\Request;

class StockController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $productsRubriques = Product::whereNull('deleted_at')
        ->whereNull('deleted_by')
        ->orderBy('id', 'desc')
        ->pluck('rubrique')
        ->toArray();

        $rubriques = Rubrique::with(['createdBy', 'updatedBy', 'deletedBy', 'products.rubrique'])
        ->whereNull('deleted_at')
        ->whereNull('deleted_by')
        ->whereIn('id', $productsRubriques)
        ->orderBy('id', 'desc')
        ->get();


        $data = [];
        foreach($rubriques as $rub)
        {
            $rubrique = [];
            $rubrique['rubrique'] = $rub->toArray();
            foreach($rub->products as $prod)
            {
                $product = [];
                $product['information'] =  $prod->toArray();
                $product['inbounds'] =  $prod->inbounds();
                $product['outbounds'] =  $prod->outbounds();
                $product['stock'] =  $prod->stock();
                $rubrique['products'][] = $product;
            }
            $data[] = $rubrique;
        }
        return Inertia::render('Stock/Index', ['stock' => $data]);
    }
}
