@extends('layouts.app')

@section('title', $team->name)
@section('content')
	@if(Auth::check() && $team->isAdmin())
		<a href="{{ route('teams.edit', ['slug' => $team->slug]) }}" class="btn btn-primary pull-right"><i class="fa fa-pencil"></i> edit</a>
	@else
		<a id="join" href="{{ (Auth::check() && $team->isMember()) ? route('ajax.team.leave', ['team_id' => $team->id]) : route('ajax.team.join', ['team_id' => $team->id]) }}" class="btn btn-primary pull-right" data-href="{{ (Auth::guest() || !$team->isMember()) ? route('ajax.team.leave', ['team_id' => $team->id]) : route('ajax.team.join', ['team_id' => $team->id]) }}">{{ (Auth::check() && $team->isMember()) ? 'Leave' : 'Join' }} team</a>
	@endif
	<h2>{{ $team->name }} - [{{ $team->tag }}]</h2>
	<div class="row">
		<div class="col-md-12">
			<h3>Members</h3>

			@if (Auth::check() && $team->isAdmin())
			<div id="autocomplete" class="ui-widget">
				<label for="users">Users: </label>
				<input id="users" class="form-control">
			</div>
			@endif
			@if(!$team->requests()->isEmpty())
				<div class="requests">
					<h4>Requests</h4>
					<div class="col-md-12">
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
			@endif
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<h4>Admins</h4>
			<div class="blocklink-wrapper">
				@foreach($team->admins() as $index => $admin)
					<div class="col-md-2 blocklink user">
						<a href="{{ route('users.show', ['slug' => $admin->slug]) }}">
							<div class="profile-img">
								<img src="{{ $admin->img or 'img/profile.png' }}" alt="{{ $admin->username }}">
							</div>
							<p>{{$admin->username}}</p>
						</a>
					</div>
					@if ($index != 0 && $index % 6 == 0)
						</div><div class="row">
					@endif
				@endforeach
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<h4>Members</h4>
			<div class="blocklink-wrapper">
				@forelse($team->onlyMembers() as $index => $member)
					<div class="col-md-2 blocklink user">
						@if (Auth::check() && $team->isAdmin())
							<div class="dropdown">
								<button class="btn btn-dropdown dropdown-toggle" type="button" data-toggle="dropdown">
									<span class="caret"></span>
								</button>
								<ul class="dropdown-menu">
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
						</div><div class="row">
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

	@if (Auth::check() && $team->isAdmin())
		<div class="modal fade" id="kick" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
						<h4 class="modal-title" id="myModalLabel">Modal title</h4>
					</div>
					<div class="modal-body">
						<p>Please confirm that you want to kick this user by entering your password</p>
						<form id="kick-form" action="{{ route('ajax.team.kick', ['team_id' => $team->id]) }}" method="POST">
							{{  csrf_field() }}
							<input type="hidden" name="member_id" id="member_id">
							<input type="password" name="password" placeholder="********">
						</form>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
						<button type="submit" class="btn btn-primary" form="kick-form">Confirm</button>
					</div>
				</div>
			</div>
		</div>
	@endif
@stop

@section('js')
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	<script>
        $( function() {
            var teamId = {{ $team->id }}

            var autoComp = $("#users").autocomplete({
                source: "ajax/users/find/" + teamId,
                minLength: 2,
                select: function( event, ui ) {
                    $.post('ajax/teams/'+teamId+'/invite', {_token: Laravel.csrfToken, team_id: teamId});
                }
            });

            autoComp.insertAfter($("#autocomplete label"));

            $('.kick').click(function() {
                console.log($(this).data('id'))
                $('#member_id').val($(this).data('id'));
                console.log($('#member_id').val());
			});

            $('#kick-form').submit(function(e) {
                e.preventDefault();

                $.post($(this).attr('action'), $(this).serialize(), function(data) {
                    $('#kick').modal('toggle');
                    if (data.success != null) {
                        console.log(data.success);
                    } else { console.log(data.error); }
				});
			});
        });
	</script>
@stop