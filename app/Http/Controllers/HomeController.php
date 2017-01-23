<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Sitemap;

use App\Event;
use App\General;
use App\Organisation;
use App\Post;
use App\Team;
use App\User;

class HomeController extends Controller
{
	public function index()
	{
		return view('pages.welcome')->with([
			'events'		=> Event::where('ends_at', '>', date('Y-m-d H:i:s'))->orderBy('starts_at')->get(),
			'organisations'	=> Organisation::popular()
		]);
	}

	/**
	 * Show the application dashboard.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function home()
	{
		return view('pages.dashboard')->with([
			'feed'      => Post::getByUser(Auth::user()->id),
            'canExpand' => Post::canExpand(1, Auth::user()->id),
            'notifications' => Auth::user()->notifications(3)
		]);
	}

	public function search(Request $request) {
		$query = $request->input('q');

		if ($query != "") { $results = General::search($query); }
		else { $results = null; }

		return view('pages.search')->with(['results' => $results, 'query' => $query]);
	}

	public function about() { return view('pages.about'); }

	public function sitemap() {

        Sitemap::addTag(route('dashboard'), date('Y-m-d H:i:s', strtotime('yesterday')), 'daily', '0.8');
        Sitemap::addTag(route('about'), date('Y-m-d H:i:s', strtotime('yesterday')), 'daily', '0.8');
        Sitemap::addTag(route('search'), date('Y-m-d H:i:s', strtotime('yesterday')), 'daily', '0.8');

        $organisations = Organisation::get();
        foreach ($organisations as $org) {
            Sitemap::addTag(route('organisations.show', ['id' => $org->id]), $org->updated_at, 'daily', '0.8');
        }

        $teams = Team::get();
        foreach ($teams as $team) {
            Sitemap::addTag(route('teams.show', ['slug' => $team->slug]), $team->updated_at, 'daily', '0.8');
            Sitemap::addTag(route('teams.members', ['slug' => $team->slug]), $team->updated_at, 'daily', '0.8');
        }

        $events = Event::get();
        foreach ($events as $event) {
            Sitemap::addTag(route('events.show', $event), $event->updated_at, 'daily', '0.8');
            Sitemap::addTag(route('events.leaderboard', $event), $event->updated_at, 'daily', '0.8');
        }

        $users = User::where('admin', 0)->get();
        foreach ($users as $user) {
            Sitemap::addTag(route('users.show', ['slug' => $user->slug]), $user->updated_at, 'daily', '0.8');
        }

        return Sitemap::render();
    }
}
