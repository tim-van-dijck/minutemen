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
		return Team::select('teams.*')->join('participations', 'participations.team_id', '=', 'teams.id')
					->where('participations.event_id', $this->id)->get();
	}

	public function rounds() { return Round::where('event_id', $this->id)->get(); }

	public function full() { return count($this->participators()) == $this->max_teams; }

	public function leaderboard() { return Leaderboard::getByEvent($this->id); }

	public function competing() {
        if (count($this->rounds()) > 0) {
            $prevRound = Round::where('event_id', $this->id)->orderBy('created_at', 'desc')->first();

            $query = Team::select('teams.*')->join('games', 'games.team_1', '=', 'teams.id')
                ->join('rounds', 'games.round_id', '=', 'rounds.id')
                ->where('games.team_1_won', 1)
                ->where('draw', 0)
                ->where('rounds.id', $prevRound->id);

            return Team::select('teams.*')->join('games', 'games.team_2', '=', 'teams.id')
                ->join('rounds', 'games.round_id', '=', 'rounds.id')
                ->where('games.team_1_won', 0)
                ->where('draw', 0)
                ->where('rounds.id', $prevRound->id)
                ->union($query)
                ->get();
        } else {
	        return $this->participators();
        }
	}

	public function extraPlayer() {
        $competing = $this->competing();
        $teams = $this->participators();

        foreach ($competing as $i => $competitor) {
            foreach ($teams as $j => $team) {
                if ($team->id == $competitor->id) {
                    unset($competing[$i]);
                    unset($teams[$j]);
                }
            }
        }

        $roundTeams = [];

        foreach ($teams as $team) {
            $roundTeams[] = $team;
        }
        usort($roundTeams, function($a, $b) {
            return $a->wins($this->id) <=> $b->wins($this->id);
        });

        return $roundTeams[0];
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

	public function eliminationRound($data) {
        $prevRound = Round::where('event_id', $this->id)->orderBy('created_at', 'desc')->first();
        $teams = $this->competing()->shuffle();
        if (count($teams) % 2 != 0) { $teams[] = $this->extraPlayer(); }

        $round = new Round($data);
        $round->save();

        while(count($teams) > 0) {

            $team_1 = $teams->shift();
            $team_2 = $teams->shift();

            $game = new Game();
            $game->team_1 = $team_1->id;
            $game->team_2 = $team_2->id;
            $game->round_id = $round->id;
            $game->save();
        }
    }

	public function isAdmin() { return Organisation::find($this->organisation_id)->isAdmin(); }
}
