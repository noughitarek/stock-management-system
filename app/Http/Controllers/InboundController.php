<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Inbound;
use App\Models\Product;
use App\Models\Rubrique;
use App\Models\Supplier;
use App\Models\InboundProduct;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreInboundRequest;
use App\Http\Requests\UpdateInboundRequest;

class InboundController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rubriques = Rubrique::whereNull('deleted_at')
        ->whereNull('deleted_by')
        ->orderBy('id', 'desc')
        ->get()
        ->toArray();
    
        $rubriqueWithInbounds = [];
        foreach ($rubriques as $index => $rubrique) {
            $rubriqueWithInbounds[$index]['rubrique'] = $rubrique;
            
            $inboundIds = InboundProduct::with('product')
                ->whereHas('product', function ($query) use ($rubrique) {
                    $query->where('rubrique', $rubrique['id']);
                })
                ->orderBy('id', 'desc')
                ->pluck('inbound')
                ->toArray();
        
            $rubriqueWithInbounds[$index]['inbounds'] = Inbound::with('supplier', 'inboundProducts.product')->whereNull('deleted_at')->whereNull('deleted_by')->whereIn('id', $inboundIds)
                ->orderBy('id', 'desc')
                ->get()
                ->toArray();
        }
        return Inertia::render('Inbounds/Index', [
            'inbounds' => $rubriqueWithInbounds,
            'from' => 1,
            'to' => count($rubriqueWithInbounds),
            'total' => count($rubriqueWithInbounds),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Rubrique $rubrique)
    {
        $suppliers = Supplier::with(['createdBy', 'updatedBy', 'deletedBy'])
        ->whereNull('deleted_at')
        ->whereNull('deleted_by')
        ->orderBy('id', 'desc')
        ->get()->toArray();
        
        $products = Product::with(['createdBy', 'updatedBy', 'deletedBy'])
        ->whereNull('deleted_at')
        ->whereNull('deleted_by')
        ->where('rubrique', $rubrique->id)
        ->orderBy('id', 'desc')
        ->get()->toArray();

        return Inertia::render('Inbounds/Create', ['suppliers' => $suppliers, 'products' => $products, 'rubrique' => $rubrique]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreInboundRequest $request)
    {
        $inbound = Inbound::create([
            'rubrique' => $request->input('rubrique'),
            'commande_note_number' => $request->input('commande_note_number'),
            'delivery_note_number' => $request->input('delivery_note_number'),
            'invoice_number' => $request->input('invoice_number'),
            'supplier' => $request->input('supplier'),
            'created_by' => Auth::user()->id
        ]);
        foreach($request->products as $product)
        {
            $productID = is_array($product['product'])?$product['product']['id']:$product['product'];
            InboundProduct::create([
                "product" => $productID,
                "unit_price_excl_tax" => $product['unit_price_excl_tax'],
                "unit_price_net" => $product['unit_price_net'],
                "qte" => $product['qte'],
                "total_amount_excl_tax" => $product['total_amount_excl_tax'],
                "total_amount_net" => $product['total_amount_net'],
                "inbound" => $inbound['id']
            ]);
        }

        if ($inbound) {
            return redirect()->route('inbounds.index')->with('success', 'Inbound created successfully.');
        } else {
            return redirect()->back()->with('error', 'Inbound could not be created.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Inbound $inbound)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Inbound $inbound)
    {
        $inbound = Inbound::with('rubrique', 'supplier', 'inboundProducts.product.rubrique')->find($inbound->id)->toArray();
        $suppliers = Supplier::with(['createdBy', 'updatedBy', 'deletedBy'])
        ->whereNull('deleted_at')
        ->whereNull('deleted_by')
        ->orderBy('id', 'desc')
        ->get()->toArray();
        
        $products = Product::with(['createdBy', 'updatedBy', 'deletedBy'])
        ->whereNull('deleted_at')
        ->whereNull('deleted_by')
        ->where('rubrique', $inbound['rubrique'])
        ->orderBy('id', 'desc')
        ->get()->toArray();

        return Inertia::render('Inbounds/Edit', ['suppliers' => $suppliers, 'products' => $products, 'inbound' => $inbound]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateInboundRequest $request, Inbound $inbound)
    {
        $inbound->update([
            'rubrique' => $request->input('rubrique'),
            'commande_note_number' => $request->input('commande_note_number'),
            'delivery_note_number' => $request->input('delivery_note_number'),
            'invoice_number' => $request->input('invoice_number'),
            'supplier' => $request->input('supplier'),
            'created_by' => Auth::user()->id
        ]);
        InboundProduct::where('inbound', $inbound->id)->delete();
        foreach($request->products as $product)
        {
            $productID = is_array($product['product'])?$product['product']['id']:$product['product'];
            InboundProduct::create([
                "product" => $productID,
                "unit_price_excl_tax" => $product['unit_price_excl_tax'],
                "unit_price_net" => $product['unit_price_net'],
                "qte" => $product['qte'],
                "total_amount_excl_tax" => $product['total_amount_excl_tax'],
                "total_amount_net" => $product['total_amount_net'],
                "inbound" => $inbound['id']
            ]);
        }

        if ($inbound->wasChanged()) {
            return redirect()->route('rubriques.index')->with('success', 'Inbound edited successfully.');
        } else {
            return redirect()->back()->with('error', 'Inbound could not be edited.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Inbound $inbound)
    {
        $inbound->update([
            "deleted_at" => now(),
            "deleted_by" => Auth::user()->id,
        ]);
        if($inbound->wasChanged()){
            return redirect()->route('inbounds.index')->with('success', 'Inbound deleted successfully.');
        }else{
            return redirect()->back()->with('error', 'Inbound could not be deleted.');
        }
    }
}
