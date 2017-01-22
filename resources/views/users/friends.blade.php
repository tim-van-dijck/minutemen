@extends('layouts.app')

@section('title', 'Friends')
@section('content')
	<div class="row">
		<div class="col-md-12">
			<div class="col-md-12">
				<i class="fa fa-2x fa-users menu-icons"></i>
				@if (!$requests->isEmpty())
					<div class="row requests">
						<div class="col-md-12">
							<h2>Friend Requests</h2>
							<div class="row blocklink-wrapper">
								@foreach($requests as $index => $request)
									<div class="col-md-2 blocklink user request">
										<a href="{{ route('users.show', ['slug' => $request->slug]) }}">
											<div class="profile-img"><img src="{{ $request->img or 'img/profile.png' }}" alt="{{ $request->username }}"></div>
											<p>{{$request->username}}</p>
										</a>
										<div class="accept-deny">
											<a class="add" href="friends/{{$request->friendship_id}}/confirm">
												<i class="fa fa-check-circle-o"></i>
											</a>
											<a class="delete" href="friends/{{$request->friendship_id}}/delete">
												<i class="fa fa-remove"></i>
											</a>
										</div>
									</div>
									@if ($index != 0 && $index+1 % 6 == 0)
							</div><div class="row blocklink-wrapper">
								@endif
								@endforeach
							</div>
						</div>
					</div>
				@endif
				<h1>{{ ($user->id == Auth::user()->id) ? 'F' : $user->username.'\'s f' }}riends</h1>
				<div class="row blocklink-wrapper">
					@forelse($friends as $index => $friend)
						<div class="col-md-2 blocklink user">
							<div class="dropdown">
								<button class="btn btn-dropdown dropdown-toggle" type="button" data-toggle="dropdown">
									<span class="caret"></span>
								</button>
								<ul class="dropdown-menu">
									<li>
										<form class="delete" data-confirm="unfriend {{ $friend->username }}" action="{{ route('friendship.delete', ['friendship_id' => $friend->friendship_id]) }}" method="POST">
											{{ csrf_field() }}
											<input type="hidden" name="_method" value="DELETE">
											<button class="confirm" type="submit">Unfriend</button>
										</form>
									</li>
								</ul>
							</div>
							<a href="{{ route('users.show', ['slug' => $friend->slug]) }}">
								<div class="profile-img"><img src="{{ $friend->img or 'img/profile.png' }}" alt="{{ $friend->username }}"></div>
								<p>{{$friend->username}}</p>
							</a>
						</div>
						@if ($index != 0 && $index % 6 == 0)
				</div><div class="row blocklink-wrapper">
					@endif
					@empty
						<li>
							<p>You have no friends yet.</p>
							<div class="btn"><a href="">invite friends</a></div>
						</li>
					@endforelse
				</div>
			</div>
		</div>
	</div>
@stop
@section('js')
	<script src="js/delete-confirm.js"></script>
@stop