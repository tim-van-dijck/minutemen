@extends('layouts.app')

@section('title', ($lobby->stealth) ? 'Stealthy' : $lobby->host->username.'\'s'.' lobby')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="col-md-12 lobby-view">
                <div class="row">
                    <div class="col-md-12">
                        <form class="delete" data-confirm="{{ (Auth::user()->id == $lobby->host->id) ? 'delete' : 'leave' }} this lobby"
                              action="{{ (Auth::user()->id == $lobby->host->id) ? route('lobbies.destroy', ['id' => $lobby->id]) : route('lobbies.leave', ['id' => $lobby->id]) }}" method="POST">
                            {{ csrf_field() }}
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="submit" class="btn btn-primary btn-small pull-right">
                                {{ (Auth::user()->id == $lobby->host->id) ? 'Delete' : 'Leave' }} lobby
                            </button>
                        </form>
                    </div>
                </div>
                @if ($lobby->stealth)
                    <i class="fa fa-2x fa-user-secret menu-icons"></i>
                @endif
                <h1>{{ ($lobby->stealth) ? 'Stealthy' : $lobby->host->username.'\'s' }} lobby</h1>
                @if ($lobby->stealth)
                    <p class="info">
                        A "stealthy" lobby means no one knows the identity of the other players.
                        It remains a surprise until you arrive and someone speaks the right passphrase or answer.
                    </p>
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
                        @if (isset($lobby->description) && $lobby->description != '')
                            <div class="row">
                                <div class="col-md-12">
                                    <h4>Description</h4>
                                    <i class="fa fa-info-circle pull-left accent"></i>
                                    <p class="description">
                                        {{ nl2br($lobby->description) }}
                                    </p>
                                </div>
                            </div>
                        @endif
                    </div>
                    @if (!$lobby->stealth)
                        <a class="pull-right accent" data-toggle="modal" data-target="#invite-players"><i class="fa fa-plus"></i> invite players</a>
                        <h4>Players</h4>
                        <div class="col-md-5">
                            <div class="row blocklink-wrapper players">
                                @foreach($lobby->players() as $player)
                                    <div class="col-md-12 blocklink">
                                        <a href="{{ route('users.show', ['slug' => $player->slug]) }}">
                                            <div class="profile-img"><img src="{{ $player->img or 'img/profile.png' }}" alt="{{ $player->username }}"></div>
                                            <p>{{$player->username}}</p>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
                @if ($lobby->stealth)
                    <div class="row">
                        <div class="col-md-12">
                            <h4>Passphrase</h4>
                            <p class="info">Used to identify the other players</p>
                            <p class="passphrase">{{ $lobby->passphrase }}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <h4>Answer</h4>
                            <p class="passphrase">{{ $lobby->answer }}</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
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
    <script src="js/delete-confirm.js"></script>
@stop

