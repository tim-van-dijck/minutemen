@extends('layouts.app')

@section('title', $event->title)
@section('content')
	<div class="banner"><img src="{{ $event->banner or 'img/event.png' }}" alt="{{ $event->title }}"></div>
	<div class="row">
		<div class="col-md-12">
			<div class="col-md-12">
				<h2>{{ $event->title }}</h2>
				<p class="info">Click a game in the current round to set the outcome.</p>
				<div id="{{ $event->type }}" class="schedule">
					@if ($event->type == 'elimination')
						@include('partial.elimination')
					@else
						@include('partial.round-robin')
					@endif
				</div>
			</div>
		</div>
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
		@elseif ($event->type == 'elimination' && (count($event->competing()) > 1 || (count($event->competing()) != 1 && count($event->participators()) > 0)))
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