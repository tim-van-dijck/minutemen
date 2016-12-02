<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Storage;

use App\Event;
use App\Organisation;
use App\Post;

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
			'description'	=> 'required',
		]);

		$input = $request->all();

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

		Organisation::makeAdmin($organisation->id, Auth::user()->id);

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
		$organisation->events = Event::where('organisation_id', $id)->get();
		$organisation->admins = Organisation::admins($id);
		$organisation->posts = Post::feed($id);

		return view('organisations.show')->with(['organisation' => $organisation]);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id)
	{
		$organisation = Organisation::find($id);
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
			'description'	=> 'required',
		]);

		$organisation = Organisation::find($id);
		$input = [
			'name'			=> $request->input('name'),
			'description'	=> $request->input('description'),
			'thumb'			=> $request->input('thumb')
		];

		// Deal with emblem upload
		if (isset($input['thumb']) && $input['thumb'] != '') {
			Storage::delete(public_path($organisation->thumb));
			$input['thumb'] = Organisation::uploadImg($input['thumb'], true);
		} else { unset($input['thumb']); }

		foreach ($input as $field => $value) {
			$organisation->{$field} = $value;
		}

		$organisation->save();

		return redirect(route('organisations.show', ['id' => $id]));
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

	public function post(Request $request, $id) {
		$this->validate($request, ['post' => 'required']);

		$organisation = Organisation::find($id);
		$input = [
			'content'			=> $request->input('post'),
			'organisation_id'	=> $id,
		];

		Post::make($input);
	}
}
