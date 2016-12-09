@extends('layouts.app')

@section('title', $event->title)
@section('content')
	<div class="banner"><img src="{{ $event->banner or 'img/event.png' }}" alt="{{ $event->title }}"></div>
	<h2>{{ $event->title }}</h2>
	@forelse($event->rounds() as $round)
		<div class="row">
			<div class="col-md-12">
				<h4>{{ $round->name }}</h4>
				<div class="row">
					<div class="col-md-1">
						<p><i class="fa fa-calendar accent"></i></p>
					</div>
					<div class="col-md-5">
						<p>{{ date("F dS H:i", strtotime($event->starts_at)) }} - {{ date("F dS H:i", strtotime($event->ends_at)) }}</p>
					</div>
				</div>
				<div class="row">
					<div class="col-md-1"><i class="fa fa-map-marker accent"></i></div>
					<div class="col-md-5"><p>{{ $event->street }} {{ $event->number }},<br>{{ $event->zip }} {{ $event->city }}</p></div>
				</div>
			</div>
		</div>
	@empty
		<div class="row">
			<div class="col-md-12">
				<p class="empty text-center">No rounds yet</p>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12"><a href="" class="btn btn-primary"><i class="fa fa-plus"></i> add round</a></div>
		</div>
	@endforelse
@stop

@section('js')
	<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
	<script src="js/forms.js"></script>
@stop