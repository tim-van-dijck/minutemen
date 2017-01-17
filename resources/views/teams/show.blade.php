@extends('layouts.app')

@section('title', $team->name)
@section('content')
	<div class="row">
		<div class="col-md-12">
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
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="col-md-12 profile-wrapper">
				<h2 class="profile-title">{{ $team->name }} - [{{ $team->tag }}]</h2>
				<div class="row">
					<div class="col-md-12 text-center">
						<div class="profile-img profile">
							<img src="{{ $team->emblem or 'img/emblem.png' }}" alt="{{ $team->name }}">
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-8 col-md-offset-2 stats-col">
						<div class="stats row">
							<div class="col-md-4">
								<h4><i class="fa fa-trophy"></i><span>Wins</span></h4>
								<p>{{ $team->wins() }}</p>
							</div>
							<div class="col-md-4">
								<h4><i class="fa fa-crosshairs"></i><span>Losses</span></h4>
								<p>{{ $team->losses() }}</p>
							</div>
							<div class="col-md-4">
								<h4><span class="ratio"><i class="fa fa-trophy"></i>/<i class="fa fa-crosshairs"></i></span></h4>
								<p>{{ (($team->wins() + $team->losses()) > 0) ? number_format($team->wins()/($team->wins() + $team->losses()), 2) : '0' }}</p>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="row">
							<div class="col-md-12">
								<p class="description">{!! $team->description !!}</p>
							</div>
						</div>
					</div>
				</div>
				<div class="row divider">
					<div class="col-md-12">
						<h3 class="text-center">Members</h3>
						<div class="row">
							<div class="col-md-4 col-md-offset-4 members">
								<div class="row blocklink-wrapper">
									@forelse($team->members() as $index=> $member)
										<div class="col-md-3 {{ ($index == 0) ? 'col-md-offset-'.floor((12 - 3*count($team->members())) / 2) : '' }} blocklink team">
											<a href="{{ route('users.show', ['slug' => $member->slug]) }}">
												<div class="profile-img">
													<img src="{{ $member->img or 'img/profile.png' }}" alt="{{ $member->username }}" title="{{ $member->username }}">
												</div>
											</a>
										</div>
									@empty
										<div class="col-md-8 col-md-offset-2">
											<p class="text-center">This team has no members yet.</p>
										</div>
									@endforelse
								</div>
								<div class="row">
									<div class="col-md-12">
										<a href="{{ route('teams.members', ['slug' => $team->slug]) }}" class="btn btn-load">
											{{ (Auth::check() && Auth::user()->isAdmin()) ? 'Manage' : 'See all' }} members
										</a>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<h3 class="text-center">Participations</h3>
						@forelse($team->participations() as $participation)
							<div class="row">
								<div class="col-md-12">
									<a href="{{ route('events.leaderboard', ['event_id' => $participation->event_id]) }}#{{ $team->slug }}"><span class="title">{{ $participation->title }}</span></a>
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
			</div>
		</div>
	</div>
@stop