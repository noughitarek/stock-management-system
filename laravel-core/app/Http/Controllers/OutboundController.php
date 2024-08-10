<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Inbound;
use App\Models\Service;
use App\Models\Outbound;
use App\Models\Rubrique;
use Illuminate\Http\Request;
use App\Models\InboundProduct;
use App\Models\OutboundProduct;
use App\Http\Controllers\Controller;
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
        $rubriques = Rubrique::with(['createdBy', 'updatedBy', 'deletedBy', 'products'])
        ->whereNull('deleted_at')
        ->whereNull('deleted_by')
        ->orderBy('id', 'desc')
        ->get();

        foreach($rubriques as &$rubrique){
            
            $rubrique->outbounds = Outbound::with(['createdBy', 'updatedBy', 'deletedBy', 'outboundProducts.product', 'rubrique', 'service'])
            ->where('rubrique_id',  $rubrique->id)
            ->whereNull('deleted_at')
            ->whereNull('deleted_by')
            ->orderBy('id', 'desc')
            ->paginate(25)
            ->onEachSide(2);
        }

        return view('pages.outbounds.index')
        ->with('rubriques', $rubriques)
        ->with('activeRubrique', $rubriques->first()->id??0);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $rubriques = Rubrique::with(['createdBy', 'updatedBy', 'deletedBy', 'products'])
        ->whereNull('deleted_at')
        ->whereNull('deleted_by')
        ->orderBy('id', 'desc')
        ->get();

        $services = Service::whereNull('deleted_at')
        ->whereNull('deleted_by')
        ->orderBy('id', 'desc')
        ->get();

        return view('pages.outbounds.create')->with('rubriques', $rubriques)->with('services', $services);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOutboundRequest $request)
    {
        $outbound = Outbound::create([
            'outbound_at' => $request->input('outbound_at'),
            'rubrique_id' => $request->input('rubrique_id'),
            'service_id' => $request->input('service_id'),
            'internal_delivery_note_number' => $request->input('internal_delivery_note_number'),
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
        ]);

        return redirect()->route("outbounds_add_products", $outbound);
    }

    public function exit_note(Outbound $outbound)
    {
        
        if($outbound->deleted_at != null || $outbound->deleted_by != null){
            abort(404);
        }
        $outbound = Outbound::with(['createdBy', 'updatedBy', 'deletedBy', 'outboundProducts.product', 'rubrique'])
        ->find($outbound->id);
        $products = '';
        foreach($outbound->outboundProducts as $product){
            $products .= '<tr>';
            $products .= '<td>'.$product->id.'</td>';
            $products .= '<td>'.$product->product->designation.'</td>';
            $products .= '<td>'.$product->qte.'</td>';
            $products .= '</tr>';
        }
        return ('<!doctypehtml><html lang=en><meta charset=UTF-8><meta content="width=device-width,initial-scale=1"name=viewport><title>Command Note</title><style>body{margin:0;padding:0;font-family:Arial,sans-serif}.a4{width:210mm;height:297mm;margin:auto;padding:20mm;box-shadow:0 0 10px rgba(0,0,0,.1);background-color:#fff;position:relative}header{text-align:center;margin-bottom:20px}h1{font-size:24px;margin:0}h2{font-size:18px;margin:0;color:#666}.section{margin-bottom:20px}.section-title{font-weight:700;font-size:16px}.section-content{margin-left:10px;font-size:14px}table{width:100%;border-collapse:collapse;margin-top:10px}td,th{border:1px solid #ddd;padding:8px;text-align:left}th{background-color:#f2f2f2;font-weight:700}footer{position:absolute;bottom:20mm;left:20mm;right:20mm;text-align:center;font-size:12px;color:#aaa}</style><div class=a4><header><h1>Bon de sortie</h1><h2>Numero: '.$outbound->internal_delivery_note_number.'</h2></header><div class=section><div class=section-title>Rubrique:</div><div class=section-content>'.$outbound->rubrique->name.'</div></div><div class=section><div class=section-title>Service:</div><div class=section-content>'.$outbound->service->name.'</div></div><div class=section><div class=section-title>Date de la creation:</div><div class=section-content>'.\Carbon\Carbon::parse($outbound->created_at)->format('d-m-Y').'</div></div><div class=section><div class=section-title>Date de livraison:</div><div class=section-content>'.\Carbon\Carbon::parse($outbound->outbound_at)->format('d-m-Y').'</div></div><div class=section><table><thead><tr><th>#<th>Designation<th>Quantite<tbody>'.$products.'</table></div><footer>© 2024 Command Notes. All rights reserved.</footer></div>');
    }

    public function add_products(Outbound $outbound)
    {
        return view('pages.outbounds.addProducts')->with('outbound', $outbound);
    }

    public function store_products(Request $request, Outbound $outbound)
    {
        foreach($request->input('products') as $product){
            $unit_price_excl_tax = InboundProduct::where('product_id', $product['product_id'])->orderBy('id', 'desc')->first()->unit_price_excl_tax;
            OutboundProduct::create([
                'product_id' =>  $product['product_id'],
                'unit_price_excl_tax'  =>  $unit_price_excl_tax,
                'unit_price_net' =>  $unit_price_excl_tax*1.19,
                'qte' =>  $product['qte'],
                'total_amount_excl_tax' => $unit_price_excl_tax*$product['qte'],
                'total_amount_net' =>  ($unit_price_excl_tax*$product['qte'])*1.19,
                'outbound_id' => $outbound->id,
            ]);
        }
        return redirect()->route("outbounds")->with('success', 'Sortie cree avec success');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Outbound $outbound)
    {
        $outbound->update([
            'deleted_by' => Auth::id(),
            'deleted_at' => now()
        ]);
        OutboundProduct::where('outbound_id', $outbound->id)
        ->delete();
        return redirect()->route("outbounds")->with('success', 'Sortie supprimee avec success');
    }

    public function outboundsRegister()
    {
        
        $rubriques = Rubrique::with(['createdBy', 'updatedBy', 'deletedBy', 'products'])
        ->whereNull('deleted_at')
        ->whereNull('deleted_by')
        ->orderBy('id', 'desc')
        ->get();

        return view('pages.outbounds.register')
        ->with('rubriques', $rubriques)
        ->with('activeRubrique', $rubriques->first()->id??0);
    }

    public function outboundsRegister_calculate(Request $request)
    {
        $startDate = Carbon::parse('01/01/'.$request->year);
        $endDate = $startDate->copy()->addYear();

        $rubrique = Rubrique::find($request->rubrique);
        $data = Outbound::with(['outboundProducts.product', 'service'])
        ->where('rubrique_id', $request->rubrique)
        ->where('outbound_at', '>=', $startDate)
        ->where('outbound_at', '<', $endDate)
        ->get();

        return view('pages.outbounds.registershow')->with('rubrique', $rubrique)->with('data', $data)->with('date', $request->year);
    }
}
