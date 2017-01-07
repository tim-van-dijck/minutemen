<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;
use DB;

class Friendship extends Model
{

	public $timestamps = false;
	protected $fillable = ['user_id', 'friend_id', 'confirmed', 'since'];

	protected function join($team_id) {
		DB::table('team_users')->insert([
			'team_id' => $team_id,
			'user_id' => Auth::user()->id
		]);
	}

	protected function getFriends($id, $confirmed = true) {
		$first = DB::table('users')->select('users.*')
									->join('friendships', 'friendships.user_id', '=', 'users.id')
									->where('friendships.friend_id', $id);

		if ($confirmed) { $first->where('confirmed', 1); }

		$second = DB::table('users')->select('users.*')
									->join('friendships', 'friendships.friend_id', '=', 'users.id')
									->where('friendships.user_id', $id);

        if ($confirmed) { $second->where('confirmed', 1);
        }
        return $second->union($first)
            ->orderBy('firstname')
            ->get();
	}

	protected function getRequests() {
		return DB::table('users')->select('users.*', 'friendships.id AS friendship_id')
									->join('friendships', 'friendships.user_id', '=', 'users.id')
									->where('friendships.friend_id', Auth::user()->id)
									->where('confirmed', 0)
									->get();
	}

	protected function getRequestCount() {
		return count(DB::table('users')
						->select('users.*', 'friendships.id AS friendship_id')
						->join('friendships', 'friendships.user_id', '=', 'users.id')
						->where('friendships.friend_id', Auth::user()->id)
						->where('confirmed', 0)
						->get()
				);
	}

	protected function getFriendsIds($id, $confirmed) {
		$result = self::getFriends(Auth::user()->id, $confirmed);
		$friends = [];

		if (!$result->isEmpty()) {
			foreach ($result as $friend) {
				$friends[] = $friend->id;
			}
		}

		return $friends;
	}

	protected function exists($id) {
		$first = DB::table('users')
					->select('users.*')
					->join('friendships', 'friendships.user_id', '=', 'users.id')
					->where('friendships.friend_id', $id)
					->where('friendships.user_id', Auth::user()->id);

		$second = DB::table('users')
					->select('users.*')
					->join('friendships', 'friendships.friend_id', '=', 'users.id')
					->where('friendships.user_id', $id)
					->where('friendships.friend_id', Auth::user()->id)
					->union($first)
					->orderBy('firstname')
					->get();

		if ($second->isEmpty()) {
			return false;
		} else {
			return true;
		}
	}

	protected function confirm($id) {
		$friendship = self::find($id);
		$friendship->confirmed = 1;
		$friendship->save();
	}
}