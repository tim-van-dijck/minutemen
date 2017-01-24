<div class="row rounds" style="width: {{ 'calc('. 12*count($event->rounds()).'em +  '.count($event->rounds())*1 .'px)' }};">
    @forelse($event->rounds() as $index => $round)
        <div class="col-md-3 round {{ ($round->isCurrentRound()) ? 'current-round' : '' }}">
            <h4>{{ $round->name }}</h4>
            @foreach($round->games() as $i => $game)
                <div class="row game" style="margin-top: {{ ($index > 0) ? ($index*16 - ($i==0)*9) - ($index-1) : 0 }}em;" title="{{ ($i > 0) }}">
                    @if (Auth::check() && $round->isCurrentRound() && $event->isAdmin())
                        <a href="#" class="game-settle" data-toggle="modal" data-target="#settle-game"
                           data-action="{{ route('ajax.game.winner', ['game_id' => $game->id]) }}">
                            @endif
                            <div class="col-md-12 participant team_1">
                                <div class="profile-img">
                                    @if ($game->team_1_won)
                                        <div class="overlay-won"><i class="fa fa-trophy"></i></div>
                                    @endif
                                    <img src="{{ $game->team1()->emblem or 'img/emblem.png' }}" alt="{{ $game->team1()->name }}">
                                </div>
                                <p class="title">{{ $game->team1()->name }}</p>
                            </div>
                            <div class="col-md-12 participant team_2" style="margin-top: {{ ($index > 0) ? ($index*16 + ($index-1)*10) - $index : $index*16 }}em;">
                                <div class="profile-img">
                                    <img src="{{ $game->team2()->emblem or 'img/emblem.png' }}" alt="{{ $game->team2()->name }}">
                                    @if ($game->team_1_won === 0)
                                        <div class="overlay-won">
                                            <i class="fa fa-trophy"></i>
                                        </div>
                                    @endif
                                </div>
                                <p class="title">{{ $game->team2()->name }}</p>

                            </div>
                            @if (Auth::check() && $round->isCurrentRound() && $event->isAdmin())
                        </a>
                    @endif
                </div>
            @endforeach
        </div>
        @if (($index+1) % 3 == 0)
</div><div class="row rounds">
    @endif
    @empty
        <div class="col-md-12">
            <p class="empty text-center">There are no rounds yet</p>
        </div>
    @endforelse
</div>