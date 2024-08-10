@extends('layouts.main')
@section('subtitle', "Fournisseurs")
@section('content')
@php
$user = Auth::user();
@endphp
<div class="col-12 col-lg-12 col-xxl-12 d-flex">
  <div class="card flex-fill">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h5 class="card-title mb-0">Fournisseurs</h5>
      @if($user->Has_Permission('suppliers_create'))
      <button data-bs-toggle="modal" data-bs-target="#createSupplier" class="btn btn-primary" > Créer </button>
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
            <th class="d-xl-table-cell">Fournisseur</th>
            <th class="d-xl-table-cell">Contact</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
            @foreach($suppliers as $supplier)
            <tr>
                <td class="d-xl-table-cell">
                  <i class="align-middle me-2 fas fa-fw fa-hashtag"></i> {{$supplier->id}}<br>
                  <i class="align-middle me-2 fas fa-fw fa-user"></i> {{$supplier->createdBy->name}}<br>
                  <i class="align-middle me-2 fas fa-fw fa-at"></i> {{$supplier->created_at}}<br>
                </td>
                <td>
                  <i class="align-middle me-2 fas fa-fw fa-align-center"></i> {{$supplier->name}}<br>
                  <i class="align-middle me-2 fas fa-fw fa-envelope-open-text"></i> {{$supplier->description}}<br>
                </td>
                <td>
                  <i class="align-middle me-2 fas fa-fw fa-align-center"></i> {{$supplier->phone}}<br>
                  <i class="align-middle me-2 fas fa-fw fa-envelope-open-text"></i> {{$supplier->address}}<br>
                </td>
                <td>
                  @if($user->Has_Permission('suppliers_edit'))
                  <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editSupplier{{$supplier->id}}">
                    Modifier
                  </button>
                  @endif
                  @if($user->Has_Permission('suppliers_delete'))
                  <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteSupplier{{$supplier->id}}">
                    Supprimer
                  </button>
                  @endif
                </td>
            </tr>
            @endforeach
        </tbody>
      </table>
    </div>
  {{ $suppliers->links('components.pagination') }}
  </div>
</div>
@if($user->Has_Permission('suppliers_create'))
<div class="modal fade" id="createSupplier" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form action="{{route('suppliers_create')}}" method="POST">
        @csrf
        <div class="modal-header">
            <h5 class="modal-title">Créer un fournisseur</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body m-3">
          <div class="mb-3">
            <label class="form-label">Nom <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control" placeholder="Ex: Hamza OMARI" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Telephone </label>
            <input type="text" name="phone" class="form-control" placeholder="Ex: 077..">
          </div>
          <div class="mb-3">
            <label class="form-label">Adresse </label>
            <input type="text" name="address" class="form-control" placeholder="Ex: Cite..">
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
@if($user->Has_Permission('suppliers_edit'))
@foreach($suppliers as $supplier)
<div class="modal fade" id="editSupplier{{$supplier->id}}" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form action="{{route('suppliers_edit', $supplier->id)}}" method="POST">
        @csrf
        @method('put')
        <div class="modal-header">
            <h5 class="modal-title">Modifier le fournisseur {{$supplier->name}}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body m-3">
          <div class="mb-3">
            <label class="form-label">Nom <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control" placeholder="Ex: Hamza OMARI" value='{{$supplier->name}}' required>
          </div>
          <div class="mb-3">
            <label class="form-label">Telephone </label>
            <input type="text" name="phone" class="form-control" placeholder="Ex: 077.."  value='{{$supplier->phone}}'>
          </div>
          <div class="mb-3">
            <label class="form-label">Adresse </label>
            <input type="text" name="address" class="form-control" placeholder="Ex: Cite.."  value='{{$supplier->address}}'>
          </div>
          <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control">{{$supplier->description}}</textarea>
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
@if($user->Has_Permission('suppliers_delete'))
@foreach($suppliers as $supplier)
<div class="modal fade" id="deleteSupplier{{$supplier->id}}" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form action="{{route('suppliers_delete', $supplier->id)}}" method="POST">
        @csrf
        @method('delete')
        <div class="modal-header">
            <h5 class="modal-title">Supprimer le fournisseur {{$supplier->name}}</h5>
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