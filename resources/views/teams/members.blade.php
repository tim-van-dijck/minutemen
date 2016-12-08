@extends('layouts.app')

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
		<div class="col-md-12">
			<h3>Members</h3>
			@if(!$team->requests()->isEmpty())
				<div class="requests">
					<h4>Requests</h4>
					<div class="col-md-12">
						<div class="row blocklink-wrapper">
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
				</div>
			@endif
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<h4>Admins</h4>
			<div class="blocklink-wrapper">
				@foreach($team->admins() as $index => $admin)
					<div class="col-md-2 blocklink user">
						<a href="{{ route('users.show', ['slug' => $admin->slug]) }}">
							<div class="profile-img">
								<img src="{{ $admin->img or 'img/profile.png' }}" alt="{{ $admin->username }}">
							</div>
							<p>{{$admin->username}}</p>
						</a>
					</div>
					@if ($index != 0 && $index % 6 == 0)
						</div><div class="row">
					@endif
				@endforeach
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<h4>Members</h4>
			<div class="blocklink-wrapper">
				@forelse($team->onlyMembers() as $index => $member)
					<div class="col-md-2 blocklink user">
						<a href="{{ route('users.show', ['slug' => $member->slug]) }}">
							<div class="profile-img">
								<img src="{{ $member->img or 'img/profile.png' }}" alt="{{ $member->username }}">
							</div>
							<p>{{$member->username}}</p>
						</a>
					</div>
					@if ($index != 0 && $index % 6 == 0)
						</div><div class="row">
					@endif
				@empty
					<div class="row">
						<div class="col-md-8 col-md-offset-2">
							<p class="text-center">This team has no members yet.</p>
						</div>
					</div>
				@endforelse
			</div>
		</div>
	</div>
@stop