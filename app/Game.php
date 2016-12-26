<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    protected $fillable = ['team_1', 'team_2', 'round_id', 'team_1_won'];
    public $timestamps = false;

    public function team1() { return Team::find($this->team_1); }
    public function team2() { return Team::find($this->team_2); }

    protected function setWinner($game_id, $winner) {
        $game = self::find($game_id);
        switch ($winner) {
            case 0:
                $game->draw = 1;
                $game->save();
                break;
            case 1:
                $game->team_1_won = 1;
                $game->save();
                break;
            case 2:
                $game->team_1_won = 0;
                $game->save();
                break;
            default:
                break;
        }
    }
}
