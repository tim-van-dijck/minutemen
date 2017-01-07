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

	public function isFriend($confirmed = true) {
		$friends = Friendship::getFriendsIds($this->id, $confirmed);
		if (in_array($this->id, $friends)) { return true; }
		return false;
	}

	public function subscriptions() {
		return Organisation::select('organisations.*')->join('organisation_roles', 'organisation_roles.organisation_id', '=', 'organisations.id')
					->where('organisation_roles.user_id', $this->id)
					->where('organisation_roles.role', 'subscriber')
					->orderBy('name')
					->get();
	}

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

	protected function getLfg($team_id, $where = null) {
		/*$query = User::select(DB::raw('*, ROUND(6353 * 2 * ASIN(SQRT(POWER(SIN(('.Auth::user()->lat.' - abs(`lat`))
								* pi()/180 / 2),2) + COS('.Auth::user()->lat.' * pi()/180 ) * COS(abs(`lat`) *  pi()/180)
								* POWER(SIN(('.Auth::user()->long.' - `long`) *  pi()/180 / 2), 2) )), 2) AS distance'))
								->where('lfg', 1);
		if (isset($where)) { $query->where($where); }
		return $query->orderBy('distance')->limit(6)->get();*/

		return User::where('lfg', 1)
                    ->whereNotExists(function ($query) use ($team_id) {
                        $query->select(DB::raw(1))
                            ->from('team_users')
                            ->where('team_users.team_id', $team_id)
                            ->whereRaw('team_users.user_id = users.id');
                    })->get();
	}

	protected function search($term, int $team_id) {
	    if ($term == '') { return []; }
	    $users =  self::select(DB::raw('id, username AS text, img'))->where('username', 'LIKE', '%'.$term.'%')
                    ->whereNotExists(function($query) use ($team_id) {
                        $query->select(DB::raw(1))
                            ->from('team_users')
                            ->whereRaw('team_users.user_id = users.id')
                            ->where('team_users.team_id', $team_id);
                    })
                    ->limit(10)->get();

        return $users;
	}

	protected function passwordConfirm($pass) {
	    if (Hash::check($pass, Auth::user()->password)) { return true; }
	    return false;
    }

    public function isAdmin() {
	    if ($this->admin == 1) { return true; }
	    else { return false; }
    }

    public function notifications() {
	    $notifications = Notification::where('user_id', $this->id)->orderBy('updated_at', 'desc')->orderBy('created_at', 'desc')->get();

	    foreach ($notifications as $notification) {
	        switch ($notification->entity_name) {
                case 'friend-request':
                    $user = User::select('username')->join('friendships', 'users.id', '=', 'friendships.friend_id')
                                    ->where('friendships.id', $notification->entity_id)->first();
                    $notification->content = '<a href="'.route('users.show', ['slug' => $user->slug]).'">'.
                                                $user->username.'</a>'.$notification->content;
                    break;
                case 'message':
                    $user = User::select('username')->join('messages', 'users.id', '=', 'messages.receiver_id')
                        ->where('messages.id', $notification->entity_id)->first();
                    $notification->content .= '<a href="'.route('users.show', ['slug' => $user->slug]).'">'.$user->name.'</a>';
                    break;
                case 'event':
                    $event = Event::find($notification->entity_id);
                    $notification->content = '<a href="'.route('', []).'">'
                                                .$event->title.'</a>'.$notification->content;
                    break;
                case 'team':
                    $team = Team::find($notification->entity_id);
                    $notification->content = '<a href="'.route('teams.show', ['slug' => $team->slug]).'">'
                                                .$team->name.'</a>'.$notification->content;
                    break;
                case 'organisation':
                    $org = Organisation::find($notification->entity_id);
                    $notification->content = '<a href="'.route('organisations.show', ['id' => $org->id]).'">'
                                                .$org->name.'</a>'.$notification->content;
                    break;
            }
        }
        return $notifications;
    }

    public function friendship() {
	    $first = DB::table('friendships')->where('friend_id', $this->id)
                    ->where('user_id', Auth::user()->id);

        return DB::table('friendships')->where('user_id', $this->id)
                    ->where('friend_id', Auth::user()->id)
                    ->union($first)
                    ->first();
    }
}
