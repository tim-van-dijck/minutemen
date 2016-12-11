<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Image;

class Event extends Model
{
    public $timestamps = false;
    protected $fillable = ['title', 'description', 'starts_at', 'ends_at', 'street', 'number', 'zip', 'city', 'banner', 'coords', 'organisation_id', 'website'];

	public function participators() {
		return Team::select('*')->join('participations', 'participations.team_id', '=', 'teams.id')
					->where('participations.event_id', $this->id)->get();
	}

	public function rounds() { return Round::where('event_id', $this->id)->get(); }

	public function full() { return count($this->participators()) == $this->max_teams; }

	public function leaderboard() { return Leaderboard::getByEvent($this->id); }

	public function competing($limit = false) {
		$query = Team::select('*')->join('games', 'games.team_1', '=', 'teams.id')
					->join('rounds', 'games.round_id', '=', 'rounds.id')
					->where('games.team_1_won', 1)
					->where('draw', 0)
					->where('rounds.event_id', $this->id)
					->get();

		$competing = Team::select('*')->join('games', 'games.team_1', '=', 'teams.id')
					->join('rounds', 'games.round_id', '=', 'rounds.id')
					->where('games.team_1_won', 0)
					->where('draw', 0)
					->where('rounds.event_id', $this->id)
					->union($query);
		
		if ($limit) { $competing->limit($limit); }

		return $competing->get();
	}

	protected function enter($event_id, $team_id) {
		$event = self::find($event_id);
		if (!$event->full()) {
			$participation = DB::table('participations')->where(['event_id' => $event_id, 'team_id' => $team_id])->first();
			if (!isset($participation->event_id)) {
				DB::table('participations')->insert([
					'event_id' => $event_id,
					'team_id' => $team_id
				]);
				return true;
			}
		}
		return false;
	}

	public function roundrobin() {
		$players = $this->participators();
		$games = [];

		for ($i = 0; $i < count($players); $i++) {
			for($j = $i+1; $j < count($players); $j++) {
				$games[] = [
					'team_1'	=> $players[$i]->id,
					'team_2'	=> $players[$j]->id,
				];
			}
		}

		shuffle($games);
		$roundCount = 0;
		foreach ($games as $index => $game) {
			$roundCount++;
			$round = new Round([
				'name'		=> 'Round '.$roundCount,
				'event_id'	=> $this->id,
			]);
			$round->save();
			
			$game['round_id'] = $round->id;
			$g = new Game($game);
			$g->save();
		}
	}

	public function isAdmin() { return Organisation::find($this->organisation_id)->isAdmin(); }
}
