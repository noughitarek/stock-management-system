@extends('layouts.main')
@section('subtitle', "Couts")
@section('content')
@php
$user = Auth::user();
@endphp
<div class="row">
  <div class="col-12 col-lg-12 col-xxl-12 d-flex">
    <div class="card flex-fill">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">Couts</h5>
      </div>
    </div>
  </div>
</div>
<div class="col-md-12 col-xl-12">
	<div class="tab-content">
    <div class="card">
				<div class="card-header">
					<h5 class="card-title mb-0">Couts</h5>
				</div>
        <div class="card-body">
          <div class="modal-body m-3">
              <form method='post' action='{{route("costs_calculate")}}'>
                @csrf
                <div class="mb-3">
                    <label class="form-label">Mois <span class="text-danger">*</span></label>
                    <select name="month" class="form-control" required>
                      <option value>Selecctioner le mois</option>
                      <option value="1">Janvier</option>
                      <option value="2">Feverier</option>
                      <option value="3">Mars</option>
                      <option value="4">Avril</option>
                      <option value="5">Mai</option>
                      <option value="6">Juin</option>
                      <option value="7">Juillet</option>
                      <option value="8">Aout</option>
                      <option value="9">Septembre</option>
                      <option value="10">Octebre</option>
                      <option value="11">Novembre</option>
                      <option value="12">Decembre</option>
                    </select>
                </div>
                <div class="mb-3">
                  <label class="form-label">Annee <span class="text-danger">*</span></label>
                  <select name="year" class="form-control" required>
                    <option value>Selecctioner l'annee</option>
                    @for($i=2024; $i<2030;$i++)
                    <option value="{{$i}}">{{$i}}</option>
                    @endfor
                  </select>
                </div>
                <div class="mb-3">
                  <label class="form-label">Rubrique <span class="text-danger">*</span></label>
                  <select name="rubrique" class="form-control" required>
                    <option value>Selecctioner la rubrique</option>
                    @foreach($rubriques as $rubrique)
                    <option value="{{$rubrique->id}}">{{$rubrique->name}}</option>
                    @endforeach
                  </select>
                </div>
                <button type='submit' class='btn btn-primary'>Calculer</button>
            </form>
          </div>
			</div>
		</div>
	</div>
</div>
@endsection