<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Auth;
use DB;
use Storage;

use App\Commendation;
use App\General;
use App\Friendship;
use App\User;


class UserController extends Controller
{
	/**
	 * Display the specified resource.
	 *
	 * @param  string  $slug
	 * @return \Illuminate\Http\Response
	 */
	public function show($slug = false)
	{
		$friends = [];
		if ($slug) { $user = User::where(['slug' => $slug])->first(); }
		else { $user = User::find(Auth::user()->id); }
		
		return view('users.show')->with(['user' => $user]);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit()
	{
		$user = User::find(Auth::user()->id);
		return view('users.edit')->with(['user' => $user]);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request)
	{
		$this->validate($request, [
			'firstname'		=> 'max:255|profanity-filter',
			'lastname'		=> 'max:255|profanity-filter',
			'email'			=> 'required|email|max:255|unique:users,email,'.Auth::user()->id,
			'street'		=> 'max:255',
			'number'		=> 'max:20',
			'zip'			=> 'max:10',
			'city'			=> 'max:255',
			'passworld_old'	=> 'required_with:password',
			'password'		=> 'min:6|confirmed',
		]);

		$user = User::find(Auth::user()->id);
		$input = $request->all();

		unset($input['password_old']);
		unset($input['password_confirmation']);
		unset($input['_token']);
		unset($input['_method']);
		unset($input['full-img']);

		// Handle img upload
		if (isset($input['img']) && $input['img'] != 'data:,') {
			Storage::delete(public_path($user->img));
			$input['img'] = General::uploadImg($input['img'], 'users');
		} else { unset($input['img']); }

		foreach ($input as $field => $value) {
		    if ($value != null && $value != '')
			$user->{$field} = $value;
		}
		
		$user->save();

		return redirect()->back();
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
		User::delete($id);
		return redirect()->back();
	}

	public function leaderboard()
	{
		$users = User::orderBy(DB::raw('kills/deaths'), 'DESC')->get();
		return view('users.leaderboard');
	}

	/**
	 * Friend-related routes
	 */

	// retrieve friends
	public function friends()
	{
		$friends = Auth::user()->friends();
		$requests = Friendship::getRequests();
		return view('users.friends')->with(['friends' => $friends, 'requests' => $requests]);
	}

	// add friend
	public function addFriend($slug)
	{
		$user = User::where('slug', $slug)->first();

		if (!Friendship::exists($user->id)) {
			$friendship = new Friendship;
			$friendship->user_id = Auth::user()->id;
			$friendship->friend_id = $user->id;

			$friendship->save();
		}
		return redirect()->back();
	}

	// confirm friend request
	public function confirmFriend($friendship_id)
	{
		Friendship::confirm($friendship_id);
		return redirect()->back();
	}

	// Unfriend / remove friend request
	public function deleteFriend($friendship_id)
	{
		$friendship = Friendship::find($friendship_id);
		$friendship->delete();
		return redirect()->back();
	}

	public function lfg() { return view('users.lfg')->with(['users' => User::lfg()]); }

	public function search(Request $request, $team_id) {
	    return json_encode(User::search($request->input('term'), $team_id));
	}
}
