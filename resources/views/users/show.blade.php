@extends('layouts.app')

@section('title', $user->name)
@section('content')
	@if (!$user->isFriend() && $user->id != Auth::user()->id)
		<a href="friends/{{$user->slug}}/add" class="btn btn-primary pull-right"><i class="fa fa-plus"></i> add friend</a>
	@elseif ($user->id == Auth::user()->id)
		<a href="{{ route('settings') }}" class="btn btn-primary pull-right"><i class="fa fa-pencil"></i> edit profile</a>
	@endif
	<h2>{{ $user->username }}</h2>
	<div class="profile-img"><img src="{{ $user->img or 'img/profile.png' }}" alt="{{ $user->username }}"></div>
	<div class="stats row">
		<div class="col-md-4">
			<h4><i class="fa fa-crosshairs"></i> Kills</h4>
			<p>{{ $user->kills }}</p>
		</div>
		<div class="col-md-4">
			<h4>Deaths</h4>
			<p>{{ $user->deaths }}</p>
		</div>
		<div class="col-md-4">
			<h4>Kill/Death Ratio</h4>
			<p>{{ ($user->deaths > 0) ? $user->kills/$user->deaths : '0' }}</p>
		</div>
	</div>
@stop