<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Rubrique;
use Illuminate\Http\Request;
use App\Models\InboundProduct;
use App\Models\OutboundProduct;
use App\Http\Controllers\Controller;

class StockController extends Controller
{
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

        return view('pages.stock.index')
        ->with('rubriques', $rubriques)
        ->with('activeRubrique', $rubriques->first()->id??0);

    }
    public function stock_note(Product $product)
    {
        $inbounds = InboundProduct::with('inbound.supplier')
            ->where('product_id', $product->id)
            ->get()
            ->map(function($item) {
                $item->date = $item->inbound->inbound_at;
                $item->is_inbound = true;
                return $item;
            });
    
        $outbounds = OutboundProduct::with('outbound.service')
            ->where('product_id', $product->id)
            ->get()
            ->map(function($item) {
                $item->date = $item->outbound->outbound_at;
                $item->is_outbound = true;
                return $item;
            });
    
        $data = $inbounds->concat($outbounds)->sortBy('date')->values();
        return view('pages.stock.ficheDeStock')
            ->with('product', $product)
            ->with('data', $data);
    }
    
}
