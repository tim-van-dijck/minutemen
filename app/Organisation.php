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

	protected function uploadImg($file) {
		$exists = true;
		$hash = '';

		while ($exists) {
			$hash = 'img/organisations/'.hash('sha512', str_random(40));

			if (is_string($file)) { $hash.='.png'; }
			else { $hash.='.'.$file->getClientOriginalExtension(); }

			$exists = file_exists(public_path($hash));
		}

		if (!is_string($file)) { $file = $file->getRealPath(); }

		$img = Image::make($file)
				->resize(250, null, function ($constraint) {
					$constraint->aspectRatio();
					$constraint->upsize();
				})->save(public_path($hash));

		return $hash;
	}

	protected function admins($id) {
		return DB::table('users')
				->select('users.*', 'organisation_roles.created_at AS joined')
				->join('organisation_roles', 'organisation_roles.user_id', '=', 'users.id')
				->join('organisations', 'organisations.id', '=', 'organisation_roles.organisation_id')
				->where('organisation_roles.organisation_id', $id)
				->where('organisation_roles.role', 'admin')
				->orderBy('joined', 'asc')
				->get();
	}

	protected function makeAdmin($organisation_id, $user_id) {
		$organisation_roles = DB::table('organisation_roles')->where(['organisation_id' => $organisation_id, 'user_id' => $user_id])->get();

		if ($organisation_roles->isEmpty()) {
			$organisation_role = DB::table('organisation_roles')->insert([
				'organisation_id'	=> $organisation_id,
				'user_id'			=> $user_id,
				'role'				=> 'admin',
				'created_at'		=> date('Y-m-d H:i:s')
			]);
		} else {
			$organisation_role = DB::table('organisation_roles')->where([
				'organisation_id' => $organisation_id,
				'user_id' => $user_id,
			])->update(['role' => 'admin']);
		}
	}

	protected function deleteAdmin($organisation_id, $user_id) {
		$organisation_role = DB::table('organisation_roles')->where([
			'organisation_id' => $organisation_id,
			'user_id' => $user_id,
		])->update(['role' => 'subscriber']);
	}

	protected function sluggify($string) {
		$slug = preg_replace('/[^A-Za-z0-9\-\_\.]/', '', $string);
		$organisations = self::where('slug', $slug)->get();

		if (!$organisations->isEmpty()) { $slug .= count($organisations); }
		
		return $slug;
	}

	protected function subscribe($organisation_id, $user_id) {
		DB::table('organisation_roles')->insert([
			'organisation_id'	=> $organisation_id,
			'user_id'			=> $user_id,
			'role'				=> 'subscriber',
			'created_at'		=> date('Y-m-d H:i:s')
		]);
	}

	protected function unsubscribe($organisation_id, $user_id) {
		DB::table('organisation_roles')
			->where(['organisation_id' => $organisation_id, 'user_id' => $user_id])
			->delete();
	}

	protected function subscriptions($id = false) {
		if (!$id) { $id = Auth::user()->id; }
		return self::select('*')->join('organisation_roles', 'organisation_roles.organisation_id', '=', 'organisations.id')
					->where('organisation_roles.user_id', $id)
					->where('organisation_roles.role', 'subscriber')
					->orderBy('name')
					->get();
	}

	protected function mine($id = false) {
		if (!$id) { $id = Auth::user()->id; }
		return self::select('organisations.*')->join('organisation_roles', 'organisation_roles.organisation_id', '=', 'organisations.id')
					->where('organisation_roles.user_id', $id)
					->where('organisation_roles.role', 'admin')
					->orderBy('name')
					->get();
	}
}
