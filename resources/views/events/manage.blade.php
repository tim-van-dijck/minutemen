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
								@if ($round->isCurrentRound())
									<a href="#" class="game-settle" data-toggle="modal" data-target="#settle-game"
									   data-action="{{ route('ajax.game.winner', ['game_id' => $game->id]) }}">
								@endif
								<div class="team_1">{{ $game->team1()->name }}</div> vs. <div class="team_2">{{ $game->team2()->name }}</div>
								@if ($round->isCurrentRound())
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
	@if ($event->type == 'round-robin' && count($event->rounds()) == 0)
		<div class="row">
			<div class="col-md-4 col-md-offset-4">
				<a href="{{ route('events.roundrobin', ['event_id' => $event->id]) }}" class="btn btn-primary">
					<i class="fa fa-plus"></i> add schedule
				</a>
			</div>
		</div>
	@elseif ($event->type == 'elimination')
		<div class="row">
			<div class="col-md-4 col-md-offset-4">
				<a href="#" class="btn btn-primary" data-toggle="modal" data-target="#add-round">
					<i class="fa fa-plus"></i> add round
				</a>
			</div>
		</div>

		@include('modals.add-round')
		@include('modals.set-winner')
	@endif
@stop

@section('js')
	<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
	<script src="js/forms.js"></script>
@stop