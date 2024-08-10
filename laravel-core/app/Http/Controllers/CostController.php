<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Product;
use App\Models\Service;
use App\Models\Outbound;
use App\Models\Rubrique;
use Illuminate\Http\Request;
use App\Models\OutboundProduct;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class CostController extends Controller
{
    public function index()
    {
        $rubriques = Rubrique::with(['createdBy', 'updatedBy', 'deletedBy', 'products'])
        ->whereNull('deleted_at')
        ->whereNull('deleted_by')
        ->orderBy('id', 'desc')
        ->get();

        return view('pages.costs.index')
        ->with('rubriques', $rubriques)
        ->with('activeRubrique', $rubriques->first()->id??0);
    }

    public function calcule(Request $request)
    {
        $startDate = Carbon::parse($request->month.'/01/'.$request->year);
        $endDate = $startDate->copy()->addMonth();
        $date = $request->month.'/'.$request->year;
        $rubrique = Rubrique::find($request->rubrique);

        $outbounds = Outbound::where('rubrique_id', $request->rubrique)
        ->where('outbound_at', '>=', $startDate)
        ->where('outbound_at', '<', $endDate)
        ->get();

        $data = [];

        foreach($outbounds as $outbound){

            $products = OutboundProduct::where('outbound_id', $outbound->id)->get();
            
            foreach($products as $product){

                if(isset($data[$outbound->service_id][$product->id])){
                    $data[$outbound->service_id][$product->id]['qte'] += $product->qte;
                }else{
                    $data[$outbound->service_id]['service'] = Service::find($outbound->service_id)->toArray();
                    $data[$outbound->service_id]['products'][$product->id] = [
                        'product' => Product::find($product->product_id)->toArray(),
                        'qte' => $product->qte,
                        'unit_price_excl_tax' => $product->unit_price_excl_tax,
                        'unit_price_net' => $product->unit_price_net,
                    ];
                }

            }
        }
        
        
        return view('pages.costs.calculate')->with('rubrique', $rubrique)->with('data', $data)->with('date', $date);
    }
}
