@extends('layouts.app')

@section('title', 'Dashboard')
@section('content')
	<div class="row">
		<div class="col-md-12">
			<div class="col-md-12">
				<i class="fa fa-2x fa-dashboard menu-icons"></i>
				<h1>Hi, {{ (Auth::user()->firstname != null && Auth::user()->firstname != '') ? Auth::user()->firstname : Auth::user()->username }}</h1>
			</div>
		</div>
	</div>
	<div class="row dashboard">
		<div class="col-md-12">
			<div class="col-md-12">
				<div class="col-md-12">
					<div class="row">
						<div class="col-md-6 bigbtn participate">
							<a href="{{ route('teams.create') }}">
								<span class="title">I want to participate</span>
								<p>Create a team</p>
							</a>
						</div>
						<div class="col-md-6 bigbtn organise">
							<a href="{{ route('organisations.create') }}">
								<span class="title">I want to organise</span>
								<p>Create an organisation</p>
							</a>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12 bigbtn lobby">
							<a href="{{ route('lobbies.create') }}">
								<span class="title">{{ (Auth::user()->hasLobby()) ? 'Go to' : 'Create a' }} lobby</span>
								<p>start playing ASAP</p>
							</a>
						</div>
					</div>
				</div>
				@if (count($notifications) > 0)
					<div class="row">
						<div class="col-md-12">
							<h2>Notifications</h2>
							<div id="notifications">
								@foreach($notifications as $notification)
									<div class="row">
										<div class="col-md-12">
											<div class="col-md-12 post" data-notification-id="{{ $notification->id }}">
												<div class="content">
													<div class="row">
														<div class="col-md-8">{!! $notification->content !!}</div>
														@if ($notification->entity_name == 'lobby-invite')
															<div class="col-md-3">
																<div class="invite pull-right">
																	<div class="col-md-6"><a href="{{ route('lobby.accept-invite', ['lobby_id' => $notification->entity_id, 'notification_id' => $notification->id]) }}">accept</a></div>
																	<div class="col-md-6"><a href="{{ route('lobby.deny-invite', ['lobby_id' => $notification->entity_id, 'notification_id' => $notification->id]) }}">deny</a></div>
																</div>
															</div>
														@else
															<div class="col-md-3"><div class="footer"><div class="accept-deny"></div></div></div>
														@endif
														<div class="col-md-1"><a href="#" class="toggleSeen"><i class="fa fa-circle{{ ($notification->seen) ? '-o' : '' }}"></i></a></div>
													</div>
												</div>
											</div>
										</div>
									</div>
								@endforeach
							</div>
						</div>
					</div>
				@endif
				@if (count($events) > 0)
					<div class="row">
						<div class="col-md-12 events">
							<h2>Upcoming events near you</h2>
							@foreach ($events as $event)
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
							@endforeach
						</div>
					</div>
				@endif
				@if (count($popular) > 0)
					<div class="row">
						<div class="col-md-12">
							<h2>You might like</h2>
							<div class="row organisations blocklink-wrapper persist-cols">
								@foreach ($popular as $organisation)
									<div class="col-md-2 blocklink organisation">
										<a href="{{ route('organisations.show', ['id' => $organisation->id]) }}">
											<div class="profile-img">
												<img src="{{ $organisation->thumb or 'img/organisation.png' }}" alt="{{ $organisation->name }}">
											</div>
											<p class="name">{{ $organisation->name }}</p>
										</a>
									</div>
								@endforeach
							</div>
						</div>
					</div>
				@endif
				@if (count($feed) > 0)
					<div class="row">
						<div class="col-md-12">
							<h2>Newsfeed</h2>
							<div id="feed">
								@foreach($feed as $post)
									<div class="col-md-12 post">
										<div class="header">{{ $post->organisation->name }}</div>
										<div class="content">
											{!! $post->content !!}
										</div>
										<div class="footer">{{ $post->updated_at }}</div>
									</div>
								@endforeach
							</div>
							@if (count($feed) > 0 && $canExpand)
								<div id="feed-ext"></div>
								<div class="row">
									<div class="col-md-12"><a href="" class="btn load-feed btn-load" data-href="{{ route('ajax.feed.extend') }}">Load more</a></div>
								</div>
							@endif
						</div>
					</div>
				@endif
			</div>
		</div>
	</div>
	@if (Auth::user()->tutorial)
		@include('modals.tutorial')
	@endif
@stop
@section('js')
	@if (Auth::user()->tutorial)
		<script src="js/dashboard.js"></script>
	@endif
@stop
