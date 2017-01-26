@extends('layouts.app')

@section('title', 'Teams')
@section('content')
	<div class="row">
		<div class="col-md-12">
			<div class="col-md-12">
				@if (Auth::check())
					<div class="pull-right"><a class="btn btn-primary" href="{{ route('teams.create') }}"><i class="fa fa-plus"></i> new team</a></div>
				@endif
				<h1>Teams</h1>
				<div class="row blocklink-wrapper teams">
					@forelse($teams as $i => $team)
						<div class="col-md-2 blocklink team">
							<a href="{{ route('teams.show', ['slug' => $team->slug]) }}">
								<div class="profile-img"><img src="{{ $team->emblem or 'img/emblem.png' }}" alt="{{ $team->name }}"></div>
								<p>{{$team->name}}</p>
							</a>
						</div>
						@if ($i != 0 && ($i+1) % 6 == 0)
				</div><div class="row blocklink-wrapper">
					@endif
					@empty
						<p>There are no teams yet.</p>
					@endforelse
				</div>
			</div>
		</div>
	</div>
@stop
