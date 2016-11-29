@extends('layouts.app')

@section('content')
	<!-- <div class="banner"><img src="{{ $organisation->banner }}" alt="{{ $organisation->name}} banner"></div> -->
	@foreach($organisation->admins as $admin)
		@if(Auth::user()->id == $admin->id)
			<a href="{{ route('organisations.edit', ['id' => $organisation->id]) }}" class="btn btn-primary pull-right"><i class="fa fa-pencil"></i> edit</a>
		@endif
	@endforeach
	<h2>
		@if ($organisation->trusted)
			<img id="trusted" src="img/trusted.svg" alt="Trusted Organisation" title="Trusted Organisation">
		@endif
		{{ $organisation->name }}
	</h2>
	<div class="row">
		<div class="col-md-5">
			<div class="profile-img">
				<img src="{{ $organisation->thumb or 'img/organisation.png' }}" alt="{{ $organisation->name }}">
			</div>
		</div>
		<div class="col-md-7">
			<h5>About {{ $organisation->name }}</h5>
			{!! $organisation->description !!}
		</div>
	</div>
	<div class="row">
		<div class="col-md-5">
			<div class="row">
				<div class="col-md-12">
					@if (!$organisation->events->isEmpty())
						<h3>Events</h3>
						@foreach($organisation->events as $event)
							<div class="row">
								<div class="col-md-12">
									<span class="title">{{ $event->title }}</span>
									<span class="period">{{ $event->starts_at }}</span>
								</div>
							</div>
						@endforeach
					@endif
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
		</div>
		<div class="col-md-7">
			@foreach($organisation->admins as $admin)
				@if(Auth::user()->id == $admin->id)
					<div class="row">
						<div class="col-md-12">
							<form id="post-form" action="{{ route('ajax.organisations.post', ['id' => $organisation->id]) }}" method="POST">
								{{ csrf_field() }}
								<textarea name="post" class="form-control" required></textarea>
								<button type="submit" class="btn btn-primary pull-right">Post</button>
							</form>
						</div>
					</div>
				@endif
			@endforeach
			<div id="feed" data-id="{{ $organisation->id }}">
				@forelse($organisation->posts as $post)
					<div class="col-md-12 post">
						{{ $post->content }}
					</div>
				@empty
					<div class="col-md-12">
						<p class="text-center">No posts yet.</p>
					</div>
				@endforelse
			</div>
		</div>
	</div>
@stop
@section('js')
	<script src="js/forms.js"></script>
@stop