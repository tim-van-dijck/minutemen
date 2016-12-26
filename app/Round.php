<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Event;
use App\Game;

class Round extends Model
{
	protected $fillable = ['event_id', 'name', 'teams', 'created_at', 'updated_at'];

	public function elimination($data) {
		$teams = Event::find($this->event_id)->competing($this->teams);

		$prev_round = Round::where('event_id');
		return $teams;
	}

	public function games() { return Game::where('round_id', $this->id)->get(); }

	public function isCurrentRound() {
        $curRound = self::where('event_id', $this->event_id)->orderBy('created_at', 'desc')->first();
        if ($curRound->id == $this->id) { return true; }
        else { return false; }
    }
}
