<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

use Auth;
use DB;
use Image;

class User extends Authenticatable
{
	use Notifiable;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['username', 'firstname', 'name', 'email', 'password', 'accuracy', 'kills', 'deaths'];

	/**
	 * The attributes that should be hidden for arrays.
	 *
	 * @var array
	 */
	protected $hidden = [ 'password', 'remember_token', ];

	protected function search($query) {
		$result = self::select('*')
						->where(function ($q) use ($query) {
							$q->where('username', 'LIKE', '%'.$query.'%')
								->orWhere('firstname', 'LIKE', '%'.$query.'%')
								->orWhere('lastname', 'LIKE', '%'.$query.'%')
								->orWhere('email', 'LIKE', '%'.$query.'%');
						});

		if (Auth::check()) { $result->where('id', '!=', Auth::user()->id); }

		$result = $result->orderBy('username')->get();

		if (Auth::check()) {
			foreach ($result as $user) {
				$user->isFriend = self::isFriend($user->id);
			}
		}

		return $result;
	}

	protected function uploadImg($file) {

		$exists = true;
		$hash = '';

		while ($exists) {
			$hash = 'img/users/'.hash('sha512', str_random(40));
			
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
		$img->save(public_path($hash));

		return $hash;
	}

	protected function sluggify($string) {
		$slug = preg_replace('/[^A-Za-z0-9\-\_\.]/', '', $string);
		$users = User::where('slug', $slug)->get();

		if (!$users->isEmpty()) { $slug .= count($users); }
		
		return $slug
	}

	protected static function isFriend($id) {
		$friends = Friendship::getFriendsIds();
		if (in_array($id, $friends)) { return true; }
		return false;
	}
}
