<?php

namespace App\Http\Controllers;

use App\Notification;
use Illuminate\Http\Request;
use App\Http\Requests;
use Auth;
use Session;
use Storage;

use App\General;
use App\Team;
use App\User;

class TeamController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$teams = Team::get();
		return view('teams.index')->with(['teams' => $teams]);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		return view('teams.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		$this->validate($request, [
			'name'			=> 'required|profanity-filter',
			'tag'			=> 'required',
			'description'	=> 'required',
		]);

		$input = $request->all();

		$input['slug'] = General::sluggify($input['name'], 'teams');
		$input['tag'] = strtoupper($input['img']);

		// Deal with emblem upload
		if (isset($input['emblem']) && $input['emblem'] != '') {
            $input['emblem'] = General::uploadImg($input['emblem'], 'teams',true);
		} else { unset($input['emblem']); }

		$team = new Team($input);
		$team->save();

		Team::join($team->id, Auth::user()->id, false, true);
		Auth::user()->nlfg();

		Session::flash('success', 'Successfully created '.$team->name);
		return redirect('teams');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $slug
	 * @return \Illuminate\Http\Response
	 */
	public function show($slug)
	{
		$team = Team::where(['slug' => $slug])->first();
		return view('teams.show')->with(['team' => $team]);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $slug
	 * @return \Illuminate\Http\Response
	 */
	public function edit($slug)
	{
		$team = Team::where(['slug' => $slug])->first();
		return view('teams.edit')->with(['team' => $team]);
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
			'name'			=> 'required|profanity-filter',
			'tag'			=> 'required',
			'description'	=> 'required',
		]);

		$team = Team::find($id);
		$input = $request->all();
		unset($input['_token']);
		unset($input['_method']);
		unset($input['full-img']);

		$input['slug'] = General::sluggify($input['name'], 'teams', $team->id);
        $input['tag'] = strtoupper($input['tag']);

		// Deal with emblem upload
        if (isset($input['img']) && $input['img'] != 'data:,') {
            if (isset($team->emblem)) { \unlink(public_path($team->emblem)); }
			$input['emblem'] = General::uploadImg($input['img'], 'teams',true);
		}
		unset($input['img']);

		foreach ($input as $field => $value) {
			$team->{$field} = $value;
		}

		$team->save();
        Notification::updatedTeam($team->id);

		Session::flash('success', 'Successfully updated '.$team->name);
        return redirect('teams/'.$team->slug);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
		Team::destroy($id);
		return redirect()->back();
	}

	public function leave($team_id) {
        Team::deleteMember($team_id, Auth::user()->id);
        return redirect()->back();
    }

	public function addMember($team_id, $user_id)
	{
		Team::addMember($team_id, $user_id);
		return redirect()->back();
	}

	public function join($team_id) { Team::join($team_id, Auth::user()->id, false, false); }

	public function members($slug) {
		$team = Team::where(['slug' => $slug])->first();
		return view('teams.members')->with(['team' => $team]);
	}

    public function kick(Request $request, $team_id) {
        if (User::passwordConfirm($request->input('password'))) {
            return Team::kick($team_id, $request->input('member_id'));
        } else { return json_encode(['error' => 'The password you entered was wrong']); }
    }

    public function makeAdmin($team_id, $user_id) {
	    Team::makeAdmin($team_id, $user_id);
    }

    public function deleteAdmin($team_id, $user_id) {
	    Team::deleteAdmin($team_id, $user_id);
    }

    public function mine() {
	    return view('teams.index')->with(['teams' => Auth::user()->teams()]);
    }
}
