<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Event;
use App\Organisation;

class OrganisationController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$organisations = Organisation::get();
		return view('organisations.index')->with(['organisations' => $organisations]);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		return view('organisations.create');
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

		$input['slug'] = Organisation::sluggify($input['name']);

		// Deal with thumb upload
		if (isset($input['thumb']) && $input['thumb'] != '') {
			$input['thumb'] = Organisation::uploadImg($input['thumb'], true);
		} else { unset($input['thumb']); }

		// Deal with banner upload
		if (isset($input['banner']) && $input['banner'] != '') {
			$input['banner'] = Organisation::uploadImg($input['banner'], true);
		} else { unset($input['banner']); }

		$organisation = new Organisation($input);
		$organisation->save();

		Organisation::join($organisation->id, true);

		return redirect('organisations');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id)
	{
		$organisation = Organisation::find($id);
		$organisation->events = Event::where('organisation_id', $organisation->id)->get();
		$organisation->admins = Organisation::admins($organisation->id);

		return view('organisations.show')->with(['organisation' => $organisation]);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($slug)
	{
		$organisation = Organisation::where(['slug' => $slug])->first();
		return view('organisations.edit')->with(['organisation' => $organisation]);
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

		$organisation = Organisation::find($id);
		$input = $request->all();

		$input['slug'] = Organisation::sluggify($input['name']);

		// Deal with emblem upload
		if (isset($input['emblem']) && $input['emblem'] != '') {
			delete(public_path($organisation->emblem));
			$input['emblem'] = Organisation::uploadImg($input['emblem'], true);
		} else { unset($input['emblem']); }

		foreach ($input as $field => $value) {
			$organisation->{$field} = $value;
		}

		$organisation->save();

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
		Organisation::destroy($id);
		return redirect()->back();
	}
}
