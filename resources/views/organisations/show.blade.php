@extends('layouts.app')

@section('content')
	<div class="banner"><img src="{{ $organisation->banner }}" alt="{{ $organisation->name}} banner"></div>
	<h2>
		{{ $organisation->name }}
		@foreach($organisation->admins as $admin)
			@if(Auth::user()->id == $admin->id)
				<a href="{{ route('organisations.edit', ['id' => $organisation->id]) }}" class="btn btn-edit"><i class="fa fa-pencil"> edit</i></a>
			@endif
		@endforeach
	</h2>
	<div class="row">
		<div class="col-md-5">
			<div class="profile-img">
				<img src="{{ $organisation->thumb or 'img/organisation.png' }}" alt="{{ $organisation->name }}">
			</div>
		</div>
		<div class="col-md-7">
			<div class="row">
				<div class="col-md-12">
					{{ $organisation->description }}
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<h3>Events</h3>
			@forelse($organisation->events as $event)
				<div class="row">
					<div class="col-md-12">
						<span class="title">{{ $event->title }}</span>
						<span class="period">{{ $event->starts_at }}</span>
					</div>
				</div>
			@empty
				<div class="row">
					<div class="col-md-8 col-md-offset-2">
						<p class="text-center">This organisation has no events yet.</p>
					</div>
				</div>
			@endforelse
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<h3>Administrators</h3>
			@foreach($organisation->admins as $admin)
				<div class="row">
					<div class="col-md-12">
						<span class="title">{{ $admin->username }}</span>
						<span class="period">since {{ date('F Y', strtotime($admin->joined)) }}</span>
					</div>
				</div>
			@endforeach
		</div>
	</div>
@stop