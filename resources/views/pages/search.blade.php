@extends('layouts.app')

@section('content')
	<h1>Results for "{{ $query }}"</h1>
	<div class="users">
		<h2>Users</h2>
		<div class="row">
			@forelse ($results['users'] as $index => $user)
				<div class="col-md-2 user">
					<div class="profile-img">
						<img src="{{ $user->img or 'img/profile.png' }}" alt="{{ $user->username }}">
					</div>
					<p>{{ $user->username }}</p>
					@if (Auth::check() && !$user->isFriend)
						<div class="btn btn-friend"><a href="friends/{{$user->slug}}/add"><i class="fa fa-plus"></i> add friend</a></div>
					@endif
				</div>
				
				@if($index != 0 && $index % 6 == 0)
					</div><div class="row">
				@endif
			@empty
				<div class="col-md-12 text-center">
					<p>There were no users matching "{{ $query }}"</p>
				</div>
			@endforelse
		</div>
	</div>
	<div class="teams">
		<h2>Teams</h2>
		<div class="row">
			@forelse ($results['teams'] as $index => $team)
				<div class="col-md-2">
					<img src="{{ $team->emblem }}" alt="{{ $team->name }}">
					<p>{{ $team->name }}</p>
				</div>
				
				@if($index != 0 && $index % 6 == 0)
					</div><div class="row">
				@endif
			@empty
				<div class="col-md-12 text-center">
					<p>There were no teams matching "{{ $query }}"</p>
				</div>
			@endforelse
		</div>
	</div>
@stop