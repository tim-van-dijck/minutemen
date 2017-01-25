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
	protected $fillable = ['username', 'firstname', 'lastname', 'slug', 'email', 'street', 'number', 'zip', 'city', 'country', 'lat', 'long', 'range', 'password', 'lfg', ''];

	/**
	 * The attributes that should be hidden for arrays.
	 *
	 * @var array
	 */
	protected $hidden = [ 'password', 'remember_token', ];

	// Friends
	public function isFriend($confirmed = true) {
		$friends = Friendship::getFriendsIds($this->id, false, $confirmed);
		if (in_array($this->id, $friends)) { return true; }
		return false;
	}

    public function friends($limit = false) { return Friendship::getFriends($this->id, $limit); }

    public function friendship() {
        $first = DB::table('friendships')->where('friend_id', $this->id)
            ->where('user_id', Auth::user()->id);

        return DB::table('friendships')->where('user_id', $this->id)
            ->where('friend_id', Auth::user()->id)
            ->union($first)
            ->first();
    }

    // Organisations
	public function subscriptions() {
		return Organisation::select('organisations.*')->join('organisation_roles', 'organisation_roles.organisation_id', '=', 'organisations.id')
					->where('organisation_roles.user_id', $this->id)
					->where('organisation_roles.role', 'subscriber')
					->orderBy('name')
					->get();
	}

    public function organisations() { return Organisation::mine($this->id); }

    // Teams
	public function teams($limit = false) {
	    if (Auth::check() && $this->id == Auth::user()->id) { return Team::mine($limit); }
	    else { return Team::getByUser($this->id, $limit); }
	}

    protected function passwordConfirm($pass) {
        if (Hash::check($pass, Auth::user()->password)) { return true; }
        return false;
    }

    // Looking For Group
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

	// Lobby
    public function hasLobby() {
	    $lobby = DB::table('lobby_users')->select('lobby_id')->where(['user_id' => Auth::user()->id, 'confirmed' => 1])->first();
	    if ($lobby == null) { return false; }
	    return $lobby->lobby_id;
    }

	// Admin
    public function isAdmin() {
	    if ($this->admin == 1) { return true; }
	    else { return false; }
    }

    // Other
    public function notifications($limit = false) {
	    $query = Notification::where('user_id', $this->id)->orderBy('updated_at', 'desc')->orderBy('created_at', 'desc');

	    if ($limit !== false) { $query->limit($limit); }

	    $notifications = $query->get();

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
                default:
                    break;
            }
        }
        return $notifications;
    }

    protected function search($term, $team_id) {
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

    public function searchAcquaintances($term) {
        $teams = Team::select('id')
                        ->join('team_users', 'team_users.team_id', '=', 'teams.id')
                        ->where('team_users.user_id', $this->id)->get();

        $first = DB::table('users')->select(DB::raw('users.id, users.username AS text, users.img'))
            ->join('friendships', 'friendships.user_id', '=', 'users.id')
            ->where('friendships.friend_id', $this->id)
            ->where('confirmed', 1)
            ->where('users.id', '!=', Auth::user()->id)
            ->where('username', 'LIKE', '%'.$term.'%');

        $second = DB::table('users')->select(DB::raw('users.id, users.username AS text, users.img'))
            ->join('friendships', 'friendships.friend_id', '=', 'users.id')
            ->where('friendships.user_id', $this->id)
            ->where('confirmed', 1)
            ->where('users.id', '!=', Auth::user()->id)
            ->where('username', 'LIKE', '%'.$term.'%');

        $result = User::select(DB::raw('id, username AS text, img'))
                    ->join('team_users', 'team_users.user_id', '=', 'users.id')
                    ->where('username', 'LIKE', '%'.$term.'%')
                    ->where('users.id', '!=', Auth::user()->id);

        foreach ($teams as $index => $team) {
            if ($index == 0) {
                $result->where('team_id', $team->id);
            } else {
                $result->orWhere('team_id', $team->id);
            }
        }

        return $result->union($first)->union($second)->groupBy('users.id')->get();
    }

    public function findRecipients($term, $conversation_id) {
        $teams = Team::select('id')
                        ->join('team_users', 'team_users.team_id', '=', 'teams.id')
                        ->where('team_users.user_id', $this->id)
                        ->get();

        $first = DB::table('users')->select(DB::raw('users.id, users.username AS text, users.img'))
            ->join('friendships', 'friendships.user_id', '=', 'users.id')
            ->whereNotExists(function ($query) use ($conversation_id) {
                $query->select(DB::raw(1))
                    ->from('conversation_users')
                    ->where('conversation_users.conversation_id', $conversation_id)
                    ->whereRaw('conversation_users.user_id = users.id');
            })
            ->where('friendships.friend_id', $this->id)
            ->where('confirmed', 1)
            ->where('users.id', '!=', Auth::user()->id)
            ->where('username', 'LIKE', '%'.$term.'%');

        $second = DB::table('users')->select(DB::raw('users.id, users.username AS text, users.img'))
            ->join('friendships', 'friendships.friend_id', '=', 'users.id')
            ->whereNotExists(function ($query) use ($conversation_id) {
                $query->select(DB::raw(1))
                    ->from('conversation_users')
                    ->where('conversation_users.conversation_id', $conversation_id)
                    ->whereRaw('conversation_users.user_id = users.id');
            })
            ->where('friendships.user_id', $this->id)
            ->where('confirmed', 1)
            ->where('users.id', '!=', Auth::user()->id)
            ->where('username', 'LIKE', '%'.$term.'%');

        $result = User::select(DB::raw('users.id, username AS text, img'))
                    ->join('team_users', 'team_users.user_id', '=', 'users.id')
                    ->whereNotExists(function ($query) use ($conversation_id) {
                        $query->select(DB::raw(1))
                            ->from('conversation_users')
                            ->where('conversation_users.conversation_id', $conversation_id)
                            ->whereRaw('conversation_users.user_id = users.id');
                    })
                    ->where('username', 'LIKE', '%'.$term.'%')
                    ->where('users.id', '!=', Auth::user()->id);

        $where = '(';
        foreach ($teams as $index => $team) {
            if ($index == 0) {
                $where .= 'team_id = '.$team->id;
            } else {
                $where .= ' OR team_id = '.$team->id;
            }
        }
        $where .= ')';
        if ($where != '()') { $result->whereRaw($where); }

        return $result->union($first)->union($second)->groupBy('users.id')->get();
    }
}
