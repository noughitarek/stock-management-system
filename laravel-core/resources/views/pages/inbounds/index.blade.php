@extends('layouts.main')
@section('subtitle', "Entrées")
@section('content')
@php
$user = Auth::user();
@endphp
<div class="row">
  <div class="col-12 col-lg-12 col-xxl-12 d-flex">
    <div class="card flex-fill">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">Entrées</h5>
        @if($user->Has_Permission('products_create') && count($rubriques)>0)
        <a href="{{route('inbounds_create')}}" class="btn btn-primary" > Créer </a>
        @endif
      </div>
    </div>
  </div>
  <div class="col-md-3 col-xl-2">
      <div class="card">
		<div class="card-header">
			<h5 class="card-title mb-0">Rubriques</h5>
		</div>
		<div class="list-group list-group-flush" role="tablist">
      @foreach($rubriques as $rubrique)
			<a class="list-group-item list-group-item-action {{$activeRubrique==$rubrique->id?'active':''}}" data-bs-toggle="list" href="#rubrique{{$rubrique->id}}" role="tab">
				{{$rubrique->name}}
			</a>
      @endforeach
		</div>
	</div>
</div>
<div class="col-md-9 col-xl-10">
	<div class="tab-content">
    @foreach($rubriques as $rubrique)
		<div class="tab-pane fade show {{$activeRubrique==$rubrique->id?'active':''}}" id="rubrique{{$rubrique->id}}" role="tabpanel">
			<div class="card">
				<div class="card-header">
					<h5 class="card-title mb-0">{{$rubrique->name}}</h5>
				</div>
				<div class="card-body">
          <div class="col-12 col-lg-12 col-xxl-12 d-flex">
            <div class="card flex-fill">
              <div class="table-responsive">
                <table class="table table-hover my-0" id="datatables-orders">
                  <thead>
                    <tr>
                      <th class="d-xl-table-cell">#</th>
                      <th class="d-xl-table-cell">Produit</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                      @foreach($rubrique->inbounds as $inbound)
                      <tr>
                          <td class="d-xl-table-cell">
                            <i class="align-middle me-2 fas fa-fw fa-hashtag"></i> {{$inbound->id}}<br>
                            <i class="align-middle me-2 fas fa-fw fa-user"></i> {{$inbound->createdBy->name}}<br>
                            <i class="align-middle me-2 fas fa-fw fa-at"></i> {{$inbound->created_at}}<br>
                          </td>
                          <td>
                            @foreach($inbound->inboundProducts as $product)
                            <i class="align-middle me-2 fas fa-fw fa-align-center"></i> {{$product->qte}} X {{$product->product->designation}}<br>
                            @endforeach
                          </td>
                          <td>
                          <a target="_blank" href='{{route("inbounds_command_note", $inbound)}}'>Bon de commande</a><br>
                          <a target="_blank" href='{{route("inbounds_delivery_note", $inbound)}}'>Bon de livraison</a>
                          </td>
                          <td>
                            @if($user->Has_Permission('inbounds_delete'))
                            <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteInbound{{$inbound->id}}">
                              Supprimer
                            </button>
                            @endif
                          </td>
                      </tr>
                      @endforeach
                  </tbody>
                </table>
              </div>
              
              {{ $rubrique->inbounds->links('components.pagination') }}
            </div>
          </div>
				</div>
			</div>
		</div>
    @endforeach
	</div>
</div>

@if($user->Has_Permission('inbounds_delete'))
@foreach($rubriques as $rubrique)
@foreach($rubrique->inbounds as $inbound)
<div class="modal fade" id="deleteInbound{{$inbound->id}}" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form action="{{route('inbounds_delete', $inbound)}}" method="POST">
        @csrf
        @method('delete')
        <div class="modal-header">
            <h5 class="modal-title">Supprimer l'entree {{$inbound->id}}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-danger">Supprimer</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endforeach
@endforeach
@endif
@endsection