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

	protected function search($query) {
		return self::select('*')->where('name', 'LIKE', $query)
						->orWhere('tag', 'LIKE', $query)
						->orWhere('description', 'LIKE', $query)
						->orderBy('name')
						->get();
	}

	protected function mine() {
		return self::select('*')->join('team_users', 'team_users.team_id', '=', 'teams.id')
					->where('team_users.user_id', Auth::user()->id)
					->orderBy('name')
					->get();
	}

	protected function join($team_id, $admin = false) {
		$data = [
			'team_id'	=> $team_id,
			'user_id'	=> Auth::user()->id,
			'pending'	=> !$admin,
			'admin'		=> $admin,
		];

		DB::table('team_users')->insert($data);
	}

	protected function uploadImg($file) {
		$exists = true;
		$hash = '';

		while ($exists) {
			$hash = 'img/teams/enblems/'.hash('sha512', str_random(40));

			if (is_string($file)) { $hash.='.png'; }
			else { $hash.='.'.$file->getClientOriginalExtension(); }

			$exists = file_exists(public_path($hash));
		}

		if (!is_string($file)) { $file = $file->getRealPath(); }

		$img = Image::make($file)
				->resize(250, null, function ($constraint) {
					$constraint->aspectRatio();
					$constraint->upsize();
				});

		return $hash;
	}

	protected function participations($id) {
		return DB::table('participations')
				->select('participations.*', 'events.title', 'events.ends_at')
				->join('teams', 'teams.id', '=', 'participations.team_id')
				->join('events', 'events.id', '=', 'participations.event_id')
				->where('participations.team_id', $id)
				->orderBy('events.ends_at', 'desc')
				->get();
	}

	protected function members($id) {
		return DB::table('users')
				->select('users.*', 'team_users.created_at AS joined', 'team_users.deleted_at AS left')
				->join('team_users', 'team_users.user_id', '=', 'users.id')
				->join('teams', 'teams.id', '=', 'team_users.team_id')
				->where('team_users.team_id', $id)
				->orderBy('left', 'desc')
				->get();
	}

	protected function admins($id) {
		$result = DB::table('users')
				->select('users.*', 'team_users.created_at AS joined', 'team_users.deleted_at AS left')
				->join('team_users', 'team_users.user_id', '=', 'users.id')
				->join('teams', 'teams.id', '=', 'team_users.team_id')
				->where('team_users.team_id', $id)
				->where('team_users.admin', 1)
				->orderBy('left', 'desc')
				->get();

		$admins = [];

		foreach ($result as $admin) {
			$admins[] = $admin->id;
		}

		return $admins;
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
		])->delete();
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

	protected function sluggify($string) {
		$slug = preg_replace('/[^A-Za-z0-9\-\_\.]/', '', $string);
		$teams = self::where('slug', $slug)->get();

		if (!$teams->isEmpty()) { $slug .= count($teams); }
		
		return $slug;
	}
}