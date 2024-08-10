@extends('layouts.main')
@section('subtitle', "Produits")
@section('content')
@php
$user = Auth::user();
@endphp
<div class="row">
  <div class="col-12 col-lg-12 col-xxl-12 d-flex">
    <div class="card flex-fill">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">Produits</h5>
        @if($user->Has_Permission('products_create') && count($rubriques)>0)
        <button data-bs-toggle="modal" data-bs-target="#createProduct" class="btn btn-primary" > Créer </button>
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
                      <th class="d-xl-table-cell">Rubrique</th>
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
                            {{$product->rubrique->name}}
                          </td>
                          <td>
                            @if($user->Has_Permission('products_edit'))
                            <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editProduct{{$product->id}}">
                              Modifier
                            </button>
                            @endif
                            @if($user->Has_Permission('products_delete'))
                            <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteProduct{{$product->id}}">
                              Supprimer
                            </button>
                            @endif
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
@if($user->Has_Permission('products_create') && count($rubriques)>0)
<div class="modal fade" id="createProduct" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form action="{{route('products_create')}}" method="POST">
        @csrf
        <div class="modal-header">
            <h5 class="modal-title">Créer un produit</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body m-3">
          <div class="mb-3">
            <label class="form-label">Designation <span class="text-danger">*</span></label>
            <input type="text" name="designation" class="form-control" placeholder="Ex: Sachet D'asri 5L" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Rubrique <span class="text-danger">*</span></label>
            <select type="text" name="rubrique_id" class="form-control" required>
              <option value>Selectionner la rubrique</option>
              @foreach($rubriques as $rubrique)
              <option value='{{$rubrique->id}}'>{{$rubrique->name}}</option>
              @endforeach
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control"></textarea>
          </div>
          <div class="mb-3">
            <label class="form-label">Stock Initial <span class="text-danger">*</span></label>
            <input type="text" name="init_stock" class="form-control" placeholder="Ex: 26" value='0' required>
          </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary">Créer</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endif
@if($user->Has_Permission('products_create'))
@foreach($rubriques as $rubrique)
@foreach($rubrique->products as $product)
<div class="modal fade" id="editProduct{{$product->id}}" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form action="{{route('products_edit', $product)}}" method="POST">
        @csrf
        @method('put')
        <div class="modal-header">
            <h5 class="modal-title">Modifier le produit {{$product->designation}}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body m-3">
          <div class="mb-3">
            <label class="form-label">Designation <span class="text-danger">*</span></label>
            <input type="text" name="designation" class="form-control" placeholder="Ex: Sachet D'asri 5L" value='{{$product->designation}}' required>
          </div>
          <div class="mb-3">
            <label class="form-label">Rubrique <span class="text-danger">*</span></label>
            <select type="text" name="rubrique_id" class="form-control" required>
              <option value>Selectionner la rubrique</option>
              @foreach($rubriques as $rubrique)
              <option value='{{$rubrique->id}}' {{$product->rubrique_id==$rubrique->id?'selected':''}}>{{$rubrique->name}}</option>
              @endforeach
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control"></textarea>
          </div>
          <div class="mb-3">
            <label class="form-label">Stock Initial <span class="text-danger">*</span></label>
            <input type="text" name="init_stock" class="form-control" placeholder="Ex: 26" value='0' required>
          </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-warning">Modifier</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endforeach
@endforeach
@endif
@if($user->Has_Permission('products_delete'))
@foreach($rubriques as $rubrique)
@foreach($rubrique->products as $product)
<div class="modal fade" id="deleteProduct{{$product->id}}" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form action="{{route('products_delete', $product)}}" method="POST">
        @csrf
        @method('delete')
        <div class="modal-header">
            <h5 class="modal-title">Supprimer le produit {{$product->designation}}</h5>
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