@extends('layouts.app')

@section('title', $event->title)
@section('content')
	<div class="banner"><img src="{{ $event->banner or 'img/event.png' }}" alt="{{ $event->title }}"></div>
	<h2>{{ $event->title }}</h2>
	<div class="row rounds">
		@forelse($event->rounds() as $index => $round)
			<div class="col-md-4">
				<h4>{{ $round->name }}</h4>
				<div class="row">
					@foreach($round->games() as $game)
						<div class="col-md-12">
							@if (Auth::check() && $round->isCurrentRound() && $event->isAdmin())
								<a href="#" class="game-settle" data-toggle="modal" data-target="#settle-game"
								   data-action="{{ route('ajax.game.winner', ['game_id' => $game->id]) }}">
							@endif
							<div class="team_1">
								@if($game->draw == 0 && $game->team_1_won !== null)
									<i class="fa fa-{{ ($game->team_1_won) ? 'trophy' : 'crosshairs' }}"></i>
								@endif
								{{ $game->team1()->name }}
							</div>
							@if($game->draw == 1)
								<i class="fa fa-pause fa-rotate-90"></i>
							@else
								vs.
							@endif
							<div class="team_2">
								@if($game->draw == 0 && $game->team_1_won !== null)
									<i class="fa fa-{{ ($game->team_1_won) ? 'crosshairs' : 'trophy' }}"></i>
								@endif
								{{ $game->team2()->name }}
							</div>
							@if (Auth::check() && $round->isCurrentRound() && $event->isAdmin())
								</a>
							@endif
						</div>
					@endforeach
				</div>
			</div>
			@if (($index + 1) % 3 == 0)
				</div><div class="row rounds">
			@endif
		@empty
			<div class="col-md-12">
				<p class="empty text-center">No rounds yet</p>
			</div>
		@endforelse
	</div>
	@if (Auth::check() && $event->isAdmin())
		@if ($event->type == 'round-robin' && count($event->rounds()) == 0)
			<div class="row">
				<div class="col-md-4 col-md-offset-4">
					<a href="{{ route('events.roundrobin', ['event_id' => $event->id]) }}" class="btn btn-primary">
						<i class="fa fa-plus"></i> add schedule
					</a>
				</div>
			</div>
		@elseif ($event->type == 'elimination' && (count($event->competing()) > 1 || (count($event->rounds()) == 1 && count($event->participators()) > 0)))
			<div class="row">
				<div class="col-md-4 col-md-offset-4">
					<a href="#" class="btn btn-primary full-width" data-toggle="modal" data-target="#add-round">
						<i class="fa fa-plus"></i> add round
					</a>
				</div>
			</div>
			@include('modals.add-round')
			@include('modals.set-winner')
		@endif
	@endif
@stop

@section('js')
	<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
	<script src="js/forms.js"></script>
@stop