<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Event;
use App\Game;

class Round extends Model
{
	protected $fillable = ['event_id', 'name', 'created_at', 'updated_at'];

	public function games() { return Game::where('round_id', $this->id)->get(); }

	public function isCurrentRound() {
        $curRound = self::where('event_id', $this->event_id)->orderBy('created_at', 'desc')->first();
        if ($curRound->id == $this->id) { return true; }
        else { return false; }
    }
}
