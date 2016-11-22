@extends('layouts.app')

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-10 col-md-offset-1">
			<h1>Users</h1>
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
		</div>
	</div>
@stop
