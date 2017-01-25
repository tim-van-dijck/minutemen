<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;
use DB;
use Image;

class Team extends Model
{

	public $timestamps = false;
	protected $fillable = ['name', 'slug', 'tag', 'description', 'emblem'];

	public function participations() {
		$participations = DB::table('participations')
				->select('events.*')
				->join('teams', 'teams.id', '=', 'participations.team_id')
				->join('events', 'events.id', '=', 'participations.event_id')
				->where('participations.team_id', $this->id)
				->orderBy('events.ends_at', 'desc')
				->get();

		foreach ($participations as $participation) {
			$participation->rank = Leaderboard::rank($participation->id, $this->id);
		}

		return $participations;
	}

	public function requests() {
		return User::join('team_users', 'team_users.user_id', '=', 'users.id')
					->where(['team_id' => $this->id, 'invite' => 0, 'pending' => 1])
					->get();
	}

	public function members($limit = false) {
		$result = User::select('users.*', 'team_users.created_at AS joined', 'team_users.deleted_at AS left')
					->join('team_users', 'team_users.user_id', '=', 'users.id')
					->join('teams', 'teams.id', '=', 'team_users.team_id')
					->where('team_users.team_id', $this->id)
					->where('team_users.pending', 0)
					->orderBy('left', 'desc');
		if ($limit != false) { $result->limit($limit); }
		return $result->get();
	}

	public function onlyMembers($limit = false) {
		$result = User::select('users.*', 'team_users.created_at AS joined', 'team_users.deleted_at AS left')
					->join('team_users', 'team_users.user_id', '=', 'users.id')
					->join('teams', 'teams.id', '=', 'team_users.team_id')
					->where('team_users.team_id', $this->id)
					->where('team_users.admin', 0)
					->orderBy('left', 'desc');
		if ($limit != false) { $result->limit($limit); }
		return $result->get();
	}

	public function admins($limit = false) {
		$result = User::select('users.*', 'team_users.created_at AS joined', 'team_users.deleted_at AS left')
					->join('team_users', 'team_users.user_id', '=', 'users.id')
					->join('teams', 'teams.id', '=', 'team_users.team_id')
					->where('team_users.team_id', $this->id)
					->where('team_users.admin', 1)
					->orderBy('left', 'desc');
		if ($limit != false) { $result->limit($limit); }
		return $result->get();
	}

    public function wins($event_id = false) {
        $first = Game::select(DB::raw('team_1_won as wins'))
            ->join('rounds', 'rounds.id', '=', 'games.round_id')
            ->where('team_1_won', 1)
            ->where('draw', 0)
            ->where('games.team_1', $this->id);

        if ($event_id) { $first->where('rounds.event_id', $event_id); }

        $first = $first->get();

        $second = Game::select(DB::raw('team_1_won as wins'))
            ->join('rounds', 'rounds.id', '=', 'games.round_id')
            ->where('team_1_won', 0)
            ->where('draw', 0)
            ->where('games.team_2', $this->id);
        if ($event_id) { $second->where('rounds.event_id', $event_id); }

        $second = $second->get();

        return count($first) + count($second);
    }

    public function losses($event_id = false) {
        $first = Game::select(DB::raw('team_1_won as losses'))
            ->join('rounds', 'rounds.id', '=', 'games.round_id')
            ->where('team_1_won', 0)
            ->where('draw', 0)
            ->where('games.team_1', $this->id);

        if ($event_id) { $first->where('rounds.event_id', $event_id); }
        $first = $first->get();

        $second = Game::select(DB::raw('team_1_won as losses'))
            ->join('rounds', 'rounds.id', '=', 'games.round_id')
            ->where('team_1_won', 1)
            ->where('draw', 0)
            ->where('games.team_2', $this->id);
        if ($event_id) { $second->where('rounds.event_id', $event_id); }
        $second = $second->get();

        return count($first) + count($second);
    }

    public function draws($event_id = false) {
        $query = Game::select(DB::raw('SUM(draw) as draws'))
            ->join('rounds', 'rounds.id', '=', 'games.round_id')
            ->where('draw', 1)
            ->where('games.team_1', $this->id)
            ->orWhere('games.team_2', $this->id)
            ->groupBy();

        if ($event_id) { $query->where('rounds.event_id', $event_id); }
        $result = $query->pluck('draws')[0];
        if ($result == null) { return 0; }

        return $result;
    }

	public function isAdmin($user_id = false) {
		if (!$user_id) {
		    if (Auth::check()) { $user_id = Auth::user()->id; }
            else { return false; }
		}
		$admin = User::select('users.*', 'team_users.created_at AS joined')
					->join('team_users', 'team_users.user_id', '=', 'users.id')
					->join('teams', 'teams.id', '=', 'team_users.team_id')
					->where('team_users.team_id', $this->id)
					->where('team_users.user_id', $user_id)
					->where('team_users.admin', 1)
					->where('team_users.pending', 0)
					->first();

		if ($admin == null) { return false; }
		return true;
	}

