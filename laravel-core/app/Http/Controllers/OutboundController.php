<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Product;
use App\Models\Service;
use App\Models\Outbound;
use App\Models\Rubrique;
use App\Models\OutboundProduct;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreOutboundRequest;
use App\Http\Requests\UpdateOutboundRequest;

class OutboundController extends Controller
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
    
        $rubriqueWithOutbounds = [];
        foreach ($rubriques as $index => $rubrique) {
            $rubriqueWithOutbounds[$index]['rubrique'] = $rubrique;
            
            $outboundIds = OutboundProduct::with('product')
                ->whereHas('product', function ($query) use ($rubrique) {
                    $query->where('rubrique', $rubrique['id']);
                })
                ->orderBy('id', 'desc')
                ->pluck('outbound')
                ->toArray();
        
            $rubriqueWithOutbounds[$index]['outbounds'] = Outbound::with('service', 'outboundProducts.product')->whereNull('deleted_at')->whereNull('deleted_by')->whereIn('id', $outboundIds)
                ->orderBy('id', 'desc')
                ->get()
                ->toArray();
        }

        return Inertia::render('Outbounds/Index', [
            'outbounds' => $rubriqueWithOutbounds,
            'from' => 1,
            'to' => count($rubriqueWithOutbounds),
            'total' => count($rubriqueWithOutbounds),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $services = Service::with(['createdBy', 'updatedBy', 'deletedBy'])
        ->whereNull('deleted_at')
        ->whereNull('deleted_by')
        ->orderBy('id', 'desc')
        ->get()->toArray();
        
        $rubriques = Rubrique::with(['createdBy', 'updatedBy', 'deletedBy'])
        ->with('products')
        ->whereNull('deleted_at')
        ->whereNull('deleted_by')
        ->orderBy('id', 'desc')
        ->whereIn('id', function ($query) {
            $query->select('rubrique')
            ->from('products')
            ->whereNull('deleted_at')
            ->whereNull('deleted_by');
        })
        ->get()->toArray();

        return Inertia::render('Outbounds/Create', ['services' => $services, 'rubriques' => $rubriques]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOutboundRequest $request)
    {
        $outbound = Outbound::create([
            'service' => $request->input('service'),
            'internal_delivery_note_number' => $request->input('internal_delivery_note_number'),
            'created_by' => Auth::user()->id
        ]);
        foreach($request->products as $product)
        {
            $productID = is_array($product['product'])?$product['product']['id']:$product['product'];
            $productDB = Product::find($productID);
            
            OutboundProduct::create([
                "product" => $productID,
                "qte" => $product['qte'],
                "outbound" => $outbound['id'],
                "unit_price_excl_tax" => $productDB->unit_price_excl_tax(),
                "unit_price_net" => $productDB->unit_price_net(),
                "total_amount_excl_tax" => $productDB->total_amount_excl_tax(),
                "total_amount_net" => $productDB->total_amount_net(),
            ]);
        }

        if ($outbound) {
            return redirect()->route('outbounds.index')->with('success', 'Outbound created successfully.');
        } else {
            return redirect()->back()->with('error', 'Outbound could not be created.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Outbound $outbound)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Outbound $outbound)
    {
        $outbound = Outbound::with('service', 'outboundProducts.product.rubrique')->find($outbound->id)->toArray();
        
        $services = Service::with(['createdBy', 'updatedBy', 'deletedBy'])
        ->whereNull('deleted_at')
        ->whereNull('deleted_by')
        ->orderBy('id', 'desc')
        ->get()->toArray();
        
        $rubriques = Rubrique::with(['createdBy', 'updatedBy', 'deletedBy'])
        ->with('products')
        ->whereNull('deleted_at')
        ->whereNull('deleted_by')
        ->orderBy('id', 'desc')
        ->whereIn('id', function ($query) {
            $query->select('rubrique')
            ->from('products')
            ->whereNull('deleted_at')
            ->whereNull('deleted_by');
        })
        ->get()->toArray();
        return Inertia::render('Outbounds/Edit', ['services' => $services, 'rubriques' => $rubriques, 'outbound' => $outbound]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOutboundRequest $request, Outbound $outbound)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Outbound $outbound)
    {
        //
    }
}
