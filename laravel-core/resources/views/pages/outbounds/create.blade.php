@extends('layouts.main')
@section('subtitle', "Créer une sortie")
@section('content')
@php
$user = Auth::user();
@endphp
<form action="{{route('outbounds_create')}}" method="post">
    @csrf
    <div class="row">
        <div class="col-12 col-lg-12 col-xxl-12 d-flex">
            <div class="card flex-fill">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Créer une sortie</h5>
                    @if($user->Has_Permission('outbounds_create'))
                    <button type="submit" class="btn btn-primary" > Suivant </button>
                    @endif
                </div>
                <div class="card-body">
                    <div class="modal-body m-3">
                        <div class="mb-3">
                            <label class="form-label">Date <span class="text-danger">*</span></label>
                            <input type="datetime-local" name="outbound_at" class="form-control" value="{{ \Carbon\Carbon::now()->format('Y-m-d\TH:i') }}" required>
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
                            <label class="form-label">Service <span class="text-danger">*</span></label>
                            <select name="service_id" class="form-control" required>
                                <option value>Selectionner le service</option>
                                @foreach($services as $service)
                                <option value="{{$service->id}}">{{$service->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">N bon de sortie</label>
                            <input type="text" name="internal_delivery_note_number" class="form-control">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    @if($user->Has_Permission('outbounds_create'))
    <button type="submit" class="btn btn-primary" > Suivant </button>
    @endif
</form>
@endsection