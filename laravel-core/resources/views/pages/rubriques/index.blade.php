@extends('layouts.main')
@section('subtitle', "Rubriques")
@section('content')
@php
$user = Auth::user();
@endphp
<div class="col-12 col-lg-12 col-xxl-12 d-flex">
  <div class="card flex-fill">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h5 class="card-title mb-0">Rubriques</h5>
      @if($user->Has_Permission('rubriques_create'))
      <button data-bs-toggle="modal" data-bs-target="#createRubrique" class="btn btn-primary" > Créer </button>
      @endif
    </div>
  </div>
</div>
<div class="col-12 col-lg-12 col-xxl-12 d-flex">
  <div class="card flex-fill">
    <div class="table-responsive">
      <table class="table table-hover my-0" id="datatables-orders">
        <thead>
          <tr>
            <th class="d-xl-table-cell">#</th>
            <th class="d-xl-table-cell">Rubrique</th>
            <th class="d-xl-table-cell">Products</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
            @foreach($rubriques as $rubrique)
            <tr>
                <td class="d-xl-table-cell">
                  <i class="align-middle me-2 fas fa-fw fa-hashtag"></i> {{$rubrique->id}}<br>
                  <i class="align-middle me-2 fas fa-fw fa-user"></i> {{$rubrique->createdBy->name}}<br>
                  <i class="align-middle me-2 fas fa-fw fa-at"></i> {{$rubrique->created_at}}<br>
                </td>
                <td>
                  <i class="align-middle me-2 fas fa-fw fa-align-center"></i> {{$rubrique->name}}<br>
                  <i class="align-middle me-2 fas fa-fw fa-envelope-open-text"></i> {{$rubrique->description}}<br>
                </td>
                <td>
                  @foreach($rubrique->products as $index=>$product)
                  @if($index < 3)
                  <i class="align-middle me-2 fas fa-fw fa-box"></i> {{$product->designation}}<br>
                  @endif
                  
                  @endforeach
                  @if(count($rubrique->products)>3)
                    + {{count($rubrique->products)-3}} produit non affiche
                  @endif
                </td>
                <td>
                  @if($user->Has_Permission('rubriques_edit'))
                  <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editRubrique{{$rubrique->id}}">
                    Modifier
                  </button>
                  @endif
                  @if($user->Has_Permission('rubriques_delete'))
                  <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteRubrique{{$rubrique->id}}">
                    Supprimer
                  </button>
                  @endif
                </td>
            </tr>
            @endforeach
        </tbody>
      </table>
    </div>
  {{ $rubriques->links('components.pagination') }}
  </div>
</div>
@if($user->Has_Permission('rubriques_create'))
<div class="modal fade" id="createRubrique" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form action="{{route('rubriques_create')}}" method="POST">
        @csrf
        <div class="modal-header">
            <h5 class="modal-title">Créer une rubrique</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body m-3">
          <div class="mb-3">
            <label class="form-label">Nom <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control" placeholder="Ex: D'asri" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Description </label>
            <textarea name="description" class="form-control"></textarea>
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
@if($user->Has_Permission('rubriques_edit'))
@foreach($rubriques as $rubrique)
<div class="modal fade" id="editRubrique{{$rubrique->id}}" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form action="{{route('rubriques_edit', $rubrique->id)}}" method="POST">
        @csrf
        @method('put')
        <div class="modal-header">
            <h5 class="modal-title">Modifier la rubrique {{$rubrique->name}}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body m-3">
          <div class="mb-3">
            <label class="form-label">Nom <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control" placeholder="Ex: D'asri" value='{{$rubrique->name}}' required>
          </div>
          <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control">{{$rubrique->description}}</textarea>
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
@endif
@if($user->Has_Permission('rubriques_delete'))
@foreach($rubriques as $rubrique)
<div class="modal fade" id="deleteRubrique{{$rubrique->id}}" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form action="{{route('rubriques_delete', $rubrique->id)}}" method="POST">
        @csrf
        @method('delete')
        <div class="modal-header">
            <h5 class="modal-title">Supprimer la rubrique {{$rubrique->name}}</h5>
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
@endif
@endsection