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
@stop

@section('js')
    <script src="js/ckeditor/ckeditor.js"></script>
    <script src="js/forms.js"></script>
    <script>
        var playerCount = parseInt({{ $lobby->playerCount() }});
        var lobbyId = parseInt({{ $lobby->id }});
        var hostId = parseInt({{ $lobby->host->id }});
        var size = parseInt({{ $lobby->size }});

        $(function() {
            $('input[name="stealth"]').change(function() {
                if ($(this).val() == 1) { $('.stealth-mode').slideDown(); }
                else { $('.stealth-mode').slideUp(); }
            });

            getPlayers();
            setInterval(function() { getPlayers(); }, 10000);
        });

        function getPlayers() {

            $.getJSON('ajax/lobby/'+lobbyId+'/player-count', function (data) {
                if (data.error == 'deleted') {
                    swal(
                        {
                            title: "Whoops, the lobby's gone!",
                            text: "Seems the host deleted this lobby",
                            type: "warning"
                        }, function() {
                            window.location.replace(base_url+"dashboard");
                        }
                    );
                }
                else if (data.count != playerCount) {
                    playerCount = data.count;
                    $.getJSON('ajax/lobby/'+lobbyId+'/get-players', function (data) {
                        $('.players').empty();
                        $.each(data, function(i,v) {
                            var img;

                            if (v.img != null) { img = v.img; }
                            else { img = 'img/profile.png'; }

                            var host = hostId == v.id

                            $player =   '<div class="row blocklink-wrapper">'+
                                        '<div class="col-md-12 blocklink"><a href="users/'+v.slug+'">'+
                                        '<div class="col-md-3"><div class="profile-img">'+
                                        '<img src="'+img+'" alt="'+v.username+'">'+
                                        '</div></div><div class="col-md-9">'+
                                        '<h5>'+v.username;

                            if (host) { $player += ' (host)'; }

                            $player += '</h5></div></a></div></div>';
                            $('.players').append($player);
                        });
                    });

                    $('.progress-bar').css('width', (data.count / size * 100)+'%');
                    $('.progress-bar .sr-only').text((data.count / size * 100)+'% full');
                    $('.progress-bar .ratio').text(data.count+'/'+size);
                }
            });
        }
    </script>
@stop
