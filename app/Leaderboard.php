<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use DB;

class Leaderboard extends Model
{
	protected $fillable = ['team_id', 'event_id', 'wins', 'losses', 'draws'];

	protected function getGlobal() {
		return DB::table('teams')
				->select(DB::raw('teams.*, COUNT(leaderboards.wins) AS wins, COUNT(leaderboards.draws) AS draws, COUNT(leaderboards.losses) AS losses'))
				->join('leaderboards', 'leaderboards.team_id', '=', 'teams.id')
				->groupBy('leaderboards.team_id')
				->orderBy('wins', 'desc')
				->orderBy('draws', 'desc')
				->orderBy('losses', 'asc')
				->limit(100)
				->get();
	}

	protected function getUsers() {
		return DB::table('users')
				->select(DB::raw('teams.*, COUNT(leaderboards.wins) AS wins, COUNT(leaderboards.draws) AS draws, COUNT(leaderboards.losses) AS losses'))
				->join('team_users', 'team_users.user_id', '=', 'users.id')
				->join('teams', 'teams.user_id', '=', 'users.id')
				->join('leaderboards', 'leaderboards.team_id', '=', 'teams.id')
				->join('events', 'leaderboards.event_id', '=', 'events.id')
				->where('events.starts_at', '>=', 'team_users.created_at')
				->where('events.ends_at', '>=', 'team_users.deleted_at')
				->groupBy('leaderboards.team_id')
				->orderBy('wins', 'desc')
				->orderBy('draws', 'desc')
				->orderBy('losses', 'asc')
				->limit(100)
				->get();
	}

	protected function rank($event_id, $team_id) {
		$leaderboard = self::select(DB::raw('COUNT(leaderboards.wins) AS wins, COUNT(leaderboards.draws) AS draws, COUNT(leaderboards.losses) AS losses'))
				->where('event_id', $event_id)
				->groupBy('leaderboards.team_id')
				->orderBy('wins', 'desc')
				->orderBy('draws', 'desc')
				->orderBy('losses', 'asc')
				->limit(100)
				->get();

		if ($leaderboard->isEmpty()) {
			return 'N/A';
		}
		foreach ($leaderboard as $key => $team) {
			if ($team->id == $team_id) {
				return $key+1;
			}
		}
	}
}
