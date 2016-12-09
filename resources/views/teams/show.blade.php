@extends('layouts.app')

@section('title', $team->name)
@section('content')
	@if($team->isAdmin())
		<a href="{{ route('teams.edit', ['slug' => $team->slug]) }}" class="btn btn-primary pull-right"><i class="fa fa-pencil"></i> edit</a>
	@else
		<a id="join" href="{{ ($team->isMember()) ? route('ajax.team.leave', ['team_id' => $team->id]) : route('ajax.team.join', ['team_id' => $team->id]) }}" class="btn btn-primary pull-right" data-href="{{ (!$team->isMember()) ? route('ajax.team.leave', ['team_id' => $team->id]) : route('ajax.team.join', ['team_id' => $team->id]) }}">{{ ($team->isMember()) ? 'Leave' : 'Join' }} team</a>
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
			@if(Auth::check() && $team->isAdmin() && !$team->requests()->isEmpty())
				<div class="requests">
					<h4>Requests</h4>
					<div class="col-md-12">
						<div class="row">
							@foreach ($team->requests() as $index => $request)
								<div class="col-md-2 blocklink user request">
									<a href="{{ route('users.show', ['slug' => $request->slug]) }}">
										<div class="profile-img"><img src="{{ $request->img or 'img/profile.png' }}" alt="{{ $request->username }}"></div>
										<p>{{$request->username}}</p>
									</a>
									<div class="accept-deny">
										<a class="add" href="{{ route('ajax.team.accept', ['team_id' => $team->id, 'user_id' => $request->id]) }}">
											<i class="fa fa-check-circle-o"></i>
										</a>
										<a class="delete" href="{{ route('ajax.team.deny', ['team_id' => $team->id, 'user_id' => $request->id]) }}">
											<i class="fa fa-remove"></i>
										</a>
									</div>
								</div>
								@if ($index != 0 && $index % 6 == 0)
									</div><div class="row">
								@endif
							@endforeach
						</div>
					</div>
					<h4>Members</h4>
				</div>
			@endif
			@forelse($team->members() as $member)
				<div class="row">
					<div class="col-md-12">
						<span class="title">{{ $member->username }}</span>
						<span class="period">{{ date('F Y', strtotime($member->joined)) }} - {{ (isset($member->left)) ? date('F Y', strtotime($member->left)) : 'present' }}</span>
					</div>
				</div>
			@empty
				<div class="row">
					<div class="col-md-8 col-md-offset-2">
						<p class="text-center">This team has no members yet.</p>
					</div>
				</div>
			@endforelse
		</div>
		<div class="col-md-7">
			<div class="stats row">
				<div class="col-md-4">
					<h4>Wins</h4>
					<p>{{ $team->wins or 0 }}</p>
				</div>
				<div class="col-md-4">
					<h4>Losses</h4>
					<p>{{ $team->losses or 0 }}</p>
				</div>
				<div class="col-md-4">
					<h4>Average Wins</h4>
					<p>{{ ($team->losses > 0) ? $team->wins/$team->losses : '0' }}</p>
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