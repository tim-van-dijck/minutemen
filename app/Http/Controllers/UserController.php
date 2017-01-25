<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Auth;
use DB;
use Session;
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
		return view('users.edit')->with(['user' => Auth::user()]);
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
			'passworld_old'	=> 'required_with:password',
			's_password'	=> 'min:6|confirmed',
		]);

		$user = User::find(Auth::user()->id);
		$input = $request->all();

		unset($input['password_old']);
		unset($input['s_password_confirmation']);
		unset($input['_token']);
		unset($input['_method']);
		unset($input['full-img']);

        // Handle img upload
        if (isset($input['img']) && $input['img'] != '' && $input['img'] != 'data:,') {
            if (isset($user->img)) { \unlink(public_path($user->img)); }
			$input['img'] = General::uploadImg($input['img'], 'users', $user->id);
		} else { unset($input['img']); }

		foreach ($input as $field => $value) {
		    if ($value != null && $value != '') {
                if ($field == 's_password') { $user->password = bcrypt($input['s_password']); }
                else { $user->{$field} = $value; }
            }
		}
		
		$user->save();

		Session::flash('success', 'Successfully updated profile');
		return redirect()->route('users.show', ['slug' => $user->slug]);
	}

	public function settings() {
	    return view('users.settings')->with(['user' => Auth::user()]);
    }

    public function updateSettings(Request $request) {
        $this->validate($request, [
            'street'    => 'max:255',
            'number'    => 'max:20',
            'zip'	    => 'max:10',
            'city'	    => 'max:255',
        ]);

        $user = User::find(Auth::user()->id);
        $input = $request->all();

        unset($input['_token']);
        unset($input['_method']);

        if ($input['coords'] != '') {
            $coords = explode(';', $input['coords']);

            $input['lat'] = $coords[0];
            $input['long'] = $coords[1];
        }
        unset($input['coords']);

        if ($input['country'] == '') { unset($input['country']); };

        foreach ($input as $field => $value) {
            if ($value != null && $value != '') {
                $user->{$field} = $value;
            }
        }

        $user->save();

        Session::flash('success', 'Successfully updated settings');
        return redirect()->back();
    }

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy()
	{
		User::destroy(Auth::user()->id);
		Session::flush();
		return redirect('/');
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
	public function friends($slug = false)
	{
	    if ($slug) { $user = User::where('slug', $slug)->first(); $requests = collect([]); }
        else {
	        $user = Auth::user();
            $requests = Friendship::getRequests();
        };

        $friends = $user->friends();
        return view('users.friends')->with(['friends' => $friends, 'requests' => $requests, 'user' => $user]);
	}

	// add friend
	public function addFriend(Request $request, $slug)
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
	public function confirmFriend(Request $request, $friendship_id)
	{
		Friendship::confirm($friendship_id);

		if ($request->ajax()) { return json_encode(['success' => 'Successfully confirmed friend']); }
        else { return redirect()->back(); }
	}

	// Unfriend / remove friend request
	public function deleteFriend(Request $request, $friendship_id)
	{
		$friendship = Friendship::find($friendship_id);
		$friendship->delete();

		if ($request->ajax()) { return json_encode(['success' => 'Successfully deleted friend']); }
        else { return redirect()->back(); }
	}

	public function getLfg($team_id) { return json_encode(User::getLfg($team_id)); }

	public function search(Request $request, $team_id) {
	    return json_encode(User::search($request->input('q'), $team_id));
	}

	public function notifications() {
	    return view('pages.notifications')->with(['notifications' => Auth::user()->notifications()]);
    }

    public function findAcquaintances(Request $request) {
	    return json_encode(Auth::user()->searchAcquaintances($request->input('term')));
    }

    public function findRecipients(Request $request, $conversation_id) {
        return json_encode(Auth::user()->findRecipients($request->input('term'), $conversation_id));
    }

    public function hideTutorial(Request $request) {
	    if ($request->input('hide') == 1) {
            $user = User::find(Auth::user()->id);
            $user->tutorial = 0;
            $user->save();
        }
    }
}
