<?php

namespace App\Http\Controllers;

use App\Notification;
use Illuminate\Http\Request;
use App\Http\Requests;
use Auth;
use Session;

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
		$events = Event::where('ends_at', '>', date('Y-m-d H:i:s'))->orderBy('starts_at')->get();
		return view('events.index')->with(['events' => $events]);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create($id) { return view('events.create')->with(['organisation' => Organisation::find($id)]); }

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
			'banner'		=> 'image|max:4096',
            'type'          => 'required',
		]);

		$input = $request->all();
		$input['organisation_id'] = $organisation_id;
		
		if (isset($input['banner']) && $input['banner'] != '') {
			$input['banner'] = General::uploadImg($input['banner'], 'events', true);
		} else { unset($input['banner']); }

		$event = new Event($input);
		$event->save();

		Notification::newEvent($event->organisation_id);

		Session::flash('success', 'Successfully created "'.$event->title.'"');
		return redirect(route('events.show', ['id' => $event->id]));
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
	public function edit($id) { return view('events.edit')->with(['event' => Event::find($id)]); }

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
		unset($input['_method']);
		unset($input['_token']);
		$event = Event::find($id);
		
		if (isset($input['banner']) && $input['banner'] != '') {
			Storage::delete(public_path($event->banner));
			$input['banner'] = General::uploadImg($input['banner'], 'events', true);
		} else { unset($input['banner']); }

		foreach ($input as $field => $value) {
			$event->{$field} = $value;
		}

		$event->save();

        Notification::updatedEvent($event->id);

        Session::flash('success', 'Successfully updated '.$event->title);
		return redirect(route('events.show', ['id' => $event->id]));
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

	public function manage($event_id) { return view('events.manage')->with(['event' => Event::find($event_id)]); }

	public function leaderboard($event_id) {
		$event = Event::find($event_id);
		return view('events.leaderboard')->with(['event' => $event, 'leaderboard' => $event->leaderboard()]);
	}

	public function enter(Request $request, $event_id) { Event::enter($event_id, intval($request->input('team'))); }
}
