@extends('layouts.app')

@section('title', $user->name)
@section('content')
	<div class="col-md-12">
		@if (Auth::check())
			@if ($user->id != Auth::user()->id)
				@if (!$user->isFriend(false))
					<a href="friends/{{$user->slug}}/add" class="btn btn-primary pull-right"><i class="fa fa-plus"></i> add friend</a>
				@elseif ($user->friendship()->confirmed)
					<a href="friends/{{$user->friendship()->id}}/delete" class="btn btn-primary pull-right">
						<i class="fa fa-user-times"></i> Unfriend
					</a>
				@elseif (!$user->friendship()->confirmed)
					<a href="friends/{{$user->friendship()->id}}/
					{{ ($user->friendship()->user_id == Auth::user()->id) ? 'delete' : 'confirm' }}"
					   class="btn btn-primary pull-right">
						<i class="fa fa-{{ ($user->friendship()->user_id == Auth::user()->id) ? 'ban' : 'plus' }}"></i>
						{{ ($user->friendship()->user_id == Auth::user()->id) ? 'Cancel' : 'Confirm' }} request
					</a>
				@endif
			@elseif ($user->id == Auth::user()->id)
				<a href="{{ route('settings') }}" class="btn btn-primary pull-right"><i class="fa fa-pencil"></i> edit profile</a>
			@endif
		@endif
		<h2>{{ $user->username }}</h2>
		<div class="row">
			<div class="col-md-6 text-center">
				<div class="profile-img profile">
					<img src="{{ $user->img or 'img/profile.png' }}" alt="{{ $user->username }}">
				</div>
				<p class="text-center">Joined {{ date('F jS Y', strtotime($user->created_at)) }}</p>
			</div>
			<div class="col-md-6">
				<div class="row">
					<div class="col-md-12">
						<h3>Teams ({{count($user->teams())}})</h3>
						@foreach ($user->teams() as $team)
							<div class="row blocklink-wrapper">
								<div class="col-md-4 blocklink">
									<a href="{{ route('teams.show', ['slug' => $team->slug]) }}">
										{{ $team->name }}
									</a>
								</div>
							</div>
						@endforeach
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<h3>Friends ({{ count($user->friends()) }})</h3>
						<div class="row blocklink-wrapper">
						@foreach ($user->friends(5) as $friend)
							<div class="col-md-2 blocklink">
								<a href="{{ route('users.show', ['slug' => $friend->slug]) }}">
									<div class="profile-img">
										<img src="{{ $friend->img or 'img/profile.png' }}" alt="{{ $friend->username }}" title="{{ $friend->username }}">
									</div>
								</a>
							</div>
						@endforeach
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@stop
