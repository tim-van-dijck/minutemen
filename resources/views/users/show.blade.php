@extends('layouts.app')

@section('title', $user->name)
@section('content')
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
	<div class="profile-img"><img src="{{ $user->img or 'img/profile.png' }}" alt="{{ $user->username }}"></div>
@stop
