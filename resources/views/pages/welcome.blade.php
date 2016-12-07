@extends('layouts.app')
@section('content')
	<h2>Events</h2>
	<div class="row blocklink-wrapper">
		@forelse($events as $i => $event)
			<div class="col-md-4 blocklink">
				<a href="{{ route('events.show', ['id' => $event->id]) }}">
					<div class="banner"><img src="{{ $event->banner or 'img/event.png' }}" alt="{{ $event->title }}"></div>
					<h4 class="text-center">{{ $event->title }}</h4>
					<p class="text-center">{{ date("F dS H:i", strtotime($event->starts_at)) }}</p>
				</a>
			</div>
			@if ($i != 0 && $i % 3 == 0)
				</div><div class="row">
			@endif
		@empty
			<div class="col-md-12 text-center">
				There are no events available at this time
			</div>
		@endforelse
	</div>
	<h2>Popular Organisations</h2>
	<div class="row blocklink-wrapper">
		@forelse($organisations as $i => $organisation)
			<div class="col-md-{{ (count($organisations) < 6) ? floor(10/count($organisations)) : 2 }} blocklink">
				<a href="{{ route('organisations.show', ['id' => $organisation->id]) }}">
					<div class="profile-img"><img src="{{ $organisation->thumb or 'img/organisation.png' }}" alt="{{ $organisation->name }}"></div>
					<p class="text-center">{{ $organisation->name }}</p>
				</a>
			</div>
			@if ($i != 0 && $i % 3 == 0)
				</div><div class="row">
			@endif
		@empty
			<div class="col-md-12 text-center">
				There are no organisations available at this time
			</div>
		@endforelse
	</div>
@stop