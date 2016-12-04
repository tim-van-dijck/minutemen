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
				->select('participations.*', 'events.title', 'events.ends_at')
				->join('teams', 'teams.id', '=', 'participations.team_id')
				->join('events', 'events.id', '=', 'participations.event_id')
				->where('participations.team_id', $this->id)
				->orderBy('events.ends_at', 'desc')
				->get();

		foreach ($participations as $participation) {
			$participation->rank = Leaderboard::rank($participation->event_id, $this->id);
		}

		return $participations;
	}

	public function requests() {
		return User::join('team_users', 'team_users.user_id', '=', 'users.id')
					->where(['team_id' => $this->id, 'invite' => 0, 'pending' => 1])
					->get();
	}

	public function members() {
		return DB::table('users')
				->select('users.*', 'team_users.created_at AS joined', 'team_users.deleted_at AS left')
				->join('team_users', 'team_users.user_id', '=', 'users.id')
				->join('teams', 'teams.id', '=', 'team_users.team_id')
				->where('team_users.team_id', $this->id)
				->where('team_users.pending', 0)
				->orderBy('left', 'desc')
				->get();
	}

	public function admins() {
		return DB::table('users')
				->select('users.*', 'team_users.created_at AS joined', 'team_users.deleted_at AS left')
				->join('team_users', 'team_users.user_id', '=', 'users.id')
				->join('teams', 'teams.id', '=', 'team_users.team_id')
				->where('team_users.team_id', $this->id)
				->where('team_users.admin', 1)
				->orderBy('left', 'desc')
				->get();

		$admins = [];

		foreach ($result as $admin) {
			$admins[] = $admin->id;
		}

		return $admins;
	}

	public function isAdmin($user_id = false) {
		if (!$user_id) { $user_id = Auth::user()->id; }
		$admin = DB::table('users')
				->select('users.*', 'team_users.created_at AS joined')
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
		if (!$user_id) { $user_id = Auth::user()->id; }
		$member = DB::table('users')
				->select('users.*', 'team_users.created_at AS joined')
				->join('team_users', 'team_users.user_id', '=', 'users.id')
				->join('teams', 'teams.id', '=', 'team_users.team_id')
				->where('team_users.team_id', $this->id)
				->where('team_users.user_id', $user_id)
				->where('team_users.pending', 0)
				->first();

		if ($member == null) { return false; }
		return true;
	}

	protected function mine() {
		return self::select('*')->join('team_users', 'team_users.team_id', '=', 'teams.id')
					->where('team_users.user_id', Auth::user()->id)
					->orderBy('name')
					->get();
	}
		
	protected function join($team_id, $user_id, $invite, $admin = false) {
		DB::table('team_users')->insert([
			'team_id'	=> $team_id,
			'user_id'	=> $user_id,
			'invite'	=> $invite,
			'admin'		=> $admin,
			'pending'	=> !$admin
		]);
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
		$team_user = DB::table('team_users')->where([
			'team_id' => $team_id,
			'user_id' => $user_id,
		])->update(['admin' => 1]);
	}

	protected function deleteAdmin($team_id, $user_id) {
		$team_user = DB::table('team_users')->where([
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
}