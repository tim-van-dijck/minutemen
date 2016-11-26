@extends('layouts.app')

@section('content')
	@if (!$requests->isEmpty())
		<h2>Friend Requests</h2>
		<div class="row">
			@foreach($requests as $index => $request)
				<div class="col-md-2 user">
					<a href="{{ route('users.show', ['slug' => $request->slug]) }}">
						<div class="profile-img"><img src="{{ $request->img or 'img/profile.png' }}" alt="{{ $request->username }}"></div>
						<p>{{$request->username}}</p>
					</a>
					<div class="row">
						<div class="col-md-6">
							<a href="friends/{{$request->friendship_id}}/confirm"><i class="fa fa-check-circle-o"></i></a>
						</div>
						<div class="col-md-6">
							<a href="friends/{{$request->friendship_id}}/delete"><i class="fa fa-remove"></i></a>
						</div>
					</div>
				</div>
				@if ($index != 0 && $index % 6 == 0)
					</div><div class="row">
				@endif
			@endforeach
		</div>
	@endif
	<h1>Friends</h1>
		<div class="row">
			@forelse($friends as $index => $friend)
				<div class="col-md-2 user">
					<a href="{{ route('users.show', ['slug' => $friend->slug]) }}">
						<div class="profile-img"><img src="{{ $friend->img or 'img/profile.png' }}" alt="{{ $friend->username }}"></div>
						<p>{{$friend->username}}</p>
					</a>
				</div>
				@if ($index != 0 && $index % 6 == 0)
					</div><div class="row">
				@endif
			@empty
				<li>
					<p>You have no friends yet.</p>
					<div class="btn"><a href="">invite friends</a></div>
				</li>
			@endforelse
		</div>
	</ul>
@stop