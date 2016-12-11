@extends('layouts.app')

@section('title', $event->title)
@section('content')
	<div class="banner"><img src="{{ $event->banner or 'img/event.png' }}" alt="{{ $event->title }}"></div>
	<h2>Leaderboard - {{ $event->title }}</h2>
	<div class="row leaderboard">
		<table>
			<thead>
				<tr>
					<th>Rank</th>
					<th>Team name</th>
					<th>Wins</th>
					<th>Draws</th>
					<th>Losses</th>
				</tr>
			</thead>
			<tbody>
				@foreach($leaderboard as $index => $team)
					<tr>
						<td>{{ $index+1 }}</td>
						<td>{{ $team->name }}</td>
						<td>{{ $team->wins }}</td>
						<td>{{ $teams->draws }}</td>
						<td>{{ $teams->losses }}</td>
					</tr>
				@endforeach
			</tbody>
		</table>
	</div>
	<div class="row">
		<div class="col-md-4 col-md-offset-4"><a href="{{ route('events.roundrobin', ['event_id' => $event->id]) }}" class="btn btn-primary"><i class="fa fa-plus"></i> add round</a></div>
	</div>
@stop

@section('js')
	<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
	<script src="js/forms.js"></script>
@stop