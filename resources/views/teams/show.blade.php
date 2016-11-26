@extends('layouts.app')

@section('content')
	<h2>
		{{ $team->name }} - [{{ $team->tag }}]
		@if(in_array(Auth::user()->id, $team->admins))
			<a href="{{ route('teams.edit', ['slug' => $team->slug]) }}" class="btn btn-edit"><i class="fa fa-pencil"> edit</i></a>
		@endif
	</h2>
	<div class="row">
		<div class="col-md-5">
			<div class="profile-img">
				<img src="{{ $team->emblem or 'img/emblem.png' }}" alt="{{ $team->name }}">
			</div>
		</div>
		<div class="col-md-7">
			<div class="row">
				<div class="col-md-12">
					{{ $team->description }}
				</div>
			</div>
			<div class="stats row">
				<div class="col-md-4">
					<h4>Wins</h4>
					<p>{{ $team->wins or 0 }}</p>
				</div>
				<div class="col-md-4">
					<h4>Losses</h4>
					<p>{{ $team->losses or 0 }}</p>
				</div>
				<div class="col-md-4">
					<h4>Average Wins</h4>
					<p>{{ ($team->losses > 0) ? $team->wins/$team->losses : '0' }}</p>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<h3>Members</h3>
			@forelse($team->members as $member)
				<div class="row">
					<div class="col-md-12">
						<span class="title">{{ $member->username }}</span>
						<span class="period">{{ date('F Y', strtotime($member->joined)) }} - {{ (isset($member->left)) ? date('F Y', strtotime($member->left)) : 'present' }}</span>
					</div>
				</div>
			@empty
				<div class="row">
					<div class="col-md-8 col-md-offset-2">
						<p class="text-center">This team has no members yet.</p>
					</div>
				</div>
			@endforelse
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<h3>Participations</h3>
			@forelse($team->participations as $participation)
				<div class="row">
					<div class="col-md-12">
						<span class="title">{{ $participation->title }}</span>
						<span class="rank">{{ $participation->rank }}</span>
					</div>
				</div>
			@empty
				<div class="row">
					<div class="col-md-8 col-md-offset-2">
						<p class="text-center">This team hasn't participated in any events yet.</p>
					</div>
				</div>
			@endforelse
		</div>
	</div>
@stop