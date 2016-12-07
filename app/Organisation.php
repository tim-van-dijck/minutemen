<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Auth;
use DB;
use Image;

class Organisation extends Model
{
	protected $fillable = ['name', 'description', 'thumb', 'banner', 'trusted', 'created_at', 'updated_at'];

	protected function search($query) {
		return self::select('*')->where('name', 'LIKE', $query)
						->orWhere('tag', 'LIKE', $query)
						->orWhere('description', 'LIKE', $query)
						->orderBy('name')
						->get();
	}

	protected function mine($user_id) {
		return self::select('organisations.*')
					->join('organisation_roles', 'organisations.id', '=', 'organisation_roles.organisation_id')
					->where('organisation_roles.user_id', $user_id)
					->where('organisation_roles.role', 'admin')
					->orderBy('name')
					->get();
	}

	protected function popular() {
		return self::select('organisations.*', DB::raw('COUNT(organisation_roles.user_id) AS subscriptions'))
					->join('organisation_roles', 'organisation_roles.organisation_id','=', 'organisations.id')
					->groupBy('organisation_roles.organisation_id')
					->orderBy('subscriptions', 'desc')
					->limit(5)->get();
	}

	public function admins() {
		return DB::table('users')
				->select('users.*', 'organisation_roles.created_at AS joined')
				->join('organisation_roles', 'organisation_roles.user_id', '=', 'users.id')
				->join('organisations', 'organisations.id', '=', 'organisation_roles.organisation_id')
				->where('organisation_roles.organisation_id', $this->id)
				->where('organisation_roles.role', 'admin')
				->orderBy('joined', 'asc')
				->get();
	}

	public function isAdmin($user_id) {
		$admin = DB::table('users')
				->select('users.*', 'organisation_roles.created_at AS joined')
				->join('organisation_roles', 'organisation_roles.user_id', '=', 'users.id')
				->join('organisations', 'organisations.id', '=', 'organisation_roles.organisation_id')
				->where('organisation_roles.organisation_id', $this->id)
				->where('organisation_roles.user_id', $user_id)
				->where('organisation_roles.role', 'admin')
				->orderBy('joined', 'asc')
				->first();

		if ($admin == null) { return false; }
		return true;
	}

	public function makeAdmin($user_id) {
		$organisation_roles = DB::table('organisation_roles')->where(['organisation_id' => $this->id, 'user_id' => $user_id])->get();

		if ($organisation_roles->isEmpty()) {
			$organisation_role = DB::table('organisation_roles')->insert([
				'organisation_id'	=> $this->id,
				'user_id'			=> $user_id,
				'role'				=> 'admin',
				'created_at'		=> date('Y-m-d H:i:s')
			]);
		} else {
			$organisation_role = DB::table('organisation_roles')->where([
				'organisation_id' => $this->id,
				'user_id' => $user_id,
			])->update(['role' => 'admin']);
		}
	}

	public function deleteAdmin($user_id) {
		$organisation_role = DB::table('organisation_roles')->where([
			'organisation_id' => $this->id,
			'user_id' => $user_id,
		])->update(['role' => 'subscriber']);
	}

	public function subscribe($user_id) {
		DB::table('organisation_roles')->insert([
			'organisation_id'	=> $this->id,
			'user_id'			=> $user_id,
			'role'				=> 'subscriber',
			'created_at'		=> date('Y-m-d H:i:s')
		]);
	}

	public function unsubscribe($user_id) {
		DB::table('organisation_roles')
			->where(['organisation_id' => $this->id, 'user_id' => $user_id])
			->delete();
	}

	public function events() { return Event::where('organisation_id', $this->id)->get(); }
	
	public function posts() { return Post::where('organisation_id', $this->id)->get(); }
}
