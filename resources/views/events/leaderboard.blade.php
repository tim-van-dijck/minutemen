@extends('layouts.app')

@section('title', $event->title)
@section('content')
	<div class="banner"><img src="{{ $event->banner or 'img/event.png' }}" alt="{{ $event->title }}"></div>
	<h2>Leaderboard - {{ $event->title }}</h2>
	<div class="row leaderboard">
		<div class="col-md-12">
			<table class="table">
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
				@foreach ($leaderboard as $index => $team)
					<tr>
						<td>{{ $index+1 }}</td>
						<td>{{ $team->name }}</td>
						<td>{{ $team->wins }}</td>
						<td>{{ $team->draws }}</td>
						<td>{{ $team->losses }}</td>
					</tr>
				@endforeach
				</tbody>
			</table>
		</div>
	</div>
@stop

@section('js')
	<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
	<script src="js/forms.js"></script>
@stop