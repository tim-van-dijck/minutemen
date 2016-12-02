@extends('layouts.app')

@section('content')
	@if (Auth::check())
		<div class="pull-right"><a class="btn btn-primary" href="{{ route('teams.create') }}"><i class="fa fa-plus"></i> new team</a></div>
	@endif

	<h1>Teams</h1>
	<div class="row teams">
		@forelse($teams as $i => $team)
			<div class="col-md-2 blocklink team">
				<a href="{{ route('teams.show', ['slug' => $team->slug]) }}">
					<div class="profile-img"><img src="{{ $team->emblem or 'img/emblem.png' }}" alt="{{ $team->name }}"></div>
					<p>{{$team->name}}</p>
				</a>
			</div>
			@if ($i != 0 && $i % 6 == 0)
				</div><div class="row">
			@endif
		@empty
			<p>There are no teams yet.</p>
		@endforelse
	</div>
@stop
