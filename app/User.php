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
	protected $fillable = ['username', 'firstname', 'name', 'email', 'password', 'accuracy', 'kills', 'deaths', 'lfg'];

	/**
	 * The attributes that should be hidden for arrays.
	 *
	 * @var array
	 */
	protected $hidden = [ 'password', 'remember_token', ];

	public function isFriend() {
		$friends = Friendship::getFriendsIds($this->id);
		if (in_array($this->id, $friends)) { return true; }
		return false;
	}

	public function subscriptions() {
		return Organisation::select('*')->join('organisation_roles', 'organisation_roles.user_id', '=', 'organisations.id')
					->where('organisation_roles.user_id', $this->id)
					->where('organisation_roles.role', 'subscriber')
					->orderBy('name')
					->get();
	}

	public function commendations() { return Commendation::count($this->id); }

	public function friends() { return Friendship::getFriends($this->id); }

	public function teams() { return Team::mine(); }

	public function organisations() { return Organisation::mine($this->id); }

	public function lfgToggle() {
		$this->lfg = !$this->lfg;
		$this->save();
	}

	public function lfg() {
		$this->lfg = 1;
		$this->save();
	}

	public function nlfg() {
		$this->lfg = 0;
		$this->save();
	}
}
