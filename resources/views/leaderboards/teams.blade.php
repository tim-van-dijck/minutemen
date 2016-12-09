@extends('layouts.app')

@section('title', 'Team leaderboard')
@section('content')
	<h2>Leaderboard: Teams</h2>
	<div class="row">
		<div class="col-md-12">
			<table class="table table-striped">
				<thead>
					<tr>
						<th>Rank</th>
						<th>Team</th>
						<th>Wins</th>
						<th>Draws</th>
						<th>Losses</th>
					</tr>
				</thead>
				<tbody>
					@forelse($teams as $i => $team)
						<tr>
							<td class="text-center">{{ $i }}</td>
							<td>{{ $team->name }}</td>
							<td class="text-center">{{ $team->wins }}</td>
							<td class="text-center">{{ $team->draws }}</td>
							<td class="text-center">{{ $team->losses }}</td>
						</tr>
					@empty
						<tr>
							<td class="empty" colspan="5">There's no leaderboard to be found</td>
						</tr>
					@endforelse
				</tbody>
			</table>
		</div>
	</div>
@stop