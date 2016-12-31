@extends('layouts.app')

@section('title', $team->name)
@section('content')
	@if (Auth::check() && $team->isAdmin())
		<a href="{{ route('teams.edit', ['slug' => $team->slug]) }}" class="btn btn-primary pull-right"><i class="fa fa-pencil"></i> edit</a>
	@elseif (Auth::check() && $team->isInvited())
		<a id="accept" href="{{ route('ajax.team.join', ['team_id' => $team->id]) }}" class="btn btn-primary pull-right">Accept invite</a>
	@else
		<a id="join" href="{{ (Auth::check() && $team->isMember()) ? route('ajax.team.leave', ['team_id' => $team->id]) : route('ajax.team.join', ['team_id' => $team->id]) }}"
		   class="btn btn-primary pull-right"
		   data-href="{{ (!$team->isMember()) ? route('ajax.team.leave', ['team_id' => $team->id]) : route('ajax.team.join', ['team_id' => $team->id]) }}">
				{{ ($team->isMember()) ? 'Leave' : 'Join' }} team
		</a>
	@endif
	<h2>{{ $team->name }} - [{{ $team->tag }}]</h2>
	<div class="row">
		<div class="col-md-5">
			<div class="profile-img">
				<img src="{{ $team->emblem or 'img/emblem.png' }}" alt="{{ $team->name }}">
			</div>
		</div>
		<div class="col-md-7">
			<div class="row">
				<div class="col-md-12">
					{!! $team->description !!}
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-5">
			<h3>Members</h3>
			<div class="row">
				@forelse($team->members() as $member)
					<div class="col-md-3 blocklink team">
						<div class="profile-img"><img src="{{ $member->thumb or 'img/emblem.png' }}" alt="{{ $member->username }}" title="{{ $member->username }}"></div>
					</div>
				@empty
					<div class="col-md-8 col-md-offset-2">
						<p class="text-center">This team has no members yet.</p>
					</div>
				@endforelse
			</div>
		</div>
		<div class="col-md-7">
			<div class="stats row">
				<div class="col-md-4">
					<h4>Wins</h4>
					<p>{{ $team->wins() }}</p>
				</div>
				<div class="col-md-4">
					<h4>Losses</h4>
					<p>{{ $team->losses() }}</p>
				</div>
				<div class="col-md-4">
					<h4>Average Wins</h4>
					<p>{{ ($team->losses() > 0) ? $team->wins()/$team->losses() : '0' }}</p>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<h3>Participations</h3>
		@forelse($team->participations() as $participation)
				<div class="row">
					<div class="col-md-12">
						<span class="title">{{ $participation->title }}</span>
						<span class="rank">{{ $participation->rank }}</span>
					</div>
				</div>
			@empty
				<div class="row">
					<div class="col-md-8 col-md-offset-2">
						<p class="text-center">This team hasn't participated in any events yet.</p>
					</div>
				</div>
			@endforelse
		</div>
	</div>
@stop