	public function isMember($user_id = false) {
        if (!$user_id) {
            if (Auth::check()) { $user_id = Auth::user()->id; }
            else { return false; }
        }
		$member = User::select('users.*', 'team_users.created_at AS joined')
					->join('team_users', 'team_users.user_id', '=', 'users.id')
					->join('teams', 'teams.id', '=', 'team_users.team_id')
					->where('team_users.team_id', $this->id)
					->where('team_users.user_id', $user_id)
					->where('team_users.pending', 0)
                    ->where('team_users.deleted_at', null)
					->first();

		if ($member == null) { return false; }
		return true;
	}

	public function isInvited($user_id = false) {
        if (!$user_id) {
            if (Auth::check()) { $user_id = Auth::user()->id; }
            else { return false; }
        }
        $member = User::select('users.*', 'team_users.created_at AS joined')
            ->join('team_users', 'team_users.user_id', '=', 'users.id')
            ->join('teams', 'teams.id', '=', 'team_users.team_id')
            ->where('team_users.team_id', $this->id)
            ->where('team_users.user_id', $user_id)
            ->where('team_users.pending', 1)
            ->where('team_users.invite', 1)
            ->first();

        if ($member == null) { return false; }
        return true;
    }

    public function isPending($user_id = false) {
        if (!$user_id) {
            if (Auth::check()) { $user_id = Auth::user()->id; }
            else { return false; }
        }
        $member = User::select('users.*', 'team_users.created_at AS joined')
            ->join('team_users', 'team_users.user_id', '=', 'users.id')
            ->join('teams', 'teams.id', '=', 'team_users.team_id')
            ->where('team_users.team_id', $this->id)
            ->where('team_users.user_id', $user_id)
            ->where('team_users.pending', 1)
            ->where('team_users.invite', 0)
            ->where('team_users.deleted_at', null)
            ->first();

        if ($member == null) { return false; }
        return true;
    }

	protected function mine($limit = false) {
        $result =  self::select('*')->join('team_users', 'team_users.team_id', '=', 'teams.id')
                        ->where('team_users.user_id', Auth::user()->id)
                        ->orderBy('name');

        if ($limit != false) { $result->limit($limit); }
        return $result->get();
    }

    protected function getByUser($user_id, $limit = false) {
        $result =  self::select('*')->join('team_users', 'team_users.team_id', '=', 'teams.id')
            ->where('team_users.user_id', $user_id)
            ->orderBy('name');

        if ($limit != false) { $result->limit($limit); }
        return $result->get();
    }
		
	protected function join($team_id, $user_id, $invite, $admin = false) {
	    $team = Team::find($team_id);
	    if (!$team->isMember($user_id)) {
            DB::table('team_users')->insert([
                'team_id'	=> $team_id,
                'user_id'	=> $user_id,
                'invite'	=> $invite,
                'admin'		=> $admin,
                'pending'	=> !$admin
            ]);
        }
	}

	protected function addMember($team_id, $user_id) {
		DB::table('team_users')->insert([
			'team_id' => $team_id,
			'user_id' => $user_id,
		]);
	}

	protected function deleteMember($team_id, $user_id) {
		DB::table('team_users')->where([
			'team_id' => $team_id,
			'user_id' => $user_id,
		])->update(['deleted_at' => date('Y-m-d H:i:s')]);
	}

	protected function makeAdmin($team_id, $user_id) {
		DB::table('team_users')->where([
			'team_id' => $team_id,
			'user_id' => $user_id,
		])->update(['admin' => 1]);
	}

	protected function deleteAdmin($team_id, $user_id) {
		DB::table('team_users')->where([
			'team_id' => $team_id,
			'user_id' => $user_id,
		])->update(['admin' => 0]);
	}

	protected function confirm($team_id, $user_id) {
		DB::table('team_users')
			->where('team_id', $team_id)
			->where('user_id', $user_id)
			->update(['pending' => 0]);
			
		$user = User::find($user_id);
		$user->nlfg();
	}

	protected function deleteRequest($team_id, $user_id) {
		$request = DB::table('team_users')->where([
						'team_id'	=> $team_id,
						'user_id'	=> $user_id,
						'pending'	=> 1
					])->delete();
	}

	protected function kick($team_id, $user_id) {
	    $team = self::find($team_id);
	    if ($team->isAdmin($user_id) && count($team->admins()) < 2) {
            $user = '';
        }
        DB::table('team_users')->where([
            'team_id' => $team_id,
            'user_id' => $user_id,
        ])->delete();
        return json_encode(['success' => 'Successfully kicked user']);
    }
}