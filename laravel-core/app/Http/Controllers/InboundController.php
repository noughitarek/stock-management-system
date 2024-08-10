<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Inbound;
use App\Models\Rubrique;
use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Models\InboundProduct;
use App\Http\Controllers\Controller;
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
        $rubriques = Rubrique::with(['createdBy', 'updatedBy', 'deletedBy', 'products'])
        ->whereNull('deleted_at')
        ->whereNull('deleted_by')
        ->orderBy('id', 'desc')
        ->get();

        foreach($rubriques as &$rubrique){
            
            $rubrique->inbounds = Inbound::with(['createdBy', 'updatedBy', 'deletedBy', 'inboundProducts.product', 'rubrique'])
            ->where('rubrique_id',  $rubrique->id)
            ->whereNull('deleted_at')
            ->whereNull('deleted_by')
            ->orderBy('id', 'desc')
            ->paginate(25)
            ->onEachSide(2);
        }

        return view('pages.inbounds.index')
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

        $suppliers = Supplier::whereNull('deleted_at')
        ->whereNull('deleted_by')
        ->orderBy('id', 'desc')
        ->get();

        return view('pages.inbounds.create')->with('rubriques', $rubriques)->with('suppliers', $suppliers);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreInboundRequest $request)
    {
        $inbound = Inbound::create([
            'inbound_at' => $request->input('inbound_at'),
            'rubrique_id' => $request->input('rubrique_id'),
            'commande_note_number' => $request->input('commande_note_number'),
            'delivery_note_number' => $request->input('delivery_note_number'),
            'invoice_number' => $request->input('invoice_number'),
            'supplier_id' => $request->input('supplier_id'),
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
        ]);

        return redirect()->route("inbounds_add_products", $inbound);
    }

    public function command_note(Inbound $inbound)
    {
        if($inbound->deleted_at != null || $inbound->deleted_by != null){
            abort(404);
        }
        $inbound = Inbound::with(['createdBy', 'updatedBy', 'deletedBy', 'inboundProducts.product', 'rubrique'])
        ->find($inbound->id);
        $products = '';
        $totalTTC = 0;
        $totalNET = 0;
        foreach($inbound->inboundProducts as $product){
            $products .= '<tr>';
            $products .= '<td>'.$product->id.'</td>';
            $products .= '<td>'.$product->product->designation.'</td>';
            $products .= '<td>'.$product->qte.'</td>';
            $products .= '<td>'.$product->unit_price_excl_tax.' DZD</td>';
            $products .= '<td>'.$product->unit_price_net.' DZD</td>';
            $products .= '<td>'.$product->total_amount_excl_tax.' DZD</td>';
            $totalTTC += $product->total_amount_excl_tax;
            $products .= '<td>'.$product->total_amount_net.' DZD</td>';
            $totalNET += $product->total_amount_net;
            $products .= '</tr>';
        }
        $products .= '<tr><td colspan="5"></td><td>'.$totalTTC.' DZD</td><td>'.$totalNET.' DZD</td></tr>';


        return ('<!doctypehtml><html lang=en><meta charset=UTF-8><meta content="width=device-width,initial-scale=1"name=viewport><title>Command Note</title><style>body{margin:0;padding:0;font-family:Arial,sans-serif}.a4{width:210mm;height:297mm;margin:auto;padding:20mm;box-shadow:0 0 10px rgba(0,0,0,.1);background-color:#fff;position:relative}header{text-align:center;margin-bottom:20px}h1{font-size:24px;margin:0}h2{font-size:18px;margin:0;color:#666}.section{margin-bottom:20px}.section-title{font-weight:700;font-size:16px}.section-content{margin-left:10px;font-size:14px}table{width:100%;border-collapse:collapse;margin-top:10px}td,th{border:1px solid #ddd;padding:8px;text-align:left}th{background-color:#f2f2f2;font-weight:700}footer{position:absolute;bottom:20mm;left:20mm;right:20mm;text-align:center;font-size:12px;color:#aaa}</style><div class=a4><header><h1>Bon de commande</h1><h2>Numero: '.$inbound->commande_note_number.'</h2></header><div class=section><div class=section-title>Rubrique:</div><div class=section-content>'.$inbound->rubrique->name.'</div></div><div class=section><div class=section-title>Fournisseur:</div><div class=section-content>'.$inbound->supplier->name.'</div><div class=section-content>'.$inbound->supplier->phone.'</div><div class=section-content>'.$inbound->supplier->address.'</div></div><div class=section><div class=section-title>Date de la creation:</div><div class=section-content>'.\Carbon\Carbon::parse($inbound->created_at)->format('d-m-Y').'</div></div><div class=section><div class=section-title>Date de livraison:</div><div class=section-content>'.\Carbon\Carbon::parse($inbound->inbound_at)->format('d-m-Y').'</div></div><div class=section><table><thead><tr><th>#<th>Designation<th>Quantite<th>Prix/Un HT<th>Prix/Un TTC<th>Montant HT<th>Montant TTC<tbody>'.$products.'</table></div><footer>© 2024 Command Notes. All rights reserved.</footer></div>');
    }
    public function delivery_note(Inbound $inbound)
    {
        if($inbound->deleted_at != null || $inbound->deleted_by != null){
            abort(404);
        }
        $inbound = Inbound::with(['createdBy', 'updatedBy', 'deletedBy', 'inboundProducts.product', 'rubrique'])
        ->find($inbound->id);
        $products = '';
        $totalTTC = 0;
        $totalNET = 0;
        foreach($inbound->inboundProducts as $product){
            $products .= '<tr>';
            $products .= '<td>'.$product->id.'</td>';
            $products .= '<td>'.$product->product->designation.'</td>';
            $products .= '<td>'.$product->qte.'</td>';
            $products .= '<td>'.$product->unit_price_excl_tax.' DZD</td>';
            $products .= '<td>'.$product->unit_price_net.' DZD</td>';
            $products .= '<td>'.$product->total_amount_excl_tax.' DZD</td>';
            $totalTTC += $product->total_amount_excl_tax;
            $products .= '<td>'.$product->total_amount_net.' DZD</td>';
            $totalNET += $product->total_amount_net;
            $products .= '</tr>';
        }
        $products .= '<tr><td colspan="5"></td><td>'.$totalTTC.' DZD</td><td>'.$totalNET.' DZD</td></tr>';


        return ('<!doctypehtml><html lang=en><meta charset=UTF-8><meta content="width=device-width,initial-scale=1"name=viewport><title>Command Note</title><style>body{margin:0;padding:0;font-family:Arial,sans-serif}.a4{width:210mm;height:297mm;margin:auto;padding:20mm;box-shadow:0 0 10px rgba(0,0,0,.1);background-color:#fff;position:relative}header{text-align:center;margin-bottom:20px}h1{font-size:24px;margin:0}h2{font-size:18px;margin:0;color:#666}.section{margin-bottom:20px}.section-title{font-weight:700;font-size:16px}.section-content{margin-left:10px;font-size:14px}table{width:100%;border-collapse:collapse;margin-top:10px}td,th{border:1px solid #ddd;padding:8px;text-align:left}th{background-color:#f2f2f2;font-weight:700}footer{position:absolute;bottom:20mm;left:20mm;right:20mm;text-align:center;font-size:12px;color:#aaa}</style><div class=a4><header><h1>Bon de commande</h1><h2>Numero: '.$inbound->commande_note_number.'</h2></header><div class=section><div class=section-title>Rubrique:</div><div class=section-content>'.$inbound->rubrique->name.'</div></div><div class=section><div class=section-title>Fournisseur:</div><div class=section-content>'.$inbound->supplier->name.'</div><div class=section-content>'.$inbound->supplier->phone.'</div><div class=section-content>'.$inbound->supplier->address.'</div></div><div class=section><div class=section-title>Date de la creation:</div><div class=section-content>'.\Carbon\Carbon::parse($inbound->created_at)->format('d-m-Y').'</div></div><div class=section><div class=section-title>Date de livraison:</div><div class=section-content>'.\Carbon\Carbon::parse($inbound->inbound_at)->format('d-m-Y').'</div></div><div class=section><table><thead><tr><th>#<th>Designation<th>Quantite<th>Prix/Un HT<th>Prix/Un TTC<th>Montant HT<th>Montant TTC<tbody>'.$products.'</table></div><footer>© 2024 Command Notes. All rights reserved.</footer></div>');
    }

    public function add_products(Inbound $inbound)
    {
        return view('pages.inbounds.addProducts')->with('inbound', $inbound);
    }

    public function store_products(Request $request, Inbound $inbound)
    {
        foreach($request->input('products') as $product){
            InboundProduct::create([
                'product_id' =>  $product['product_id'],
                'unit_price_excl_tax'  =>  $product['unit_price_excl_tax'],
                'unit_price_net' =>  $product['unit_price_net'],
                'qte' =>  $product['qte'],
                'total_amount_excl_tax' =>  $product['total_amount_excl_tax'],
                'total_amount_net' =>  $product['total_amount_net'],
                'inbound_id' => $inbound->id,
            ]);
        }
        return redirect()->route("inbounds")->with('success', 'Entee cree avec success');
    }

    public function inboundsRegister()
    {
        
        $rubriques = Rubrique::with(['createdBy', 'updatedBy', 'deletedBy', 'products'])
        ->whereNull('deleted_at')
        ->whereNull('deleted_by')
        ->orderBy('id', 'desc')
        ->get();

        return view('pages.inbounds.register')
        ->with('rubriques', $rubriques)
        ->with('activeRubrique', $rubriques->first()->id??0);
    }

    public function inboundsRegister_calculate(Request $request)
    {
        $startDate = Carbon::parse('01/01/'.$request->year);
        $endDate = $startDate->copy()->addYear();

        $rubrique = Rubrique::find($request->rubrique);
        $data = Inbound::with(['inboundProducts.product', 'rubrique'])
        ->where('rubrique_id', $request->rubrique)
        ->where('inbound_at', '>=', $startDate)
        ->where('inbound_at', '<', $endDate)
        ->get();
        return view('pages.inbounds.registershow')->with('rubrique', $rubrique)->with('data', $data)->with('date', $request->year);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Inbound $inbound)
    {
        $inbound->update([
            'deleted_by' => Auth::id(),
            'deleted_at' => now()
        ]);
        InboundProduct::where('inbound_id', $inbound->id)
        ->delete();
        return redirect()->route("inbounds")->with('success', 'Entee supprimee avec success');
    }
}
