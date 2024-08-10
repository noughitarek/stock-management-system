@extends('layouts.main')
@section('subtitle', "Créer une entrée")
@section('content')
@php
$user = Auth::user();
@endphp
<form action="{{route('inbounds_create')}}" method="post">
    @csrf
    <div class="row">
        <div class="col-12 col-lg-12 col-xxl-12 d-flex">
            <div class="card flex-fill">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Créer une entrée</h5>
                    @if($user->Has_Permission('inbounds_create'))
                    <button type="submit" class="btn btn-primary" > Suivant </button>
                    @endif
                </div>
                <div class="card-body">
                    <div class="modal-body m-3">
                        <div class="mb-3">
                            <label class="form-label">Date <span class="text-danger">*</span></label>
                            <input type="datetime-local" name="inbound_at" class="form-control" value="{{ \Carbon\Carbon::now()->format('Y-m-d\TH:i') }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Rubrique <span class="text-danger">*</span></label>
                            <select name="rubrique_id" class="form-control" required>
                                <option value>Selectionner la rubrique</option>
                                @foreach($rubriques as $rubrique)
                                <option value="{{$rubrique->id}}">{{$rubrique->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Fournisseur <span class="text-danger">*</span></label>
                            <select name="supplier_id" class="form-control" required>
                                <option value>Selectionner le fournisseur</option>
                                @foreach($suppliers as $supplier)
                                <option value="{{$supplier->id}}">{{$supplier->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">N bon de commande</label>
                            <input type="text" name="commande_note_number" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">N bon de livraison</label>
                            <input type="text" name="delivery_note_number" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">N Facture</label>
                            <input type="text" name="invoice_number" class="form-control">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    @if($user->Has_Permission('inbounds_create'))
    <button type="submit" class="btn btn-primary" > Suivant </button>
    @endif
</form>
@endsection