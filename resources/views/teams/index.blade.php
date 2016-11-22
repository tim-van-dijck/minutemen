@extends('layouts.app')

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-10 col-md-offset-1">
			@if (Auth::check())
				<div class="pull-right"><a class="btn btn-primary" href="{{ route('teams.create') }}"><i class="fa fa-plus"></i> new team</a></div>
			@endif

			<h1>Teams</h1>
			@forelse($teams as $team)
				<div class="col-md-2 team">
					<a href="{{ route('teams.show', ['slug' => $team->slug]) }}">
						<div class="profile-img"><img src="{{ $team->emblem or 'img/emblem.png' }}" alt="{{ $team->name }}"></div>
						<p>{{$team->name}}</p>
					</a>
				</div>
			@empty
				<p>There are no teams yet.</p>
			@endforelse
		</div>
	</div>
</div>
@stop
