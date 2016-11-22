<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Commendation;
use App\Friendship;
use App\User;

use Auth;
use DB;

class UserController extends Controller
{
	public function search()
	{
		return view('users.search');
	}

	public function find(Request $request)
	{
		$query = $request->input('query');
		$users = User::search($query);
		return view('users.search')->with(['users' => $users]);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  string  $slug
	 * @return \Illuminate\Http\Response
	 */
	public function show($slug = false)
	{
		$friends = [];
		if ($slug) {
			$user = User::where(['slug' => $slug])->first();
			$friends = Friendship::getFriendsIds();
		} else {
			$user = User::find(Auth::user()->id);
		}

		$user->friends = Friendship::getFriends($user->id);
		$user->commendations = Commendation::count($user->id);
		
		return view('users.show')->with(['user' => $user, 'friends' => $friends]);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id)
	{
		$user = User::find($id);
		return view('users.edit')->with(['user' => $user]);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id)
	{
		$this->validate($request, [
			'username'		=> 'required',
			'firstname'		=> 'required',
			'lastname'		=> 'required',
			'email'			=> 'required|email',
			'password'		=> 'min:6|confirmed',
			'img'			=> 'image|max:4096',
		]);

		$user = User::find($id);
		$input = $request->all();

		// Handle img upload
		if (isset($input['img']) && $input['img'] != '') {
			delete(public_path($user->img));
			$input['img'] = User::uploadFile($input['img']);
		} else { unset($input['img']); }

		foreach ($input as $field => $value) {
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
		$friends = Friendship::getFriends();
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
}
