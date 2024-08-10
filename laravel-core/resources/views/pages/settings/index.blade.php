@extends('layouts.main')
@section('subtitle', "Settings")
@section('content')
@php
$user = Auth::user();
@endphp
<form action="{{route('settings_edit')}}" method="POST" enctype="multipart/form-data">
@csrf
<div class="row">
  <div class="col-12 col-lg-12 col-xxl-12 d-flex">
    <div class="card flex-fill">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">Settings</h5>
        @if($user->Has_Permission('users_edit'))
		<button type="submit" class="btn btn-primary">Save changes</button>
		@endif
      </div>
    </div>
  </div>
    <div class="col-md-3 col-xl-2">
        <div class="card">
			<div class="card-header">
				<h5 class="card-title mb-0">Settings</h5>
			</div>
			<div class="list-group list-group-flush" role="tablist">
				<a class="list-group-item list-group-item-action active" data-bs-toggle="list" href="#general" role="tab">
					General
				</a>
			</div>
		</div>
	</div>
	<div class="col-md-9 col-xl-10">
		<div class="tab-content">
			<div class="tab-pane fade show active" id="general" role="tabpanel">
				<div class="card">
					<div class="card-header">
						<h5 class="card-title mb-0">General</h5>
					</div>
					<div class="card-body">
						<div class="row mb-2">
							<div class="col-md-12">
								<div class="mb-3">
									<label class="form-label" for="id">ID</label>
									<input type="text" class="form-control" name="settings-id" id="settings-id" value="{{config('settings.id')}}">
								</div>
								<div class="mb-3">
									<label class="form-label" for="title">Title</label>
									<input type="text" class="form-control" name="settings-title" id="settings-title" value="{{config('settings.title')}}">
								</div>
								<div class="mb-3">
									<label class="form-label" for="title">Conversations limit</label>
									<input type="text" class="form-control" name="settings-limits-conversations" id="settings-limits-conversations" value="{{config('settings.limits.conversations')}}">
								</div>
								<div class="mb-3">
									<label class="form-label" for="title">Message per conversation limit</label>
									<input type="text" class="form-control" name="settings-limits-message_per_conversation" id="settings-limits-message_per_conversation" value="{{config('settings.limits.message_per_conversation')}}">
								</div>
								<div class="mb-3">
									<label class="form-label" for="title">Max simultaneous message</label>
									<input type="text" class="form-control" name="settings-limits-max_simultaneous_message" id="settings-limits-max_simultaneous_message" value="{{config('settings.limits.max_simultaneous_message')}}">
								</div>
								<div class="mb-3">
									<label class="form-label" for="title">Order conversations count</label>
									<input type="text" class="form-control" name="settings-limits-order_conversations_count" id="settings-limits-order_conversations_count" value="{{config('settings.limits.order_conversations_count')}}">
								</div>
							</div>
						</div>
                        @if($user->Has_Permission('settings_edit'))
						<button type="submit" class="btn btn-primary">Save changes</button>
						@endif
					</div>
				</div>
			</div>
		</div>
	</div>
</form>
@endsection