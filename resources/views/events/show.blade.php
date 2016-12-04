@extends('layouts.app')

@section('content')
	<div class="banner"><img src="{{ $event->banner or 'img/event.png' }}" alt="{{ $event->username }}"></div>
	<h2>{{ $event->title }}</h2>
	<div class="row">
		<div class="col-md-9">
			<h4>Description</h4>
			{!! $event->description !!}
		</div>
		<div class="col-md-3">
			<h4>Participators</h4>
			@foreach($event->participators() as $team)
				<div class="col-md-3 team">
					<div class="profile-img"><img src="{{ $team->thumb or 'img/emblem.png' }}" alt="{{ $team->name }}" title="{{ $team->name }}"></div>
				</div>
			@endforeach
		</div>
	</div>
	<div class="row data">
		<div class="col-md-12">
			<h4>Data</h4>
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

		@if (Auth::check() && !$event->full())
			<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#enter-event">sign up your team</button>
			
			<div class="modal fade" id="enter-event" tabindex="-1" role="dialog">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
							<h4 class="modal-title text-center">Pick a team to sign up with</h4>
						</div>
						<div class="modal-body">
							<form id="enter-form" action="{{ route('events.enter', ['id' => $event->id]) }}" method="POST">
								{{ csrf_field() }}
								<select name="team" id="team">
									<option></option>
									@foreach ($myTeams as $team)
										<option value="{{ $team->id }}">{{ $team->name }}</option>
									@endforeach
								</select>
							</form>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
							<button type="submit" class="btn btn-primary" form="enter-form">Save changes</button>
						</div>
					</div>
				</div>
			</div>
		@endif
	</div>
@stop

@section('js')
	<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
	<script src="js/forms.js"></script>
	<script type="text/javascript">
		$(function() {
			$('#team').select2({
				placeholder: "Do something",
				allowClear: true
			});
		});
	</script>
@stop