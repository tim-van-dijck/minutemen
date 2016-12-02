<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class General extends Model
{
    protected function search($query) {
		$users = User::select('*')->where('username', 'LIKE', '%'.$query.'%')->orWhere('firstname', 'LIKE', '%'.$query.'%')->orWhere('lastname', 'LIKE', '%'.$query.'%')->orderBy('username')->get();
		$teams = Team::select('*')->where('name', 'LIKE', '%'.$query.'%')->orWhere('tag', 'LIKE', '%'.$query.'%')->orWhere('description', 'LIKE', '%'.$query.'%')->orderBy('name')->get();
		$events = Event::select('*')->where('title', 'LIKE', '%'.$query.'%')->orWhere('description', 'LIKE', '%'.$query.'%')->orderBy('title')->get();
		$organisations = Organisation::select('*')->where('name', 'LIKE', '%'.$query.'%')->orWhere('description', 'LIKE', '%'.$query.'%')->orderBy('name')->get();

    	$results = [
    		'users'			=> $users,
    		'teams'			=> $teams,
    		'events'		=> $events,
    		'organisations'	=> $organisations,
    	];

    	return $results;
    }
}
