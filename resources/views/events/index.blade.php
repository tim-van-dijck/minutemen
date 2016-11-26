@extends('layouts.app')

@section('content')
	@if (Auth::check())
		<div class="pull-right"><a class="btn btn-primary" href="{{ route('events.create') }}"><i class="fa fa-plus"></i> new event</a></div>
	@endif

	<h1>Events</h1>
	@forelse($events as $event)
		<div class="col-md-2 event">
			<a href="{{ route('events.show', ['id' => $event->id]) }}">
				<div class="profile-img"><img src="{{ $event->thumb or 'img/event.png' }}" alt="{{ $event->name }}"></div>
				<p>{{$event->name}}</p>
			</a>
		</div>
	@empty
		<p>There are no events yet.</p>
	@endforelse
@stop
