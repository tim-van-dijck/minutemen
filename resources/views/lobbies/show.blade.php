@extends('layouts.app')

@section('title', ($lobby->stealth) ? 'Stealthy' : $lobby->host->username.'\'s'.' lobby')
@section('content')
    <form action="{{ (Auth::user()->id == $lobby->host->id) ?
        route('lobbies.destroy', ['id' => $lobby->id]) : route('lobbies.leave', ['id' => $lobby->id]) }}" method="POST">
        {{ csrf_field() }}
        <input type="hidden" name="_method" value="DELETE">
        <button type="submit" class="btn btn-primary pull-right">
            {{ (Auth::user()->id == $lobby->host->id) ? 'Delete' : 'Leave' }} lobby
        </button>
    </form>
    <h1>{{ ($lobby->stealth) ? 'Stealthy' : $lobby->host->username.'\'s' }} lobby</h1>
    @if ($lobby->stealth)
        <p><em>
            A "stealthy" lobby means no one knows the identity of the other players.
            It remains a surprise until you arrive and someone speaks the right passphrase or answer.
        </em></p>
    @endif

    <div class="row">
        <div class="col-md-12">
            <h4>Players in lobby</h4>
            <div class="progress">
                <div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: {{ ($lobby->playerCount() / $lobby->size) * 100 }}%;">
                    <span class="sr-only">{{ ($lobby->playerCount() / $lobby->size) * 100 }}% full</span>
                    <span class="ratio">{{ $lobby->playerCount().'/'.$lobby->size }}</span>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-7">
            <h3>@ {{ $lobby->location_name }}</h3>
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-1"><i class="fa fa-map-marker fa-fw accent"></i></div>
                        <div class="col-md-11"><p>{{ $lobby->address }}</p></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-1">
                            <p><i class="fa fa-clock-o fa-fw accent"></i></p>
                        </div>
                        <div class="col-md-11">
                            <p>{{ date("H:i", strtotime($lobby->meet_at)) }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @if (!$lobby->stealth)
            <a class="pull-right" data-toggle="modal" data-target="#invite-players"><i class="fa fa-plus"></i> invite players</a>
            <h4>Players</h4>
            <div class="col-md-5 players">
                @foreach($lobby->players() as $player)
                    <div class="row blocklink-wrapper">
                        <div class="col-md-12 blocklink">
                            <a href="{{ route('users.show', ['slug' => $player->slug]) }}">
                                <div class="col-md-3">
                                    <div class="profile-img">
                                        <img src="{{ $player->img or 'img/profile.png' }}" alt="{{ $player->username }}">
                                    </div>
                                </div>
                                <div class="col-md-9">
                                    <h5>{{ $player->username }}{{ ($player->id === $lobby->host->id) ? ' (HOST)' : '' }}</h5>
                                </div>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
    @if ($lobby->stealth)
        <div class="row">
            <div class="col-md-12">
                <h4>Passphrase</h4>
                <p><em>Used to identify the other players</em></p>
                <p>{{ $lobby->passphrase }}</p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <h4>Answer</h4>
                <p>{{ $lobby->answer }}</p>
            </div>
        </div>
    @endif
    @include('modals.invite-players')
@stop

@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
    <script src="js/forms.js"></script>
    <script>
        var playerCount = parseInt({{ $lobby->playerCount() }});
        var lobbyId = parseInt({{ $lobby->id }});
        var hostId = parseInt({{ $lobby->host->id }});
        var size = parseInt({{ $lobby->size }});
    </script>
    <script src="js/lobbies.js"></script>
@stop
