@extends('layouts.app')

@section('title', 'Home')
@section('content')
	<h2>Upcoming</h2>
	<div class="row blocklink-wrapper">
		@if (!$events->isEmpty())
			@for($i = 0; $i < count($events); $i++)
				<div class="col-md-3 blocklink">
					<a href="{{ route('events.show', ['id' => $events[$i]->id]) }}">
						<div class="banner"><img src="{{ $events[$i]->banner or 'img/event.png' }}" alt="{{ $events[$i]->title }}"></div>
						<h4 class="text-center">{{ $events[$i]->title }}</h4>
						<p class="text-center">{{ date("F dS H:i", strtotime($events[$i]->starts_at)) }}</p>
					</a>
				</div>
			@endfor
				<div class="col-md-3 blocklink">
					<a href="{{ route('events.index') }}">See more &raquo;</a>
				</div>
	@else
			<div class="col-md-12 text-center">
				There are no events available at this time
			</div>
		@endif
	</div>
	<h2>Popular Organisations</h2>
	<div class="row blocklink-wrapper">
		@forelse($organisations as $i => $organisation)
			<div class="col-md-3 {{ (count($organisations) < 4 && count($organisations) % 2 == 0) ? 'col-md-offset-'.floor((12-3*count($organisations))/2) : '' }} blocklink">
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