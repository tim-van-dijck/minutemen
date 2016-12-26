<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Event;
use App\Round;

class RoundController extends Controller
{
	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int $event_id
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request, $event_id)
	{
        $event = Event::find($event_id);

        if ($event->type == 'round-robin' && count($event->rounds()) == 0) { $event->roundrobin(); }
        else {
            $this->validate($request, ['name' => 'required|max:255|profanity-filter']);

            $data = [
                'name'      => $request->input('name'),
                'event_id'  => $event_id
            ];

            $event->eliminationRound($data);
        }

        return redirect()->back();
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
		//
	}
}
