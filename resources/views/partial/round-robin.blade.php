<div class="row rounds">
    @forelse($event->rounds() as $index => $round)
        <?php $game = $round->games()[0] ?>
        <div class="col-md-12 round {{ ($round->isCurrentRound()) ? 'current-round' : '' }}">
            <h4 class="text-center">{{ $round->name }}</h4>
            <div class="row persist-cols-mobile">
                <div class="col-md-8 col-md-offset-2">
                    @if (Auth::check() && $event->isAdmin())
                        <a href="#" class="game-settle" data-toggle="modal" data-target="#settle-game"
                           data-action="{{ route('ajax.game.winner', ['game_id' => $game->id]) }}">
                    @endif
                    <div class="row game persist-cols">
                        <div class="col-md-4 participant team_1">
                            <div class="profile-img">
                                @if ($game->team_1_won)
                                    <div class="overlay-won"><i class="fa fa-trophy"></i></div>
                                @endif
                                <img src="{{ $game->team1()->emblem or 'img/emblem.png' }}" alt="{{ $game->team1()->name }}">
                            </div>
                            <p class="title">{{ $game->team1()->name }}</p>
                        </div>
                        <div class="col-md-4 text-center versus"><span>{!! ($game->draw) ? '<i class="fa fa-pause fa-rotate-90"></i>' : 'vs.'!!}</span></div>
                        <div class="col-md-4 participant team_2">
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
                    </div>
                    @if (Auth::check() && $event->isAdmin())
                        </a>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div class="col-md-12">
            <p class="empty text-center">There are no rounds yet</p>
        </div>
    @endforelse
</div>