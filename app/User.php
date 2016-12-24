<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

use Auth;
use DB;
use Hash;
use Image;

class User extends Authenticatable
{
	use Notifiable;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['username', 'firstname', 'lastname', 'slug', 'email', 'street', 'number', 'zip', 'city', 'lat', 'long', 'password', 'lfg'];

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

	protected function getLfg($where = null) {
		$query = User::select(DB::raw('*, ROUND(6353 * 2 * ASIN(SQRT(POWER(SIN(('.Auth::user()->lat.' - abs(`lat`))
								* pi()/180 / 2),2) + COS('.Auth::user()->lat.' * pi()/180 ) * COS(abs(`lat`) *  pi()/180)
								* POWER(SIN(('.Auth::user()->long.' - `long`) *  pi()/180 / 2), 2) )), 2) AS distance'))
								->where('lfg', 1);
		if (isset($where)) { $query->where($where); }
		return $query->orderBy('distance')->limit(6)->get();
	}

	protected function search($term, int $team_id) {
	    /*return [
	        'team_id' => $team_id,
            'term'      => $term
        ];*/
	    return self::where('username', 'LIKE', '%'.$term.'%')
                    ->whereNotExists(function($query) use ($team_id) {
                        $query->select(DB::raw(1))
                            ->from('team_users')
                            ->whereRaw('team_users.user_id = users.id')
                            ->where('team_users.team_id', $team_id);
                    })
                    ->limit(10)->get();
	}

	protected function passwordConfirm($pass) {
	    if (Hash::check($pass, Auth::user()->password)) { return true; }
	    return false;
    }

    public function isAdmin() {
	    if ($this->admin == 1) { return true; }
	    else { return false; }
    }
}
