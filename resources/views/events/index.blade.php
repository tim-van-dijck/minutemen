@extends('layouts.app')

@section('title', 'Upcoming events')
@section('content')
	<h1>Upcoming events</h1>
	<div class="row blocklink-wrapper">
		@forelse($events as $i => $event)
			<div class="col-md-4 blocklink event">
				<a href="{{ route('events.show', ['id' => $event->id]) }}">
					<div class="banner"><img src="{{ $event->banner or 'img/event.png' }}" alt="{{ $event->title }}"></div>
					<h4 class="text-center">{{ $event->title }}</h4>
					<p class="period text-center">{{ date("F dS H:i", strtotime($event->starts_at)) }}</p>
				</a>
			</div>
			@if ($i !== 0 && $i+1 % 3 == 0)
				</div><div class="row blocklink-wrapper">
			@endif
		@empty
			<p>There are no events yet.</p>
		@endforelse
	</div>
@stop
