@extends('layouts.app')

@section('title', $team->name)
@section('content')
    <div class="banner"><img src="{{ $team->emblem or 'img/event.png' }}" alt="{{ $team->name }}"></div>
    <h2>Invite users</h2>
    <div class="row">
        <div class="col-md-12">
            @if (!$requests->isEmpty())
                <h3>Join Requests</h3>
                <div class="row">
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
                        @if ($index != 0 && $index % 6 == 0)
                </div><div class="row">
                    @endif
                    @endforeach
                </div>
            @endif
            <h3>Looking for group</h3>
            <div class="row">
                @forelse($users as $index => $user)
                    <div class="col-md-2 blocklink user">
                        <a href="{{ route('users.show', ['slug' => $user->slug]) }}">
                            <div class="profile-img"><img src="{{ $user->img or 'img/profile.png' }}" alt="{{ $user->username }}"></div>
                            <p>{{$user->username}}</p>
                        </a>
                    </div>
                    @if ($index != 0 && $index % 6 == 0)
                        </div><div class="row">
                    @endif
                @empty
                    <p class="empty text-center">There are no players currently looking for a group.</p>
                @endforelse
            </div>
        </div>
    </div>
@stop

@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
    <script src="js/forms.js"></script>
@stop