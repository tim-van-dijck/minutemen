@extends('layouts.app')

@section('title', $team->name)
@section('content')
	<div class="row">
		<div class="col-md-12">
			<div class="col-md-12">
				@if ($team->isAdmin())
					<i class="fa fa-2x fa-unlock-alt menu-icons" title="You can manage this page"></i>
				@endif
				@if (Auth::check() && $team->isAdmin())
					<a href="{{ route('teams.edit', ['slug' => $team->slug]) }}" class="btn btn-primary pull-right"><i class="fa fa-pencil"></i> edit</a>
				@elseif (Auth::check() && $team->isInvited())
					<a id="accept" href="{{ route('ajax.team.join', ['team_id' => $team->id]) }}" class="btn btn-primary pull-right">Accept invite</a>
				@else
					<form class="delete" data-confirm="leave this team" action="{{ route('team.leave', ['team_id' => $team->id]) }}" method="POST">
						{{ csrf_field() }}
						<input type="hidden" name="_method" value="DELETE">
						<button type="submit" class="btn btn-primary pull-right {{ (Auth::check() && $team->isMember()) ? '' : 'hidden' }}">Leave team</button>
					</form>
					<a id="join" href="{{ route('ajax.team.join', ['team_id' => $team->id]) }}"
					   class="btn btn-primary pull-right {{ (Auth::check() && $team->isMember()) ? 'hidden' : '' }}">Join team
					</a>
				@endif
			</div>
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
									@forelse($team->members(4) as $index => $member)
										<div class="col-md-3 {{ ($index == 0) ? 'col-md-offset-'.floor((12 - 3*count($team->members(4))) / 2) : '' }} blocklink team">
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
						<div class="events">
							@forelse($team->participations() as $event)
								<div class="row event">
									<div class="col-md-12">
										<div class="blocklink">
											<a href="{{ route('events.show', ['id' => $event->id]) }}">
												<div class="row">
													<div class="col-md-1">
														<p class="month">{{ strtoupper(date('M', strtotime($event->starts_at))) }}</p>
														<p class="day">{{ date('d', strtotime($event->starts_at)) }}</p>
													</div>
													<div class="col-md-6 banner-wrapper">
														<div class="banner">
															<img src="{{ $event->banner or 'img/event.png' }}" alt="{{ $event->title }}">
														</div>
													</div>
													<div class="col-md-5 data">
														<div class="row">
															<div class="col-md-10 col-md-offset-2">
																<h4>{{ $event->title }}</h4>
															</div>
														</div>
														<div class="row">
															<div class="col-md-2"><i class="fa fa-map-marker accent"></i></div>
															<div class="col-md-10">
																<p class="address">
																	{{ $event->street }} {{ $event->number }}<br>
																	{{ $event->zip }} {{ $event->city }}
																</p>
															</div>
														</div>
													</div>
												</div>
											</a>
										</div>
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
	</div>
@stop
@section('js')
	<script src="js/delete-confirm.js"></script>
@stop