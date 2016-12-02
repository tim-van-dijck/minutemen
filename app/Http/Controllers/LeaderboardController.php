<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Leaderboard;

class LeaderboardController extends Controller
{
	public function index()
	{

	}

	public function teams()
	{
		$teams = Leaderboard::getGlobal();
		return view('leaderboards.teams')->with(['teams' => $teams]);
	}
}
