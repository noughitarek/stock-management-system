<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Inbound;
use App\Models\Product;
use App\Models\Rubrique;
use App\Models\Supplier;
use App\Models\InboundProduct;
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
        
            $rubriqueWithInbounds[$index]['inbounds'] = Inbound::with('supplier', 'inboundProducts.product')->whereIn('id', $inboundIds)
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
    public function create()
    {
        $suppliers = Supplier::with(['createdBy', 'updatedBy', 'deletedBy'])
        ->whereNull('deleted_at')
        ->whereNull('deleted_by')
        ->orderBy('id', 'desc')
        ->get()->toArray();
        
        $products = Product::with(['createdBy', 'updatedBy', 'deletedBy'])
        ->whereNull('deleted_at')
        ->whereNull('deleted_by')
        ->orderBy('id', 'desc')
        ->get()->toArray();

        return Inertia::render('Inbounds/Create', ['suppliers' => $suppliers, 'products' => $products]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreInboundRequest $request)
    {
        //
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateInboundRequest $request, Inbound $inbound)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Inbound $inbound)
    {
        //
    }
}
