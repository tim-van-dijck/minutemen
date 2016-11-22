@extends('layouts.app')
@section('content')
	<div class="container">
		<div class="row">
			<div class="col-md-6 col-md-offset-3">
				<h2>Events</h2>
				<div class="row">
					@forelse($events as $event)
						<div class="col-md-4">
							<h4>{{ $event->title }}</h4>
							<p>{{ date('d/m/Y', $event->starts_at) }}</p>
						</div>
					@empty
						<div class="col-md-12 text-center">
							There are no events available at this time
						</div>
					@endforelse
				</div>
			</div>
		</div>
	</div>
@stop