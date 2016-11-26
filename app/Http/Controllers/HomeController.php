<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Event;
use App\Team;
use App\User;

class HomeController extends Controller
{
	public function index()
	{
		return view('pages.welcome')->with([
			'events' => Event::orderBy('starts_at')->get(),
		]);
	}

	/**
	 * Show the application dashboard.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function home()
	{
		return view('home')->with([
			'feed' => Post::getByUser(Auth::user()->id),
		]);
	}

	public function search(Request $request) {
		$query = $request->input('q');

		$results['users'] = User::search($query);
		$results['teams'] = Team::search($query);

		return view('pages.search')->with(['results' => $results, 'query' => $query]);
	}
}
