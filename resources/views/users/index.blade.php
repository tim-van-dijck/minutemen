@extends('layouts.app')

@section('title', 'Users')
@section('content')
	<h1>Users</h1>
	<div class="row">
		@forelse($friends as $index => $friend)
			<div class="col-md-2 blocklink user">
				<a href="{{ route('users.show', ['slug' => $friend->slug]) }}">
					<div class="profile-img">
						<img src="{{ $friend->img or 'img/profile.png' }}" alt="{{ $friend->username }}">
					</div>
					<p>{{$friend->username}}</p>
				</a>
			</div>
			@if ($index != 0 && $index % 6 == 0)
				</div><div class="row">
			@endif
		@empty
			<div class="col-md-6 col-md-offset-3">
				<p>You have no friends yet.</p>
			</div>
		@endforelse
	</div>
	@if (count($user->friends() > 12))
@stop
