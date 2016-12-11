<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    protected $fillable = ['team_1', 'team_2', 'round_id', 'team_1_won'];
    public $timestamps = false;

    public function team1() { return Team::find($this->team_1); }
    public function team2() { return Team::find($this->team_2); }
}
