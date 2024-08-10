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
                      <th class="d-xl-table-cell">Init</th>
                      <th class="d-xl-table-cell">Entree</th>
                      <th class="d-xl-table-cell">Sortie</th>
                      <th class="d-xl-table-cell">Stock</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                      @foreach($rubrique->products as $product)
                      <tr>
                          <td class="d-xl-table-cell">
                            <i class="align-middle me-2 fas fa-fw fa-hashtag"></i> {{$product->id}}<br>
                            <i class="align-middle me-2 fas fa-fw fa-user"></i> {{$product->createdBy->name}}<br>
                            <i class="align-middle me-2 fas fa-fw fa-at"></i> {{$product->created_at}}<br>
                          </td>
                          <td>
                            <i class="align-middle me-2 fas fa-fw fa-align-center"></i> {{$product->designation}}<br>
                            <i class="align-middle me-2 fas fa-fw fa-envelope-open-text"></i> {{$product->description}}<br>
                          </td>
                          <td>
                            {{$product->init_stock}}
                          </td>
                          <td>
                            {{$product->total_inbounds()}}
                          </td>
                          <td>
                          {{$product->total_outbounds()}}
                          </td>
                          <td>
                          {{$product->stock()}}
                          </td>
                          <td>
                            <a target="_blank" href='{{route("stock_note", $product)}}'>Fiche de stock</a>
                          </td>
                      </tr>
                      @endforeach
                  </tbody>
                </table>
              </div>
              
              {{ $rubrique->products->links('components.pagination') }}
            </div>
          </div>
				</div>
			</div>
		</div>
    @endforeach
	</div>
</div>
@endsection