@extends('layouts.main')
@section('subtitle', "Services")
@section('content')
@php
$user = Auth::user();
@endphp
<div class="col-12 col-lg-12 col-xxl-12 d-flex">
  <div class="card flex-fill">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h5 class="card-title mb-0">Services</h5>
      @if($user->Has_Permission('services_create'))
      <button data-bs-toggle="modal" data-bs-target="#createService" class="btn btn-primary" > Créer </button>
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
            <th class="d-xl-table-cell">Service</th>
            <th class="d-xl-table-cell">Responsable</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
            @foreach($services as $service)
            <tr>
                <td class="d-xl-table-cell">
                  <i class="align-middle me-2 fas fa-fw fa-hashtag"></i> {{$service->id}}<br>
                  <i class="align-middle me-2 fas fa-fw fa-user"></i> {{$service->createdBy->name}}<br>
                  <i class="align-middle me-2 fas fa-fw fa-at"></i> {{$service->created_at}}<br>
                </td>
                <td>
                  <i class="align-middle me-2 fas fa-fw fa-align-center"></i> {{$service->name}}<br>
                  <i class="align-middle me-2 fas fa-fw fa-envelope-open-text"></i> {{$service->description}}<br>
                </td>
                <td>
                  <i class="align-middle me-2 fas fa-fw fa-phone"></i> {{$service->responsible_name}}<br>
                  <i class="align-middle me-2 fas fa-fw fa-envelope-open-text"></i> {{$service->responsible_phone}}<br>
                </td>
                <td>
                  @if($user->Has_Permission('services_edit'))
                  <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editService{{$service->id}}">
                    Modifier
                  </button>
                  @endif
                  @if($user->Has_Permission('services_delete'))
                  <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteService{{$service->id}}">
                    Supprimer
                  </button>
                  @endif
                </td>
            </tr>
            @endforeach
        </tbody>
      </table>
    </div>
  {{ $services->links('components.pagination') }}
  </div>
</div>
@if($user->Has_Permission('services_create'))
<div class="modal fade" id="createService" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form action="{{route('services_create')}}" method="POST">
        @csrf
        <div class="modal-header">
            <h5 class="modal-title">Créer une service</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body m-3">
          <div class="mb-3">
            <label class="form-label">Nom <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control" placeholder="Ex: Geneco" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Nom du responsable</label>
            <input type="text" name="responsible_name" class="form-control" placeholder="Ex: Hamza OMARI">
          </div>
          <div class="mb-3">
            <label class="form-label">Telephone du responsable</label>
            <input type="text" name="responsible_phone" class="form-control" placeholder="Ex: 077..">
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
@if($user->Has_Permission('services_edit'))
@foreach($services as $service)
<div class="modal fade" id="editService{{$service->id}}" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form action="{{route('services_edit', $service->id)}}" method="POST">
        @csrf
        @method('put')
        <div class="modal-header">
            <h5 class="modal-title">Modifier la service {{$service->name}}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body m-3">
          <div class="mb-3">
            <label class="form-label">Nom <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control" placeholder="Ex: Geneco" value='{{$service->name}}' required>
          </div>
          <div class="mb-3">
            <label class="form-label">Nom du responsable</label>
            <input type="text" name="responsible_name" class="form-control" placeholder="Ex: Hamza OMARI" value='{{$service->responsible_name}}'>
          </div>
          <div class="mb-3">
            <label class="form-label">Telephone du responsable</label>
            <input type="text" name="responsible_phone" class="form-control" placeholder="Ex: 077.." value='{{$service->responsible_phone}}'>
          </div>
          <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control">{{$service->description}}</textarea>
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
@if($user->Has_Permission('services_delete'))
@foreach($services as $service)
<div class="modal fade" id="deleteService{{$service->id}}" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form action="{{route('services_delete', $service->id)}}" method="POST">
        @csrf
        @method('delete')
        <div class="modal-header">
            <h5 class="modal-title">Supprimer la service {{$service->name}}</h5>
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