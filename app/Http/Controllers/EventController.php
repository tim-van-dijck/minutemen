<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Auth;

use App\Event;
use App\General;
use App\Organisation;
use App\Team;

class EventController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$events = Event::where('ends_at', '>', date('Y-m-d H:i:s'))->get();
		return view('events.index')->with(['events' => $events]);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create($id)
	{
		$organisation = Organisation::find($id);
		return view('events.create')->with(['organisation' => $organisation]);
	}

	/**
	 * Create a new resource.
	 *
	 * @param  int  $organisation_id
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request, $organisation_id)
	{
		$this->validate($request, [
			'title'			=> 'required|max:255',
			'description'	=> 'required',
			'starts_at'		=> 'required|date|after:today',
			'ends_at'		=> 'required|date|after:starts_at',
			'street'		=> 'required',
			'number'		=> 'required',
			'zip'			=> 'required',
			'city'			=> 'required',
			'banner'		=> 'image|max:4096'
		]);

		$input = $request->all();
		$input['organisation_id'] = $organisation_id;
		
		if (isset($input['banner']) && $input['banner'] != '') {
			$input['banner'] = General::uploadImg($input['banner'], 'events', true);
		} else { unset($input['banner']); }

		$event = new Event($input);
		$event->save();

		return redirect(route('organisations.show', ['id' => $organisation_id]));
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $organisation_id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id)
	{
		$data['event'] = Event::find($id);
		if (Auth::check()) { $data['myTeams'] = Team::mine(); }

		return view('events.show')->with($data);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id)
	{
		$event = Event::find($id);
		return view('events.edit')->with(['event' => $event]);
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
			'title'			=> 'required|max:255',
			'description'	=> 'required',
			'starts_at'		=> 'required|date|after:today',
			'ends_at'		=> 'required|date|after:starts_at',
			'street'		=> 'required',
			'number'		=> 'required',
			'zip'			=> 'required',
			'city'			=> 'required',
			'banner'		=> 'image|max:4096'
		]);

		$input = $request->all();
		$event = Event::find($id);
		
		if (isset($input['banner']) && $input['banner'] != '') {
			Storage::delete(public_path($event->banner));
			$input['banner'] = General::uploadImg($input['banner'], 'events', true);
		} else { unset($input['banner']); }

		foreach ($input as $field => $value) {
			$event->{$field} = $value;
		}

		$event->save();

		return redirect(route('organisations.show', ['id' => $event->organisation_id]));
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
		Event::delete($id);
		return redirect()->back();
	}

	public function enter(Request $request, $event_id)
	{
		Event::enter($event_id, intval($request->input('team')));
	}
}
