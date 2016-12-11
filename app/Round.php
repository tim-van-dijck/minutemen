<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Event;
use App\Game;

class Round extends Model
{
	public $timestamps = false;
	protected $fillable = ['event_id', 'name', 'teams'];

	public function elimination($limit) {
		$teams = Event::find($this->event_id)->competing($this->teams);
		return $teams;
	}

	public function games() { return Game::where('round_id', $this->id)->get(); }
}
