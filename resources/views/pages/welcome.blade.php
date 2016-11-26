@extends('layouts.app')
@section('content')
	<h2>Events</h2>
	<div class="row">
		@forelse($events as $i => $event)
			<div class="col-md-4">
				<h4>{{ $event->title }}</h4>
				<p>{{ date('d/m/Y', $event->starts_at) }}</p>
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