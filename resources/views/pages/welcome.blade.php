@extends('layouts.app')
@section('content')
	<h2>Events</h2>
	<div class="row">
		@forelse($events as $i => $event)
			<div class="col-md-4 blocklink">
				<a href="{{ route('events.show', ['id' => $event->id]) }}">
					<div class="banner"><img src="{{ $event->banner or 'img/event.png' }}" alt="{{ $event->name }}"></div>
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
@stop