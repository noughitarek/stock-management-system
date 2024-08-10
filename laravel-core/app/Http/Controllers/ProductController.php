<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Rubrique;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rubriques = Rubrique::with(['createdBy', 'updatedBy', 'deletedBy', 'products'])
        ->whereNull('deleted_at')
        ->whereNull('deleted_by')
        ->orderBy('id', 'desc')
        ->get();

        foreach($rubriques as &$rubrique){
            $rubrique->products = Product::with(['createdBy', 'updatedBy', 'deletedBy'])
            ->whereNull('deleted_at')
            ->whereNull('deleted_by')
            ->where('rubrique_id',  $rubrique->id)
            ->orderBy('id', 'desc')
            ->paginate(25)
            ->onEachSide(2);
        }
        
        return view('pages.products.index')
        ->with('rubriques', $rubriques)
        ->with('activeRubrique', $rubriques->first()->id??0);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        $product = Product::create([
            'designation' => $request->input('designation'),
            'rubrique_id' => $request->input('rubrique_id'),
            'description' => $request->input('description'),
            'init_stock' => $request->input('init_stock'),
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
        ]);
        return redirect()
        ->route('products')
        ->with('success', 'Produit créé avec succès');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        $product->update([
            'designation' => $request->input('designation'),
            'rubrique_id' => $request->input('rubrique_id'),
            'description' => $request->input('description'),
            'init_stock' => $request->input('init_stock'),
            'updated_by' => Auth::id(),
        ]);
        return redirect()
        ->route('products')
        ->with('success', 'Produit édité avec succès');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->update([
            'deleted_by' => Auth::id(),
            'deleted_at' => now(),
        ]);
        return redirect()
        ->route('rubriques')
        ->with('success', 'Produit supprimé avec succès');
    }
}
