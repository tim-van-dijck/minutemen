@extends('layouts.app')

@section('title', 'Members - '.$team->name)
@section('content')
	<div class="row">
		<div class="col-md-12">
			<div id="users" class="col-md-12 team-members">
				<h2>{{ $team->name }} - [{{ $team->tag }}]</h2>
				<div class="row">
					<div class="col-md-12">
						<h3>Members</h3>
						@if (Auth::check() && $team->isAdmin())
							<h4>Invite users</h4>
							<form id="users-find-form" action="{{ route('ajax.team.invite.batch', ['team_id' => $team->id]) }}" method="POST">
								{{ csrf_field() }}
								<select name="invite[]" id="user-find" multiple="multiple"></select>
								<button class="btn btn-load" type="submit">Invite selected</button>
							</form>
						@endif
					</div>
				</div>

				@if(!$team->requests()->isEmpty())
					<div class="col-md-12">
						<div class="row requests">
							<div class="col-md-12">
								<h4>Requests</h4>
								<div class="row blocklink-wrapper">
									@foreach ($team->requests() as $index => $request)
										<div class="col-md-2 blocklink user request">
											<a href="{{ route('users.show', ['slug' => $request->slug]) }}">
												<div class="profile-img"><img src="{{ $request->img or 'img/profile.png' }}" alt="{{ $request->username }}"></div>
												<p>{{$request->username}}</p>
											</a>
											<div class="accept-deny">
												<a class="add" href="{{ route('ajax.team.accept', ['team_id' => $team->id, 'user_id' => $request->id]) }}">
													<i class="fa fa-check-circle-o"></i>
												</a>
												<a class="delete" href="{{ route('ajax.team.deny', ['team_id' => $team->id, 'user_id' => $request->id]) }}">
													<i class="fa fa-remove"></i>
												</a>
											</div>
										</div>
										@if ($index != 0 && $index % 6 == 0)
								</div><div class="row">
									@endif
									@endforeach
								</div>
							</div>
						</div>
					</div>
				@endif
				<div class="row">
					<div class="col-md-12 admins">
						<h4>Admins</h4>
						<div class="row blocklink-wrapper">
							@foreach($team->admins() as $index => $admin)
								<div class="col-md-2 blocklink user">
									@if (Auth::check() && $team->isAdmin() && $admin->id != Auth::user()->id)
										<div class="dropdown">
											<button class="btn btn-dropdown dropdown-toggle" type="button" data-toggle="dropdown">
												<span class="caret"></span>
											</button>
											<ul class="dropdown-menu">
												<li><a href="{{ route('ajax.team.admin.delete', ['team_id' => $team->id, 'user_id' => $admin->id]) }}"
													   data-href="{{ route('ajax.team.admin.make', ['team_id' => $team->id, 'user_id' => $admin->id]) }}"
													   class="ajax-button admin-delete" data-id="{{ $admin->id }}">
														Delete admin
													</a>
												</li>
												<li><a href="#" class="kick" data-toggle="modal" data-target="#kick" data-id="{{ $admin->id }}">Kick</a></li>
											</ul>
										</div>
									@endif
									<a href="{{ route('users.show', ['slug' => $admin->slug]) }}">
										<div class="profile-img">
											<img src="{{ $admin->img or 'img/profile.png' }}" alt="{{ $admin->username }}">
										</div>
										<p>{{$admin->username}}</p>
									</a>
								</div>
								@if ($index != 0 && $index % 6 == 0)
						</div><div class="row blocklink-wrapper">
							@endif
							@endforeach
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12 members">
						<h4>Members</h4>
						<div class="row blocklink-wrapper">
							@forelse($team->onlyMembers() as $index => $member)
								<div class="col-md-2 blocklink user">
									@if (Auth::check() && $team->isAdmin() && $member->id != Auth::user()->id)
										<div class="dropdown">
											<button class="btn btn-dropdown dropdown-toggle" type="button" data-toggle="dropdown">
												<span class="caret"></span>
											</button>
											<ul class="dropdown-menu">
												<li>
													<a href="{{ route('ajax.team.admin.make', ['team_id' => $team->id, 'user_id' => $member->id]) }}"
													   data-href="{{ route('ajax.team.admin.delete', ['team_id' => $team->id, 'user_id' => $member->id]) }}"
													   class="ajax-button admin-add" data-id="{{ $member->id }}">
														Make admin
													</a>
												</li>
												<li><a href="#" class="kick" data-toggle="modal" data-target="#kick" data-id="{{ $member->id }}">Kick</a></li>
											</ul>
										</div>
									@endif
									<a href="{{ route('users.show', ['slug' => $member->slug]) }}">
										<div class="profile-img">
											<img src="{{ $member->img or 'img/profile.png' }}" alt="{{ $member->username }}">
										</div>
										<p>{{$member->username}}</p>
									</a>
								</div>
								@if ($index != 0 && $index % 6 == 0)
									</div><div class="row blocklink-wrapper">
								@endif
							@empty
								<div class="row">
									<div class="col-md-8 col-md-offset-2">
										<p class="text-center">This team has no members yet.</p>
									</div>
								</div>
							@endforelse
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	@if (Auth::check() && $team->isAdmin())
		@include('modals.team-kick')
	@endif
@stop

@section('js')
	<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
	<script src="js/forms.js"></script>
	<script>
        var teamId = {{ $team->id }}
	</script>
	<script src="js/team-manage.js"></script>
@stop