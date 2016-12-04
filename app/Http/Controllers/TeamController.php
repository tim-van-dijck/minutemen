<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Auth;
use Storage;

use App\General;
use App\Team;

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

		// Deal with emblem upload
		if (isset($input['emblem']) && $input['emblem'] != '') {
			$input['emblem'] = General::uploadImg($input['emblem'], 'teams',true);
		} else { unset($input['emblem']); }

		$team = new Team($input);
		$team->save();

		Team::join($team->id, Auth::user()->id, false, true);
		Auth::user()->nlfg();

		return redirect('teams');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
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
	 * @param  int  $id
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
			'name'			=> 'required',
			'tag'			=> 'required',
			'description'	=> 'required',
		]);

		$team = Team::find($id);
		$input = $request->all();

		$input['slug'] = Team::sluggify($input['name']);

		// Deal with emblem upload
		if (isset($input['emblem']) && $input['emblem'] != '') {
			Storage::delete(public_path($team->emblem));
			$input['emblem'] = General::uploadImg($input['emblem'], 'teams',true);
		} else { unset($input['emblem']); }

		foreach ($input as $field => $value) {
			$team->{$field} = $value;
		}

		$team->save();

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
		Team::destroy($id);
		return redirect()->back();
	}

	public function addMember($team_id, $user_id)
	{
		Team::addMember($team_id, $user_id);
		return redirect()->back();
	}

	public function join($team_id) {
		Team::join($team_id, Auth::user()->id, false, false);
	}
}
